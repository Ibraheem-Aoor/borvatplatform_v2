<?php
namespace App\Services;

use App\Services\Shipping\BaseShippingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Services\ApiService;
use Illuminate\Support\Facades\Storage;

class ZianpeslyShippingService extends BaseShippingService
{
    private $configs;
    public function __construct()
    {
        $this->configs = config('shipping_services.zianpesly');
        parent::__invoke(
            auth_type: $this->configs['auth_type'],
            base_url: $this->configs['base_url'],
            headers: $this->configs['headers'],
            user_name: $this->configs['username'],
            password: $this->configs['password'],
        );
    }


    /**
     * Generate Lables On Zinapesly
     */
    public function generateLables($shipments, $carrier_data)
    {
        $order_numbers = $this->getOrderNoFromShipments($shipments);
        $data = [
            'User' => $this->user_name,
            'Password' => $this->password,
            'OrderNo' => $order_numbers, #"4044112004,4044111049",#$order_numbers,
            // 'Carrier' => $carrier_data,
        ];
        $endpoint = $this->base_url . 'Shipment/Orders';
        $response = $this->api_service->post($endpoint, $data);
        dd($response);
    }

    protected function getOrderNoFromShipments($shipments): string
    {
        $order_numbers = [];
        foreach ($shipments as $shipments) {
            $order_numbers[] = $shipments->order?->api_id;
        }
        return implode(',', $order_numbers);
    }



    /**
     * Fetch Single Order Label
     * @param $order
     */
    public function fetchOrderLabel($order)
    {
        $data = [
            'User' => $this->user_name,
            'Password' => $this->password,
            'OrderNo' => $order->api_id,
        ];
        $endpoint = $this->base_url . 'Shipment/Labels';
        $response = $this->api_service->get($endpoint, $data)->json();
        return @$response[0]['Label'];
    }

    /**
     * Save Order's Label In The Given Path
     * @param $order
     * @param $path
     */
    public function saveOrderLabel($order, $path)
    {
        $label = $this->fetchOrderLabel($order);
        if ($label) {
            Storage::disk('public')->put('labels/' . $order->shipment?->api_id . '.pdf', base64_decode($label));
            return true;
        } else {
            return false; // Handle the case where no label was saved or found.
        }
    }

}
