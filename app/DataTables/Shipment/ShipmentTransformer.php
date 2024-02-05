<?php
namespace App\DataTables\Shipment;

use App\Models\Shipment;
use Illuminate\Support\Facades\Storage;
use League\Fractal\TransformerAbstract;


class ShipmentTransformer extends TransformerAbstract
{


    public function transform(Shipment $shipment)
    {
        return [
            'image' => $shipment->getImagesForTable(),
            'order_id' => $shipment->order?->api_id,
            'api_id' => $shipment->api_id,
            'account' => "<span class='text-primary'>{$shipment->account->name}</span>",
            'label' =>  @$shipment->transport['shippingLabelId'],
            'firstName' => $shipment->first_name,
            'surname' => $shipment->surname,
            'country_code' => $shipment->country_code,
            'place_date' => $shipment->place_date,
            'is_printed' => $shipment->getIsPrintedInHtml(),
            'checkbox' => '<input class="form-check-input" type="checkbox" name="id[]" value="' . $shipment->id . '">',
            'note' => '<span id="row-' . $shipment->id . '">' . $shipment->note . '</span>',
        ];
    }




}
