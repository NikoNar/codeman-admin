<?php

namespace Codeman\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
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
        if($this->get('_method') == 'PUT'){
            $slug = $this->get('slug');
            $lang  = $this->get('lang');
            return [
                'title'  => 'required',
                'slug'   => [
                    'required',
                    Rule::unique('pages')->where(function ($query) use($slug, $lang) {
                        return $query->where('slug', '!=', $slug)
                            ->where('lang', '!=', $lang);
                    })
                ],
                'status' => 'required',
            ];
        }else{
            return [
                'title'  => 'required',
                'slug'   => 'nullable|unique:pages',
                'status' => 'required',
            ];
        };
    }
}
