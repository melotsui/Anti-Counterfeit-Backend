<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'admin',
            'password' => Hash::make('admin'),
            'email' => 'admin',
            'email_verified_at' => Carbon::now(),
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Melo Tsui',
            'password' => Hash::make('Abc123456'),
            'email' => 'qonkgonk@gmail.com',
            'email_verified_at' => Carbon::now(),
        ]);

        $this->call([
            DistrictSeeder::class,
        ]);

        $this->call([
            CategorySeeder::class,
        ]);
    }
}
