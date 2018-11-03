<?php

namespace App\Admin\Controllers;

use App\Model\MessageModel;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class MessageController extends Controller
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

            $content->header('消息');
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

            $content->header('消息');
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

            $content->header('消息');
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
        $show = new Show(MessageModel::findOrFail($id));
        $show->id('ID');
        $show->title('标题');
        $show->content('内容');
        $show->type('类型')->display(function ($type) {
            return $type == 1 ? '系统' : '平台';
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
        return Admin::grid(MessageModel::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->column('title','标题');
            $grid->column('content','内容');
            $grid->column('type','类型')->display(function ($type) {
                return $type == 1 ? '系统' : '平台';
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
        return Admin::form(MessageModel::class, function (Form $form) {
            $types = [
                '1' => '系统',
                '2' => '平台',
            ];
            $form->display('id', 'ID');
            $form->text('title','标题')->rules('required');
            $form->text('content','内容')->rules('required');
            $form->select('type','类型')->options($types)->rules('required');
            $form->display('create_time', '创建时间');
            $form->display('update_time', '修改时间');
        });
    }
}
