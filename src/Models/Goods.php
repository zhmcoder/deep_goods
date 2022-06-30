<?php

namespace Andruby\DeepGoods\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        "goods_class_path" => "json",
        'created_at' => "Y-m-d H:i:s",
        'price' => 'double',
        'line_price' => 'double',
        'cost_price' => 'double',
        'show_app' => 'array',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $with = ['images'];

    const OFF_SHELF = 0; // 下架
    const ON_SHELF = 1; // 立即上架
    const TIME_SHELF = 2; // 自动上架
    const SHELF = [
        self::OFF_SHELF => '下架',
        self::ON_SHELF => '立即上架',
        self::TIME_SHELF => '自动上架',
    ];

    /**
     * 产品销售属性列表
     * @return HasMany
     */
    public function attr_map(): HasMany
    {
        return $this->hasMany(GoodsAttrMap::class, 'goods_id')
            ->with([
                'attr.values',
                'value_map',
                'value_map.attr_value',
            ]);
    }

    public function attr_value_map(): hasMany
    {
        return $this->hasMany(GoodsAttrValueMap::class, 'goods_id');
    }

    /**
     * 产品 SKU
     * @return HasMany
     */
    public function skus(): HasMany
    {
        return $this->hasMany(GoodsSku::class, 'goods_id')->where('status', 1)->with(['stock', 'attrs']);
    }

    /**
     * 产品 库存列表
     * @return HasMany
     */
    public function stock(): HasMany
    {
        return $this->hasMany(GoodsSkuStock::class, 'goods_id');
    }

    /**
     * 产品详情内容关联
     * @return HasOne
     */
    public function content(): HasOne
    {
        return $this->hasOne(GoodsContent::class);
    }

    /**
     * 产品分类关联
     * @return BelongsTo
     */
    public function goodsClass(): BelongsTo
    {
        return $this->belongsTo(GoodsClass::class);
    }

    /**
     * 产品品牌管理
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * 产品图片关联
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(GoodsImage::class);
    }

    /**
     * 产品封面字段
     * @param $key
     * @return mixed
     */
    public function getCoverAttribute($key)
    {
        return collect($this->images)->first();
    }

    protected static function boot()
    {
        parent::boot();
        self::deleting(function ($model) {
            $model->content()->delete();
            $model->stock()->delete();
            $model->skus()->delete();
            $model->attr_map()->delete();
            $model->attr_value_map()->delete();
            $model->images()->delete();
        });
    }
}
