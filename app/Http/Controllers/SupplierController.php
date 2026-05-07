<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view suppliers')->only(['index', 'show']);
        $this->middleware('permission:create suppliers')->only(['create', 'store']);
        $this->middleware('permission:edit suppliers')->only(['edit', 'update']);
        $this->middleware('permission:delete suppliers')->only(['destroy']);
    }

    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Fournisseur ajouté !');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Fournisseur mis à jour !');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Fournisseur supprimé !');
    }
}
