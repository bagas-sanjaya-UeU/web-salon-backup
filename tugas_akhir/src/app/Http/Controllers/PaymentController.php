<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('booking.user')->latest()->get();
        return view('payments.index', compact('payments'));
    }

    public function show($id)
    {
        $payment = Payment::with('booking.services')->findOrFail($id);
        return view('payments.show', compact('payment'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:paid,unpaid',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update([
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}

