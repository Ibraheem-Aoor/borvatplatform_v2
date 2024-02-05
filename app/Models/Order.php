<?php

namespace App\Models;

use App\Traits\BelongsToBol;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use BelongsToBol;
    use HasFactory;
    protected $guarded = [];

    protected $with = [
        'account',
    ];

    ### START RELATIONS ###
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, OrderProduct::class)->withPivot([
            'quantity',
            'quantity_shipped',
            'commission',
            'unit_price'
        ]);
    }


    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class, 'order_id');
    }


    ### END RELATIONS ###

    ### START GETTERS / SETTERS ##
    public function getOrderItemsAttribute()
    {
        $attribute = $this->attributes['order_items'];
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
    ### END GETTERS / SETTERS ##


    public function getTotalQty()
    {
        return $this->products->sum(function ($product) {
            return $product->pivot->quantity - $product->pivot->quantity_cancelled;
        });
    }

    //return weight in kg
    public function getTotalWeight()
    {
        return $this->products()->sum('weight');
    }

    public function getProductsTitles()
    {
        $products = $this->shipment->products;
        $titles = '';
        foreach ($products as $product) {
            $titles .= '<p style="font-size:12px !important;">' . $product->title . '<br><span style="color:gray">EAN: ' . $product->ean . '</span></p>';
        }
        return $titles;
    }

    public function getPrices()
    {
        $prices = '<ul>';
        foreach ($this->shipment->products as $product) {
            $prices .= '<li>' . $product->pivot->unit_price . '</li>';
        }
        return $prices;
    }

    public function getTotalAmount()
    {
        $total = 0.0;
        $this->products()->each(function ($product) use (&$total) {
            $total += (($product->pivot->quantity - $product->pivot->quantity_cancelled) * $product->pivot->unit_price);
        });
        return $total;
    }






    public function isProductNumberOfPiecesSet()
    {
        return true;
        $products = $this->products;
        foreach ($products as $product) {
            if (is_null($product->number_of_pieces)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Store Order Product In `order_products_table`
     */
    public function storeProduct(array $item, Product $stored_product): void
    {
        OrderProduct::query()->updateOrCreate([
            'order_item_id' => $item['orderItemId'],
            'order_id' => $this->id,
            'product_id' => $stored_product->id,
        ], [
            'order_item_id' => $item['orderItemId'],
            'offer_id' => @$item['offer']['offer_id'],
            'quantity' => @$item['quantity'],
            'quantity_shipped' => @$item['quantity'],
            'quantity_cancelled' => 0,
            'unit_price' => @$item['unitPrice'],
            'commission' => @$item['commission'],
            'fulfilment_method' => @$item['fulfilment']['method'],
            'fulfilment_status' => isset($this->shipment) ? 'HANDELD' : 'OPEN',
            'order_id' => $this->id,
            'product_id' => $stored_product->id,
        ]);
    }
}
