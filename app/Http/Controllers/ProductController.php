<?php

namespace App\Http\Controllers;

use App\DataTables\Product\ProductTransformer;
use App\Models\Product;
use App\Traits\BoolApiTrait;
use App\Traits\OrderTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    // use BoolApiTrait , OrderTrait;
    public function index()
    {
        return view('admin.products.index');
    }



    public function getAllProducts()
    {
        return DataTables::of(Product::query())
            ->setTransformer(ProductTransformer::class)->make(true);
    }


    public function getNoImageProducts()
    {
        return DataTables::of(Product::query()->whereNull('image'))
        ->setTransformer(ProductTransformer::class)->make(true);
    }

    public function update(Request $request)
    {
        $request->validate([
                        'weight' => 'required',
                        'number_of_pieces' => 'required|numeric',
                    ]);
        $product = Product::findOrFail($request->product_id);
        $product->purchase_place = $request->purchase_place;
        $product->purchase_price = $request->purchase_price;
        $product->weight = $request->weight / 1000; //in KG
        $product->number_of_pieces = $request->number_of_pieces;
        $product->width = $request->width ?? 0;
        $product->height = $request->height ?? 0;
        $product->length = $request->length ?? 0;
        $product->note = $request->note;
        $product->content = $request->content;
        $product->save();
        $image = $request->file('product_image');
        try{
            $response_data = [
                'status' => true,
                'number_of_pieces' => $product->number_of_pieces,
                'purchase_place' => $product->purchase_place,
                'purchase_price' => $product->purchase_price,
                'weight' => $product->weight,
                'width' => $product->width,
                'length' => $product->length,
                'height' => $product->height,
                'note'  =>  $product->note,
                'content'  =>  $product->content,
                'image_id' => $product->id,
            ];
            if($image)
            {
                $image_name = Storage:: disk('public')->put('products/'.$product->id.'/' , $image);
                Storage::disk('public')->delete('products/'.$product->id.'/'.$product->image);
                $product->image = basename($image_name);
                $product->save();
                $response_data = array_merge($response_data , ['image_stored' => true , 'image_path' => Storage::url('products/'.$product->id.'/'.$product->image)]);
                return response()->json($response_data , 200);
            }else{
                $response_data = array_merge($response_data  , ['is_updated' => true]);
                return response()->json($response_data , 200);
            }
        }catch(Throwable $ex){
            dd($ex);
            return response()->json(['status' => true , 'image_stored' => true] , 200);
        }

    }



    public function setProductsWeight()
    {
        ini_set('max_execution_time', 240); // 120 (seconds) = 2 Minutes
        Product::query()->orderByDesc('created_at')->chunk(200 , function($products){
            foreach($products as $product)
            {
                $weight = $this->getProductWeight($product->ean);
                $product->weight = $weight ?? 0;
                $product->save();
            }
        });
        dd('Done Successsfullyy');
    }


    /**
     * Get The Product Weight From API
     * return weight in KG.
     */
    public function getProductWeight($ean)
    {
        $url  = $this->getBaseUrl().'content/catalog-products/'.$ean;
        $detailed_product = $this->makeRequest($url); //list of orders
        foreach($detailed_product['attributes'] as $attribute)
        {
            if($attribute['id'] == 'Weight')
            {
                // dd($attribute['values'][0]);
                $weight = $attribute['values'][0]['value'];
                // dd($weight);
                $weiht_unit = explode('.' ,  $attribute['values'][0]['unitId']);
                if($weiht_unit[2] == 'GRM')
                {
                    $weight = $weight / 1000; //In KG
                }
                return $weight;
            }
        }
    }

}
