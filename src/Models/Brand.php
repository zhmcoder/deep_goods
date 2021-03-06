<?php

namespace Andruby\DeepGoods\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'show_app' => 'array',
    ];
}
