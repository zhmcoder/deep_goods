<?php

namespace Andruby\DeepGoods\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsImage extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $hidden = ['id', 'deleted_at'];

    protected $guarded = [];
}
