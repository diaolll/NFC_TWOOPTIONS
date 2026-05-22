<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\NfcCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NfcController extends Controller
{
    public function scan(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'serial_number' => 'required|string',
            'data' => 'nullable|string',
            'scanner_device' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data',
                'errors' => $validator->errors(),
            ], 422);
        }

        $serialNumber = $request->input('serial_number');

        $nfcCard = NfcCard::active()->bySerial($serialNumber)->first();

        if (!$nfcCard) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu NFC tidak terdaftar atau tidak aktif',
                'data' => null,
            ], 404);
        }

        $user = $nfcCard->user;

        $todayAttendance = Attendance::today()
            ->where('user_id', $user->id)
            ->first();

        $status = 'present';
        $notes = null;

        if ($todayAttendance) {
            return response()->json([
                'success' => true,
                'message' => 'Sudah melakukan absensi hari ini',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'nim' => $user->nim,
                        'email' => $user->email,
                    ],
                    'attendance' => [
                        'scanned_at' => $todayAttendance->scanned_at->format('H:i:s'),
                        'status' => $todayAttendance->status,
                    ],
                ],
            ]);
        }

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'nfc_card_id' => $nfcCard->id,
            'status' => $status,
            'scanner_device' => $request->input('scanner_device'),
            'notes' => $notes,
            'scanned_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'nim' => $user->nim,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'nfc_card' => [
                    'serial_number' => $nfcCard->serial_number,
                ],
                'attendance' => [
                    'id' => $attendance->id,
                    'scanned_at' => $attendance->scanned_at->format('H:i:s'),
                    'status' => $attendance->status,
                ],
            ],
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'serial_number' => 'required|string|unique:nfc_cards,serial_number',
            'data' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data',
                'errors' => $validator->errors(),
            ], 422);
        }

        $nfcCard = NfcCard::create([
            'user_id' => $request->input('user_id'),
            'serial_number' => $request->input('serial_number'),
            'data' => $request->input('data'),
            'is_active' => true,
            'issued_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kartu NFC berhasil terdaftar',
            'data' => $nfcCard->load('user'),
        ], 201);
    }
}
