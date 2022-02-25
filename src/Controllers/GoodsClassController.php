<?php

namespace Andruby\DeepGoods\Controllers;

use Andruby\DeepGoods\Models\GoodsClass;
use SmallRuralDog\Admin\Components\Attrs\SelectOption;
use SmallRuralDog\Admin\Components\Form\CSwitch;
use SmallRuralDog\Admin\Components\Form\InputNumber;
use SmallRuralDog\Admin\Components\Form\Select;
use SmallRuralDog\Admin\Components\Form\Upload;
use SmallRuralDog\Admin\Components\Grid\Image;
use SmallRuralDog\Admin\Controllers\AdminController;
use SmallRuralDog\Admin\Form;
use SmallRuralDog\Admin\Grid;

class GoodsClassController extends AdminController
{
    public function grid()
    {
        $grid = new Grid(new GoodsClass());

        $grid->pageBackground()
            ->defaultSort('id', 'desc')
            ->quickSearch(['name', 'goods_class_key'])
            ->stripe(true)
            ->fit(true)
            ->defaultSort('id', 'desc')
            ->perPage(env('PER_PAGE', 15))
            ->size(env('TABLE_SIZE', ''))
            ->border(env('TABLE_BORDER', false))
            ->emptyText("暂无数据");

        $grid->addDialogForm($this->form()->isDialog()->className('p-15'));
        $grid->editDialogForm($this->form(true)->isDialog()->className('p-15'));

        $grid->model()->with(['children']);
        $grid->model()->where('parent_id', 0);
        $grid->tree();
        $grid->column('id', '序号')->width(100)->sortable()->align('center');
        $grid->column('name', '名称');
        $grid->column('goods_class_key', '唯一标识');
        $grid->column('icon', '图标')->component(Image::make()->size(50, 50)->preview());

        $grid->column('order', '排序');
        $grid->column('status', '状态')->customValue(function ($row, $value) {
            return $value == 1 ? "开启" : "关闭";
        });

        $grid->toolbars(function (Grid\Toolbars $toolbars) {
            $toolbars->createButton()->content("添加分类");
        });

        return $grid;
    }

    public function form($isEdit = false)
    {
        $form = new Form(new GoodsClass());
        $form->getActions()->buttonCenter();

        $form->item('parent_id', '上级菜单')->component(Select::make(0)->options(function () {
            return GoodsClass::query()->where('parent_id', 0)->orderBy('order')->get()->map(function ($item) {
                return SelectOption::make($item->id, $item->name);
            })->prepend(SelectOption::make(0, '顶级菜单'));
        }));
        $form->item('name', '名称')->inputWidth(15)->required();
        $form->item('goods_class_key', '唯一标识')->inputWidth(15)->required()->unique(true, 'goods_classes', 'goods_class_key', '唯一标识');
        $form->item('icon', '图标')->required()->component(Upload::make()->width(80)->height(80));
        $form->item('order', '排序')->required(true, 'integer')->component(InputNumber::make(1));
        $form->item('status', '状态')->component(CSwitch::make());

        return $form;
    }
}
