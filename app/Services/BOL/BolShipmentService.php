<?php
namespace App\Services\BOL;

use App\Models\BolAccount;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Catch_;
use Picqer\BolRetailerV10\Client;
use Picqer\BolRetailerV10\Exception\RateLimitException;
use Picqer\BolRetailerV10\Exception\UnauthorizedException;
use Throwable;

class BolShipmentService extends BaseBolService
{
    protected const MODEL = Shipment::class;

    protected $bol_order_service, $shipment_product_service;

    public function __construct(BolAccount $bol_account)
    {
        parent::__construct($bol_account);
        $this->bol_order_service = new BolOrderService($bol_account);
        $this->shipment_product_service = new ShipmentProductService($bol_account);
    }


    /**
     * Get Shipments
     * @throws RateLimitException
     * @throws UnauthorizedException
     * @throws Throwable
     */
    public function fetchShipments($page = 1, $order_id = null, $fulfilment_method = null)
    {
        try {
            if (!Cache::has($this->getBolRetailer()->getBolAccount()->name . '_shipments_rate_limit_reached')) {
                $this->getBolRetailer()->generateToken();
                return $this->getBolRetailer()->getClient()->getShipments(page: $page, fulfilmentMethod: null, orderId: $order_id);
            }
        } catch (RateLimitException $e) {
            Cache::remember(
                $this->getBolRetailer()->getBolAccount()->name . '_shipments_rate_limit_reached',
                Carbon::now()->addSeconds($e->getRetryAfter()),
                function () {
                    return true;
                }
            );
            sleep($e->getRetryAfter());
            return $this->fetchShipments($page);
        } catch (UnauthorizedException $e) {
            $this->getBolRetailer()->generateToken();
            return $this->fetchShipments($page);
        } catch (Throwable $e) {
            info('BOL SERVICE ERROR in fetchShipments:');
            info($e->getMessage());
        }
    }


    /**
     * Find Shipment By Shipment Id
     * @throws RateLimitException
     * @throws UnauthorizedException
     * @throws Throwable
     */
    public function findShipment($shipment_id)
    {
        try {
            if (!Cache::has($this->getBolRetailer()->getBolAccount()->name . '_shipment_details_rate_limit_reached')) {
                $this->getBolRetailer()->generateToken();
                return $this->getBolRetailer()->getClient()->getShipment($shipment_id);
            }
        } catch (RateLimitException $e) {
            Cache::remember(
                $this->getBolRetailer()->getBolAccount()->name . '_shipment_details_rate_limit_reached',
                Carbon::now()->addSeconds($e->getRetryAfter()),
                function () {
                    return true;
                }
            );
            sleep($e->getRetryAfter());
            return $this->findShipment($shipment_id);
        } catch (UnauthorizedException $e) {
            $this->getBolRetailer()->generateToken();
            return $this->findShipment($shipment_id);
        }
    }



    /**
     * Store shipment in our db from incoming bol_shipment_data
     */
    public function store(array $bol_shipment_data)
    {
        $order_id = Order::where('api_id', $bol_shipment_data['order']['orderId'])->first()?->id;
        $this->saveShipmentInDB($bol_shipment_data, $order_id);
        // else {
        //     $bol_order_data = $this->bol_order_service->findOrder($bol_shipment_data['order']['orderId']);
        //     $stored_order = $this->bol_order_service->store($bol_order_data);
        //     $this->saveShipmentInDB($bol_shipment_data, $stored_order->id);
        // }
    }



    /**
     * Save Shipment into db
     */
    protected function saveShipmentInDB(array $bol_shipment_data, $order_id)
    {
        try {
            $data = [
                'api_id' => $bol_shipment_data['shipmentId'],
                'pickup_point' => @$bol_shipment_data['pickupPoint'],
                'order_id' => $order_id,
                'place_date' => Carbon::parse($bol_shipment_data['shipmentDateTime'])->toDateTimeString(),
                'shipment_details' => @json_encode($bol_shipment_data['shipmentDetails']),
                'billing_details' => @json_encode($bol_shipment_data['billingDetails']),
                'items' => @json_encode($bol_shipment_data['shipmentItems']),
                'transport' => @json_encode($bol_shipment_data['transport']),
                'first_name' => $bol_shipment_data['shipmentDetails']['firstName'],
                'surname' => $bol_shipment_data['shipmentDetails']['surname'],
                'house_no' => $bol_shipment_data['shipmentDetails']['houseNumber'],
                'city' => $bol_shipment_data['shipmentDetails']['city'],
                'street_name' => $bol_shipment_data['shipmentDetails']['streetName'],
                'zip_code' => $bol_shipment_data['shipmentDetails']['zipCode'],
                'country_code' => $bol_shipment_data['shipmentDetails']['countryCode'],
                'email' => $bol_shipment_data['shipmentDetails']['email'],
                'bol_account_id' => $this->getBolRetailer()->getBolAccount()->id,
            ];
            DB::beginTransaction();
            Shipment::create($data);
            DB::commit();
            // $this->shipment_product_service->storeFromShipment($shipment);
        } catch (QueryException $e) {
            $erro_code = $e->errorInfo[1];
            if ($erro_code == 1062) {
                DB::rollBack();
            } else {
                info('STORE BOL SHIPMENT ERROR in saveShipmentInDB  for shipmentId' . @$bol_shipment_data['shipmentId']);
                info($e->getMessage());
            }
        } catch (Throwable $e) {
            info('STORE BOL SHIPMENT ERROR in saveShipmentInDB  for shipmentId' . @$bol_shipment_data['shipmentId']);
            info($e->getMessage());
        }
    }


    /**
     * Go To The Order Of The Shipment And Update It's products fields ['unit_price' , 'commission']
     */
    private function updateShipmentOrderProducts(Shipment $shipment): void
    {
        $shipment_products = $shipment->getShipmentItems();
        foreach ($shipment_products as $product) {
            $order_product = OrderProduct::query()->where([['order_id', $shipment->order->id], ['order_item_id', $product['orderItemId']]])->first();
            $order_product->update([
                'unit_price' => $product['unitPrice'],
                'commission' => $product['commission'],
            ]);
        }
    }


    /**
     * Retrive the Shipping Label Of A Shipment Using ShippingLabelId
     * @throws RateLimitException
     * @throws UnauthorizedException
     * @throws Throwable
     */
    private function getLabel(string $shipping_label_id): ?string
    {
        try {
            if (!Cache::has($this->getBolRetailer()->getBolAccount()->name . '_shipping_labels_rate_limit_reached')) {
                $this->getBolRetailer()->generateToken();
                return $this->getBolRetailer()->getClient()->getShippingLabel($shipping_label_id);
            }
        } catch (RateLimitException $e) {
            Cache::remember(
                $this->getBolRetailer()->getBolAccount()->name . '_shipping_labels_rate_limit_reached',
                Carbon::now()->addSeconds($e->getRetryAfter()),
                function () {
                    return true;
                }
            );
            sleep($e->getRetryAfter());
            return $this->getLabel($shipping_label_id);
        } catch (UnauthorizedException $e) {
            $this->getBolRetailer()->generateToken();
            return $this->getLabel($shipping_label_id);
        }
    }


    /**
     * Fetch & Store Shipping Lables For Current Bol Account.
     */
    public function fetchLabels()
    {
        $this->getBolRetailer()
            ->getBolAccount()
            ->shipments()
            ->whereHasLabel(false)
            ->chunk(200, function ($shipments) {
                $this->storeLabels($shipments);
            });
    }

    private function storeLabels(Collection $shipments): void
    {
        foreach ($shipments as $shipment) {
            $shipping_label_id = @$shipment->transport['shippingLabelId'];
            $label = $this->getLabel($shipping_label_id);
            if ($label) {
                $path = 'labels/' . $shipment->api_id . '.pdf';
                saveImage($path, $label);
                $shipment->has_label = true;
                $shipment->save();
            }
        }
        sleep(60);
    }
}
?>
