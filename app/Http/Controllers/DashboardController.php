<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Expense;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // Stats du jour
        $salesToday = Order::whereDate('created_at', $today)->sum('total_amount');
        $ordersCountToday = Order::whereDate('created_at', $today)->count();
        
        // Stats du mois
        $salesThisMonth = Order::whereMonth('created_at', Carbon::now()->month)->sum('total_amount');
        
        // Dépenses
        $expensesToday = \App\Models\Expense::whereDate('entry_date', $today)->sum('amount');
        $expensesThisMonth = \App\Models\Expense::whereMonth('entry_date', Carbon::now()->month)->sum('amount');
        
        // Valeur du Stock (Prix d'achat total)
        $totalStockValue = Product::sum(DB::raw('stock_quantity * purchase_price'));
        
        // Données pour le graphique (7 derniers jours)
        $chartSales = [];
        $chartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->translatedFormat('d M');
            $chartSales[] = (float) Order::whereDate('created_at', $date)->sum('total_amount');
        }

        // Répartition par catégorie
        $categoryDistribution = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(order_items.subtotal) as total'))
            ->groupBy('categories.name')
            ->get();
        
        // Top produits (les plus vendus en quantité)
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take(5)
            ->get();
            
        // Produits en stock critique
        $lowStockProducts = Product::where('stock_quantity', '<', 5)->get();
        
        // Dernières ventes
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('dashboard', compact(
            'salesToday', 
            'ordersCountToday', 
            'salesThisMonth', 
            'expensesToday',
            'expensesThisMonth',
            'totalStockValue',
            'chartSales',
            'chartLabels',
            'categoryDistribution',
            'topProducts', 
            'lowStockProducts',
            'recentOrders'
        ));
    }
}
