<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductPropertyRequest extends BaseUserRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'properties' => ['required', 'array'],
            'properties.*' => ['required'],
            'active_properities' => ['required', 'array'],
            'active_properities.*' => ['nullable', Rule::in(['on', 'off'])],
        ];
    }
}
