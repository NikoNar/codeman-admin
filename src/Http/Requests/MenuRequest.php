<?php

namespace Codeman\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->has("id")){
            return [
                'name' => 'required|unique:menus,name,'.$this->get("id"),
                'lang' => 'required',
            ];

        }else{
           return [
               'name' => 'required|unique:menus',
               'lang' => 'required',
           ]; 
        }
    }
}
