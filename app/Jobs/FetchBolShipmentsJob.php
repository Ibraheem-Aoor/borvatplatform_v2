<?php

namespace App\Jobs;

use App\Models\BolAccount;
use App\Service\BOL\BolOrderService;
use App\Services\BOL\BolShipmentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchBolShipmentsJob extends BaseBolJob
{

    /**
     * @var $bol_shipment_service to handle and manipulate orders.
     */
    private $bol_shipment_service;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BolAccount $bol_account)
    {
        parent::__construct($bol_account);
        $this->bol_shipment_service = new BolShipmentService($bol_account);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        for ($i = 0; $i < 15; $i++) {
            $shipments = $this->bol_service->fetchShipments($i);
            foreach ($shipments as $shipment) {
                $shipment = $shipments->toArray();
                $detaled_shippment = $this->bol_service->findShipment($shipment['shipmentId']);
                $this->bol_shipment_service->store($detaled_shippment);
            }
        }
    }
}
