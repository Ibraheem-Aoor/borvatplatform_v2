<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends BaseUserRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'weight' => 'required',
            'number_of_pieces' => 'required|numeric',
            'purchase_place' => 'nullable|string',
            'purchase_price' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'note' => 'nullable|string',
            'content' => 'nullable|string',
        ];
    }
}
