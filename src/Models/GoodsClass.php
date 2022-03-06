<?php

namespace Andruby\DeepGoods\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Andruby\DeepAdmin\Traits\ModelTree;

class GoodsClass extends Model
{
    use SoftDeletes, ModelTree;

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function children(): HasMany
    {
        $where = [];
        $name = request('__search__');
        if (!empty($name)) {
            $where[] = ['name', 'like', '%' . $name . '%'];
        }

        return $this->hasMany(get_class($this), 'parent_id')->where($where)->orderBy('order');
    }
}
