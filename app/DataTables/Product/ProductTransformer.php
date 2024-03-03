<?php
namespace App\DataTables\Product;

use Illuminate\Support\Facades\Storage;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    public function transform($product)
    {
        return [
            'image' => $this->getImage($product),
            'title' => $product->title,
            'ean' => $product->ean,
            'number_of_pieces' => '<span id="p-no-of-pieces-' . $product->id . '">' . $product->number_of_pieces . '</span>',
            'purchase_place' => '<span id="p-place-' . $product->id . '">' . $product->purchase_place . '</span>',
            'purchase_price' => '<span id="p-price-' . $product->id . '">' . $product->purchase_price . '</span>',
            'weight' => '<span id="p-weight-' . $product->id . '">' . ((double) ($product->weight * 1000)) . ' gm</span>',
            'width' => '<span id="p-width-' . $product->id . '">' . $product->width . '</span>',
            'height' => '<span id="p-height-' . $product->id . '">' . $product->height . '</span>',
            'length' => '<span id="p-length-' . $product->id . '">' . $product->length . '</span>',
            'note' => '<span id="p-note-' . $product->id . '">' . $product->note . '</span>',
            'content' => '<span id="p-content-' . $product->id . '">' . $product->content . '</span>',
            'action' => $this->getActionBtns($product),
        ];
    }

    public function getImage($product)
    {
        $path = $product->image ?
            Storage::url('products/' . $product->id . '/' . $product->image)
            :
            asset('assets/img/product-placeholder.webp');
        return '<img id="' . $product->id . '" src="' . $path . '" width="200"/>';
    }


    public function getDataImageAttr($product)
    {
        return $product->image ? 'data-image="' . Storage::url("products/" . $product->id . '/' . $product->image) . '"' : null;
    }


    public function getActionBtns($product)
    {
        return '<div class="d-flex"><button id="btn-' . $product->id . '" type="button" class="btn-sm btn btn-success"
        data-id="' . $product->id . '" data-title="' . $product->title . '"
        ' . $this->getDataImageAttr($product) .
            'data-bs-toggle="modal" data-bs-target="#exampleModal"><i
            class="fa fa-edit"></i></button> &nbsp;
         <button  type="button" class="btn-sm btn btn-success"
        data-product_id="' . $product->id . '" data-title="' . $product->title . '"
        data-bs-toggle="modal" data-bs-target="#product-properities-modal" data-title="'.$product->title.'"  data-props='.(json_encode($product->properties)).' data-action="' . route('product.update_properities'  ,$product->id) . '" data-method="POST"><i
            class="fa fa-cogs"></i></button></div>';
    }
}
