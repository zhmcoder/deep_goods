<?php

namespace Andruby\DeepGoods\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsSku extends Model
{
    use SoftDeletes;

    const status = [-1, 0, 1];

    protected $guarded = [];

    protected $appends = ['stock_num', 'cover'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $with = ['stock'];

    protected $casts = [
        'price' => 'double',
        'line_price' => 'double',
        'cost_price' => 'double',
    ];

    /**
     * @return BelongsTo
     */
    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class);
    }

    /**
     * SKU 库存
     * @return HasOne
     */
    public function stock(): HasOne
    {
        return $this->hasOne(GoodsSkuStock::class, 'sku_id');
    }

    public function attrs(): BelongsToMany
    {
        return $this->belongsToMany(GoodsAttrValue::class, 'goods_sku_attr_value_maps', 'goods_sku_id', 'attr_value_id')->whereNull('goods_sku_attr_value_maps.deleted_at');
    }

    public function getStockNumAttribute($key)
    {
        return $this->stock->quantity;
    }

    public function getCoverAttribute($key)
    {
        return admin_file_url($this->image);
    }

    public static function setSkuStatus($goods, $status)
    {
        if (in_array($status, self::status)) {
            //修改sku表
            self::query()->where('goods_id', $goods->id)->update([
                'status' => $status
            ]);
            //修改库存表
            GoodsSkuStock::query()->where('goods_id', $goods->id)->update([
                'status' => $status
            ]);
        } else {
            abort(400, 'SKU状态错误');
        }
    }

    public static function setSkuStock($goods, $goods_sku, $quantity)
    {
        GoodsSkuStock::query()
            ->where('goods_id', $goods->id)
            ->where('sku_id', $goods_sku->id)
            ->updateOrCreate([], [
                'sku_id' => $goods_sku->id,
                'goods_id' => $goods->id,
                'quantity' => $quantity,
                'status' => 1
            ]);
    }

    public static function setSkuAttrValueMap($goods, $goods_sku, $attrs)
    {
        GoodsSkuAttrValueMap::query()->where('goods_sku_id', $goods_sku->id)->delete();
        collect($attrs)->map(function ($item) use ($goods_sku, $goods) {
            GoodsSkuAttrValueMap::query()->create([
                'goods_id' => $goods->id,
                'goods_sku_id' => $goods_sku->id,
                'attr_value_id' => $item['id'],
                'attr_id' => $item['goods_attr_id'] ?? $item['_attr_id'] ?? 0,
            ]);
        });
        $sku_name = collect($attrs)->pluck("name")->join(" ");
        // $sku_name = collect($attrs)->sortBy("id")->pluck("name")->join(" ");
        $goods_sku->name = $goods->name . " " . $sku_name;
        $goods_sku->save();
    }
}
