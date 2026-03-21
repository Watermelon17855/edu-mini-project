<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_courses' => Course::count(),
            'total_enrollments' => Enrollment::count(),
            'recent_enrollments' => Enrollment::with(['user', 'course'])->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
