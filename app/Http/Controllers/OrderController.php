<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:access pos')->only(['index']);
        $this->middleware('permission:view orders')->only(['history']);
    }

    public function index()
    {
        $products = Product::with('category')->where(function($query) {
            $query->where('stock_quantity', '>', 0)
                  ->orWhere('is_stockable', false);
        })->get();
        $categories = Category::all();
        $customers = \App\Models\Customer::all();
        return view('pos.index', compact('products', 'categories', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array|min:1',
            'payment_method' => 'required|string|in:cash,card,credit',
            'total_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|string|in:fixed,percentage',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        if ($request->payment_method === 'credit' && !$request->customer_id) {
            return response()->json(['success' => false, 'message' => 'Un client doit être sélectionné pour une vente à crédit.'], 400);
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_id' => $request->customer_id,
                'total_amount' => $request->total_amount,
                'discount_amount' => $request->discount_amount ?? 0,
                'discount_type' => $request->discount_type ?? 'fixed',
                'payment_method' => $request->payment_method,
                'status' => 'completed',
            ]);

            // If payment is credit, increase customer balance
            if ($request->payment_method === 'credit') {
                $customer = \App\Models\Customer::findOrFail($request->customer_id);
                $customer->increment('balance', $request->total_amount);
            }

            foreach ($request->cart as $item) {
                $product = Product::findOrFail($item['id']);
                
                // Double check stock
                if ($product->is_stockable && $product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Stock insuffisant pour le produit: " . $product->name);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->selling_price,
                    'subtotal' => $product->selling_price * $item['quantity'],
                ]);

                if ($product->is_stockable) {
                    // Reduce stock
                    // Record stock movement instead of direct decrement
                    StockMovement::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'quantity' => -$item['quantity'],
                        'type' => 'sale',
                        'notes' => 'Vente #' . $order->id,
                    ]);

                    $product->decrement('stock_quantity', $item['quantity']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Commande effectuée avec succès !',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function history(Request $request)
    {
        $query = Order::with(['user', 'customer', 'refunds', 'items.product'])->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use($q) {
                $w->where('id', 'like', "%$q%")
                  ->orWhereHas('customer', function($c) use($q) {
                      $c->where('name', 'like', "%$q%");
                  });
            });
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(15)->withQueryString();
        return view('orders.history', compact('orders'));
    }

    public function print(Order $order)
    {
        $order->load('items.product', 'user');
        return view('orders.print', compact('order'));
    }

    public function invoice(Order $order)
    {
        $order->load(['items.product', 'customer', 'user']);
        return view('orders.invoice', compact('order'));
    }

    public function todaySales(Request $request)
    {
        $orders = Order::with(['items.product', 'customer', 'user'])
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->map(function ($order) {
                return [
                    'id'             => $order->id,
                    'time'           => $order->created_at->format('H:i'),
                    'cashier'        => $order->user->name,
                    'customer'       => $order->customer?->name ?? null,
                    'payment_method' => $order->payment_method,
                    'discount'       => $order->discount_amount,
                    'total'          => $order->total_amount,
                    'items'          => $order->items->map(fn($i) => [
                        'name'     => $i->product->name,
                        'qty'      => $i->quantity,
                        'price'    => $i->price,
                        'subtotal' => $i->subtotal,
                    ]),
                ];
            });

        return response()->json([
            'orders'      => $orders,
            'total'       => $orders->sum('total'),
            'count'       => $orders->count(),
            'avg'         => $orders->count() > 0 ? $orders->avg('total') : 0,
        ]);
    }

    public function salesReport(Request $request)
    {
        $this->middleware('role:admin');

        $users = \App\Models\User::orderBy('name')->get();

        $query = Order::with('user', 'items')
            ->where('status', 'completed')
            ->where('type', '!=', 'refund');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $orders = $query->latest()->get();

        // Summary stats
        $grandTotal     = $orders->sum('total_amount');
        $orderCount     = $orders->count();
        $averageBasket  = $orderCount > 0 ? $grandTotal / $orderCount : 0;
        $totalDiscount  = $orders->sum('discount_amount');

        // Per-user breakdown
        $perUser = $orders->groupBy('user_id')->map(function ($userOrders) {
            $user = $userOrders->first()->user;
            return [
                'name'        => $user->name,
                'count'       => $userOrders->count(),
                'total'       => $userOrders->sum('total_amount'),
                'discount'    => $userOrders->sum('discount_amount'),
                'average'     => $userOrders->avg('total_amount'),
            ];
        })->sortByDesc('total')->values();

        // Per-day breakdown
        $perDay = $orders->groupBy(fn($o) => $o->created_at->format('Y-m-d'))
            ->map(fn($d) => ['date' => $d->first()->created_at->format('d/m/Y'), 'count' => $d->count(), 'total' => $d->sum('total_amount')])
            ->sortKeys()->values();

        return view('reports.sales', compact(
            'users', 'orders', 'grandTotal', 'orderCount',
            'averageBasket', 'totalDiscount', 'perUser', 'perDay'
        ));
    }

    public function refund(Request $request, Order $order)
    {
        if ($order->isRefund()) {
            return back()->with('error', 'Cette commande est déjà un retour.');
        }

        if ($order->refunds()->exists()) {
            return back()->with('error', 'Cette commande a déjà fait l\'objet d\'un retour.');
        }

        try {
            DB::beginTransaction();

            // Create refund order
            $refund = Order::create([
                'user_id' => Auth::id(),
                'customer_id' => $order->customer_id,
                'total_amount' => $order->total_amount, // Keep positive, the type 'refund' indicates it's an outflow
                'discount_amount' => $order->discount_amount,
                'discount_type' => $order->discount_type,
                'payment_method' => $order->payment_method,
                'status' => 'completed',
                'type' => 'refund',
                'refund_of_order_id' => $order->id,
                'refund_reason' => $request->refund_reason ?? 'Retour client',
            ]);

            // Update original order status to refunded
            $order->update(['status' => 'refunded']);

            // If payment was credit, decrease customer balance
            if ($order->payment_method === 'credit' && $order->customer_id) {
                $customer = \App\Models\Customer::findOrFail($order->customer_id);
                $customer->decrement('balance', $order->total_amount);
            }

            // Copy items and restore stock
            foreach ($order->items as $item) {
                $product = $item->product;
                
                OrderItem::create([
                    'order_id' => $refund->id,
                    'product_id' => $product->id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                ]);

                if ($product->is_stockable) {
                    // Restore stock
                    StockMovement::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'quantity' => $item->quantity, // positive to add stock back
                        'type' => 'return',
                        'notes' => 'Retour commande #' . $order->id . ' (Réf: #' . $refund->id . ')',
                    ]);

                    $product->increment('stock_quantity', $item->quantity);
                }
            }

            DB::commit();

            return back()->with('success', 'Le retour a été enregistré avec succès. Le stock a été mis à jour.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors du retour : ' . $e->getMessage());
        }
    }

    public function refundsIndex(Request $request)
    {
        $this->middleware('role:admin');

        $query = Order::with(['user', 'customer', 'originalOrder'])
            ->where('type', 'refund')
            ->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use($q) {
                $w->where('id', 'like', "%$q%")
                  ->orWhere('refund_of_order_id', 'like', "%$q%")
                  ->orWhereHas('customer', function($c) use($q) {
                      $c->where('name', 'like', "%$q%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $refunds = $query->paginate(15)->withQueryString();
        return view('orders.refunds', compact('refunds'));
    }
}
