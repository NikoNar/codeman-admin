<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSlider extends Model
{
    protected $table = 'home_sliders';
    protected $fillable = ['from_to_date_en','from_to_date_arm', 'main_text_en', 'main_text_arm', 'thumbnail', 'order'];
}
