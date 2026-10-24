<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashFlow;
use Carbon\Carbon;

class CashFlowController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : Carbon::now()->endOfDay();

        $cashflows = CashFlow::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalMasuk = $cashflows->where('type', 'in')->sum('amount');
        $totalKeluar = $cashflows->where('type', 'out')->sum('amount');
        $saldo = $totalMasuk - $totalKeluar;

        return view('cashflow.index', compact('cashflows', 'totalMasuk', 'totalKeluar', 'saldo', 'startDate', 'endDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:in,out',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        CashFlow::create($request->only('type', 'category', 'amount', 'description', 'date'));

        return back()->with('success', 'Catatan kas berhasil ditambahkan.');
    }

    public function destroy(CashFlow $cashflow)
    {
        $cashflow->delete();
        return back()->with('success', 'Catatan kas berhasil dihapus.');
    }
}
