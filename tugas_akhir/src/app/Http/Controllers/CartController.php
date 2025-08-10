<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add($serviceId)
    {
        $user = Auth::user();
        $service = Service::findOrFail($serviceId);

        $existing = Cart::where('user_id', $user->id)->where('service_id', $serviceId)->first();

        if ($existing) {
            $existing->increment('quantity');
        } else {
            Cart::create([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'quantity' => 1,
            ]);
        }

        return redirect()->back()->with('success', 'Layanan ditambahkan ke keranjang.');
    }

    public function index()
    {
        $cartItems = Cart::with('service')->where('user_id', Auth::id())->get();
        return view('pages.cart.index', compact('cartItems'));
    }

    public function remove($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();

        return redirect()->back()->with('success', 'Layanan berhasil dihapus dari keranjang.');
    }

    public function addApi($serviceId)
    {
        $user = Auth::user();
        $service = Service::findOrFail($serviceId);

        $existing = Cart::where('user_id', $user->id)->where('service_id', $serviceId)->first();

        if ($existing) {
            $existing->increment('quantity');
        } else {
            Cart::create([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'quantity' => 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Layanan ditambahkan ke keranjang.',
        ]);
    }

    public function removeApi($serviceId)
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->where('service_id', $serviceId)->first();
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan di keranjang.',
            ]);
        }
        // Check if the cart item belongs to the authenticated user
        if ($cart->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.',
            ], 403);
        }
        // Delete the cart item
        $cart->delete();

        // Return a success response
        
        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang.',
        ]);
    }

    public function cartDashboard()
    {
        $cartItems = Cart::with('service')->where('user_id', Auth::id())->get();
        return view('dashboards.booking.cart', compact('cartItems'));
    }
}

