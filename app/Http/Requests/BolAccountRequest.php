<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BolAccountRequest extends BaseUserRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $name_validation = 'required|string|unique:bol_accounts,name' . ($this->id ? ("," . $this->id) : null);
        return [
            'logo' => 'nullable|image',
            'name' => $name_validation,
            'client_id' => 'required|string',
            'client_key' => 'required|string',
            'address' => 'required|array',
            'address.*' => 'required',
        ];
    }
}
