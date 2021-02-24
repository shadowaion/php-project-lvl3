<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlCheck extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $attributes = [
        'url_id' => '',
        'status_code' => 200,
        'h1' => '',
        'keywords' => '',
        'description' => ''
    ];
}
