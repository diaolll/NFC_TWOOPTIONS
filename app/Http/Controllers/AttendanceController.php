<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Attendance::with(['user', 'nfcCard'])
            ->orderByDesc('scanned_at');

        if ($request->filled('date')) {
            $query->whereDate('scanned_at', $request->date);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->paginate(20);

        $stats = [
            'today' => Attendance::today()->count(),
            'present' => Attendance::today()->present()->count(),
            'late' => Attendance::today()->late()->count(),
            'total' => Attendance::count(),
        ];

        $students = \App\Models\User::where('role', 'student')
            ->orderBy('name')
            ->get();

        return view('attendances.index', compact('attendances', 'stats', 'students'));
    }

    public function today(): JsonResponse
    {
        $attendances = Attendance::today()
            ->with(['user', 'nfcCard'])
            ->orderByDesc('scanned_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $attendances,
        ]);
    }

    public function stats(): JsonResponse
    {
        $stats = [
            'today' => Attendance::today()->count(),
            'present' => Attendance::today()->present()->count(),
            'late' => Attendance::today()->late()->count(),
            'total' => Attendance::count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    public function export(Request $request): JsonResponse
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $attendances = Attendance::with(['user'])
            ->whereBetween('scanned_at', [$startDate, $endDate])
            ->orderByDesc('scanned_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $attendances,
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
                'count' => $attendances->count(),
            ],
        ]);
    }
}
