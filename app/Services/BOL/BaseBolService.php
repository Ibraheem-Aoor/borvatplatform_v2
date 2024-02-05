<?php
namespace App\Services\BOL;

use App\Models\BolAccount;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

class BaseBolService
{
    protected $bol_retailer_service;
    public function __construct(BolAccount $bol_account)
    {
        $this->bol_retailer_service = new BolRetailerApiService($bol_account);
    }


    public function getBolRetailer(): BolRetailerApiService
    {
        return $this->bol_retailer_service;
    }
}
?>
