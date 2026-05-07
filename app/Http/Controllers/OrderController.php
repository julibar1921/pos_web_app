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
        $products = Product::with('category')->where('stock_quantity', '>', 0)->get();
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
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Stock insuffisant pour le produit: " . $product->name);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->selling_price,
                    'subtotal' => $product->selling_price * $item['quantity'],
                ]);

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
        $query = Order::with('user', 'customer')->latest();

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
}
