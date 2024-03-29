<?php

namespace Andruby\DeepGoods\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsAttr extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function values(): HasMany
    {
        return $this->hasMany(GoodsAttrValue::class)->orderby('id');
    }

    protected $allAttrs;

    public function allAttrs()
    {
        if (!empty($this->allAttrs)) {
            return $this->allAttrs;
        }
        return $this->allAttrs = self::query()->with(['values'])->orderByDesc("sort")->orderByDesc('id')->get()->makeHidden(["created_at", "updated_at"]);
    }
}
