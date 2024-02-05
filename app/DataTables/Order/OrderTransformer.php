<?php
namespace App\DataTables\Order;

use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    public function transform(Order $order)
    {
        return [
            'checkbox' => '<input type="checkbox" name="id[]" value="' . $order->id . '" >',
            'id' => $order->id,
            'image' => $this->getImage($order),
            'api_id' => $order->api_id,
            'account' => $order->account->name,
            'title' => $order->getProductsTitles(),
            'quantity' => $order->getTotalQty(),
            'unit_price' => $order->getPrices(),
            'total' => $order->getTotalAmount(),
            'place_date' => $order->place_date,
            'country_code' => $order->country_code,
        ];
    }

    public function getImage($order)
    {
        $products = $order->products;
        $images = '';
        foreach ($products as $product) {
            $path = $product->image ?
                Storage::url('products/' . $product->id . '/' . $product->image)
                :
                asset('assets/img/product-placeholder.webp');
            $images .= '<img src="' . $path . '" width="200"/><br>';
        }
        return $images;
    }
}
