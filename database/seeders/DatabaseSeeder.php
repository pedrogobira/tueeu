<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'Maria',
            'email' => 'maria@example.com',
            'password' => bcrypt('123'),
        ]);

        User::factory()->create([
            'name' => 'João',
            'email' => 'joao@example.com',
            'password' => bcrypt('123'),
        ]);
    }
}
