<?php

namespace App\Admin\Controllers;

use App\Category;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class CategoryController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('类别');
            $content->description(trans('admin::lang.list'));
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('类别');
            $content->description(trans('admin::lang.edit'));
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('类别');
            $content->description(trans('admin::lang.create'));
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Category::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->slug(trans('admin::lang.slug'))->editable()->sortable();
            $grid->name(trans('admin::lang.name'))->editable();
            $grid->created_at(trans('admin::lang.created_at'))->sortable();
            $grid->updated_at(trans('admin::lang.updated_at'))->sortable();
            $grid->actions(function ($actions) {
                $actions->append('<a href="' . e(action('\App\Admin\Controllers\BookController@index', ['category' => $actions->getkey()])) . '"><i class="fa fa-eye"></i></a>');
            });
            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->like('slug', trans('admin::lang.slug'));
                $filter->like('name', trans('admin::lang.name'));
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Category::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('slug', trans('admin::lang.slug'))->rules('required');
            $form->text('name', trans('admin::lang.name'))->rules('required');
            $form->display('created_at', trans('admin::lang.created_at'));
            $form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }
}
