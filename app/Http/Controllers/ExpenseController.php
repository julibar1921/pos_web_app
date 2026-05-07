<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view expenses')->only(['index']);
        $this->middleware('permission:create expenses')->only(['store']);
        $this->middleware('permission:delete expenses')->only(['destroy']);
    }

    public function index()
    {
        $expenses = Expense::with('category')->latest()->get();
        $categories = ExpenseCategory::all();
        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'entry_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Expense::create($request->all());

        return redirect()->back()->with('success', 'Dépense enregistrée !');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->back()->with('success', 'Dépense supprimée !');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name',
        ]);

        ExpenseCategory::create($request->all());

        return redirect()->back()->with('success', 'Catégorie de dépense ajoutée !');
    }
}
