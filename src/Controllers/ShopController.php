<?php

namespace Andruby\DeepGoods\Controllers;

use Andruby\DeepAdmin\Controllers\ContentController;
use SmallRuralDog\Admin\Grid;

class ShopController extends ContentController
{
    function grid_list(Grid $grid)
    {
        $grid->quickSearch(['name']);

        $grid->addDialogForm($this->form()->isDialog()->className('p-15'));
        $grid->editDialogForm($this->form(true)->isDialog()->className('p-15'));

        $grid->toolbars(function (Grid\Toolbars $toolbars) {
            $toolbars->createButton()->content("添加店铺");
        });

        return $grid;
    }
}
