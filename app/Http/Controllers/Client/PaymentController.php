<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Ghi log để sau này dễ kiểm tra trên server
        Log::info('SePay Data:', $request->all());

        $content = $request->input('content'); // Nội dung chuyển khoản
        $amount = $request->input('amount');   // Số tiền nhận được

        // Tìm mã đơn hàng từ nội dung (Regex lấy số sau chữ "EDU")
        // Ví dụ khách chuyển: "EDU 25" -> lấy được 25
        if (preg_match('/EDU\s*(\d+)/i', $content, $matches)) {
            $transactionId = $matches[1];

            $transaction = Transaction::where('id', $transactionId)
                ->where('status', 'pending')
                ->first();

            if ($transaction && $amount >= $transaction->amount) {
                // 1. Cập nhật trạng thái giao dịch
                $transaction->update(['status' => 'completed']);

                // 2. Kích hoạt quyền vào học (Enrollment)
                Enrollment::updateOrCreate([
                    'user_id' => $transaction->user_id,
                    'course_id' => $transaction->course_id
                ]);

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Invalid data']);
    }
}
