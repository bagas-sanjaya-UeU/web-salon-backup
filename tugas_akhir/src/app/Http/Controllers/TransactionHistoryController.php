<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionHistory;
use App\Models\Worker;
use App\Models\User;
use App\Models\Booking;


// Model untuk mengelola riwayat transaksi
// dan pekerjaan yang dilakukan oleh pekerja

class TransactionHistoryController extends Controller
{
    public function index()
    {
        if(auth()->user()->role == 'worker') {
            $worker = Worker::where('user_id', auth()->id())->first();
            $histories = TransactionHistory::with(['booking.user', 'booking.services'])
                ->where('worker_id', $worker->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $histories = TransactionHistory::with(['booking.user', 'worker.user'])
            ->orderBy('created_at', 'desc')
            ->get();
        }
        return view('dashboards.worker-histories.index', compact('histories'));
    }

    public function show($id)
    {
        $history = TransactionHistory::with(['booking.user', 'booking.services', 'worker.user'])
            ->findOrFail($id);

        return view('dashboards.worker-histories.detail', compact('history'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:ongoing,completed,canceled',
            'confirmation_status' => 'required|in:pending,confirmed',
        ]);

        $history = TransactionHistory::findOrFail($id);
        $history->update($request->only('status', 'confirmation_status'));

        return back()->with('success', 'Riwayat pekerjaan berhasil diperbarui.');
    }

    public function updateClear(Request $request, $id)
    {
        
        $history = TransactionHistory::findOrFail($id);
        $history->update([
            'status' => 'completed',
            'confirmation_status' => 'confirmed',
        ]);

        // Update booking status to completed
        $booking = Booking::findOrFail($history->booking_id);
        $booking->update([
            'status' => 'completed',
        ]);

        return back()->with('success', 'Riwayat pekerjaan berhasil diperbarui.');
    }

   

}
