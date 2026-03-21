<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class SepayController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();

        // 1. Lấy nội dung chuyển khoản (Ví dụ: "EDU1774100278")
        $content = $data['content'] ?? '';

        // 2. Dùng Regex để tách lấy mã (Lấy cả chữ EDU và số)
        if (preg_match('/EDU\d+/', $content, $matches)) {
            $checkoutCode = $matches[0]; // Kết quả sẽ là "EDU1774100278"

            // 3. QUAN TRỌNG: Tìm theo cột 'code' chứ không phải 'id'
            $transaction = \App\Models\Transaction::where('code', $checkoutCode)->first();

            if ($transaction && $transaction->status !== 'completed') {
                // 4. Cập nhật trạng thái thành công
                $transaction->update(['status' => 'completed']);

                // (Tùy chọn) Ghi log để mình yên tâm
                \Illuminate\Support\Facades\Log::info("Đã thanh toán thành công đơn hàng: " . $checkoutCode);

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Transaction not found']);
    }
}
