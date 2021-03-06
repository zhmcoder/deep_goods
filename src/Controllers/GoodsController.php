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

        $grid->column('id', "??????")->width(80)->sortable()->align('center');
        $grid->column('name', "????????????")->width(150);
        $grid->column('cover.path', '????????????')->component(Image::make()->size(50, 50)->preview())->align("center");
        $grid->column('goodsClass.name', "????????????");
        $grid->column('brand.name', "??????");
        $grid->column('on_shelf', "????????????")->align("center")->customValue(function ($row, $value) {
            return $value == 1 ? "??????" : "??????";
        })->component(Tag::make()->type(["??????" => "success", "??????" => "danger"]));

        $grid->column('created_at', '????????????')->customValue(function ($row, $value) {
            return $value;
        })->width(150);

        $grid->column('show_app', '??????app')->customValue(function ($row, $value) {
            $appInfo = [];
            foreach ($value as $appId) {
                $appInfo[] = GridCacheService::instance()->app_name($appId);
            }
            return $appInfo;
        })->component(Tag::make())->width(200);

        $grid->actions(function (Grid\Actions $actions) {
            $rowInfo = $actions->getRow();

            if ($rowInfo['on_shelf'] == Goods::OFF_SHELF) {
                $name = '??????';
                $shelf = Goods::ON_SHELF;
            } else {
                $name = '??????';
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
            $filter->like('name', '????????????')->component(Input::make());
            $filter->equal('brand_id', '????????????')->component(Select::make()->options(function () {
                return Brand::query()->get()->map(function ($item) {
                    return SelectOption::make($item->id, $item->name);
                })->all();
            }));
            $filter->date('created_at', '????????????')->component(DatePicker::make()->style('width:150px;margin-left:5px;'));

            $filter->like('show_app', '??????app')->component(
                Select::make()->options(function () {
                    return AppInfoService::instance()->app_info();
                })->clearable()->filterable()
            );
        });

        $grid->quickFilter()->filterKey('on_shelf')->defaultValue(null)
            ->quickOptions([Radio::make(1, '??????'), Radio::make(0, '??????')]);

        $grid->toolbars(function (Grid\Toolbars $toolbars) {
            $toolbars->createButton()->content("????????????");
        })->actionFixed('right');

        return $grid;
    }

    public function form($isEdit = false)
    {
        $form = new Form(new Goods());
        $form->labelWidth("120px");
        $form->getActions()->buttonCenter();

        $form->item('name', "????????????")->required()->inputWidth(10)->topComponent(Divider::make("????????????"));

        $form->item('brand_id', "????????????")->required(true, 'integer')->serveRules("min:1")->component(Select::make(null)->filterable()->options(function () {
            return Brand::query()->orderBy('index_name')->get()->map(function ($item) {
                return SelectOption::make($item->id, $item->name)->avatar(admin_file_url($item->icon))->desc(strtoupper($item->index_name));
            })->all();
        }))->inputWidth(10);

        $form->item("goods_class_path", "????????????")->required(true, 'array')->component(function () {
            $goods_class = new GoodsClass();
            $allNodes = $goods_class->toTree();
            return Cascader::make()->options($allNodes)->value("id")->label("name")->expandTrigger("hover");
        })->inputWidth(10);

        $form->item("images", "????????????")->required(true, 'array')
            ->component(Upload::make()->width(130)
                ->height(130)->multiple(true, "id", "path")->limit(10))
            ->help("??????750x750?????????????????????2M??????,??????10????????????????????????????????????")
            ->inputWidth(24);

        $form->item('description', "????????????")->inputWidth(13)
            ->help("??????????????????????????????????????????????????????????????? ??????????????? ????????????");

        $form->item('one_attr', "????????????")->component(RadioGroup::make(1)->options([
            Radio::make(1, "?????????"),
            Radio::make(2, "?????????"),
        ])->disabled($isEdit))->topComponent(Divider::make("??????/??????"))->help("?????????????????????");

        $form->item("price", "??????(???)")->vif("one_attr", 1)->component(InputNumber::make()->precision(2));

        $form->item("cost_price", "?????????(???)")->vif("one_attr", 1)->component(InputNumber::make()->precision(2));

        $form->item("line_price", "?????????(???)")->vif("one_attr", 1)->component(InputNumber::make()->precision(2));

        $form->item("stock_num", "??????(???)")->vif("one_attr", 1)->component(InputNumber::make());

        $form->item("goods_sku", "????????????")->vif("one_attr", 2)->component(GoodsSku::make())->inputWidth(24);

        $form->item("on_shelf", "??????")->component(CSwitch::make());

        $options = [
            SelectOption::make(1, '????????????'),
            SelectOption::make(2, '????????????'),
        ];
        $form->item('commission_type', '????????????')->component(Select::make(1)->options($options));
        $form->item("commission_ratio", "??????(%)")->vif("commission_type", 1)->component(InputNumber::make()->precision(2)->max(100));
        $form->item("commission_price", "??????(???)")->vif("commission_type", 2)->component(InputNumber::make()->precision(2));

        $uploadImages = config('deep_admin.route.api_prefix') . '/upload/images';
        $form->item("content.content", "????????????")
            ->component(
                WangEditor::make()->uploadImgServer($uploadImages)->uploadFileName('file')->style('min-height:200px;')
            )->inputWidth(24);

        $form->item('show_app', '??????app')->component(
            Select::make()->options(function () {
                return AppInfoService::instance()->app_info();
            })->clearable()->filterable()->multiple()
        )->inputWidth(24)->required(true, 'array');

        /*
        $form->addValidatorRule([
            'goods_sku.goods_sku_list.*.price' => ["numeric", "min:0.01"],
            'goods_sku.goods_sku_list' => ["array", "min:1"]
        ], [
            'goods_sku.goods_sku_list.*.price.min' => '???????????????0.01',
            'goods_sku.goods_sku_list.min' => '???????????????????????????'
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
                return \Admin::responseError('???????????????????????????');
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
                abort(400, '????????????????????????' . config('app.debug') ? $exception->getMessage() : '');
            }
            try {
                $skus = $form->input("goods_sku")['goods_sku_list'] ?? [];
                //????????????????????????
                \Andruby\DeepGoods\Models\GoodsSku::setSkuStatus($goods, -1);

                if (collect($skus)->count() <= 0) {
                    //???????????????
                    //???????????????
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
                    //????????????
                    \Andruby\DeepGoods\Models\GoodsSku::setSkuStock($goods, $goods_sku, $form->stock_num);

                } else {
                    collect($skus)->filter(function ($item) {
                        return is_array($item);
                    })->map(function ($sku) use ($goods) {
                        //???????????????
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
                        //????????????
                        \Andruby\DeepGoods\Models\GoodsSku::setSkuStock($goods, $goods_sku, $sku['stock_num']);

                        \Andruby\DeepGoods\Models\GoodsSku::setSkuAttrValueMap($goods, $goods_sku, $sku['attrs']);

                        //TODO ?????????????????????????????????
                    });
                }
            } catch (\Exception $exception) {
                abort(400, 'SKU????????????' . config('app.debug') ? $exception->getMessage() : '');
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
                'name.required' => '???????????????',
                'name.unique' => '???????????????',
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
                'name.required' => '???????????????',
                'name.unique' => '???????????????',
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
        return \Admin::response($data, '????????????');
    }

    public function goodsAttr(Request $request)
    {
        $goodsAttr = new GoodsAttr();
        return \Admin::response($goodsAttr->allAttrs());
    }

    public function goodsAttrValue(Request $request)
    {
        $goods_attr_id = $request->input("goods_attr_id");

        $goodsAttrValue = new GoodsAttrValue();
        return \Admin::response($goodsAttrValue->allValues($goods_attr_id));
    }
}
