<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Repayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view customers')->only(['index', 'show']);
        $this->middleware('permission:create customers')->only(['create', 'store']);
        $this->middleware('permission:edit customers')->only(['edit', 'update']);
        $this->middleware('permission:delete customers')->only(['destroy']);
    }

    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Client ajouté avec succès !');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Client mis à jour !');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Client supprimé !');
    }

    public function repay(Request $request, Customer $customer)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            Repayment::create([
                'customer_id' => $customer->id,
                'user_id' => Auth::id(),
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            $customer->decrement('balance', $request->amount);

            DB::commit();
            return redirect()->back()->with('success', 'Remboursement de ' . number_format($request->amount, 3) . ' enregistré !');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function show(Customer $customer)
    {
        $orders = $customer->orders()->with('user')->latest()->get();
        $repayments = $customer->repayments()->with('user')->latest()->get();
        
        return view('customers.show', compact('customer', 'orders', 'repayments'));
    }
}
