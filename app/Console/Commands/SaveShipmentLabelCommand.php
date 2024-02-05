<?php

namespace App\Console\Commands;

use App\Models\BolAccount;
use App\Models\Order;
use App\Models\Shipment;
use App\Services\BOL\BolShipmentService;
use App\Services\ShippingService;
use App\Services\ZianpeslyShippingService;
use App\Traits\BoolApiTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SaveShipmentLabelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipment-label:get {bol_account_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get The Bol shipping labels for its shipments';

    protected $bol_account;

    protected $bol_shipment_service;



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
        try {
            $this->bol_shipment_service->fetchLabels();
            info('BOL ACCOUNT ' . $this->bol_account->name . ' LABLES FETCHED');
        } catch (Throwable $e) {
            info('BOL SHIPPING LABELS ERROR FOR ACCOUNT: ' . $this->bol_account->name);
            info($e);
        }
    }
}
