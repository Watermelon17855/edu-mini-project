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

        // 1. Ghi log để kiểm tra (xem trong storage/logs/laravel.log)
        Log::info('SePay Webhook Data:', $data);

        // 2. Lấy nội dung chuyển khoản (Ví dụ: "Thanh toan EDU123")
        $content = $data['content'] ?? '';

        // 3. Dùng Regex để "móc" lấy ID đơn hàng (ví dụ mã đơn là EDU123)
        if (preg_match('/EDU(\d+)/', $content, $matches)) {
            $transactionId = $matches[1];

            // 4. Tìm giao dịch trong Database
            $transaction = Transaction::find($transactionId);

            if ($transaction && $transaction->status !== 'completed') {
                // Kiểm tra xem số tiền có khớp không (tùy chọn nhưng nên có)
                if ($data['transferAmount'] >= $transaction->amount) {

                    // Cập nhật trạng thái thành công
                    $transaction->update(['status' => 'completed']);

                    // TẠI ĐÂY: Bạn viết thêm code để mở khóa học cho User
                    // Ví dụ: $transaction->user->courses()->attach($transaction->course_id);

                    return response()->json(['success' => true, 'message' => 'Xác nhận thành công!']);
                }
            }
        }

        return response()->json(['success' => false, 'message' => 'Không tìm thấy đơn hàng']);
    }
}
