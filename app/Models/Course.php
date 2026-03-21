<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // Cho phép Laravel lưu dữ liệu vào các cột này
    protected $fillable = [
        'title',
        'thumbnail',
        'description',
        'price'
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order', 'asc');
    }

    public function enrollments()
    {
        // Một khóa học có thể có nhiều lượt đăng ký
        return $this->hasMany(Enrollment::class);
    }
}
