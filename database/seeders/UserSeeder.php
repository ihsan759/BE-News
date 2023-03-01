<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->create([
            'name' => "Fulan",
            "email" => "fulan@gmail.com",
            "password" => "fulan"
        ]);

        User::query()->create([
            'name' => "Fulanah",
            "email" => "fulanah@gmail.com",
            "password" => "fulanah"
        ]);
    }
}
