<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        switch (auth()->user()->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');

            case 'lecturer':
                return redirect()->route('lecturer.dashboard');

            case 'student':
                return redirect()->route('student.dashboard');

            default:
                return redirect()->route('login');
        }
    }
}
