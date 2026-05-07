<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Expense;
use App\Models\DailyClosing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClosingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view orders');
    }

    public function index()
    {
        $today = Carbon::today();
        
        // Calculate expected cash
        $cashSales = Order::whereDate('created_at', $today)
            ->where('payment_method', 'cash')
            ->sum('total_amount');
            
        $expenses = Expense::whereDate('entry_date', $today)
            ->sum('amount');
            
        $expectedCash = $cashSales - $expenses;
        
        $closings = DailyClosing::with('user')->latest()->paginate(10);
        $isClosedToday = DailyClosing::whereDate('closed_at', $today)->exists();

        return view('closings.index', compact('expectedCash', 'cashSales', 'expenses', 'closings', 'isClosedToday'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'actual_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $today = Carbon::today();
        
        if (DailyClosing::whereDate('closed_at', $today)->exists()) {
            return redirect()->back()->with('error', 'La caisse a déjà été clôturée pour aujourd\'hui.');
        }

        $cashSales = Order::whereDate('created_at', $today)
            ->where('payment_method', 'cash')
            ->sum('total_amount');
            
        $expenses = Expense::whereDate('entry_date', $today)
            ->sum('amount');
            
        $expectedCash = $cashSales - $expenses;
        $difference = $request->actual_amount - $expectedCash;

        DailyClosing::create([
            'user_id' => Auth::id(),
            'expected_amount' => $expectedCash,
            'actual_amount' => $request->actual_amount,
            'difference' => $difference,
            'notes' => $request->notes,
            'closed_at' => now(),
        ]);

        return redirect()->route('closings.index')->with('success', 'Clôture de caisse enregistrée avec succès !');
    }
}
