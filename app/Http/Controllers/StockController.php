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
        $this->middleware('permission:view products'); // Reuse product permissions
    }

    public function index()
    {
        $movements = StockMovement::with(['product', 'user'])->latest()->paginate(20);
        return view('stock.index', compact('movements'));
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
            'quantity' => 'required|numeric', // Can be negative
            'type' => 'required|in:restock,adjustment,damage,return',
            'notes' => 'nullable|string',
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
