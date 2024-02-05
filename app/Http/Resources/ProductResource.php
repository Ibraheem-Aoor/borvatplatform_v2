<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'api_id' => $this->id,
            'title' => $this->title,
            'image_url' => url($this->getImageUrl($this->image)),
            'ean' => $this->ean,
            'country' => $this->country,
            'weight' => $this->weight,
            'height' => $this->height,
            'width' => $this->width,
            'length' => $this->length,
        ];
    }

    public function getImageUrl($image)
    {
        return $image != null ? Storage::url('products/' . $this->id . '/' . $this->image)
                :
                asset('assets/img/product-placeholder.webp');
    }
}
