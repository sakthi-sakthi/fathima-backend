<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(FileSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(OptionSeeder::class);

        User::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin@gmail.com'),
            'role' => 'admin',
            'name' => 'Admin'
        ]);
    }
}
