<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShipmentProduct extends Model
{
    use HasFactory;
    protected $guarded = [];

    public  function storeFulfilment($fulfilment)
    {
        $this->fulfilment()->updateOrCreate([
            'shipment_product_id' => $this->id,
        ], [
            'method' => @$fulfilment['method'],
            'distribution_party' => @$fulfilment['distributionParty'],
            'latest_delivery_date' => @$fulfilment['latestDeliveryDate'],
            'shipment_product_id' => $this->id,
        ]);
    }

    public function fulfilment(): HasOne
    {
        return $this->hasOne(ShipmentProductFulfilment::class, 'shipment_product_id');
    }
}
