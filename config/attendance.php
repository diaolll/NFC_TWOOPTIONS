<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Attendance Late Time
    |--------------------------------------------------------------------------
    |
    | Waktu batas untuk dianggap terlambat. Default: 09:00
    | Siswa yang scan setelah waktu ini akan dianggap terlambat.
    |
    */

    'late_time' => env('ATTENDANCE_LATE_TIME', '09:00'),

    /*
    |--------------------------------------------------------------------------
    | Attendance Method Priority
    |--------------------------------------------------------------------------
    |
    | Metode absensi yang diizinkan: 'nfc', 'qrcode', 'manual'
    |
    */

    'methods' => [
        'nfc' => true,
        'qrcode' => true,
        'manual' => false,
    ],
];
