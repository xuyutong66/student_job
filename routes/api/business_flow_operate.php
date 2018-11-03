<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/30
 * Time: 23:14
 */

/*
|--------------------------------------------------------------------------
| 商家h5流程操作
|--------------------------------------------------------------------------
*/

// 工作邀约详情页面
Route::get('position/detail/from','BusinessFlowOperateController@workInviteDetailFrom');

// 商家录取学生api
Route::post('company/admissions','BusinessFlowOperateController@employmentStudent');

// 商家评价页面
Route::get('company/comment_from','BusinessFlowOperateController@companyCommentFrom');

// 商家评价api
Route::get('company/comment','BusinessFlowOperateController@companyComment');