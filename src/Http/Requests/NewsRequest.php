<?php

namespace Codeman\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
        if($this->has('id')){
            return [
               'title'  => 'required',
               'slug'   => 'required|unique:news,slug,'.$this->get("id"),
               'status' => 'required',
               'excerpt' => 'required',
               'category_id' => 'required'
            ];
        }else{
            return [
               'title'  => 'required',
               // 'slug'   => 'required|unique:news',
               'status' => 'required',
               'excerpt' => 'required',
               'category_id' => 'required'
            ];
        }
    }
}
