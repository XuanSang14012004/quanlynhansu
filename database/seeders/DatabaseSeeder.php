<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use HopDongsTableSeeder;
use HoSosTableSeeder;
use Illuminate\Database\Seeder;
use NhanSusTableSeeder;
use PhongBansTableSeeder;
use QuyetDinhsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

         $this->call(LaratrustSeeder::class);
        $this->call(PhongBansTableSeeder::class);
        $this->call(HoSosTableSeeder::class);
        $this->call(NhanSusTableSeeder::class);
        $this->call(HopDongsTableSeeder::class);
        $this->call(QuyetDinhsTableSeeder::class);
    }
}
