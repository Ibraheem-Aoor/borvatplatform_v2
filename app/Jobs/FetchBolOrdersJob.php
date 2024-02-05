<?php

namespace App\Jobs;

use App\Models\BolAccount;
use App\Services\BOL\BolOrderService;
use App\Services\BOL\BolRetailerApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchBolOrdersJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $bol_account, $bol_service;

    /**
     * @var $bol_order_service to handle and manipulate orders.
     */
    private $bol_order_service;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($bol_account_id)
    {
        $this->bol_account = BolAccount::query()->find($bol_account_id);
        $this->bol_service = new BolRetailerApiService($this->bol_account);
        $this->bol_order_service = new BolOrderService($this->bol_account);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        for ($i = 0; $i < 15; $i++) {
            $orders = $this->bol_service->fetchOrders($i);
            info($orders);
        }
    }
}
