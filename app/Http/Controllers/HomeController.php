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
use Spatie\Browsershot\Browsershot;

class HomeController extends Controller
{


    public function index()
    {
        $s = Browsershot::html('<h2>TEST</h2>')
        ->noSandbox()
        ->setNodeBinary('C:/Program Files/nodejs/node.exe')
        ->setChromePath("C:\Program Files\Google\Chrome\Application\chrome.exe")->pdf();

        
        dd($s);
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
