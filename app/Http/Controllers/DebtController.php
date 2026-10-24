<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Debt;
use App\Models\DebtPayment;
use Illuminate\Support\Facades\DB;

class DebtController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'receivable');

        $debts = Debt::where('type', $type)
            ->orderByRaw("FIELD(status, 'unpaid', 'partial', 'paid')")
            ->latest()
            ->get();

        $totalPiutang = Debt::where('type', 'receivable')->whereIn('status', ['unpaid', 'partial'])->selectRaw('SUM(total_amount - paid_amount) as sisa')->value('sisa') ?? 0;
        $totalHutang = Debt::where('type', 'payable')->whereIn('status', ['unpaid', 'partial'])->selectRaw('SUM(total_amount - paid_amount) as sisa')->value('sisa') ?? 0;

        return view('debts.index', compact('debts', 'type', 'totalPiutang', 'totalHutang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:receivable,payable',
            'person_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'total_amount' => 'required|numeric|min:1',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string|max:255',
        ]);

        Debt::create($request->only('type', 'person_name', 'phone', 'total_amount', 'due_date', 'notes'));

        return back()->with('success', 'Data hutang/piutang berhasil dicatat.');
    }

    public function show(Debt $debt)
    {
        $debt->load('payments');
        return view('debts.show', compact('debt'));
    }

    public function pay(Request $request, Debt $debt)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $debt->remaining,
            'notes' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $debt) {
            DebtPayment::create([
                'debt_id' => $debt->id,
                'amount' => $request->amount,
                'notes' => $request->notes,
            ]);

            $debt->paid_amount += $request->amount;

            if ($debt->paid_amount >= $debt->total_amount) {
                $debt->status = 'paid';
            } else {
                $debt->status = 'partial';
            }

            $debt->save();
        });

        return back()->with('success', 'Pembayaran berhasil dicatat. Sisa: Rp ' . number_format($debt->remaining, 0, ',', '.'));
    }

    public function destroy(Debt $debt)
    {
        if ($debt->paid_amount > 0) {
            return back()->with('error', 'Tidak bisa menghapus data yang sudah ada pembayarannya.');
        }

        $debt->delete();
        return back()->with('success', 'Data hutang/piutang berhasil dihapus.');
    }
}
