<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Models\Service;


class CustomerController extends Controller
{
    public function transactionHistory(){
        $bookings = Booking::with('user', 'services')
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboards.user-histories.index', compact('bookings'));
   }

    public function transactionHistoryDetail($id){
        $booking = Booking::with(['user', 'services'])->where('user_id', auth()->id())->where('id', $id)->firstOrFail();

        $worker = \App\Models\TransactionHistory::with('worker.user')
            ->where('booking_id', $booking->id)
            ->first();

        return view('dashboards.user-histories.detail', compact('booking', 'worker'));
    }

    public function cancelTransaction($id)
    {
        $request = request();

        if ($request->has(['bank_name', 'bank_account_number', 'bank_account_name'])) {
            // Lalu validasi
            $validated = $request->validate([
                'bank_name' => 'required|string|max:255',
                'bank_account_number' => 'required|string|max:255',
                'bank_account_name' => 'required|string|max:255',
            ]);

            // Update user bank info
            auth()->user()->update([
                'bank_name' => $validated['bank_name'],
                'bank_account_number' => $validated['bank_account_number'],
                'bank_account_name' => $validated['bank_account_name'],
            ]);
        }

        // Cari booking
        $booking = Booking::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        // Cek durasi booking
        if ($booking->created_at->diffInMinutes(now()) > 30) {
            // Jika sudah lebih dari 30 menit, tidak bisa dibatalkan
            $booking->update([
                'status' => 'completed',
            ]);
            
            return redirect()->back()->with('error', 'Transaksi tidak dapat dibatalkan setelah 30 menit.');
        }

        // Update status booking
        $booking->update([
            'status' => 'canceled',
            'cancel_status' => 'requested',
        ]);

        return redirect()->back()->with('success', 'Transaction canceled successfully.');
    }

    public function index()
    {
        $user = auth()->user();
        return view('dashboards.profile.index', compact('user'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'string|max:255',
            'phone' => 'string|max:255',
            'address' => 'string|max:255',
        ]);

        $user = auth()->user();
        $user->update($request->only('name', 'phone', 'address'));

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
        ],
        [
            'current_password.required' => 'Password Sekarang diperlukan.',
            'current_password.string' => 'Password Sekarang harus berupa Karakter.',
            'new_password.required' => 'Password baru diperlukan.',
            'new_password.string' => 'Password baru harus berupa Karakter.',
            'new_password.confirmed' => 'Password baru tidak cocok.',
            'new_password.min' => 'Password baru harus terdiri dari minimal 8 karakter.',
            'new_password_confirmation.string' => 'Konfirmasi password harus berupa Karakter.',
            'new_password_confirmation.required' => 'Konfirmasi password diperlukan.',
            'new_password_confirmation.min' => 'Konfirmasi password harus terdiri dari minimal 8 karakter.',
        ]);

        $user = auth()->user();

        if (!password_verify($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Password Sekarang tidak cocok.');
        }

        $user->update(['password' => bcrypt($request->new_password)]);

        return redirect()->route('profile')->with('success', 'Password updated successfully.');
    }

    public function giveRating($id)
    {
        $request = request();

        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        // Cari booking
        $booking = Booking::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        // Cek apakah rating sudah diberikan
        if ($booking->rating) {
            return redirect()->back()->with('error', 'Rating sudah diberikan sebelumnya.');
        }
        // Give rating
        $booking->update([
            'rating' => $request->rating,
        ]); 

    
        return redirect()->back()->with('success', 'Rating berhasil diberikan.');
    }


}
