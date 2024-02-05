<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Api\Product\ProductController as ProductProductController;
use App\Http\Controllers\Borvat\OrderController as BorvatOrderController;
use App\Http\Controllers\Borvat\ShipmentController;
use App\Http\Controllers\GeneralShippingLabelController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProParcelController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShippmentController;
use App\Jobs\OrderFetchJob;
use App\Models\BolShippingLabel;
use App\Models\BorvatOrder;
use App\Models\CreatedShipment;
use App\Models\Order;
use App\Models\OrderCoupon;
use App\Models\Product;
use App\Models\Shipment;
use App\Services\ApiService;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Mpdf\Tag\A;
use MyParcelNL\Sdk\src\Services\Web\AccountWebService;
use MyParcelNL\Sdk\src\Services\Web\CarrierOptionsWebService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/





Auth::routes([
    'register' => false,
]);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
        Route::get('api-data', [OrderController::class, 'index'])->name('index');
        Route::get('achive', [OrderController::class, 'index'])->name('archive');
        Route::get('api-orders', [OrderController::class, 'getApiOrders'])->name('api-orders');
        Route::get('archive-orders', [OrderController::class, 'getArchiveOrders'])->name('archive.get-archive-orders');
    });
    Route::group(['prefix' => 'shippment', 'as' => 'shippment.'], function () {
        Route::get('/api-data', [ShippmentController::class, 'index'])->name('index');
        Route::get('/get-api-data', [ShippmentController::class, 'getApiShipments'])->name('get-api-data');
        Route::get('achive', [ShippmentController::class, 'archive'])->name('archive');
        Route::get('/get-archive-data', [ShippmentController::class, 'getArchiveShipments'])->name('get-archive-data');
        Route::get('/downlaods', [ShippmentController::class, 'getRecentDonwloads'])->name('recents');
        Route::get('/downlaod/{id}', [ShippmentController::class, 'downloadShipmentPdf'])->name('recents.download');
        Route::post('/pdf', [ShippmentController::class, 'generateShippmentPdf'])->name('pdf');
        Route::post('/full-pdf', [ShippmentController::class, 'generateFullShippmentPdf'])->name('full-pdf');
        Route::post('store-note', [ShippmentController::class, 'storeNote'])->name('store-note');
        Route::get('/serach', [ShippmentController::class, 'search'])->name('search');
        Route::post('/full-excel', [ShippmentController::class, 'generateFullShippmentExcel'])->name('full-excel');
        Route::get('delete-to-fix', function () {
            $deleted_shipments = Shipment::query()->where('created_at', Carbon::today()->toDateString())
                ->where('place_date', '!=', Carbon::today()->toDateString())
                ->delete();
            $deleted_orders = Order::query()->where('created_at', Carbon::today()->toDateString())
                ->where('place_date', '!=', Carbon::today()->toDateString())
                ->delete();
            dd($deleted_shipments, $deleted_orders);
        });

    });

    Route::group(['prefix' => 'prodcut', 'as' => 'product.'], function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/no-image', [ProductController::class, 'index'])->name('no-image');
        Route::get('all-products', [ProductController::class, 'getAllProducts'])->name('get-all');
        Route::get('/get-no-image-products', [ProductController::class, 'getNoImageProducts'])->name('get-no-image');
        Route::post('/edit', [ProductController::class, 'update'])->name('edit');
    });

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/sender-details', [SettingController::class, 'senderSettingIndex'])->name('sender-details.index');
        Route::post('/sender-details-update', [SettingController::class, 'updateSenderSetting'])->name('sender-details.update');
        Route::get('/email-msg', [SettingController::class, 'emailMsgIndex'])->name('email-msg.index');
        Route::post('/email-update', [SettingController::class, 'updateEmailMsg'])->name('email-msg.update');
    });

    Route::group(['prefix' => 'bol-accounts', 'as' => 'bol_accounts.'], function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::post('/store', [AccountController::class, 'store'])->name('store');
        Route::post('/update/{id}', [AccountController::class, 'update'])->name('update');
        Route::get('/table-data', [AccountController::class, 'getTableData'])->name('table_data');
    });

    Route::get('/clear-cache', [HomeController::class, 'clearCache'])->name('clear-cache');
});


