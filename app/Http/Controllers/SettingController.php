<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function senderSettingIndex()
    {
        $shipment_sender_details = BusinessSetting::query()->where('key' , 'shipment_sender_details')->first()->value;
        $data['shipment_sender_details']  = json_decode($shipment_sender_details , true);
        return view('admin.settings.sender-details' , $data);
    }

    public function updateSenderSetting(Request $request)
    {
        $request->validate([
            'company' => 'required',
            'street_and_house' => 'required',
            'city_and_zip' => 'required',
        ]);
        $data = $request->toArray();
        $shipment_sender_details = BusinessSetting::query()->where('key' , 'shipment_sender_details')->first();
        $shipment_sender_details->value = json_encode($data);
        $shipment_sender_details->save();
        return response()->json(['status' => true , 'is_updated' => true , 'message' => 'Success'] , 200);
    }



    public function emailMsgIndex()
    {
        $data['shipment_mail_msg'] = BusinessSetting::query()->where('key' , 'shipment_mail_msg')->first()->value;
        return view('admin.settings.email-msg' , $data);
    }

    public function updateEmailMsg(Request $request)
    {
        $request->validate(['msg' => 'required']);
        $shipment_mail_msg = BusinessSetting::query()->where('key' , 'shipment_mail_msg')->first();
        $shipment_mail_msg->value = $request->msg;
        $shipment_mail_msg->save();
        return response()->json(['status' => true , 'is_updated' => true , 'message' => 'Success'] , 200);

    }
}
