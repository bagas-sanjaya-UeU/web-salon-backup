<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;


class WebhookController extends Controller
{
    public function midtransCallback(Request $request)
    {
        $notification = $request->all();

        // Validasi input data
        if (!isset($notification['order_id'], $notification['status_code'], $notification['gross_amount'], $notification['signature_key'])) {
            Log::error('Invalid notification payload', $notification);
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // Midtrans server key untuk autentikasi
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashedSignatureKey = hash(
            'sha512',
            $notification['order_id'] .
            $notification['status_code'] .
            $notification['gross_amount'] .
            $serverKey
        );

        // Validasi signature key dari Midtrans
        if ($notification['signature_key'] !== $hashedSignatureKey) {
            Log::warning('Invalid signature key from Midtrans');
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        $transaction = $notification['transaction_status'];
        $order_id = $notification['order_id'];
        $payment_type = $notification['payment_type'];
        $fraud = $notification['fraud_status'];

        $payment = Payment::where('midtrans_order_id', $order_id)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        switch ($transaction) {
            case 'capture':
                if ($notification['payment_type'] === 'credit_card' && $notification['fraud_status'] === 'challenge') {
                    $booking = Booking::find($payment->booking_id);
                    if ($booking) {
                        $booking->update(['payment_status' => 'unpaid', 'status' => 'pending']);
                        Log::info('Booking status updated to pending', ['booking' => $booking]);
                    }
                    $payment->payment_status = 'unpaid'; // Pembayaran perlu di-review
                } else {
                    $booking = Booking::find($payment->booking_id);
                    if ($booking) {
                        $booking->update(['payment_status' => 'paid', 'status' => 'comfirmed']);
                        Log::info('Booking status updated to confirmed', ['booking' => $booking]);
                    }
                    $payment->payment_status = 'paid'; // Pembayaran berhasil
                }
                break;
            case 'settlement':
                $booking = Booking::find($payment->booking_id);
                if ($booking) {
                    $booking->update(['payment_status' => 'paid', 'status' => 'confirmed']);
                    Log::info('Booking status updated to confirmed', ['booking' => $booking]);
                }
                $payment->payment_status = 'paid'; // Pembayaran berhasil
                Log::info('Payment status updated to pending', ['payment' => $payment]);
                
                break;
            case 'deny':
                $booking = Booking::find($payment->booking_id);
                if ($booking) {
                    $booking->update(['payment_status' => 'unpaid', 'status' => 'pending']);
                    Log::info('Booking status updated to canceled', ['booking' => $booking]);
                }
                $payment->payment_status = 'unpaid';
                Log::info('Payment status updated to unpaid', ['payment' => $payment]);
                break;
            case 'cancel':
                $booking = Booking::find($payment->booking_id);
                if ($booking) {
                    $booking->update(['payment_status' => 'unpaid', 'status' => 'canceled']);
                    Log::info('Booking status updated to canceled', ['booking' => $booking]);
                }
                $payment->payment_status = 'unpaid';
                Log::info('Payment status updated to unpaid', ['payment' => $payment]);
                break;
            case 'expire':
                $booking = Booking::find($payment->booking_id);
                if ($booking) {
                    $booking->update(['payment_status' => 'unpaid', 'status' => 'pending']);
                    Log::info('Booking status updated to expired', ['booking' => $booking]);
                }
                $payment->payment_status = 'unpaid';
                break;
            case 'pending':
                $booking = Booking::find($payment->booking_id);
                if ($booking) {
                    $booking->update(['payment_status' => 'unpaid', 'status' => 'pending']);
                    Log::info('Booking status updated to pending', ['booking' => $booking]);
                }
                $payment->payment_status = 'unpaid';
                break;
            default:
                Log::warning('Unknown transaction status', ['transaction' => $transaction]);
                return response()->json(['message' => 'Unknown transaction status'], 400);
        }

       
        $payment->save();

        
        return response()->json(['message' => 'Payment status updated']);
    }
}

