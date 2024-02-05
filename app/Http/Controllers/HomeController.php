<?php

namespace App\Http\Controllers;

use App\Models\BolAccount;
use App\Models\BorvatOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipment;
use App\Services\BOL\BolOrderService;
use App\Services\BOL\BolProductService;
use App\Services\BOL\BolShipmentService;
use App\Services\ShippingService;
use App\Traits\Borvat\BorvatApiTrait;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\Helper;
use Throwable;

class HomeController extends Controller
{


    public function index()
    {

        $s =  new BolProductService(BolAccount::whereName('SOROUH')->first());
        // $image = file_get_contents('https://media.s-bol.com/rqq9nGqpVnA4/GgwxlK/250x200.jpg');
        // dd($image);
        // dd(saveImage('products/2', $image));
        try{
            $s->fetchImages();
        }catch(Throwable $e)
        {
            dd($e);
        }
        // $o = Order::query()->update(['is_shipped_by_dashboard' => 1]);
        // DB::connection('temp')->table('products')->update(['image' => null]);
        // // DB::connection('temp')->table('products')->get()->dd();
        // DB::connection('temp')->table('products')->orderByDesc('created_at')->chunk(100, function ($products) {
        //     foreach ($products as $product) {
        //         Product::query()->updateOrCreate([
        //             'ean' => $product->ean,
        //         ], [
        //             'ean' => $product->ean,
        //             'title' => $product->title,
        //             'num_of_sales' => $product->num_of_sales,
        //             'weight' => $product->weight,
        //             'purchase_place' => $product->purchase_place,
        //             'purchase_price' => $product->purchase_price,
        //             'width' => $product->width,
        //             'length' => $product->length,
        //             'height' => $product->height,
        //             'number_of_pieces' => $product->number_of_pieces,
        //             'note' => $product->note,
        //             'content' => $product->content,
        //             'bol_account_id'    =>  1, //up
        //         ]);
        //     }
        // });
        // dd('PRODUCTS STORED SUCCESSFULLY');

        // $s = Shipment::query()->whereHas('account' , function($account){
        //     $account->whereId(1);
        // })->count();
        // dd($s);
        // dd($o->products->first()  );
        // $bol_account = BolAccount::first();
        // $bol_shipment_service = new BolShipmentService($bol_account);
        // $shipments = $bol_shipment_service->fetchShipments();
        // set_time_limit(0);
        // foreach ($shipments as $shipment) {
        //     try{

        //         Order::query()->updateOrCreate([
        //             'api_id' => $shipment->order->orderId,
        //             'bol_account_id' => $bol_account->id,
        //         ], [
        //             'api_id' => $shipment->order->orderId,
        //             'place_date' => Carbon::parse($shipment->order->orderPlacedDateTime)->toDateTimeString(),
        //             'bol_account_id' => $bol_account->id,
        //         ]);
        //         $shipment = $bol_shipment_service->findShipment($shipment->shipmentId);
        //         $bol_shipment_service->store($shipment->toArray());
        //     }catch(Throwable $e)
        //     {
        //         dd($e);
        //     }
        // }
        // dd('SHIPMENTS F ETCHED SUCCESSFULLY');
        // $order = Order::first();
        // dd($order->shipment->products()->pivot()->sum('quantity'));
        $data['top_products_eans'] = [];
        $data['top_products_sales'] = [];
        $top_products = Product::orderByDesc('num_of_sales')->take(20)->pluck('num_of_sales', 'ean');
        foreach ($top_products as $ean => $sales_count) {
            array_push($data['top_products_eans'], $ean);
            array_push($data['top_products_sales'], $sales_count);
        }
        $data['top_products_eans'] = json_encode($data['top_products_eans']);
        $data['top_products_sales'] = json_encode($data['top_products_sales']);
        return view('dashboard', $data);
    }


    public function test()
    {
        $url = config('borvat-api.base_url') . 'orders';
        $result = $this->makeRequest($url);
        $borvat_orders = @$result['orders']['data'];
        foreach ($borvat_orders as $order) {
            dd($order);
            DB::beginTransaction();
            try {
                $order_data = [
                    'basic_attributes' => array_except($order, ['shipment', 'address', 'payment', 'products']),
                    'shipment' => json_encode($order['shipment']),
                    'address' => json_encode($order['address']),
                    'payment' => json_encode($order['payment']),
                    'order_items' => json_encode($order['products']),
                ];
                dd($order_data);
                dd(array_merge($order, ['api_id' => $order['id']]));
                BorvatOrder::create();
                DB::commit();
            } catch (QueryException $e) {
                $erro_code = $e->errorInfo[1];
                if ($erro_code == 1062) {
                    DB::rollBack();
                }
                dd($e);
            } catch (Throwable $e) {
                dd($e);
            }
        }
    }
    public function clearCache()
    {
        Artisan::call('optimize:clear');
        return back()->with('success', 'Cache Cleared Successfully');
    }


    public function testShipmentsAuth()
    {
        (new ShippingService())->getBorvatComapnyShipmnets();
    }
}
