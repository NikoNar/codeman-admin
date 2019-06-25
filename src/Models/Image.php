<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
	protected $fillable = ['original_name', 'filename', 'alt', 'width', 'height', 'file_size', 'file_type'];
    public static $rules = [
        'file' => 'required|mimes:png,gif,jpeg,jpg,bmp,application/pdf,pdf,doc,docx,ppt,pptx,xls,xlsx'
    ];
    public static $messages = [
        'file.mimes' => 'Uploaded file format is invalid',
        'file.required' => 'File is required'
    ];
}
