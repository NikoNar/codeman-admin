<?php

namespace Codeman\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        auth()->check();
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
               'title'  => 'required|string',
               'status' => 'required',
               'thumbnail' => 'required',
               'description' => 'required', 
               'duration' => 'required|string',          
               'frequency' => 'required|string',          
               'price' => 'required|string',   
               'start_date' => 'required|string',   
                       
               
            ];
        }else{
            return [
               'title'  => 'required|string',
               'status' => 'required',
               'thumbnail' => 'required',
               'description' => 'required', 
               'duration' => 'required|string',          
               'frequency' => 'required|string',          
               'price' => 'required|string',  
               'start_date' => 'required|string',   

            ];
        }
    }
}
