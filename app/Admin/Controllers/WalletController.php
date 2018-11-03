<?php

namespace App\Admin\Controllers;

use App\Model\WalletModel;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Show;

class WalletController extends Controller
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

            $content->header('提现');
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

            $content->header('提现');
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

            $content->header('体现');
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
        $show = new Show(WalletModel::findOrFail($id));

        $show->id('ID');
        $show->name('手机号');
        $show->money('密码');
        $show->account_number('密码');
        $show->type('类型')->display(function ($type) {
            return $type == 1 ? '支付宝' : '微信';
        });

        $show->create_time('创建时间');

        return $show;
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(WalletModel::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->column('name','名称');
            $grid->column('money','金额');
            $grid->column('account_number','账号');
            $grid->column('type','类型')->display(function ($type) {
                return $type == 1 ? '支付宝' : '微信';
            });
            $grid->column('create_time','创建时间');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(WalletModel::class, function (Form $form) {
            $types = [
                '1' => '支付宝',
                '2' => '微信',
            ];
            $form->display('id', 'ID');
            $form->text('title','名称')->rules('required');
            $form->text('content','金额')->rules('required');
            $form->text('content','账号')->rules('required');
            $form->select('type','类型')->options($types)->rules('required');
            $form->display('create_time', '创建时间');
        });
    }
}
