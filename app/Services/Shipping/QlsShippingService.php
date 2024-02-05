<?php
namespace App\Services\Shipping;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class QlsShippingService extends BaseShippingService
{
    private $configs;
    private $borvat_company_id = $configs['borvat_company_id'];
    public function __construct()
    {
        $this->configs = config('shipping_services.qls');
        parent::__invoke(
            auth_type: $this->configs['auth_type'],
            base_url: $this->configs['base_url'],
            headers: $this->configs['headers'],
            user_name: $this->configs['username'],
            password: $this->configs['password'],
        );
    }

    /**
     * Fetch List Of Shipments.
     * @notUsed
     */
    public function getCompaniesList(): array
    {
        $endpoint = $this->base_url . 'companies';
        $companies = $this->api_service->get($endpoint)->json();
        return @$companies['meta']['code'] == 200 ? @$companies['data'] : [];
    }


    /**
     * Cache Borvat Company Id
     * @notUsed
     */
    public function cacheBorvatCompanyId()
    {
        $companies = $this->getCompaniesList();
        if (count($companies) > 0) {
            $this->borvat_company_id = $companies[0]['id'];
            Cache::put('borvat_company_id', $this->borvat_company_id, Carbon::now()->addWeek());
        }
    }


    /**
     * Get Borvat Comopany Shipmnets Using Borvat Company Id.
     * @return array $shipments
     */
    public function getBorvatComapnyShipmnets($page = 1): array
    {
        $endpoint = $this->base_url . "companies/{$this->borvat_company_id}/shipments";
        $response = $this->api_service->get($endpoint, ['page' => $page])->json();
        if (@$response['meta']['code'] == 200) {
            return $response;
        } else {
            info('QLS Shipping Service Error: getBorvatComapnyShipmnets()');
            info($response);
            sleep(120);
            return $this->getBorvatComapnyShipmnets($page);
        }
    }



    /**
     * Get Shipment By it's Id
     * @return array $shipment.
     */
    public function getShipment(string $shipment_id): array
    {
        $endoint = $this->base_url . "companies/{$this->borvat_company_id}/shipments/{$shipment_id}";
        $data = [
            'returnShipmentLabel' => true
        ];
        $response = $this->api_service->get($endoint, $data)->json();
        if (@$response['meta']['code'] == 200) {
            return $response['data'];
        } else {
            info('Shipping Service Error: getShipment()');
            info($response);
            sleep(120);
            return $this->getShipment($shipment_id);
        }
    }





}
