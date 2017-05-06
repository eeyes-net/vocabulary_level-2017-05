<?php

namespace App\Admin\Controllers;

use App\Book;
use App\Category;
use App\Http\Controllers\Controller;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\InfoBox;

class HomeController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('Dashboard');
            $content->description('系统概况');

            $content->row(function ($row) {
                $row->column(3, new InfoBox('书目', 'book', 'aqua', '/admin/books', Book::count()));
                $row->column(3, new InfoBox('类别', 'tags', 'green', '/admin/categories', Category::count()));
                $row->column(3, new InfoBox('用户', 'users', 'yellow', '/admin/auth/users', Administrator::count()));
                $row->column(3, new InfoBox('浏览', 'globe', 'red', '/admin/files', 0));
            });
        });
    }
}
