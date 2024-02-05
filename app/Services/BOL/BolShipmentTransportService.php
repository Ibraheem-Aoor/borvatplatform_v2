<?php
namespace App\Services\BOL;

use App\Models\BolAccount;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Shipment;
use App\Models\ShipmentTransport;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;
use Picqer\BolRetailerV10\Client;
use Picqer\BolRetailerV10\Exception\RateLimitException;
use Picqer\BolRetailerV10\Exception\UnauthorizedException;
use Throwable;

class BolShipmentTransportService extends BaseBolService
{
    protected const MODEL = ShipmentTransport::class;


    public function __construct(BolAccount $bol_account)
    {
        parent::__construct($bol_account);
    }


    /**
     * Each Shipment Has Transport Data
     * So We Have To Store Them Here
     */
    protected function storeShipmentTransport(Shipment $shipment)
    {
        try {
            // $shipment->tra
        } catch (Throwable $e) {

        }
    }

}
?>
