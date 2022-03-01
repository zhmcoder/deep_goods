<?php

namespace Andruby\DeepGoods\Controllers;

use Andruby\DeepGoods\Models\Brand;
use SmallRuralDog\Admin\Components\Form\Upload;
use SmallRuralDog\Admin\Components\Grid\Image;
use SmallRuralDog\Admin\Controllers\AdminController;
use SmallRuralDog\Admin\Form;
use SmallRuralDog\Admin\Grid;

class BrandController extends AdminController
{
    public function grid()
    {
        $grid = new Grid(new Brand());

        $grid->addDialogForm($this->form()->isDialog()->className('p-15'));
        $grid->editDialogForm($this->form(true)->isDialog()->className('p-15'));

        $grid->quickSearch(['name']);

        $grid->column("id", "序号")->width(80)->align('center')->sortable();
        $grid->column("name", "品牌名称")->width(200);
        $grid->column("index_name", "索引首字母")->width(150)->align("center");
        $grid->column("icon", "品牌logo")->component(Image::make()->size(50, 50)->preview())->width(100)->align("center");
        $grid->column("source", "产地国家")->width(200)->align("center");
        $grid->column("source_icon", "产地图标")->component(Image::make()->size(50, 50)->preview())->width(100)->align("center");

        $grid->toolbars(function (Grid\Toolbars $toolbars) {
            $toolbars->createButton()->content("添加品牌");
        });

        return $grid;
    }

    public function form($isEdit = false)
    {
        $form = new Form(new Brand());
        $form->getActions()->buttonCenter();
        $form->labelWidth('150px');

        $form->item("name", "品牌名称")->required()->inputWidth(15);
        $form->item("index_name", "索引首字母")->required()->inputWidth(15);
        $form->item("icon", "品牌logo")->required()->component(Upload::make()->width(80)->height(80))->inputWidth(15);
        $form->item("source", "产地国家")->required()->inputWidth(15);
        $form->item("source_icon", "产地图标")->required()->component(Upload::make()->width(80)->height(80))->inputWidth(15);

        return $form;
    }
}
