<?php

namespace App\Http\Controllers;

use App\DataTables\Order\OrderTransformer;
use App\DataTables\OrderDataTable;
use App\Jobs\SendEmailJob;
use App\Models\BolAccount;
use App\Models\Fulfilment;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\ShippingDetail;
use App\Services\BOL\BolRetailerApiService;
use App\Services\ApiService;
use App\Services\ProParcelShippingService;
use App\Services\Shipping\KeenDeliveryShippingService;
use App\Traits\BoolApiTrait;
use App\Traits\OrderTrait;
use App\Traits\ShipmentTrait;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Mpdf\Tag\Dd;
use Str;
use Throwable;
use PDF;
use PhpParser\Node\Stmt\Catch_;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;
use setasign\Fpdi\Fpdi;
use App\Services\PDF_HTML;



class OrderController extends Controller
{


    public function index(Request $request)
    {
        $data['ajax_route'] = Route::currentRouteName() == 'order.index' ? route('order.api-orders', $request->query()) : route('order.archive.get-archive-orders', $request->query());
        $data['bol_accounts'] = BolAccount::getCachedRecords();
        return view('admin.orders.index', $data);
    }





    /**
     * return all new orders that have not been printed yet
     */
    public function getApiOrders(Request $request)
    {
        return DataTables::of(
            Order::query()
                ->when($request->has('account_id'), function ($query) use ($request) {
                    $query->where('bol_account_id', $request->query('account_id'));
                })
                ->where('is_shipped_by_dashboard', true)
        )
            ->setTransformer(OrderTransformer::class)
            ->filterColumn('title', function ($query, $keyword) {
                $query->whereHas('products', function ($products) use ($keyword) {
                    $products->where('title', 'like', '%' . $keyword . '%')
                        ->orWhere('ean', 'like', '%' . $keyword . '%');
                });
            })
            ->filterColumn('quantity', function ($query, $keyword) {
                $query->whereHas('products', function ($products) use ($keyword) {
                    $products->where('quantity', 'like', '%' . $keyword . '%');
                });
            })
            ->filterColumn('account', function ($shipment, $keyword) {
                $shipment->whereHas('account', function ($account) use ($keyword) {
                    $account->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->filterColumn('unit_price', function ($query, $keyword) {
                $query->whereHas('products', function ($products) use ($keyword) {
                    $products->where('unit_price', 'like', '%' . $keyword . '%');
                });
            })
            ->orderColumn('quantity', function ($query, $order) {
                $query->whereHas('products', function ($products) use ($query, $order) {
                    $products->orderBy('quantity', $order);
                });
            })
            ->orderColumn('unit_price', function ($query, $order) {
                $query->whereHas('products', function ($products) use ($query, $order) {
                    $products->orderBy('unit_price', $order);
                });
            })
            ->orderColumn('account', function ($shipment, $order) {
                $shipment->orderBy('bol_account_id', $order);
            })
            ->make(true);
    }


    public function getArchiveOrders(Request $request)
    {
        return DataTables::of(
            Order::query()
                ->when($request->has('account_id'), function ($query) use ($request) {
                    $query->where('bol_account_id', $request->query('account_id'));
                })
        )
            ->setTransformer(OrderTransformer::class)
            ->make(true);
    }

    /**
     * Bind orders table with order_products table
     */
    // public function bindOrderProducts()
    // {
    //     Order::query()->chunk(300, function ($orders) {
    //         foreach ($orders as $order) {
    //             $order_items = json_decode($order->order_items, true);
    //             foreach ($order_items as $order_item) {
    //                 $product_data = $order_item['product'];
    //                 $product = Product::query()->updateOrCreate(['ean' => $product_data['ean']], $product_data);
    //                 Fulfilment::query()->updateOrCreate([
    //                     'method' => @$order_item['fulfilment']['method'],
    //                     'distribution_party' => @$order_item['fulfilment']['distributionParty'],
    //                     'latest_delivery_date' => @$order_item['fulfilment']['latestDeliveryDate'],
    //                     'expiry_date' => @$order_item['fulfilment']['expiryDate'],
    //                     'time_frame_type' => @$order_item['fulfilment']['timeFrameType'],
    //                     'order_id' => $order->id,
    //                 ]);
    //                 OrderProduct::query()->updateOrCreate([
    //                     'order_item_id' => @$order_item['orderItemId'],
    //                     'order_id' => $order->id,
    //                     'product_id' => $product->id,
    //                 ], [
    //                     'order_item_id' => @$order_item['orderItemId'],
    //                     'order_id' => $order->id,
    //                     'product_id' => $product->id,
    //                     'cancellation_request' => @$order_item['cancellationRequest'],
    //                     'quantity' => @$order_item['quantity'],
    //                     'quantity_shipped' => @$order_item['quantityShipped'],
    //                     'quantity_cancelled' => @$order_item['quantityCancelled'],
    //                     'unit_price' => @$order_item['unitPrice'],
    //                     'commission' => @$order_item['commission'],
    //                     'latest_changed_date_time' => new DateTime($order_item['latestChangedDateTime']),
    //                 ]);
    //             }
    //             // dd($order_items);
    //         }
    //     });
    //     dd('DONE');
    // }

    /**
     * Bind orders table with shipping_details table
     */
    // public function bindOrderShippingDetails()
    // {
    //     Order::query()->chunk(300, function ($orders) {
    //         foreach ($orders as $order) {
    //             $shipment_details = json_decode($order->shipment_details, true);
    //             ShippingDetail::query()->updateOrCreate([
    //                 'order_id' => $order->id
    //             ], [
    //                 'salutation' => @$shipment_details['salutation'],
    //                 'first_name' => @$shipment_details['firstName'],
    //                 'surname' => @$shipment_details['surname'],
    //                 'street_name' => @$shipment_details['streetName'],
    //                 'house_number' => @$shipment_details['houseNumber'],
    //                 'zip_code' => @$shipment_details['zipCode'],
    //                 'city' => @$shipment_details['city'],
    //                 'country_code' => @$shipment_details['countryCode'],
    //                 'email' => @$shipment_details['email'],
    //                 'language' => @$shipment_details['language'],
    //                 'order_id' => $order->id
    //             ]);
    //             $order->country_code = @$shipment_details['countryCode'];
    //             $order->save();
    //         }
    //     });
    //     dd('DONE');
    // }

}






