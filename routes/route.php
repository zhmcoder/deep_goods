<?php

use Illuminate\Routing\Router;

Route::group([
    'domain' => config('deep_admin.route.domain'),
    'prefix' => config('deep_admin.route.api_prefix'),
    'namespace' => '\Andruby\DeepGoods\Controllers',
    'middleware' => config('deep_admin.route.middleware')
], function (Router $router) {
    // 产品
    $router->resource('category', 'CategoryController')->names('category');
    $router->resource('supplier', 'SuppliersController')->names('supplier');
    $router->resource('brand', 'BrandController')->names('brand');
    $router->resource('shop', 'ShopController')->names('shop');

    // 产品操作
    $router->resource('goods/class', 'GoodsClassController')->names('goods.class');
    $router->resource('goods/list', 'GoodsController')->names('goods.list');
    $router->post("goods/addGoodsAttr", "GoodsController@addGoodsAttr")->name("addGoodsAttr");
    $router->post("goods/addGoodsAttrValue", "GoodsController@addGoodsAttrValue")->name("addGoodsAttrValue");
    $router->post("goods/goodsAttr", "GoodsController@goodsAttr")->name("goodsAttr");
    $router->post("goods/goodsAttrValue", "GoodsController@goodsAttrValue")->name("goodsAttrValue");
    $router->get('goods/on_shelf/{id}', 'GoodsController@on_shelf')->name('goods.on_shelf');
});

