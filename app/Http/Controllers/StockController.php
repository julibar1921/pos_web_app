<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view products')->only(['index', 'restockAssistant']);
        $this->middleware('role:admin')->only(['adjust']); // Only admin can adjust stock
    }

    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user'])->latest();

        // Filter by product name
        if ($request->filled('product')) {
            $query->whereHas('product', fn($q) =>
                $q->where('name', 'like', '%' . $request->product . '%')
            );
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by movement type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $query->paginate(20)->withQueryString();
        $users = \App\Models\User::orderBy('name')->get();

        return view('stock.index', compact('movements', 'users'));
    }

    public function restockAssistant()
    {
        $lowStockProducts = Product::with('supplier')
            ->where('stock_quantity', '<', 5)
            ->get()
            ->groupBy(function($product) {
                return $product->supplier ? $product->supplier->name : 'Sans Fournisseur';
            });

        return view('stock.restock', compact('lowStockProducts'));
    }

    public function adjust(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|numeric|not_in:0',
            'type'     => 'required|in:restock,adjustment,damage,return,sale',
            'notes'    => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'quantity' => $request->quantity,
                'type' => $request->type,
                'notes' => $request->notes,
            ]);

            $product->increment('stock_quantity', $request->quantity);

            DB::commit();
            return redirect()->back()->with('success', 'Stock mis à jour avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour du stock.');
        }
    }
}
