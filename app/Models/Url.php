<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    // @codingStandardsIgnoreStart
    use HasFactory;
    // @codingStandardsIgnoreEnd

    public $timestamps = false;

    protected $attributes = [
        'name' => '',
    ];
}
