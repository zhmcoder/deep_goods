<?php

namespace Andruby\DeepGoods\Controllers;

use Andruby\DeepAdmin\Components\Attrs\SelectOption;
use Andruby\DeepAdmin\Components\Form\Select;
use Andruby\DeepAdmin\Components\Grid\Tag;
use Andruby\DeepGoods\Models\Supplier;
use Andruby\DeepAdmin\Components\Form\Input;
use Andruby\DeepAdmin\Controllers\AdminController;
use Andruby\DeepAdmin\Form;
use Andruby\DeepAdmin\Grid;
use Andruby\HomeConfig\Models\AppInfo;
use App\Admin\Services\GridCacheService;

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

        $grid->column('show_app', '展示app')->customValue(function ($row, $value) {
            $appInfo = [];
            foreach ($value as $appId) {
                $appInfo[] = GridCacheService::instance()->app_name($appId);
            }
            return $appInfo;
        })->component(Tag::make())->width(200);

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

        $form->item('show_app', '展示app')->component(
            Select::make()->options(function () {
                return AppInfo::query()->get()->map(function ($item) {
                    return SelectOption::make($item->app_id, $item->name);
                })->all();
            })->clearable()->multiple()
        )->inputWidth(24);

        return $form;
    }
}
