<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Course::create([
            'title' => 'Lập trình Laravel cho người mới bắt đầu',
            'thumbnail' => 'https://via.placeholder.com/300x200',
            'description' => 'Học cách xây dựng ứng dụng web hiện đại với Laravel Framework.',
            'price' => 500000,
        ]);

        Course::create([
            'title' => 'Thiết kế giao diện với Tailwind CSS',
            'thumbnail' => 'https://via.placeholder.com/300x200',
            'description' => 'Làm chủ CSS framework hot nhất hiện nay.',
            'price' => 300000,
        ]);

        Course::create([
            'title' => 'Fullstack Web với Nodejs & React',
            'thumbnail' => 'https://via.placeholder.com/300x200',
            'description' => 'Trở thành lập trình viên Fullstack trong 6 tháng.',
            'price' => 1200000,
        ]);
    }
}
