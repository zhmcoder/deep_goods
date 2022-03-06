<?php

namespace Andruby\DeepGoods\Controllers;

use Andruby\DeepGoods\Models\GoodsClass;
use Andruby\DeepAdmin\Components\Attrs\SelectOption;
use Andruby\DeepAdmin\Components\Form\CSwitch;
use Andruby\DeepAdmin\Components\Form\InputNumber;
use Andruby\DeepAdmin\Components\Form\Select;
use Andruby\DeepAdmin\Components\Form\Upload;
use Andruby\DeepAdmin\Components\Grid\Image;
use Andruby\DeepAdmin\Controllers\AdminController;
use Andruby\DeepAdmin\Form;
use Andruby\DeepAdmin\Grid;

class GoodsClassController extends AdminController
{
    public function grid()
    {
        $grid = new Grid(new GoodsClass());

        $grid->quickSearch(['name'])->defaultExpandAll()->tree();

        $grid->addDialogForm($this->form()->isDialog()->className('p-15'));
        $grid->editDialogForm($this->form(true)->isDialog()->className('p-15'));

        $grid->model()->with(['children']);
        $grid->model()->where('parent_id', 0);

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
        $form->labelWidth('120px');

        $form->item('parent_id', '上级菜单')->component(
            Select::make(0)->options(function () {
                return GoodsClass::query()->where('parent_id', 0)->orderBy('order')->get()
                    ->map(function ($item) {
                        return SelectOption::make($item->id, $item->name);
                    })->prepend(SelectOption::make(0, '顶级菜单'));
            })
        );
        $form->item('name', '名称')->inputWidth(15)->required();
        $form->item('goods_class_key', '唯一标识')->inputWidth(15)->required()->unique(true, 'goods_classes', 'goods_class_key', '唯一标识');
        $form->item('icon', '图标')->required()->component(Upload::make()->width(80)->height(80))->inputWidth(15);
        $form->item('order', '排序')->component(InputNumber::make(1));
        $form->item('status', '状态')->component(CSwitch::make());

        return $form;
    }
}
