<?php
namespace App\Services\BOL;

use App\Models\BolAccount;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Services\BOL\BaseBolService;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Throwable;

class BolOrderProductsService extends BaseBolService
{
    protected const MODEL = OrderProduct::class;


    public function __construct(BolAccount $bol_account)
    {
        parent::__construct($bol_account);
    }


    public function store(array $order_product)
    {
        try {
            DB::beginTransaction();
            OrderProduct::query()->updateOrCreate([
                'order_item_id' => $order_product['orderItemId'],
                'order_id' => @$order_product['order_id'],
            ], [
                'order_item_id' => $order_product['orderItemId'],
                'cancellation_request' => $order_product['cancellationRequest'],
                'offer_id' => @$order_product['offerId'],
                'quantity' => $order_product['quantity'],
                'quantity_shipped' => $order_product['quantityShipped'],
                'quantity_cancelled' => $order_product['quantityCancelled'],
                'unit_price' => @$order_product['unitPrice'],
                'commission' => @$order_product['commission'],
                'fulfilment_method' =>  @$order_product['fulfilmentMethod'] ?? @$order_product['fulfilment']['method'],
                'fulfilment_status' =>  @$order_product['fulfilmentStatus'] ?? 'HANDELD',
                'latest_changed_date_time' => @Carbon::parse($order_product['latestChangedDateTime'])->toDateTime(),
                'order_id' => @$order_product['order_id'],
                'product_id' => @$order_product['product_id'],
            ]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            info('STORE ORDER PRODUCTS ERROR: ');
            info($e->getMessage());
        }
    }

}
?>
