<?php
namespace App\DataTables\ShippingLabel;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use League\Fractal\TransformerAbstract;

class ShippingLabelTransformer extends TransformerAbstract
{
    public function transform($shipping_label)
    {
        return [
            'order_api_id' => $shipping_label->order()?->code ?? $shipping_label->order()?->api_id ,
            'shipment_no' => $shipping_label->shipment_id,
            'ref' => $shipping_label->ref,
            'first_name' => @$shipping_label->getShipmentDetails()['firstName'] ?? $shipping_label->getShipmentDetails()['name'],
            'surname' => @$shipping_label->getShipmentDetails()['surname'] ?? null,
            'city' => $shipping_label->getShipmentDetails()['city'],
            'street_name' => @$shipping_label->getShipmentDetails()['streetName'] ?? $shipping_label->getShipmentDetails()['address'],
            'created_at' => Carbon::parse($shipping_label->created_at)->format('Y-M-d'),
            'checkbox' => '<input class="form-check-input" type="checkbox" name="id[]" value="'.($shipping_label->shipment_id ?? $shipping_label->id).'">',
        ];
    }
}
