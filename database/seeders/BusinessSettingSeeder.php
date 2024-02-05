<?php

namespace Database\Seeders;

use App\Models\BusinessSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [
            'shipment_mail_msg' => 'Thank You For Purchase ,  Your Order Has Been Shipped Successfully!',
            'shipment_sender_details' => json_encode([
                'company' => '  Borvat.com',
                'street_and_house' => 'Overwelving 2',
                'city_and_zip' => '7201LT Zutphen',
            ]),
        ];

        foreach($records as $key => $value)
        {
            BusinessSetting::create(['key' => $key , 'value' => $value]);
        }
    }
}
