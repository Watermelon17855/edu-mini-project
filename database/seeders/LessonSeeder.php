<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Lesson::create([
            'course_id' => 1, // ID của khóa học Laravel chẳng hạn
            'title' => 'Bài 1: Cài đặt môi trường',
            'content' => '<p>Hướng dẫn cài đặt PHP và Composer...</p>',
            'video_url' => 'dQw4w9WgXcQ', // ID video Youtube
            'order' => 1
        ]);

        \App\Models\Lesson::create([
            'course_id' => 1,
            'title' => 'Bài 2: Cấu trúc thư mục',
            'content' => '<p>Tìm hiểu các thư mục quan trọng trong Laravel...</p>',
            'video_url' => 'i6f_ZfLp-Xo',
            'order' => 2
        ]);
    }
}
