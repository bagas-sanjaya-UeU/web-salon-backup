<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Service;
use App\Models\BookingService;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use Midtrans\Snap;
use Carbon\Carbon;
use Midtrans\Config;
use App\Models\Worker;
use App\Models\TransactionHistory;
use App\Models\User;



class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('user', 'services')
            ->orderBy('created_at', 'desc')
            ->get();

        $workers  = Worker::with('user')
            ->where('availability_status', 'available')
            ->get();

        return view('dashboards.booking.index', compact('bookings', 'workers'));
    }

    public function detail($id)
    {
        $booking = Booking::with('user', 'services')->findOrFail($id);

        $worker = TransactionHistory::where('booking_id', $id)
            ->with('worker')
            ->first();

        return view('dashboards.booking.detail', compact('booking', 'worker'));
    }

    public function setWorker(Request $request, $id)
    {
        
        $request->validate([
            'worker_id' => 'required',
        ]);

        $booking = Booking::findOrFail($id);

        DB::beginTransaction();

        try {
            
            TransactionHistory::create([
                'booking_id' => $booking->id,
                'worker_id' => $request->worker_id,
            ]);

            // Update worker availability status
            $worker = Worker::findOrFail($request->worker_id);
            $worker->update(['availability_status' => 'unavailable']);

            DB::commit();
            return back()->with('success', 'Pekerja berhasil ditugaskan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
            'booking_date' => 'required|date',
            'booking_time' => 'required',
            'home_service' => 'required|boolean',
            'distance' => 'nullable|numeric',
        ]);

        try {
            DB::beginTransaction();

            $services = Service::whereIn('id', $request->services)->get();
            $totalPrice = $services->sum('price');
            $shippingFee = ($request->home_service && $request->distance)
                ? $request->distance * 5000 : 0;
            $grandTotal = $totalPrice + $shippingFee;

            $booking = Booking::create([
                'user_id' => $request->user_id,
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'status' => 'pending',
                'total_price' => $grandTotal,
                'home_service' => $request->home_service,
                'distance' => $request->distance,
                'shipping_fee' => $shippingFee,
                'payment_status' => 'unpaid',
            ]);

            foreach ($services as $service) {
                BookingService::create([
                    'booking_id' => $booking->id,
                    'service_id' => $service->id,
                    'price' => $service->price,
                ]);
            }

            DB::commit();

            return redirect('/booking')->with('success', 'Booking berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancel(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if (auth()->id() !== $booking->user_id) {
            return back()->with('error', 'Kamu tidak berhak membatalkan booking ini.');
        }

        if (in_array($booking->status, ['completed', 'canceled'])) {
            return back()->with('error', 'Booking sudah tidak bisa dibatalkan.');
        }

        if ($booking->created_at->diffInMinutes(now()) > 30) {
            return back()->with('error', 'Batal booking hanya bisa dilakukan dalam 30 menit.');
        }

        $booking->update(['status' => 'canceled']);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    public function checkout()
    {
        $cartItems = \App\Models\Cart::with('service')
            ->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Keranjang kamu kosong.');
        }

        return view('pages.booking.checkout', compact('cartItems'));
    }

    public function processCheckout(Request $request)
    {
        
        $request->validate([
            'booking_date' => 'required|date',
            'booking_time' => 'required',
            'home_service' => 'required|in:0,1',
            'address' => 'required_if:home_service,1|string|max:255',
            'phone' => 'required_if:home_service,1|max:15',
            'distance' => 'nullable|numeric',
            'shipping_fee' => 'nullable|numeric',
        ]);
        

        $cartItems = \App\Models\Cart::with('service')
            ->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Keranjang kosong.');
        }

        DB::beginTransaction();

        try {
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item->service->price * $item->quantity;
            }

            $grandTotal = $total + $request->shipping_fee;

            User::where('id', auth()->id())->update([
                'address' => $request->home_service ? $request->address : null,
                'phone' => $request->home_service ? $request->phone : null,
            ]);

            $booking = \App\Models\Booking::create([
                'user_id' => auth()->id(),
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'status' => 'pending',
                'total_price' => $grandTotal,
                'home_service' => $request->home_service,
                'distance' => $request->distance,
                'shipping_fee' => $request->shipping_fee ?? 0,
                'payment_status' => 'unpaid',
            ]);

            foreach ($cartItems as $item) {
                \App\Models\BookingService::create([
                    'booking_id' => $booking->id,
                    'service_id' => $item->service->id,
                    'price' => $item->service->price,
                ]);
            }

            // Setup Midtrans
            // Set your Merchant Server Key
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            \Midtrans\Config::$isProduction = false;
            // Set sanitization on (default)
            \Midtrans\Config::$isSanitized = true;
            // Set 3DS transaction for credit card to true
            \Midtrans\Config::$is3ds = true;

            $orderId = 'BOOK-' . $booking->id . '-' . time();
            $snapPayload = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $grandTotal,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'phone' => auth()->user()->phone_number,
                ],
            ];

            $snapToken = Snap::getSnapToken($snapPayload);

            // Simpan ke tabel payments
            Payment::create([
                'booking_id' => $booking->id,
                'payment_date' => Carbon::now(),
                'payment_amount' => $grandTotal,
                'payment_method' => 'midtrans',
                'payment_status' => 'unpaid',
                'payment_token' => $snapToken,
                'midtrans_order_id' => $orderId,
            ]);

            // Kosongkan keranjang
            \App\Models\Cart::where('user_id', auth()->id())->delete();

            DB::commit();
            // Redirect ke halaman bayar
            return view('pages.booking.pay', compact('snapToken', 'grandTotal', 'orderId'));
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi Kesalahan Midtrans belum terhubung.');
        }
    }

    public function success(Request $request)
    {
        $orderId = $request->query('order_id');

        if (!$orderId) {
            abort(404, 'Order ID not found');
        }

        $payment = Payment::where('midtrans_order_id', $orderId)->first();
        if (!$payment) {
            abort(404, 'Payment not found');
        }

        $booking = Booking::find($payment->booking_id);
        if ($booking) {
            $booking->update([
                'payment_status' => 'paid',
                'status' => 'confirmed'
            ]);
        }

        return view('pages.booking.payment-success');
    }

    public function pending(Request $request)
    {
        return view('pages.booking.payment-pending');
    }
    public function failed(Request $request)
    {
        return view('pages.booking.payment-failed');
    }

    public function refundTrigger(Request $request, $id)
    {

        $request->validate([
            'cancel_status' => 'required|string|max:255',
        ]);

        $booking = Booking::findOrFail($id);

        $booking->update(['status' => 'canceled', 'cancel_status' => $request->cancel_status]);

        return back()->with('success', 'Refund berhasil diproses.');
    }

    public function getUnavailableTimes(Request $request)
    {
        $date = $request->input('date');

        $bookedTimes = \App\Models\Booking::whereDate('booking_date', $date)
            ->where('status', '!=', 'canceled') // ⛔ Jangan ambil booking yang sudah dibatalkan
            ->pluck('booking_time')
            ->map(fn ($time) => \Carbon\Carbon::parse($time)->format('H:i'));

        return response()->json($bookedTimes);
    }

    public function manualCreateBooking(){
        
        $services = Service::all();
        return view('dashboards.booking.create', compact('services'));
    }
    
    public function checkoutAdmin(){
        $cartItems = \App\Models\Cart::with('service')
            ->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Keranjang kamu kosong.');
        }

        return view('dashboards.booking.checkout', compact('cartItems'));
    }

    public function manualCheckoutProcess(Request $request)
    {
        $request->validate([
            'booking_date' => 'required|date',
            'booking_time' => 'required',
            'home_service' => 'required|in:0,1',
            'address' => 'required|string|max:255',
            'phone' => 'required|max:15',
            'distance' => 'nullable|numeric',
            'shipping_fee' => 'nullable|numeric',
            'name' => 'required|string|max:255',
        ]);
        

        $cartItems = \App\Models\Cart::with('service')
            ->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Keranjang kosong.');
        }

        DB::beginTransaction();

        try {
            $total = 0;
           
            // Cek apakah ada request shipping_fee
            if ($request->has('shipping_fee')) {
                $shippingFee = $request->shipping_fee;
            } else {
                $shippingFee =  ($request->home_service) ? 30000 : 0;
            }

                
            foreach ($cartItems as $item) {
                $total += $item->service->price * $item->quantity;
            }

            $grandTotal = $total + $shippingFee;

           

            $booking = \App\Models\Booking::create([
                'user_id' => auth()->id(),
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'status' => 'pending',
                'total_price' => $grandTotal,
                'home_service' => $request->home_service,
                'distance' => $request->distance,
                'shipping_fee' => $shippingFee,
                'payment_status' => 'unpaid',
                'name' => $request->name,
                'address' => $request->address,
                'phone' =>$request->phone,
            ]);

            foreach ($cartItems as $item) {
                \App\Models\BookingService::create([
                    'booking_id' => $booking->id,
                    'service_id' => $item->service->id,
                    'price' => $item->service->price,
                ]);
            }

            // Setup Midtrans
            // Set your Merchant Server Key
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            \Midtrans\Config::$isProduction = false;
            // Set sanitization on (default)
            \Midtrans\Config::$isSanitized = true;
            // Set 3DS transaction for credit card to true
            \Midtrans\Config::$is3ds = true;

            $orderId = 'BOOK-' . $booking->id . '-' . time();
            $snapPayload = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $grandTotal,
                ],
                'customer_details' => [
                    'first_name' => $request->name,
                    'email' => auth()->user()->email,
                    'phone' => $request->phone,
                ],
            ];

            $snapToken = Snap::getSnapToken($snapPayload);

            // Simpan ke tabel payments
            Payment::create([
                'booking_id' => $booking->id,
                'payment_date' => Carbon::now(),
                'payment_amount' => $grandTotal,
                'payment_method' => 'midtrans',
                'payment_status' => 'unpaid',
                'payment_token' => $snapToken,
                'midtrans_order_id' => $orderId,
            ]);

            // Kosongkan keranjang
            \App\Models\Cart::where('user_id', auth()->id())->delete();

            DB::commit();
            // Redirect ke halaman bayar
            return view('dashboards.booking.pay', compact('snapToken', 'grandTotal', 'orderId'));
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi Kesalahan Midtrans belum terhubung.');
        }
    }

    public function ubahStatusPembayaran(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'status' => 'required|in:paid,unpaid',
        ]);

        if ($booking->payment_status === $request->status) {
            return back()->with('error', 'Status pembayaran sudah sesuai.');
        }

        if ($request->status === 'paid') {
            $booking->update(['payment_status' => 'paid', 'status' => 'confirmed']);
        } else {
            $booking->update(['payment_status' => 'unpaid', 'status' => 'pending']);
        }

        return back()->with('success', 'Status booking berhasil diubah.');
    }

}
