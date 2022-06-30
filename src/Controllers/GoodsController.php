<?php

namespace Andruby\DeepGoods\Controllers;

use Andruby\DeepAdmin\Controllers\ContentController;
use Andruby\DeepGoods\Models\Brand;
use Andruby\DeepGoods\Models\Goods;
use Andruby\DeepGoods\Models\GoodsAttr;
use Andruby\DeepGoods\Models\GoodsAttrMap;
use Andruby\DeepGoods\Models\GoodsAttrValue;
use Andruby\DeepGoods\Models\GoodsAttrValueMap;
use Andruby\DeepGoods\Models\GoodsClass;
use Andruby\DeepGoods\Models\GoodsImage;
use Andruby\DeepGoods\Services\GoodsSku;
use Andruby\HomeConfig\Services\AppInfoService;
use App\Admin\Services\GridCacheService;
use App\Models\AdminRoleUser;
use Illuminate\Http\Request;
use Andruby\DeepAdmin\Components\Attrs\SelectOption;
use Andruby\DeepAdmin\Components\Form\Cascader;
use Andruby\DeepAdmin\Components\Form\CSwitch;
use Andruby\DeepAdmin\Components\Form\DatePicker;
use Andruby\DeepAdmin\Components\Form\Input;
use Andruby\DeepAdmin\Components\Form\InputNumber;
use Andruby\DeepAdmin\Components\Form\Radio;
use Andruby\DeepAdmin\Components\Form\RadioGroup;
use Andruby\DeepAdmin\Components\Form\Select;
use Andruby\DeepAdmin\Components\Form\Upload;
use Andruby\DeepAdmin\Components\Form\WangEditor;
use Andruby\DeepAdmin\Components\Grid\Image;
use Andruby\DeepAdmin\Components\Grid\Tag;
use Andruby\DeepAdmin\Components\Widgets\Divider;
use Andruby\DeepAdmin\Form;
use Andruby\DeepAdmin\Grid;

class GoodsController extends ContentController
{
    public function grid()
    {
        $grid = new Grid(new Goods());

        $grid->model()->where(function ($query) {
            $user = \Admin::user();
            if ($user['role_id'] > AdminRoleUser::ROLE_ADMINISTRATOR) {
                $showApp = json_decode($user['show_app'], true);
                foreach ($showApp as $appId) {
                    $query->orWhere('show_app', 'like', '%"' . $appId . '"%');
                }
            }
        });

        $grid->addDialogForm($this->form()->isDialog()->className('p-15'), '1000px')->isDrawerForm();
        $grid->editDialogForm($this->form(true)->isDialog()->className('p-15'), '1000px')->isDrawerForm();

        $grid->column('id', "序号")->width(80)->sortable()->align('center');
        $grid->column('name', "商品名称")->width(150);
        $grid->column('cover.path', '产品图片')->component(Image::make()->size(50, 50)->preview())->align("center");
        $grid->column('goodsClass.name', "产品分类");
        $grid->column('brand.name', "品牌");
        $grid->column('on_shelf', "是否上架")->align("center")->customValue(function ($row, $value) {
            return $value == 1 ? "上架" : "下架";
        })->component(Tag::make()->type(["上架" => "success", "下架" => "danger"]));

        $grid->column('created_at', '发布时间')->customValue(function ($row, $value) {
            return $value;
        })->width(150);

        $grid->column('show_app', '展示app')->customValue(function ($row, $value) {
            $appInfo = [];
            foreach ($value as $appId) {
                $appInfo[] = GridCacheService::instance()->app_name($appId);
            }
            return $appInfo;
        })->component(Tag::make())->width(200);

        $grid->actions(function (Grid\Actions $actions) {
            $rowInfo = $actions->getRow();

            if ($rowInfo['on_shelf'] == Goods::OFF_SHELF) {
                $name = '上架';
                $shelf = Goods::ON_SHELF;
            } else {
                $name = '下架';
                $shelf = Goods::OFF_SHELF;
            }
            $actions->add(Grid\Actions\ActionButton::make($name)->order(0)
                ->beforeEmit("tableSetLoading", true)
                ->successEmit("tableReload")
                ->afterEmit("tableSetLoading", false)
                ->handler(Grid\Actions\ActionButton::HANDLER_REQUEST)
                ->uri('/admin-api/goods/on_shelf/{id}?on_shelf=' . $shelf)
            );
        })->actionWidth('150px')->actionFixed('right');

        $grid->filter(function (Grid\Filter $filter) {
            $filter->like('name', '商品名称')->component(Input::make());
            $filter->equal('brand_id', '所属品牌')->component(Select::make()->options(function () {
                return Brand::query()->get()->map(function ($item) {
                    return SelectOption::make($item->id, $item->name);
                })->all();
            }));
            $filter->date('created_at', '发布日期')->component(DatePicker::make()->style('width:150px;margin-left:5px;'));

            $filter->like('show_app', '展示app')->component(
                Select::make()->options(function () {
                    return AppInfoService::instance()->app_info();
                })->clearable()->filterable()
            );
        });

        $grid->quickFilter()->filterKey('on_shelf')->defaultValue(null)
            ->quickOptions([Radio::make(1, '上架'), Radio::make(0, '下架')]);

        $grid->toolbars(function (Grid\Toolbars $toolbars) {
            $toolbars->createButton()->content("添加产品");
        })->actionFixed('right');

        return $grid;
    }

    public function form($isEdit = false)
    {
        $form = new Form(new Goods());
        $form->labelWidth("120px");
        $form->getActions()->buttonCenter();

        $form->item('name', "商品名称")->required()->inputWidth(10)->topComponent(Divider::make("基本信息"));

        $form->item('brand_id', "商品品牌")->required(true, 'integer')->serveRules("min:1")->component(Select::make(null)->filterable()->options(function () {
            return Brand::query()->orderBy('index_name')->get()->map(function ($item) {
                return SelectOption::make($item->id, $item->name)->avatar(admin_file_url($item->icon))->desc(strtoupper($item->index_name));
            })->all();
        }))->inputWidth(10);

        $form->item("goods_class_path", "产品分类")->required(true, 'array')->component(function () {
            $goods_class = new GoodsClass();
            $allNodes = $goods_class->toTree();
            return Cascader::make()->options($allNodes)->value("id")->label("name")->expandTrigger("hover");
        })->inputWidth(10);

        $form->item("images", "商品图片")->required(true, 'array')
            ->component(Upload::make()->width(130)
                ->height(130)->multiple(true, "id", "path")->limit(10))
            ->help("尺寸750x750像素以上，大小2M以下,最多10张图片，第一张为产品主图")
            ->inputWidth(24);

        $form->item('description', "商品卖点")->inputWidth(13)
            ->help("选填，商品卖点简述，例如：此款商品美观大方 性价比较高 不容错过");

        $form->item('one_attr', "规格类型")->component(RadioGroup::make(1)->options([
            Radio::make(1, "单规格"),
            Radio::make(2, "多规格"),
        ])->disabled($isEdit))->topComponent(Divider::make("规格/库存"))->help("保存后无法修改");

        $form->item("price", "价格(元)")->vif("one_attr", 1)->component(InputNumber::make()->precision(2));

        $form->item("cost_price", "进货价(元)")->vif("one_attr", 1)->component(InputNumber::make()->precision(2));

        $form->item("line_price", "划线价(元)")->vif("one_attr", 1)->component(InputNumber::make()->precision(2));

        $form->item("stock_num", "库存(个)")->vif("one_attr", 1)->component(InputNumber::make());

        $form->item("goods_sku", "产品规格")->vif("one_attr", 2)->component(GoodsSku::make())->inputWidth(24);

        $form->item("on_shelf", "上架")->component(CSwitch::make());

        $uploadImages = config('deep_admin.route.api_prefix') . '/upload/images';
        $form->item("content.content", "产品详情")
            ->component(
                WangEditor::make()->uploadImgServer($uploadImages)->uploadFileName('file')->style('min-height:200px;')
            )->inputWidth(24);

        $form->item('show_app', '展示app')->component(
            Select::make()->options(function () {
                return AppInfoService::instance()->app_info();
            })->clearable()->filterable()->multiple()
        )->inputWidth(24)->required(true, 'array');

        /*
        $form->addValidatorRule([
            'goods_sku.goods_sku_list.*.price' => ["numeric", "min:0.01"],
            'goods_sku.goods_sku_list' => ["array", "min:1"]
        ], [
            'goods_sku.goods_sku_list.*.price.min' => '价格最小为0.01',
            'goods_sku.goods_sku_list.min' => '至少要添加一个规则'
        ]);
        */

        $form->saving(function (Form $form) use ($isEdit) {
            $form->goods_class_id = collect($form->input("goods_class_path"))->last();
            $form->user_id = \Admin::user()->id;
            $form->shop_id = 0;

            GoodsImage::query()->where('goods_id', $form->model()->id)->delete();
            $form->images = collect($form->input("images"))->map(function ($item, $index) {
                $item["order"] = $index;
                return $item;
            })->all();

            $skus = $form->input("goods_sku")['goods_sku_list'] ?? [];
            $one_attr = $form->input("one_attr");
            if ($one_attr == 2 && count($skus) <= 0) {
                return \Admin::responseError('至少要添加一个规则');
            }

            return $this->saving_event($form, $isEdit);
        });

        $form->editQuery(function (Form $form, $editData) {
            $form->editData["goods_sku"] = [
                "goods_attrs" => $form->model()->attr_map,
                "goods_sku_list" => $form->model()->skus,
            ];
        });

        $form->DbTransaction(function (form $form) {
            /**@var Goods $goods */
            $goods = $form->model();
            try {
                $attrs = $form->input("goods_sku")["goods_attrs"] ?? [];
                GoodsAttrMap::query()->where('goods_id', $goods->id)->delete();
                GoodsAttrValueMap::query()->where('goods_id', $goods->id)->delete();
                collect($attrs)->map(function ($attr, $index) use ($goods) {
                    $attr_map = GoodsAttrMap::query()->create([
                        'goods_id' => $goods->id,
                        'attr_id' => $attr['id'],
                        'index' => $index
                    ]);
                    $values = collect($attr['sku_list'])->filter(function ($item) {
                        return @$item['id'] > 0;
                    })->map(function ($value, $index) use ($attr_map) {
                        return [
                            'attr_map_id' => $attr_map->id,
                            'goods_id' => $attr_map->goods_id,
                            'attr_id' => $attr_map->attr_id,
                            'attr_value_id' => $value['id'],
                            'image' => @$value['image'],
                            'index' => $index
                        ];
                    })->all();
                    GoodsAttrValueMap::query()->insert($values);
                });
            } catch (\Exception $exception) {
                abort(400, '销售属性保存失败' . config('app.debug') ? $exception->getMessage() : '');
            }
            try {
                $skus = $form->input("goods_sku")['goods_sku_list'] ?? [];
                //首先将原有的删除
                \Andruby\DeepGoods\Models\GoodsSku::setSkuStatus($goods, -1);

                if (collect($skus)->count() <= 0) {
                    //无商品规格
                    //更新或创建
                    $goods_sku = \Andruby\DeepGoods\Models\GoodsSku::query()
                        ->where('goods_id', $goods->id)
                        ->where('attr_key', "0")
                        ->updateOrCreate([], [
                            'goods_id' => $goods->id,
                            'name' => $goods->name,
                            'attr_key' => "0",
                            'image' => $goods->cover->path,
                            'price' => $form->price,
                            'cost_price' => $form->stock_price ?? 0.00,
                            'line_price' => $form->line_price ?? 0.00,
                            'code' => $form->code ?? "",
                            'sold_num' => $form->sold_num ?? 0,
                            'status' => 1
                        ]);
                    //更新库存
                    \Andruby\DeepGoods\Models\GoodsSku::setSkuStock($goods, $goods_sku, $form->stock_num);

                } else {
                    collect($skus)->filter(function ($item) {
                        return is_array($item);
                    })->map(function ($sku) use ($goods) {
                        //更新或创建
                        $goods_sku = \Andruby\DeepGoods\Models\GoodsSku::query()
                            ->where('goods_id', $goods->id)
                            ->where('attr_key', $sku['attr_key'])
                            ->updateOrCreate([], [
                                'goods_id' => $goods->id,
                                'name' => '',
                                'attr_key' => $sku['attr_key'],
                                'image' => $sku['image'] ?? $goods->cover->path,
                                'price' => $sku['price'],
                                'cost_price' => $sku['cost_price'] ?? 0.00,
                                'line_price' => $sku['line_price'] ?? 0.00,
                                'code' => $sku['code'],
                                'sold_num' => $sku['sold_num'] ?? 0,
                                'status' => 1
                            ]);
                        //更新库存
                        \Andruby\DeepGoods\Models\GoodsSku::setSkuStock($goods, $goods_sku, $sku['stock_num']);

                        \Andruby\DeepGoods\Models\GoodsSku::setSkuAttrValueMap($goods, $goods_sku, $sku['attrs']);

                        //TODO 根据订单关联，更新销量
                    });
                }
            } catch (\Exception $exception) {
                abort(400, 'SKU保存失败' . config('app.debug') ? $exception->getMessage() : '');
            }
        });
        return $form;
    }


    public function addGoodsAttr(Request $request)
    {
        try {
            \Admin::validatorData($request->all(), [
                'name' => 'required|unique:goods_attrs,name'
            ], [
                'name.required' => '请输入名称',
                'name.unique' => '名称已存在',
            ]);
            $name = $request->input("name");

            $ga = GoodsAttr::query()->create([
                'store_id' => 0,
                'name' => $name,
                'sort' => 1
            ]);
            return \Admin::response($ga->allAttrs());

        } catch (\Exception $exception) {
            return \Admin::responseError($exception->getMessage());
        }
    }

    public function addGoodsAttrValue(Request $request)
    {
        try {
            \Admin::validatorData($request->all(), [
                'name' => 'required|unique:goods_attr_values,name',
                'goods_attr_id' => 'required|numeric|min:1'
            ], [
                'name.required' => '请输入名称',
                'name.unique' => '名称已存在',
            ]);
            $name = $request->input("name");
            $goods_attr_id = $request->input("goods_attr_id");

            $ga = GoodsAttrValue::query()->create([
                'goods_attr_id' => $goods_attr_id,
                'store_id' => 0,
                'name' => $name,
                'sort' => 1
            ]);
            return \Admin::response($ga->allValues($goods_attr_id));

        } catch (\Exception $exception) {
            return \Admin::responseError($exception->getMessage());
        }
    }

    public function on_shelf($id)
    {
        $on_shelf = request('on_shelf');

        $data = ['on_shelf' => $on_shelf];
        Goods::query()->where(['id' => $id])->update($data);

        $data['action']['emit'] = 'tableReload';
        return \Admin::response($data, '操作成功');
    }
}
