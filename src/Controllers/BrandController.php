<?php

namespace Andruby\DeepGoods\Controllers;

use Andruby\DeepAdmin\Components\Form\Select;
use Andruby\DeepAdmin\Components\Grid\Tag;
use Andruby\DeepAdmin\Controllers\ContentController;
use Andruby\DeepGoods\Models\Brand;
use Andruby\DeepAdmin\Components\Form\Upload;
use Andruby\DeepAdmin\Components\Grid\Image;
use Andruby\DeepAdmin\Form;
use Andruby\DeepAdmin\Grid;
use Andruby\HomeConfig\Services\AppInfoService;
use App\Admin\Services\GridCacheService;
use App\Models\AdminRoleUser;

class BrandController extends ContentController
{
    public function grid()
    {
        $grid = new Grid(new Brand());

        $grid->model()->where(function ($query) {
            $user = \Admin::user();
            if ($user['role_id'] > AdminRoleUser::ROLE_ADMINISTRATOR) {
                $showApp = json_decode($user['show_app'], true);
                foreach ($showApp as $appId) {
                    $query->orWhere('show_app', 'like', '%"' . $appId . '"%');
                }
            }
        });

        $grid->addDialogForm($this->form()->isDialog()->className('p-15'));
        $grid->editDialogForm($this->form(true)->isDialog()->className('p-15'));

        $grid->quickSearch(['name']);

        $grid->column("id", "序号")->width(80)->align('center')->sortable();
        $grid->column("name", "品牌名称")->width(200);
        $grid->column("index_name", "索引首字母")->width(150)->align("center");
        $grid->column("icon", "品牌logo")->component(Image::make()->size(50, 50)->preview())->width(100)->align("center");
        $grid->column("source", "产地国家")->width(200)->align("center");
        $grid->column("source_icon", "产地图标")->component(Image::make()->size(50, 50)->preview())->width(100)->align("center");

        $grid->column('show_app', '展示app')->customValue(function ($row, $value) {
            $appInfo = [];
            foreach ($value as $appId) {
                $appInfo[] = GridCacheService::instance()->app_name($appId);
            }
            return $appInfo;
        })->component(Tag::make())->width(200);

        $grid->toolbars(function (Grid\Toolbars $toolbars) {
            $toolbars->createButton()->content("添加品牌");
        });

        $grid->filter(function (Grid\Filter $filter) {
            $filter->like('show_app', '展示app')->component(
                Select::make()->options(function () {
                    return AppInfoService::instance()->app_info();
                })->clearable()->filterable()
            );
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

        $form->item('show_app', '展示app')->component(
            Select::make()->options(function () {
                return AppInfoService::instance()->app_info();
            })->clearable()->filterable()->multiple()
        )->inputWidth(24);

        return $form;
    }
}
