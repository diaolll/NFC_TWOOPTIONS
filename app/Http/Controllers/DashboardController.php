<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            // Admin sees all stats
            $stats = [
                'users' => User::count(),
                'students' => User::where('role', 'student')->count(),
                'attendances_today' => Attendance::today()->count(),
                'attendances_present' => Attendance::today()->where('status', 'present')->count(),
                'attendances_total' => Attendance::count(),
            ];

            $recentAttendances = Attendance::with(['user', 'nfcCard'])
                ->orderByDesc('scanned_at')
                ->limit(10)
                ->get();
        } else {
            // Student sees only their own stats
            $stats = [
                'attendances_today' => $user->attendances()->today()->count(),
                'attendances_present' => $user->attendances()->today()->where('status', 'present')->count(),
                'attendances_total' => $user->attendances()->count(),
                'attendances_late' => $user->attendances()->where('status', 'late')->count(),
            ];

            $recentAttendances = $user->attendances()
                ->with('nfcCard')
                ->orderByDesc('scanned_at')
                ->limit(10)
                ->get();
        }

        return view('dashboard', compact('stats', 'recentAttendances'));
    }
}
