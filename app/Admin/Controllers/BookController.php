<?php

namespace App\Admin\Controllers;

use App\Book;
use App\Category;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class BookController extends Controller
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
            $content->header('书目');
            $content->description(trans('admin::lang.list'));
            $content->body($this->grid(request('category', null)));
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
            $content->header('书目');
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
            $content->header('书目');
            $content->description(trans('admin::lang.create'));
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid($category_id = null)
    {
        return Admin::grid(Book::class, function (Grid $grid) use ($category_id) {
            if (!is_null($category_id)) {
                $grid->model()->whereHas('categories', function ($query) use ($category_id) {
                    $query->where('id', $category_id);
                });
            }
            $grid->id('ID')->sortable();
            $grid->name('书名')->editable();
            $grid->author('作者')->editable();
            $grid->vocabulary('词汇量')->editable()->sortable();
            $grid->image('封面图片')->image();
            $grid->publish_date('出版日期')->editable('date')->sortable();
            $grid->categories('类别')->display(function ($category) {
                $category = array_map(function ($role) {
                    return "<span class=\"label label-success\">{$role['name']}</span>";
                }, $category);
                return join(' ', $category);
            });
            $grid->created_at(trans('admin::lang.created_at'))->sortable();
            $grid->updated_at(trans('admin::lang.updated_at'))->sortable();
            $grid->filter(function ($filter) {
                $filter->useModal();
                $filter->disableIdFilter();
                $filter->like('name', '书名');
                $filter->like('author', '作者');
                $filter->between('vocabulary', '词汇量');
                $filter->between('publish_date', '出版日期')->datetime();
                $filter->where(function ($query) {
                    $input = $this->input;
                    $query->whereHas('categories', function ($query) use ($input) {
                        $query->where('slug', 'like', "%{$input}%");
                    });
                }, trans('admin::lang.slug'));
                $filter->where(function ($query) {
                    $input = $this->input;
                    $query->whereHas('categories', function ($query) use ($input) {
                        $query->where('name', 'like', "%{$input}%");
                    });
                }, trans('admin::lang.name'));
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
        return Admin::form(Book::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('name', '书名')->rules('required');
            $form->text('author', '作者');
            $form->number('vocabulary', '词汇量')->rules('required');
            $form->image('image', '封面图片');
            $form->date('publish_date', '出版日期');
            $form->multipleSelect('categories', '类别')->options(Category::all()->pluck('name', 'id'));
            $form->display('created_at', trans('admin::lang.created_at'));
            $form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }
}
