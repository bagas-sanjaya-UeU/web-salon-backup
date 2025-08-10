<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\User;
use App\Models\Booking;
use App\Models\TransactionHistory;
use App\Models\Payment;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
{
    if (auth()->user()->role == 'admin') {
        $users = User::where('role', 'user')->count();
        $workers = Worker::count();
        $bookings = Booking::count();
        $transactions = TransactionHistory::count();
        $payments = Payment::count();
        $services = Service::count();

        // Menambahkan penghitungan jumlah pengunjung (pengguna unik yang memesan)
        $visitors = Booking::distinct('user_id')->count('user_id');

        // Menambahkan penghitungan total pendapatan
        $totalRevenue = Booking::where('payment_status', 'paid')->sum('total_price');


        // Booking Graph monthly
        $currentYear = Carbon::now()->year;

        // Inisialisasi array bulan
        $bulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        // Ambil total booking per bulan (di tahun ini)
        $bookingsPerMonth = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Siapkan data untuk chart
        $bookingCounts = [];
        foreach (range(1, 12) as $m) {
            $bookingCounts[] = $bookingsPerMonth[$m]->count ?? 0;
        }

        // PerHari
        $currentMonth = Carbon::now()->month;

        // Booking per hari bulan ini
        $bookingsPerDay = Booking::selectRaw('DATE(created_at) as day, COUNT(*) as count')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $tanggalHarian = $bookingsPerDay->pluck('day')->map(function ($day) {
            return Carbon::parse($day)->format('d M');
        })->toArray();

        $jumlahHarian = $bookingsPerDay->pluck('count')->toArray();


        // Per Minggu

        $bookingsPerWeek = Booking::selectRaw('WEEK(created_at, 1) as week_number, COUNT(*) as count')
            ->whereYear('created_at', $currentYear)
            ->groupBy('week_number')
            ->orderBy('week_number')
            ->get();

        $minggu = $bookingsPerWeek->pluck('week_number')->map(fn($week) => 'Minggu ' . $week)->toArray();
        $jumlahMingguan = $bookingsPerWeek->pluck('count')->toArray();


        return view('dashboards.dashboard', compact('users', 'workers', 'bookings', 'transactions', 'payments', 'services', 
        'bookingCounts', 'bulan', 'tanggalHarian', 'jumlahHarian', 'minggu', 'jumlahMingguan', 'visitors', 'totalRevenue'));
    } elseif (auth()->user()->role == 'worker') {
        $worker = Worker::where('user_id', auth()->id())->first();
        $histories = TransactionHistory::with(['booking.user', 'booking.services'])
            ->where('worker_id', $worker->id)
            ->orderBy('created_at', 'desc')
            ->count();
        

        return view('dashboards.dashboard', compact('histories'));
    } elseif (auth()->user()->role == 'user') {
        $bookings = Booking::where('user_id', auth()->id())->count();
        

        return view('dashboards.dashboard', compact('bookings'));
    } else {
        return redirect('/')->with('error', 'Unauthorized access.');
    }
}

    public function profile()
    {
        return view('dashboard.profile');
    }

    public function settings()
    {
        return view('dashboard.settings');
    }

    public function notifications()
    {
        return view('dashboard.notifications');
    }
}
