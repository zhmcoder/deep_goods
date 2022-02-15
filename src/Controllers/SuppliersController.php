<?php

namespace Andruby\DeepGoods\Controllers;

use Andruby\DeepGoods\Models\Supplier;
use SmallRuralDog\Admin\Components\Form\Input;
use SmallRuralDog\Admin\Controllers\AdminController;
use SmallRuralDog\Admin\Form;
use SmallRuralDog\Admin\Grid;

class SuppliersController extends AdminController
{
    public function grid()
    {
        $grid = new Grid(new Supplier());

        $grid->pageBackground()
            ->defaultSort('id', 'desc')
            ->quickSearch(['name', 'phone', 'qq', 'email', 'principal'])
            ->stripe(true)
            ->fit(true)
            ->defaultSort('id', 'desc')
            ->perPage(env('PER_PAGE', 15))
            ->size(env('TABLE_SIZE', ''))
            ->border(env('TABLE_BORDER', false))
            ->emptyText("暂无数据");

        $grid->column('id', '序号')->sortable()->align('center');
        $grid->column('name', '供货商');
        $grid->column('phone', '手机号');
        $grid->column('qq', 'QQ');
        $grid->column('email', '邮箱');
        $grid->column('principal', '负责人');
        $grid->column('address', '地址');

        $grid->toolbars(function (Grid\Toolbars $toolbars) {
            $toolbars->createButton()->content("添加供货商");
        });

        $grid->dialogForm($this->form()->isDialog(), 500);

        return $grid;
    }

    public function form($isEdit = false)
    {
        $form = new Form(new Supplier());
        $form->getActions()->buttonCenter();

        $form->item('name', '供货商名称')->inputWidth(10)->required();
        $form->item('phone')->inputWidth(10)->required();
        $form->item('qq')->inputWidth(10);
        $form->item('email')->inputWidth(10);
        $form->item('principal', "负责人")->inputWidth(10);
        $form->item('address', "地址")->inputWidth(10)->required();
        $form->item('remark', "备注")->inputWidth(10)->component(Input::make()->textarea());

        return $form;
    }
}
