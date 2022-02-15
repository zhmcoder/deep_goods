<?php

namespace Andruby\DeepGoods\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsSkuAttrValueMap extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $guarded = [];
}
