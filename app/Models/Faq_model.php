<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq_model extends Model
{
    use HasFactory;
    protected $table = 'faqs';
    protected $fillable = [
        'question',
        'answer',
        'status',
        'category',
        'site_lang',
        'sub_category',
        'slug',
        'author',
        'author_dp'
    ];
}
