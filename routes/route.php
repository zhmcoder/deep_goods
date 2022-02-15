<?php

use Illuminate\Routing\Router;

Route::group([
    'domain' => config('deep_admin.route.domain'),
    'prefix' => config('deep_admin.route.api_prefix'),
    'namespace' => '\Andruby\DeepGoods\Controllers',
    'middleware' => config('admin.route.middleware')
], function (Router $router) {
    // 产品操作
    $router->resource('goods/class', 'GoodsClassController')->names('goods.class');
    $router->resource('goods/list', 'GoodsController')->names('goods.list');
    $router->post("goods/addGoodsAttr", "GoodsController@addGoodsAttr")->name("addGoodsAttr");
    $router->post("goods/addGoodsAttrValue", "GoodsController@addGoodsAttrValue")->name("addGoodsAttrValue");
});

