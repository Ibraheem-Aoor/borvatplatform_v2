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
            'name'  =>  'ABO SHAM',
            'password'  =>  Hash::make('123123123'),
        ]);
        User::query()->updateOrCreate([
            'email' => 'hema@borvat.com',
        ], [
            'email' => 'hema@borvat.com',
            'name'  =>  'hema',
            'password'  =>  Hash::make('123123123'),
        ]);
        User::query()->updateOrCreate([
            'email' => 'sulieman@borvat.com',
        ], [
            'email' => 'sulieman@borvat.com',
            'name'  =>  'sulieman',
            'password'  =>  Hash::make('123123123'),
        ]);
    }
}
