<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Ghi log để kiểm tra dữ liệu SePay gửi sang
        Log::info('SePay Data:', $request->all());

        $content = $request->input('content'); // Nội dung chuyển khoản (VD: "EDU1774110415")
        $amount = $request->input('transferAmount'); // SePay dùng key 'transferAmount' cho số tiền thực nhận

        // 1. SỬA LOGIC: Regex lấy toàn bộ mã "EDU..." để tìm theo cột 'code'
        if (preg_match('/EDU\d+/i', $content, $matches)) {
            $checkoutCode = strtoupper($matches[0]); // Chuyển về chữ hoa cho khớp DB

            // 2. SỬA LOGIC: Tìm theo cột 'code' thay vì 'id'
            $transaction = Transaction::where('code', $checkoutCode)
                ->where('status', 'pending')
                ->first();

            if ($transaction && $amount >= $transaction->amount) {
                // Cập nhật trạng thái giao dịch
                $transaction->update(['status' => 'completed']);

                // Kích hoạt quyền vào học (Enrollment)
                Enrollment::updateOrCreate([
                    'user_id' => $transaction->user_id,
                    'course_id' => $transaction->course_id
                ]);

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Invalid data or transaction not found']);
    }

    public function showCheckout($id)
    {
        $course = Course::findOrFail($id);
        $user = Auth::user();

        // Tạo hoặc lấy giao dịch đang chờ
        $transaction = Transaction::firstOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'status' => 'pending'
            ],
            [
                'amount' => $course->price,
                'code' => 'EDU' . time() . $user->id,
            ]
        );

        // 3. SỬA LOGIC: Thêm 'user' vào compact để trang checkout không báo lỗi undefined
        return view('client.payment.checkout', compact('course', 'transaction', 'user'));
    }
}
