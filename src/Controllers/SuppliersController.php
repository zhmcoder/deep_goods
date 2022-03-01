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

        $grid->quickSearch(['name', 'phone', 'qq', 'email', 'principal']);

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

        $grid->addDialogForm($this->form()->isDialog()->className('p-15'));
        $grid->editDialogForm($this->form(true)->isDialog()->className('p-15'));

        return $grid;
    }

    public function form($isEdit = false)
    {
        $form = new Form(new Supplier());
        $form->getActions()->buttonCenter();
        $form->labelWidth('120px');

        $form->item('name', '供货商名称')->inputWidth(15)->required();
        $form->item('phone')->inputWidth(15)->required();
        $form->item('qq')->inputWidth(15);
        $form->item('email')->inputWidth(15);
        $form->item('principal', "负责人")->inputWidth(15);
        $form->item('address', "地址")->inputWidth(15)->required();
        $form->item('remark', "备注")->inputWidth(15)->component(Input::make()->textarea());

        return $form;
    }
}
