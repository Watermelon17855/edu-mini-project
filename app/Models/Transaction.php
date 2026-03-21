<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // 1. Khai báo các cột được phép thêm/sửa dữ liệu
    protected $fillable = [
        'user_id',
        'course_id',
        'amount',
        'code',
        'status'
    ];

    // 2. Thiết lập mối quan hệ với User (để biết ai thanh toán)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 3. Thiết lập mối quan hệ với Course (để biết mua khóa nào)
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
