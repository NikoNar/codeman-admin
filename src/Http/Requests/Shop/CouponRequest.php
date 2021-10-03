<?php

namespace Codeman\Admin\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
        $data = [
            'type' => 'required|in:percent,fixed_cart,fixed_product',
            'discount' => 'required|numeric',
            'usage_limit' => 'nullable|numeric',
            'items_usage_limit' => 'nullable|numeric',
            'user_usage_limit' => 'nullable|numeric',
            'customer_email' => 'nullable|email',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:now|after:start_date'
        ];
        if($this->get('_method') == 'PUT'){
            $data['code'] = 'bail|required|string|min:8|max:255|unique:coupons,id,'.$this->id;
        }else{
            $data['code'] = 'bail|required|string|min:8|max:255|unique:coupons';
        }
        return $data;
    }

        /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
    }
}
