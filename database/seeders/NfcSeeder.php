<?php

namespace Database\Seeders;

use App\Models\NfcCard;
use App\Models\User;
use Illuminate\Database\Seeder;

class NfcSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['name' => 'Ahmad Dahlan', 'email' => 'ahmad@example.com', 'nim' => '2023001'],
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'nim' => '2023002'],
            ['name' => 'Citra Dewi', 'email' => 'citra@example.com', 'nim' => '2023003'],
            ['name' => 'Dian Permata', 'email' => 'dian@example.com', 'nim' => '2023004'],
            ['name' => 'Eko Prasetyo', 'email' => 'eko@example.com', 'nim' => '2023005'],
        ];

        $serials = [
            '04:A3:B5:C7:D9:01',
            '04:A3:B5:C7:D9:02',
            '04:A3:B5:C7:D9:03',
            '04:A3:B5:C7:D9:04',
            '04:A3:B5:C7:D9:05',
        ];

        foreach ($students as $index => $student) {
            $user = User::firstOrCreate(
                ['email' => $student['email']],
                [
                    'name' => $student['name'],
                    'nim' => $student['nim'],
                    'password' => bcrypt('password'),
                    'role' => 'student',
                ]
            );

            if (!$user->nfcCard) {
                NfcCard::create([
                    'user_id' => $user->id,
                    'serial_number' => $serials[$index],
                    'data' => json_encode(['type' => 'student_card']),
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('✅ Created ' . count($students) . ' students with NFC cards');
    }
}
