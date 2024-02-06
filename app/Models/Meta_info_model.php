<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta_info_model extends Model
{
    use HasFactory;
    protected $table = 'meta_info';
    protected $fillable = [
        'page_name',
        'slug',
        'site_lang',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'image',
        'og_title',
        'og_description',
        'og_image',
    ];
}
