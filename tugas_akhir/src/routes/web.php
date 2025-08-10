<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;

Route::get('/', [PageController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/services/{id}/detail', [PageController::class, 'serviceDetail'])->name('services.detail');

Route::get('/payment/success', [BookingController::class, 'success'])->name('booking.success');
Route::get('/payment/pending', [BookingController::class, 'pending'])->name('booking.pending');
Route::get('/payment/failed', [BookingController::class, 'failed'])->name('booking.error');

Route::get('/booking/unavailable-times', [BookingController::class, 'getUnavailableTimes'])->name('booking.unavailable-times');

Route::get('forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'reset'])->name('password.update');

Route::group(['middleware' => 'auth'], function () {

    // Authenticated Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Cart

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    Route::get('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::post('/cart/add/api/{serviceId}', [CartController::class, 'addApi'])->name('cart.api.add');
    Route::delete('/cart/remove/api/{serviceId}', [CartController::class, 'removeApi'])->name('cart.api.remove');
    Route::get('/cart/count', function () {
        $count = \App\Models\Cart::where('user_id', auth()->id())->count();
        return response()->json(['count' => $count]);
    })->name('cart.count');

    Route::get('/booking/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
    Route::post('/booking/checkout', [BookingController::class, 'processCheckout'])->name('booking.process');

    Route::get('/profile', [CustomerController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [CustomerController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [CustomerController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [CustomerController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/change-password', [CustomerController::class, 'updatePassword'])->name('profile.update-password');

    Route::get('/dashboard/user/transaction-history', [CustomerController::class, 'transactionHistory'])->name('user.transaction-history');
    Route::get('/dashboard/user/transaction-history/{id}/detail', [CustomerController::class, 'transactionHistoryDetail'])->name('user.transaction-history.detail');
    Route::post('/dashboard/user/transaction-history/{id}/cancel', [CustomerController::class, 'cancelTransaction'])->name('user.transaction-history.cancel');
    Route::post('/dashboard/user/transaction-history/{id}/rating', [CustomerController::class, 'giveRating'])->name('user.transaction-history.rating');



    Route::group(['middleware' => 'is_staff'], function () {
        // Staff Routes (Worker and Admin)

        // Transaction History
        Route::get('/dashboard/worker-history', [TransactionHistoryController::class, 'index'])->name('dashboard.transaction-history.index');
        Route::get('/dashboard/worker-history/{id}/edit', [TransactionHistoryController::class, 'edit'])->name('dashboard.transaction-history.edit');
        Route::put('/dashboard/worker-history/{id}/update', [TransactionHistoryController::class, 'update'])->name('dashboard.transaction-history.update');
        Route::delete('/dashboard/worker-history/{id}/delete', [TransactionHistoryController::class, 'destroy'])->name('dashboard.transaction-history.destroy');
        Route::get('/dashboard/worker-history/{id}/detail', [TransactionHistoryController::class, 'show'])->name('dashboard.transaction-history.detail');
        Route::post('/dashboard/worker-history/{id}/update-clear', [TransactionHistoryController::class, 'updateClear'])->name('dashboard.transaction-history.confirm');
        
    });

    Route::group(['middleware' => 'is_admin'], function () {
        // Admin Routes

        // Transaction History
        Route::get('/dashboard/transaction-history', [BookingController::class, 'index'])->name('dashboard.bookings.index');
        Route::get('/dashboard/transaction-history/{id}/detail', [BookingController::class, 'detail'])->name('dashboard.bookings.detail');
        Route::post('/dashboard/transaction-history/setWorker/{id}', [BookingController::class, 'setWorker'])->name('dashboard.bookings.setWorker');
        Route::post('/dashboard/transaction-history/refundTrigger/{id}', [BookingController::class, 'refundTrigger'])->name('dashboard.bookings.refundTrigger');


        // Services
        Route::get('/dashboard/services', [ServiceController::class, 'index'])->name('dashboard.services.index');
        Route::get('/dashboard/services/create', [ServiceController::class, 'create'])->name('dashboard.services.create');
        Route::post('/dashboard/services/store', [ServiceController::class, 'store'])->name('dashboard.services.store');
        Route::get('/dashboard/services/{id}/edit', [ServiceController::class, 'edit'])->name('dashboard.services.edit');
        Route::put('/dashboard/services/{id}/update', [ServiceController::class, 'update'])->name('dashboard.services.update');
        Route::delete('/dashboard/services/{id}/delete', [ServiceController::class, 'destroy'])->name('dashboard.services.destroy');
        Route::get('/dashboard/services/{id}/detail', [ServiceController::class, 'detail'])->name('dashboard.services.detail');

        // workers
        Route::get('/dashboard/workers', [WorkerController::class, 'index'])->name('dashboard.workers.index');
        Route::get('/dashboard/workers/create', [WorkerController::class, 'create'])->name('dashboard.workers.create');
        Route::post('/dashboard/workers/store', [WorkerController::class, 'store'])->name('dashboard.workers.store');
        Route::get('/dashboard/workers/{id}/edit', [WorkerController::class, 'edit'])->name('dashboard.workers.edit');
        Route::put('/dashboard/workers/{id}/update', [WorkerController::class, 'update'])->name('dashboard.workers.update');
        Route::delete('/dashboard/workers/{id}/delete', [WorkerController::class, 'destroy'])->name('dashboard.workers.destroy');
        Route::get('/dashboard/workers/{id}/detail', [WorkerController::class, 'detail'])->name('dashboard.workers.detail');
        Route::post('/dashboard/workers/{id}/update-status', [WorkerController::class, 'updateStatus'])->name('dashboard.workers.update-status');
        Route::post('/dashboard/workers/update-role', [WorkerController::class, 'changeRole'])->name('dashboard.workers.changeRole');

        // Create Booking
        Route::get('/dashboard/create-booking', [BookingController::class, 'manualCreateBooking'])->name('dashboard.booking-menu.index');
        
        Route::get('/booking/checkout/service', [BookingController::class, 'checkoutAdmin'])->name('booking.admin.checkout');
        Route::post('/dashboard/create-booking', [BookingController::class, 'manualCheckoutProcess'])->name('dashboard.checkout.process');

        Route::patch('/dashboard/change-payment-status/{id}/update', [BookingController::class, 'ubahStatusPembayaran'])->name('dashboard.booking-menu.updatePaymentStatus');
        
        Route::get('/dashboard/cart', [CartController::class, 'cartDashboard'])->name('cart.admin.index');

    });

    

    
});

