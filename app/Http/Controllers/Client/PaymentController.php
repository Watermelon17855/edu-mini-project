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
        // 1. Tìm khóa học và lấy User hiện tại
        $course = \App\Models\Course::findOrFail($id);
        $user = \Illuminate\Support\Facades\Auth::user();

        // 2. KIỂM TRA QUYỀN SỞ HỮU: Nếu đã mua rồi thì đá về trang chủ ngay
        $isEnrolled = \App\Models\Enrollment::where('user_id', $user->id)
            ->where('course_id', $id)
            ->exists();

        if ($isEnrolled) {
            // Quay về trang chủ kèm thông báo (giúp xử lý vụ bấm Back trình duyệt)
            return redirect()->route('client.home')->with('success', 'Bạn đã sở hữu khóa học này rồi!');
        }

        // 3. TẠO HOẶC CẬP NHẬT GIAO DỊCH: 
        // Dùng updateOrCreate để nếu giá khóa học thay đổi thì mã QR cũng đổi theo
        $transaction = \App\Models\Transaction::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'status' => 'pending'
            ],
            [
                'amount' => $course->price, // Luôn cập nhật giá mới nhất
                'code' => 'EDU' . time() . $user->id, // Tạo mã nội dung chuyển khoản duy nhất
            ]
        );

        // 4. Trả về View (đã sửa đường dẫn thành client.checkout như bạn để file)
        return view('client.checkout', compact('course', 'transaction', 'user'));
    }
}
