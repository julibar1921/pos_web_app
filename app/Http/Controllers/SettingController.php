<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        
        // Ensure default structure if empty
        $groups = [
            'general' => 'Informations Générales',
            'contact' => 'Coordonnées',
            'receipt' => 'Paramètres de Ticket',
        ];

        return view('settings.index', compact('settings', 'groups'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            if ($request->hasFile($key)) {
                // Handle file upload (logo)
                $path = $request->file($key)->store('settings', 'public');
                $value = $path;
                
                // Delete old logo if exists
                $oldPath = Setting::get($key);
                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Determine group based on key prefix or mapping
            $group = $this->getGroupForKey($key);
            
            Setting::set($key, $value, $group);
        }

        return redirect()->back()->with('success', 'Paramètres mis à jour avec succès !');
    }

    private function getGroupForKey($key)
    {
        $map = [
            'company_name' => 'general',
            'company_email' => 'contact',
            'company_phone' => 'contact',
            'company_address' => 'contact',
            'logo' => 'general',
            'footer_text' => 'receipt',
            'currency' => 'general',
            'tax_number' => 'general',
            'tax_rate' => 'general',
        ];

        return $map[$key] ?? 'general';
    }

    public function exportProducts()
    {
        $products = \App\Models\Product::with('category')->get();
        $csvFileName = 'produits_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($products) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($file, ['ID', 'Nom', 'Catégorie', 'Code-barres', 'Prix Achat', 'Prix Vente', 'Stock']);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->category->name,
                    $product->barcode,
                    $product->purchase_price,
                    $product->selling_price,
                    $product->stock_quantity,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportOrders()
    {
        $orders = \App\Models\Order::with('user', 'customer')->get();
        $csvFileName = 'ventes_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($orders) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($file, ['ID', 'Date', 'Utilisateur', 'Client', 'Total', 'Remise', 'Paiement']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->created_at->format('d/m/Y H:i'),
                    $order->user->name,
                    $order->customer ? $order->customer->name : 'Passage',
                    $order->total_amount,
                    $order->discount_amount,
                    $order->payment_method,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function downloadBackup()
    {
        $dbPath = database_path('database.sqlite');
        
        if (file_exists($dbPath)) {
            $fileName = 'backup_pos_' . now()->format('Y-m-d_H-i-s') . '.sqlite';
            return response()->download($dbPath, $fileName);
        }

        return redirect()->back()->with('error', 'Fichier de base de données introuvable.');
    }
}
