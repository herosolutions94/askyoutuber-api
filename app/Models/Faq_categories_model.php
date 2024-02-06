<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq_categories_model extends Model
{
    use HasFactory;
    protected $table = 'faq_categories';
    protected $fillable = [
        'name',
        'status',
        'site_lang',
        'parent',
        'slug'
    ];
    function faqs(){
        return $this->hasMany(Faq_model::class,'sub_category','id')->where('status','=', 1);
    }
    function sub_cats(){
        return $this->hasMany(Faq_categories_model::class,'parent','id')->where('status','=', 1);
    }
}
