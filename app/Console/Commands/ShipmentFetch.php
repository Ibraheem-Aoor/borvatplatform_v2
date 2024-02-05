<?php

namespace App\Console\Commands;

use App\Jobs\FetchBolShipmentsJob;
use App\Models\BolAccount;
use App\Models\Order;
use App\Services\BOL\BolShipmentService;
use App\Traits\BoolApiTrait;
use App\Traits\OrderTrait;
use App\Traits\ShipmentTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Throwable;

class ShipmentFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipments:get {bol_account_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Shipments';

    protected $bol_shipment_service;

    protected $bol_account;



    public function __construct()
    {
        parent::__construct();
    }




    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->bol_account = BolAccount::query()->find($this->argument('bol_account_id'));
        $this->bol_shipment_service = new BolShipmentService($this->bol_account);
        for ($i = 1; $i < 7; $i++) {
            $shipments = $this->bol_shipment_service->fetchShipments(page: $i);
            if (is_array($shipments) && isset($shipments[0])) {
                if ($this->storeShipmentsOrders($shipments)) {
                    $this->storeShipments($shipments);
                    sleep(60);
                }
            }
        }
    }

    private function storeShipmentsOrders(array $shipments): bool
    {
        $counter = 0;
        foreach ($shipments as $shipment) {
            Order::query()->updateOrCreate([
                'api_id' => $shipment->order->orderId,
                'bol_account_id' => $this->bol_account->id,
            ], [
                'api_id' => $shipment->order->orderId,
                'place_date' => Carbon::parse($shipment->order->orderPlacedDateTime)->toDateTimeString(),
                'bol_account_id' => $this->bol_account->id,
                'is_shipped_by_dashboard' => true,
            ]);
            $counter++;
        }
        return $counter == count($shipments);
    }


    private function storeShipments(array $shipments): void
    {
        foreach ($shipments as $shipment) {
            $shipment = $this->bol_shipment_service->findShipment($shipment->shipmentId);
            $this->bol_shipment_service->store($shipment->toArray());
        }
    }
}
