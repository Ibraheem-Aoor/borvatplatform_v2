<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->updateOrCreate([
            'email' => 'admin@borvat.com',
        ], [
            'email' => 'admin@borvat.com',
            'name'  =>  'admin',
            'password'  =>  Hash::make('123123123'),
        ]);
    }
}
