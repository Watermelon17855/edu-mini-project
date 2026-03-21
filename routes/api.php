<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Nhớ import đúng Controller này
use App\Http\Controllers\Client\PaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 1. Sửa lại để gọi đúng hàm handleWebhook trong PaymentController
Route::post('/sepay-webhook', [PaymentController::class, 'handleWebhook']);

// 2. Sửa lại tên route là /check-transaction cho khớp với đoạn fetch() ở trang Checkout
Route::get('/check-transaction/{id}', function ($id) {
    $transaction = \App\Models\Transaction::find($id);
    return response()->json([
        'status' => $transaction ? $transaction->status : 'not_found'
    ]);
});
