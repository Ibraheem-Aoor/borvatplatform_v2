<?php
namespace App\Services\BOL;

use App\Models\BolAccount;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;
use Picqer\BolRetailerV10\Client;
use Picqer\BolRetailerV10\Exception\RateLimitException;
use Picqer\BolRetailerV10\Exception\UnauthorizedException;
use Picqer\BolRetailerV10\Model\Order as BolOrder;
use Picqer\BolRetailerV10\Model\ReducedOrder;
use Throwable;

class BolOrderService extends BaseBolService
{
    protected const MODEL = Order::class;
    protected $bol_product_service;

    public function __construct(BolAccount $bol_account)
    {
        parent::__construct($bol_account);
        $this->bol_product_service = new BolProductService($bol_account);
    }


    /**
     * Get Client Orders.
     * @throws RateLimitException
     * @throws UnauthorizedException
     * @throws Throwable
     */
    public function fetchOrders($page = 1, $status = 'ALL')
    {
        try {
            if (!Cache::has($this->bol_retailer_service->getBolAccount()->name . '_orders_rate_limit_reached')) {
                $this->bol_retailer_service->generateToken();
                $orders = $this->bol_retailer_service->getClient()->getOrders($page, status: $status);
                info($orders);
                return $orders;
            }
        } catch (RateLimitException $e) {
            Cache::remember(
                $this->bol_retailer_service->getBolAccount()->name . '_orders_rate_limit_reached',
                Carbon::now()->addSeconds($e->getRetryAfter()),
                function () {
                    return true;
                }
            );
            sleep($e->getRetryAfter());
            return $this->bol_retailer_service->getClient()->getOrders($page, status: $status);
        } catch (UnauthorizedException $e) {
            $this->bol_retailer_service->generateToken();
            return $this->fetchOrders($page);
        } catch (Throwable $e) {
            info('BOL ORDER SERVICE ERROR in "fetchOrders" :');
            info($e->getMessage());
        }
    }


    /**
     * get order by order_id
     *  @throws RateLimitException
     * @throws UnauthorizedException
     * @throws Throwable
     */
    public function findOrder($order_id)
    {
        try {
            if (!Cache::has($this->bol_retailer_service->getBolAccount() . '_order_details_rate_limit_reached')) {
                $this->bol_retailer_service->generateToken();
                return $this->bol_retailer_service->getClient()->getOrder($order_id);
            }
        } catch (RateLimitException $e) {
            Cache::remember(
                $this->bol_retailer_service->getBolAccount() . '_order_details_rate_limit_reached',
                Carbon::now()->addSeconds($e->getRetryAfter()),
                function () {
                    return true;
                }
            );
            return $this->bol_retailer_service->getClient()->getOrder($order_id);
        } catch (UnauthorizedException $e) {
            $this->bol_retailer_service->generateToken();
            return $this->findOrder($order_id);
        } catch (Throwable $e) {
            info('BOL ORDER SERVICE ERROR in findOrder for orderId: ' . $order_id);
            info($e);
        }
    }


    /**
     * Storing Reduced Order.
     */
    public function store($bol_order)
    {
        try {
            DB::beginTransaction();
            $stored_order = $this->storeOrderInDB($bol_order);
            $this->bol_product_service->storeFromOrder($bol_order, $stored_order);
            DB::commit();
            return $stored_order;
        } catch (QueryException $e) {
            $erro_code = $e->errorInfo[1];
            if ($erro_code == 1062) {
                DB::rollBack();
            } else {
                info('BOL ORDER SERVICE Error "queryExeception" in store for orderId:' . $bol_order->orderId);
                info($e);
            }
        } catch (Throwable $e) {
            info('BOL ORDER SERVICE Error in store for orderId:' . $bol_order->orderId);
            info($e);
        }
    }


    /**
     * Save Reduced Order Into DB
     */
    protected function storeOrderInDB($bol_order): ?Order
    {
        return self::MODEL::query()->updateOrCreate(
            [
                'api_id' => $bol_order->orderId,
            ]
            ,
            [
                'api_id' => $bol_order->orderId,
                // 'pickup_point' => $bol_order->pickupPoint,
                'place_date' => Carbon::parse($bol_order->orderPlacedDateTime)->toDateTimeString(),
                // 'shipment_details' => json_encode($bol_order->shipmentDetails?->toArray()),
                // 'billing_details' => json_encode($bol_order->billingDetails?->toArray()),
                'order_items' => json_encode($bol_order->orderItems),
                'bol_account_id' => $this->bol_retailer_service->getBolAccount()->id,
            ]
        );
    }


}
?>
