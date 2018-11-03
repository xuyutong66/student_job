<?php

namespace App\Admin\Controllers;

use App\Model\UserModel;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Show;

class UserController extends Controller
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

            $content->header('用户');
            $content->description('列表');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('用户');
            $content->description('编辑');

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

            $content->header('用户');
            $content->description('添加');

            $content->body($this->form());
        });
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('消息')
            ->description(trans('admin.detail'))
            ->body($this->detail($id));
    }

    /**
     * 详情
     * @param $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(UserModel::findOrFail($id));

        $show->id('ID');
        $show->phone('手机号');
        $show->password('密码');
        $show->type('类型')->display(function ($type) {
            return $type == 1 ? '商家' : '学生';
        });

        $show->create_time('创建时间');
        $show->update_time('更新时间');

        return $show;
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(UserModel::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->column('phone','手机号');
            $grid->column('password','密码');
            $grid->column('type','类型')->display(function ($type) {
                return $type == 1 ? '商家' : '学生';
            });
            $grid->column('status','审核状态')->display(function ($type) {
                return $type == 1 ? '通过' : '未通过';
            });
            $grid->column('create_time','创建时间');
            $grid->column('update_time','更新时间');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(UserModel::class, function (Form $form) {
            $types = [
                '1' => '商家',
                '2' => '学生',
            ];
            $approval = [
                '1' => '通过',
                '2' => '不通过',
            ];
            $form->display('id', 'ID');
            $form->text('phone','手机号')->rules('required');
            $form->text('password','密码')->rules('required');
            $form->select('type','类型')->options($types)->rules('required');
            $form->select('status','审核类型')->options($approval)->rules('required');
            $form->display('create_time', '创建时间');
            $form->display('update_time', '修改时间');
        });
    }
}
