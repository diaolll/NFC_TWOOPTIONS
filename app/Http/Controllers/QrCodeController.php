<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{


    /**
     * Halaman lihat QR Code sendiri (MAHASISWA ONLY)
     */
    public function myQrCode(Request $request)
    {
        $user = $request->user();

        // Security: Pastikan hanya mahasiswa yang bisa akses, bukan admin
        if ($user->role === 'admin') {
            abort(403, 'Admin tidak bisa mengakses fitur ini. Gunakan /qrcode/download/{userId} untuk download QR mahasiswa.');
        }

        // Generate QR data
        $qrData = [
            'type' => 'attendance',
            'user_id' => $user->id,
            'nim' => $user->nim,
            'name' => $user->name,
            'timestamp' => now()->timestamp,
        ];

        return view('qrcode.my-code', [
            'user' => $user,
            'qrData' => json_encode($qrData),
        ]);
    }

    /**
     * Halaman scan QR Code
     */
    public function scan()
    {
        return view('qrcode.scan');
    }

    /**
     * Handle hasil scan QR Code
     */
    public function scanSubmit(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string',
        ]);

        try {
            $qrData = json_decode($request->qr_data, true);

            // Validasi QR Code format
            if (!isset($qrData['type']) || $qrData['type'] !== 'attendance') {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid. Harap scan QR Code absensi.',
                ], 400);
            }

            // Cari user berdasarkan user_id dari QR
            $user = User::find($qrData['user_id']);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.',
                ], 404);
            }

            // Cek nfc card untuk user ini (atau create virtual)
            $nfcCard = $user->nfcCard;

            if (!$nfcCard) {
                // Create virtual NFC card untuk QR Code users
                $nfcCard = \App\Models\NfcCard::create([
                    'user_id' => $user->id,
                    'serial_number' => 'QR-' . $user->id,
                    'data' => json_encode($qrData),
                    'is_active' => true,
                    'issued_at' => now(),
                ]);
            }

            // Tentukan status (present/late) berdasarkan waktu
            $currentTime = now()->format('H:i');
            $lateTime = config('attendance.late_time', '09:00');
            $status = $currentTime > $lateTime ? 'late' : 'present';

            // Cek apakah sudah absen hari ini
            $existingAttendance = Attendance::today()
                ->where('user_id', $user->id)
                ->where('nfc_card_id', $nfcCard->id)
                ->first();

            if ($existingAttendance) {
                return response()->json([
                    'success' => true,
                    'message' => 'Anda sudah absen hari ini pukul ' . $existingAttendance->scanned_at->format('H:i'),
                    'data' => [
                        'user' => [
                            'name' => $user->name,
                            'nim' => $user->nim,
                        ],
                        'attendance' => [
                            'scanned_at' => $existingAttendance->scanned_at->toW3cString(),
                            'status' => $existingAttendance->status,
                        ],
                    ],
                    'already_recorded' => true,
                ]);
            }

            // Buat record absensi baru
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'nfc_card_id' => $nfcCard->id,
                'status' => $status,
                'scanned_at' => now(),
                'scanner_device' => 'QR Code Scanner - ' . $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil dicatat!',
                'data' => [
                    'user' => [
                        'name' => $user->name,
                        'nim' => $user->nim,
                    ],
                    'attendance' => [
                        'scanned_at' => $attendance->scanned_at->toW3cString(),
                        'status' => $status,
                    ],
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download QR Code untuk dicetak
     */
    public function download(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $qrData = [
            'type' => 'attendance',
            'user_id' => $user->id,
            'nim' => $user->nim,
            'timestamp' => now()->timestamp,
        ];

        $qrString = json_encode($qrData);

        // Generate QR Code SVG
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(400),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $writer = new \BaconQrCode\Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrString);

        return response($qrCodeSvg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="qrcode-' . ($user->nim ?? $user->id) . '.svg"');
    }
}
