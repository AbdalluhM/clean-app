<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupCategoryRequest extends FormRequest
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
            'category_id' => 'required|integer',
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'image' => 'required|mimes:png,jpg,jpeg',
            'desc' => 'required|string'
        ];
    }
}
