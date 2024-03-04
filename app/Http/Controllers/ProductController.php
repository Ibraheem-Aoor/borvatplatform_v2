<?php

namespace App\Http\Controllers;

use App\DataTables\Product\ProductTransformer;
use App\Http\Requests\ProductPropertyRequest;
use App\Http\Requests\ProductRequest;
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

    public function update(ProductRequest $request)
    {
        try {
            $product = Product::findOrFail($request->product_id);
            $product->update([
                'purchase_place' => $request->purchase_place,
                'purchase_price' => $request->purchase_price,
                'weight' => $request->weight / 1000, //in KG
                'number_of_pieces' => $request->number_of_pieces,
                'width' => $request->width ?? 0,
                'height' => $request->height ?? 0,
                'length' => $request->length ?? 0,
                'note' => $request->note,
                'content' => $request->content,
            ]);
            $image = $request->file('product_image');
            if ($image) {
                $image_name = Storage::disk('public')->put('products/' . $product->id . '/', $image);
                Storage::disk('public')->delete('products/' . $product->id . '/' . $product->image);
                $product->image = basename($image_name);
                $product->save();
            }
            return response()->json(generateResponse(status: true, modal_to_hide: '#product-update-modal', table_reload: true), 200);
        } catch (Throwable $ex) {
            dd($ex);
            return response()->json(['status' => true, 'image_stored' => true], 200);
        }

    }

    /**
     * Update The Product Properties
     */
    public function updateProperities(ProductPropertyRequest $request, $id)
    {
        try {
            $product = Product::query()->findOrFail($id);
            $properties = $request->validated('properties');
            $active_properities = $request->validated('active_properities');
            $product->properties()->delete();
            foreach ($properties as $key => $property) {
                $product->properties()->updateOrCreate([
                    'name' => $property,
                ], [
                    'name' => $property,
                    'is_active' => isset($active_properities[$key]),
                ]);
            }
            $response = generateResponse(status: true, modal_to_hide: '#product-properities-modal', table_reload: true);
        } catch (Throwable $e) {
            dd($e);
            $response = generateResponse(false);
        }
        return response()->json($response);
    }


}
