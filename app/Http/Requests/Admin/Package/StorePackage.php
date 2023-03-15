<?php

namespace App\Http\Requests\Admin\Package;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePackage extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public $request;
    public function authorize()
    {
        return true;
    }
    public function _constructor($request){
        $this->request = $request;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $request = $this->request;
        $id = $request->get('id');
        return [
            'price' => 'required',
            'type' => 'required',
            'status' => 'required',
            'name' => [
                'required',
                Rule::unique('packages')->ignore($id)
            ],
        ];
    }
}
