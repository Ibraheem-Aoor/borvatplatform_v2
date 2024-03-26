<?php

namespace App\Http\Controllers;

use App\DataTables\BolAccount\BolAccountTransformer;
use App\DataTables\Product\ProductTransformer;
use App\Http\Requests\BolAccountRequest;
use App\Jobs\FetchBolOrdersJob;
use App\Jobs\FetchBolShipmentsJob;
use App\Models\BolAccount;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipment;
use App\Services\BOL\BolShipmentService;
use App\Traits\BoolApiTrait;
use App\Traits\OrderTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\Facades\DataTables;
use Str;

class AccountController extends Controller
{
    public function index()
    {
        $s = BolAccount::query()->latest()->first();
        Shipment::query()->update([
            'bol_account_id'    =>  $s->id,
        ]);
        $data['ajax_route'] = route('bol_accounts.table_data');
        return view('admin.accounts.index', $data);
    }

    public function store(BolAccountRequest $request)
    {
        try {
            $data = $request->toArray();
            $data['logo'] = $request->hasFile('logo') ? saveImage('accounts', $request->file('logo')) : null;
            $bol_account = BolAccount::query()->create($data);
            $account_id_for_env = strtoupper(Str::snake(strtolower($data['name'])) . "_BOL_ACCOUNT_ID");
            $account_key_for_env = strtoupper(Str::snake(strtolower($data['name'])) . "_BOL_ACCOUNT_KEY");
            $this->overWriteEnvFile($account_id_for_env, $data['client_id']);
            $this->overWriteEnvFile($account_key_for_env, $data['client_key']);
            $response = generateResponse(status: true, modal_to_hide: '#account-create-update-modal', reset_form: true, table_reload: true);
            Cache::forget('bol_accounts');
        } catch (Throwable $e) {
            dd($e);
            $response = generateResponse(status: false);
        }
        return response()->json($response);
    }
    public function update(BolAccountRequest $request, $id)
    {
        try {
            $data = $request->toArray();
            $data['logo'] = $request->hasFile('logo') ? saveImage('accounts', $request->file('logo')) : null;
            $bol_account = BolAccount::query()->find($id);
            $bol_account->update($data);
            $account_id_for_env = strtoupper(Str::snake(strtolower($data['name'])) . "_BOL_ACCOUNT_ID");
            $account_key_for_env = strtoupper(Str::snake(strtolower($data['name'])) . "_BOL_ACCOUNT_KEY");
            $this->overWriteEnvFile($account_id_for_env, $data['client_id']);
            $this->overWriteEnvFile($account_key_for_env, $data['client_key']);
            $response = generateResponse(status: true, modal_to_hide: '#account-create-update-modal', reset_form: true, table_reload: true);
            Cache::forget('bol_accounts');
        } catch (Throwable $e) {
            $response = generateResponse(status: false);
        }
        return response()->json($response);
    }

    /**
     * overWrite the Env File values.
     * @param  String type
     * @param  String value
     * @return \Illuminate\Http\Response
     */
    protected function overWriteEnvFile($type, $val)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $val = '"' . trim($val) . '"';
            if (is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0) {
                file_put_contents(
                    $path,
                    str_replace(
                        $type . '="' . env($type) . '"',
                        $type . '=' . $val,
                        file_get_contents($path)
                    )
                );
            } else {
                file_put_contents($path, file_get_contents($path) . "\r\n" . $type . '=' . $val);
            }
        }
    }
    public function getTableData()
    {
        return DataTables::of(BolAccount::query())
            ->setTransformer(BolAccountTransformer::class)
            ->make(true);
    }

    public function fetchAndStoreShipments($id)
    {
        try
        {
            set_time_limit(0);
            $bol_account = BolAccount::query()->find($id);
            $bol_shipments_service = new BolShipmentService($bol_account);
            $shipments = $bol_shipments_service->fetchShipments();
            foreach ($shipments as $shipment) {
                Order::query()->updateOrCreate([
                    'api_id' => $shipment->order->orderId,
                    'bol_account_id' => $bol_account->id,
                ], [
                    'api_id' => $shipment->order->orderId,
                    'place_date' => Carbon::parse($shipment->order->orderPlacedDateTime)->toDateTimeString(),
                    'bol_account_id' => $bol_account->id,
                    'is_shipped_by_dashboard' => true,
                ]);
                $shipment = $bol_shipments_service->findShipment($shipment->shipmentId);
                $bol_shipments_service->store($shipment->toArray());
            }
            dd('DONE');
        }catch(Throwable $e)
        {
            dd($e);
        }
    }
}
