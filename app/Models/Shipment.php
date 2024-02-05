<?php

namespace App\Models;

use App\Traits\BelongsToBol;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Shipment extends Model
{
    use HasFactory, BelongsToBol;
    protected $guarded = [];


    protected $with = [
        'order',
        'account'
    ];


    protected static function boot()
    {
        parent::boot();
        static::created(function ($shipment) {
            $shipment->storeProducts();
            $shipment->storeTransport();
        });
    }


    /**
     * Store Shipment Transport Data Using The json column 'transport'
     */
    private function storeTransport()
    {
        $this->transport()->updateOrCreate([
            'api_id' => @$this->transport['transportId'],
        ], [
            'api_id' => @$this->transport['transportId'],
            'transporter_code' => @$this->transport['transporterCode'],
            'track_and_trace' => @$this->transport['trackAndTrace'],
            'shipping_label_id' => @$this->transport['shippingLabelId'],
        ]);
    }


    /**
     * Storing Products into products table.
     * then storing the shipment related products with their fulfilment.
     */
    private function storeProducts()
    {
        foreach ($this->items as $item) {
            $product = Product::updateOrCreate(
                [
                    'ean' => $item['product']['ean'],
                ],
                [
                    'ean' => $item['product']['ean'],
                    'title' => $item['product']['title'],
                    'bol_account_id' => $this->account->id,
                ]

            );
            ShipmentProduct::updateOrCreate([
                'order_item_id' => $item['orderItemId'],
                'shipment_id' => $this->id,
                'product_id' => $product->id,
            ], [
                'order_item_id' => $item['orderItemId'],
                'offer_id' => @$item['offer']['offer_id'],
                'quantity' => @$item['quantity'],
                'unit_price' => @$item['unitPrice'],
                'commission' => @$item['commission'],
                'shipment_id' => $this->id,
                'product_id' => $product->id,
            ])->storeFulfilment($item['fulfilment']);
            $this->order->storeProduct($item, $product);
            $this->order->update(
                [
                    'country_code' => $this->country_code
                ]
            );
        }
    }



    ### START RELATIONS ##
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, ShipmentProduct::class)->withPivot([
            'quantity',
            'commission',
            'unit_price'
        ]);
    }
    public function transport()
    {
        return $this->hasOne(ShipmentTransport::class, 'shipment_id');
    }
    ### END RELATIONS ##



    #### START GETTERS / SETTERS ####
    public function getItemsAttribute()
    {
        $attribute = $this->attributes['items'];
        return is_array($attribute) ? $attribute : json_decode($attribute, true);
    }
    public function getTransportAttribute()
    {
        $attribute = $this->attributes['transport'];
        return is_array($attribute) ? $attribute : json_decode($attribute, true);
    }
    public function getShipmentDetailsAttribute()
    {
        $attribute = $this->attributes['shipment_details'];
        return is_array($attribute) ? $attribute : json_decode($attribute, true);
    }
    public function getBillingDetailsAttribute()
    {
        $attribute = $this->attributes['billing_details'];
        return is_array($attribute) ? $attribute : json_decode($attribute, true);
    }
    #### END GETTERS / SETTERS ####



    public function getImagesForTable(): string
    {
        $products = $this->products;
        $images = '';
        foreach ($products as $product) {
            $path = $product->image ?
                Storage::url('products/' . $product->id . '/' . $product->image)
                :
                asset('assets/img/product-placeholder.webp');
            $images .= '<img src="' . $path . '" width="100"/><br>';
        }
        return $images;
    }


}
