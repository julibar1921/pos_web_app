<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view products')->only(['index', 'show']);
        $this->middleware('permission:create products')->only(['create', 'store']);
        $this->middleware('permission:edit products')->only(['edit', 'update']);
        $this->middleware('permission:delete products')->only(['destroy']);
    }

    public function index()
    {
        $products = Product::with('category')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|string|max:255',
            'barcode'        => 'nullable|string|unique:products,barcode',
            'selling_price'  => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'unit'           => 'required|string',
            'image'          => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image', 'stock_quantity']);
        $data['stock_quantity'] = 0; // Stock always starts at 0 — adjust via stock module
        $data['is_stockable'] = $request->has('is_stockable');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produit ajouté avec succès ! Utilisez le module Stock pour approvisionner.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['is_stockable'] = $request->has('is_stockable');

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produit mis à jour !');
    }

    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produit supprimé !');
    }

    public function printLabel(Product $product)
    {
        return view('products.label', compact('product'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), "r");
        
        // Skip header
        fgetcsv($handle, 1000, ",");

        $imported = 0;
        $errors = 0;

        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($data) < 5) continue;

                $name = $data[0];
                $barcode = $data[1];
                $purchase_price = $data[2];
                $selling_price = $data[3];
                $stock_quantity = $data[4];
                $category_name = $data[5] ?? 'Divers';

                // Find or create category
                $category = Category::firstOrCreate(['name' => $category_name]);

                $product = Product::updateOrCreate(
                    ['barcode' => $barcode],
                    [
                        'name' => $name,
                        'category_id' => $category->id,
                        'purchase_price' => $purchase_price,
                        'selling_price' => $selling_price,
                        'stock_quantity' => $stock_quantity,
                    ]
                );

                // Record initial stock if new or modified
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'quantity' => $stock_quantity,
                    'type' => 'restock',
                    'notes' => 'Importation CSV initiale',
                ]);

                $imported++;
            }
            DB::commit();
            fclose($handle);

            return redirect()->back()->with('success', "$imported produits importés avec succès !");
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', "Erreur lors de l'importation : " . $e->getMessage());
        }
    }
}
