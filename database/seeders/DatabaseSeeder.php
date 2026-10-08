<?php

namespace Database\Seeders;

use App\Models\SavingSetting;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed a ready-to-use demo account so login and the dashboard work right away.
     *
     * Savings are only recorded for *past* days, so the "Jangan lupa menabung"
     * reminder is visible on the dashboard the first time you sign in.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'mahasiswa@contoh.id'],
            [
                'name' => 'Mahasiswa Demo',
                'password' => 'password',
            ]
        );

        SavingSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'target_amount' => SavingSetting::DEFAULT_TARGET,
                'daily_amount' => SavingSetting::DEFAULT_DAILY,
            ]
        );

        if ($user->transactions()->exists()) {
            return;
        }

        $transactions = [
            ['income', Transaction::CATEGORY_ALLOWANCE, 'Uang saku mingguan dari orang tua', 500000, 7],
            ['income', 'Sampingan', 'Les privat adik kelas', 150000, 5],
            ['expense', 'Makanan', 'Makan siang di kantin kampus', 25000, 6],
            ['expense', 'Transportasi', 'Ojek bolak-balik kampus', 15000, 4],
            ['expense', 'Kebutuhan Kuliah', 'Fotokopi modul dan print tugas', 60000, 3],
            ['expense', 'Pulsa', 'Isi pulsa paket internet', 20000, 2],
            ['expense', 'Belanja', 'Beli alat tulis', 35000, 1],
        ];

        foreach ($transactions as [$type, $category, $description, $amount, $daysAgo]) {
            $user->transactions()->create([
                'type' => $type,
                'category' => $category,
                'description' => $description,
                'amount' => $amount,
                'date' => now()->subDays($daysAgo)->toDateString(),
            ]);
        }

        // Riwayat menabung 5 hari terakhir (hari ini sengaja dikosongkan).
        foreach ([5, 4, 3, 2, 1] as $daysAgo) {
            $user->savings()->create([
                'amount' => SavingSetting::DEFAULT_DAILY,
                'date' => now()->subDays($daysAgo)->toDateString(),
                'note' => 'Nabung harian',
            ]);
        }
    }
}
