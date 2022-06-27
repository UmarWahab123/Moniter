<?php

namespace App\Http\Requests\Admin\SystemFeature;

use Illuminate\Foundation\Http\FormRequest;

class StoreSystemFeatureRequest extends FormRequest
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
            'name' => 'required|unique:system_features',
        ];
    }
}
