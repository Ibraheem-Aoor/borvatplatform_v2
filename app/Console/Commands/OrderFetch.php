<?php

namespace App\Console\Commands;

use App\Jobs\FetchBolOrdersJob;
use App\Models\BolAccount;
use App\Models\Order;
use App\Services\BOL\BolOrderService;
use App\Services\BOL\BolRetailerApiService;
use App\Services\BOL\BolShipmentService;
use App\Traits\BoolApiTrait;
use App\Traits\OrderTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Throwable;

class OrderFetch extends Command
{
    protected $bol_order_service, $bol_shipment_service;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Bol Orders for all accounts.';


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
        $bol_accounts = BolAccount::get();
        foreach ($bol_accounts as $bol_account) {
            $this->bol_order_service = new BolOrderService($bol_account);
            $this->bol_shipment_service = new BolShipmentService($bol_account);
            $this->fetchOrders();
        }
    }


    /**
     * Fetching Orders From Bol.com Then Store To DB.
     */
    public function fetchOrders()
    {
        $orders = null;
        for ($i = 0; $i < 10; $i++) {
            $orders = $this->bol_order_service->fetchOrders($i, 'SHIPPED');
            try {
                if (is_array($orders)) {
                    $this->storeOrders($orders);
                }
            } catch (Throwable $e) {
                info('ERORR FETCHING ORDERS IN COMMAND');
                info($e->getMessage());
            }
        }
    }

    public function storeOrders(array $orders = [])
    {
        foreach ($orders as $order) {
            $stored_order = $this->bol_order_service->store($order);
            if ($stored_order instanceof Order) {
                $shipments = $this->bol_shipment_service->fetchShipments(order_id: $stored_order->api_id);
                if (is_array($shipments) && isset($shipments[0])) {
                    $shipment = $shipments[0];
                    $shipment = $this->bol_shipment_service->findShipment($shipment->shipmentId);
                    $this->bol_shipment_service->store($shipment->toArray());
                }
            }
            info('ORDER STORED SUCCESSFULLY');
        }
    }
}
