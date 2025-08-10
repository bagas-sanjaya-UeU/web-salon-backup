<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\CartController;

Route::post('/midtrans/notification', [WebhookController::class, 'midtransCallback']);
