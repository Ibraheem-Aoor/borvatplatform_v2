<?php
namespace App\Services\Shipping;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Catch_;
use Picqer\BolRetailerV10\Client;
use Picqer\BolRetailerV10\Exception\RateLimitException;
use Picqer\BolRetailerV10\Exception\UnauthorizedException;

class KeenDeliveryShippingService extends BaseShippingService
{
    private $configs;

    public function __construct()
    {
        $this->configs = config('shipping_services.keeny_delivery_api');
        parent::__invoke(
            auth_type: $this->configs['auth_type'],
            base_url: $this->configs['base_url'],
            headers: $this->configs['headers'],
            token: $this->configs['token'],
        );
    }
    /**
     * Get The Avilable Shipment Methods From keendelivery API
     */
    public function getAvilableShipmentMethods()
    {
        if (!Cache::get('keen_delivery_shipping_methods')) {
            $endpoint = $this->base_url . 'shipping_methods';
            $response = $this->api_service->get($endpoint , ['api_token' => $this->token])->json();
            $epires_at = Carbon::now()->addHours(24);
            Cache::put('keen_delivery_shipping_methods', (is_array($response) ? array_pop($response) : []), $epires_at);
        }
        return Cache::get('keen_delivery_shipping_methods');
    }

}
