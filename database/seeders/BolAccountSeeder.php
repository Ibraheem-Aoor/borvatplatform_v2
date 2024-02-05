<?php

namespace Database\Seeders;

use App\Models\BolAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BolAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accounts = $this->getDataToSeed();
        foreach($accounts as $account)
        {
            BolAccount::query()->create($account);
        }
    }

    private function getDataToSeed(): array
    {
        return [
            [
                'name' => 'UP',
            ],
            [
                'name' => 'SOROUH',
            ],
            [
                'name' => 'LAPIDOUS', //murad
            ],
            [
                'name' => 'BAISON',
            ],
            [
                'name' => 'IBO', //conan
            ],
            [
                'name' => 'IBO SHOP', //qnan
            ],
        ];
    }
}
