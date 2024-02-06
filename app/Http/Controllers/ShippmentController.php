<?php

namespace App\Http\Controllers;

use App\Models\ShipmentDownload;
use App\DataTables\Shipment\ShipmentTransformer;
use App\Exports\ShipmentExport;
use App\Jobs\SendEmailJob;
use App\Mail\OrderShippedMail;
use App\Models\BolAccount;
use App\Models\BusinessSetting;
use App\Models\Order;
use App\Models\Shipment;
use App\Services\BOL\BolShipmentService;
use App\Services\PDF_HTML;
use App\Services\ZianpeslyShippingService;
use App\Traits\BoolApiTrait;
use App\Traits\OrderTrait;
use App\Traits\ShipmentTrait;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Throwable;
use Yajra\DataTables\Facades\DataTables;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use Str;
use Barryvdh\DomPDF\Facade\Pdf;
use iio\libmergepdf\Merger;
use Maatwebsite\Excel\Facades\Excel;

class ShippmentController extends Controller
{


    public function index(Request $request)
    {
        $data['form_route'] = route('shippment.pdf');
        $data['full_pdf_route'] = route('shippment.full-pdf');
        $data['full_excel_route'] = route('shippment.full-excel');
        $data['full_pdf_copy_route'] = route('shippment.full-pdf', ['is_copy' => true]);
        $data['store'] = $request->store;
        $data['table_data_url'] = route('shippment.get-api-data', $request->query());
        $data['bol_accounts'] = BolAccount::getCachedRecords();
        $data['current_account_name'] = $request->query('account_id') ? $data['bol_accounts'][$request->query('account_id')] : null;
        $data['today_shipments_count'] = $this->getTodayShipmentsCount($request);
        return view('admin.shippments.index', $data);
    }



    public function getApiShipments(Request $request)
    {
        return DataTables::of(
            Shipment::query()
                ->whereDate('place_date', Carbon::today())
                ->where('is_printed', false)
                ->when($request->has('account_id'), function ($query) use ($request) {
                    $query->where('bol_account_id', $request->query('account_id'));
                })
        )
            ->filterColumn('account', function ($shipment, $keyword) {
                $shipment->whereHas('account', function ($account) use ($keyword) {
                    $account->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->orderColumn('account', function ($shipment, $order) {
                $shipment->orderBy('bol_account_id', $order);
            })
            ->setTransformer(ShipmentTransformer::class)
            ->make(true);
    }
    public function getArchiveShipments(Request $request)
    {
        $query = Shipment::query();

        $query->when($request->query('from_date'), function ($query) use ($request) {
            $query->whereBetween('place_date', [
                Carbon::parse($request->query('from_date'))->toDateString(),
                Carbon::parse($request->query('to_date'))->toDateString()
            ])->orWhereBetween('created_at', [
                        Carbon::parse($request->query('from_date'))->toDateString(),
                        Carbon::parse($request->query('to_date'))->toDateString()
                    ]);
        });
        $query->when($request->query('account_id'), function ($query) use ($request) {
            $query->where('bol_account_id', $request->query('account_id'));
        });
        return DataTables::of($query)
            ->filterColumn('account', function ($shipment, $keyword) {
                $shipment->whereHas('account', function ($account) use ($keyword) {
                    $account->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->orderColumn('account', function ($shipment, $order) {
                $shipment->orderBy('bol_account_id', $order);
            })
            ->setTransformer(ShipmentTransformer::class)
            ->make(true);
        // return DataTables::of(Shipment::query()->with('order')->orderByDesc('place_date'))
        //     ->setTransformer(ShipmentTransformer::class)->make(true);
    }

    protected function getTodayShipmentsCount($request): int
    {
        return Shipment::query()->whereDate('place_date', Carbon::today()->toDateTimeString())
            ->when($request->query('account_id'), function ($shipment) use ($request) {
                $shipment->where('bol_account_id', $request->query('account_id'));
            })->count();
    }



    public function archive(Request $request)
    {
        $data['form_route'] = route('shippment.pdf');
        $data['full_pdf_route'] = route('shippment.full-pdf');
        $data['full_excel_route'] = route('shippment.full-excel');
        $data['full_pdf_copy_route'] = route('shippment.full-pdf', ['is_copy' => true]);
        $data['table_data_url'] = route('shippment.get-archive-data', [
            'account_id' => $request->query('account_id'),
        ]);
        $data['bol_accounts'] = BolAccount::getCachedRecords();
        $data['current_account_name'] = $request->query('account_id') ? $data['bol_accounts'][$request->query('account_id')] : null;
        $data['today_shipments_count'] = $this->getTodayShipmentsCount($request);
        // dd($data);
        return view('admin.shippments.index', $data);
    }





    public function generateShippmentPdf(Request $request)
    {
        ini_set('max_execution_time', 240); // 120 (seconds) = 2 Minutes
        set_time_limit(0);
        $ids = $request->id;
        if ($ids) {
            $shippments = Shipment::all();
            $views = [];
            $pdf = null;
            $shipment_sender_details = json_decode(BusinessSetting::where('key', 'shipment_sender_details')->first()->value, true);
            foreach ($shippments as $shippment) {
                if (in_array($shippment->id, $ids)) {
                    if (is_null($pdf)) {
                        $pdf = PDF::loadView('admin.pdf.shippment-pdf', compact('shippment', 'shipment_sender_details'));
                        continue;
                    }
                    // Add another page and add HTML from view to this
                    $pdf->getMpdf()->AddPage();
                    $pdf->getMpdf()->WriteHTML((string) view('admin.pdf.shippment-pdf', compact('shippment', 'shipment_sender_details')));
                }
            }
            return $pdf->download('shipment-' . date('Y-M-d') . '.pdf');
        }
    }



    public function generateFullShippmentPdf(Request $request)
    {
        try {

            if (ob_get_level() > 0) {
                ob_clean();
            }
            ini_set('max_execution_time', 900); // 120 (seconds) = 2 Minutes
            set_time_limit(0);
            $ids = $request->id;
            $is_mail_allowed = true;
            $data['bol_logo'] = asset('assets/img/bol-logo.jpg');
            // $data['shipment_sender_details'] = json_decode(BusinessSetting::where('key', 'shipment_sender_details')->first()->value, true);
            $data['inside_netherlands_logo'] = asset("assets/img/inside_netherlands_logo.jpg");
            $data['outside_netherlands_logo'] = asset("assets/img/outside_netherlands_logo.jpg");
            $data['iterator'] = [];
            $data['page_count'] = 1;
            $data['table_font_size'] = '13px !important';
            $merger = new Merger;
            if ($request->is_copy) {
                $data['today_date'] = Carbon::today()->toDateString();
            }
            $views = [];
            $shipments = Shipment::query()->whereIn('id', $ids)->get();
            // $zinapesly_shipping_service = new ZianpeslyShippingService();
            $zinapesly_rate_limit_counter = 0;
            foreach ($shipments as $shipment) {
                $data['shipment'] = $shipment;
                $data['products'] = $shipment->products;
                $pdf = Pdf::loadView('admin.pdf.full-shipment', $data);
                $pdf->setPaper('A5');
                $temp_pdf = public_path('storage/temp_pdf/' . time() . '-' . mt_rand(100000000000000, 200000000000000000) . '.pdf');
                file_put_contents($temp_pdf, $pdf->output());
                array_push($data['iterator'], $temp_pdf);
                if ($shipment->has_label) {
                    $label = public_path('storage/labels/' . $shipment->api_id . '.pdf');
                    array_push($data['iterator'], $label);
                } elseif (isset($shipment->transport['shippingLabelId'])) {
                    $bol_shipment_service = new BolShipmentService($shipment->account);
                    $shipping_label_id = @$shipment->transport['shippingLabelId'];
                    $label = $bol_shipment_service->getLabel($shipping_label_id);
                    if ($label) {
                        $path = 'labels/' . $shipment->api_id . '.pdf';
                        $label = saveImage($path, $label);
                        $shipment->has_label = true;
                        $shipment->save();
                        array_push($data['iterator'], $label);
                    }
                }else {
                    $pdf = Pdf::loadView('admin.pdf.shipment-label', $data);
                    $pdf->setPaper('A5');
                    $temp_pdf = public_path('storage/temp_pdf/' . time() . '-' . mt_rand(100000000000000, 200000000000000000) . '.pdf');
                    file_put_contents($temp_pdf, $pdf->output());
                    array_push($data['iterator'], $temp_pdf);
                }
                $shipment->is_printed = true;
                $shipment->save();
                $data['page_count'] += 1;
            } //end foreach
            $merger->addIterator($data['iterator']);
            $createdPdf = $merger->merge();
            file_put_contents(public_path('result.pdf'), $createdPdf);
            return response()->download(public_path('result.pdf'), 'BOL-Shipments-' . Carbon::now()->toDateTimeString() . '.pdf');
        } catch (Throwable $e) {
            dd($e);
        }
    }


    /**
     * Store A Shipment Note.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeNote(Request $request)
    {
        try {
            $shipment = Shipment::query()->findOrFail($request->id);
            $shipment->update([
                'note' => $request->note,
            ]);
            $response_data['status'] = true;
            $response_data['is_stored'] = true;
            $response_data['message'] = 'Note Created Successfully';
            $response_data['note'] = $shipment->note;
            $response_data['row'] = $shipment->id;
            $error_no = 200;
        } catch (Throwable $e) {
            $response_data['status'] = false;
            $response_data['message'] = 'Something Went Wrong';
            $error_no = 500;
        }
        return response()->json($response_data, $error_no);
    }




    public function search(Request $request)
    {
        $data['form_route'] = route('shippment.pdf');
        $data['full_pdf_route'] = route('shippment.full-pdf');
        $data['full_excel_route'] = route('shippment.full-excel');
        $data['table_data_url'] = route('shippment.get-archive-data', $request->query());
        $data['from_date'] = $request->query('from_date');
        $data['to_date'] = $request->query('to_date');
        $data['full_pdf_copy_route'] = route('shippment.full-pdf', ['is_copy' => true]);
        $data['today_shipments_count'] = $this->getTodayShipmentsCount($request);
        $data['bol_accounts'] = BolAccount::getCachedRecords();
        $data['current_account_name'] = $request->query('account_id') ? $data['bol_accounts'][$request->query('account_id')] : null;
        return view('admin.shippments.index', $data);
    }




    public function getRecentDonwloads(Request $request)
    {
        if ($request->query('from_date')) {
            $from_date = Carbon::parse($request->query('from_date'))->toDateTimeString();
            $to_date = Carbon::parse($request->query('to_date'))->toDateTimeString();
            $data['files'] = ShipmentDownload::query()->whereBetween('created_at', [$from_date, $to_date])->paginate(50);
        } else {
            $data['files'] = ShipmentDownload::query()->orderByDesc('created_at')->paginate(50);
        }
        return view('admin.shippments.downloads', $data);
    }



    public function downloadShipmentPdf($id)
    {
        try {
            $file = ShipmentDownload::query()->find($id);
            return Storage::disk('public')->download('shipments/' . $file->file_name);
        } catch (Throwable $e) {
            dd($e);
        }
    }

    /**
     * Excel generate.
     */
    public function generateFullShippmentExcel(Request $request)
    {
        ini_set('max_execution_time', 240); // 120 (seconds) = 2 Minutes
        set_time_limit(0);
        $ids = $request->id;
        if ($ids) {
            try {
                return Excel::download(new ShipmentExport($ids), 'borvat-shipment-' . date('Y-M-d') . '.xlsx');
            } catch (Throwable $e) {
                return back();
            }
        } else {
            return back();
        }
    }

}
