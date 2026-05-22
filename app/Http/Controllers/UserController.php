<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', 'student')
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(20);

        return view('users.index', compact('students'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nim' => 'required|string|max:50|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = 'student';
        $validated['email_verified_at'] = now();

        User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function show(User $user): View
    {
        $this->authorizeStudentAccess($user);

        $attendances = $user->attendances()
            ->orderByDesc('scanned_at')
            ->paginate(20);

        $stats = [
            'total' => $user->attendances()->count(),
            'present' => $user->attendances()->where('status', 'present')->count(),
            'late' => $user->attendances()->where('status', 'late')->count(),
            'this_month' => $user->attendances()
                ->whereMonth('scanned_at', now()->month)
                ->whereYear('scanned_at', now()->year)
                ->count(),
        ];

        return view('users.show', compact('user', 'attendances', 'stats'));
    }

    public function edit(User $user): View
    {
        $this->authorizeStudentAccess($user);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeStudentAccess($user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nim' => 'required|string|max:50|unique:users,nim,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeStudentAccess($user);

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Mahasiswa berhasil dihapus.');
    }

    public function showMyAttendance(Request $request): View
    {
        $user = $request->user();

        $query = $user->attendances()->orderByDesc('scanned_at');

        if ($request->filled('date_from')) {
            $query->whereDate('scanned_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('scanned_at', '<=', $request->date_to);
        }

        $attendances = $query->paginate(20);

        $stats = [
            'total' => $user->attendances()->count(),
            'present' => $user->attendances()->where('status', 'present')->count(),
            'late' => $user->attendances()->where('status', 'late')->count(),
            'this_month' => $user->attendances()
                ->whereMonth('scanned_at', now()->month)
                ->whereYear('scanned_at', now()->year)
                ->count(),
        ];

        return view('users.my-attendance', compact('user', 'attendances', 'stats'));
    }

    private function authorizeStudentAccess(User $user): void
    {
        if (auth()->user()->role !== 'admin' && auth()->id() !== $user->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }
}
