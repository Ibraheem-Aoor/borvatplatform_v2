<?php

namespace App\Http\Controllers\API;

use App\Models\Shipment;
use App\Services\BOL\BolShipmentService;
use App\Http\Controllers\Controller;

class ShippmentController extends Controller
{
    public function getLabelForOrder(string $bol_order_id)
    {
        // Find shipment by bol order id
        $shipment = Shipment::query()
            ->whereHas('order', function ($q) use ($bol_order_id) {
                $q->where('api_id', $bol_order_id);
            })
            ->first();

        if (!$shipment) {
            return response()->json(['error' => 'Shipment not found'], 404);
        }

        $labelPath = public_path('storage/labels/' . $shipment->api_id . '.pdf');
        $pngPath = public_path('storage/labels/' . $shipment->api_id . '.png');



        // If label not saved yet, fetch from bol.com
        if (!$shipment->has_label && isset($shipment->transport['shippingLabelId'])) {
            $bol_shipment_service = new BolShipmentService($shipment->account);
            $label = $bol_shipment_service->getLabel($shipment->transport['shippingLabelId']);
            if ($label) {
                $path = 'labels/' . $shipment->api_id . '.pdf';
                saveImage($path, $label);
                $shipment->has_label = true;
                $shipment->save();
            }
        }

        if (!file_exists($labelPath)) {
            return response()->json(['error' => 'Label not available'], 404);
        }
        // Convert PDF to PNG if not already done
        if (!file_exists($pngPath)) {
            $imagick = new \Imagick();
            $imagick->setResolution(150, 150);
            $imagick->readImage($labelPath . '[0]'); // [0] = first page
            $imagick->setImageFormat('png');
            $imagick->writeImage($pngPath);
        }

        return response()->file($pngPath, ['Content-Type' => 'image/png']);
    }
}
