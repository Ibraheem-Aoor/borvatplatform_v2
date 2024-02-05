<?php
namespace App\Services\BOL;

use App\Models\BolAccount;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\ShipmentProduct;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;
use Picqer\BolRetailerV10\Client;
use Picqer\BolRetailerV10\Exception\RateLimitException;
use Picqer\BolRetailerV10\Exception\UnauthorizedException;
use Throwable;

class ShipmentProductService extends BaseBolService
{
    protected const MODEL = ShipmentProduct::class;


    public function __construct(BolAccount $bol_account)
    {
        parent::__construct($bol_account);
    }



    /**
     * Storing Products into products table.
     * then storing the shipment related products with their fulfilment.
     */

    public function storeFromShipment(Shipment $shipment): void
    {
        foreach ($shipment->items as $item) {
            $product = Product::updateOrCreate(
                [
                    'ean' => $item['product']['ean'],
                ],
                $item['product']
            );
            ShipmentProduct::updateOrCreate([
                'order_item_id' => $item['orderItemId'],
                'shipment_id' => $shipment->id,
                'product' => $product->id,
            ], [
                'order_item_id' => $item['orderItemId'],
                'offer_id' => @$item['offer']['offer_id'],
                'quantity' => @$item['quantity'],
                'unit_price' => @$item['unitPrice'],
                'commission' => @$item['commission'],
                'shipment_id' => $shipment->id,
                'product' => $product->id,
            ])->storeFulfilment($item['fulfilment']);
        }
    }

}
?>
