<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Cart;


class PageController extends Controller
{
    public function index()
    {
        return view('pages.home');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function services()
    {
        $services = Service::all();
        return view('pages.service', compact('services'));
    }

    public function serviceDetail($id)
    {
        $service = Service::findOrFail($id);

        // Check if cart exists for the service by the user
        $cart = Cart::where('service_id', $id)->where('user_id', auth()->id())->first();
        if ($cart) {
            $service->is_in_cart = true;
        } else {
            $service->is_in_cart = false;
        }
        

        return view('pages.detail-service', compact('service'));
    }

    public function cart()
    {

        return view('pages.cart');
    }
}
