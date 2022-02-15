<?php

namespace Andruby\DeepGoods\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsSkuStock extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = ['deleted_at'];
}
