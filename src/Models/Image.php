<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
	protected $fillable = ['original_name', 'filename', 'alt', 'width', 'height', 'file_size', 'file_type'];

    public static $rules = [
        'file' => 'required|mimes:svg,png,gif,jpeg,jpg,jpeg2000,webp,bmp,application/pdf,pdf,doc,docx,ppt,pptx,xls,xlsx,csv'
    ];

    public static $messages = [
        'file.mimes' => 'Uploaded file format is invalid',
        'file.required' => 'File is required'
    ];

    /**
     * Get the parent commentable model (post or video).
     */
    public function imageable()
    {
        return $this->morphTo();
    }

}
