<?php

namespace App\Http\Requests\Api\Medicines;

use Illuminate\Foundation\Http\FormRequest;

class storeMedicinesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'code' => 'required',
            'parent' => '',
        ];
    }
}