<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    // Cho phép lưu dữ liệu vào các cột này
    protected $fillable = [
        'user_id',
        'course_id',
        'status'
    ];

    // Thiết lập quan hệ để sau này lôi tên User hoặc tên Khóa học ra dễ dàng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
