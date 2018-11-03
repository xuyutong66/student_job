<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/30
 * Time: 23:08
 */
/*
|--------------------------------------------------------------------------
| 学生h5报名后按钮操作
|--------------------------------------------------------------------------
*/

// 报名详情页面
Route::get('sign_up/from','StudentFlowOperateController@signUpDetailFrom');

// 学生报名api
Route::post('sign_up','StudentFlowOperateController@studentSignUp');

// 报名失败页面
Route::get('sign_up_error_from','StudentFlowOperateController@signUpErrorFrom');

// 报名成功页面
Route::get('sign_up_success_from','StudentFlowOperateController@signUpSuccessFrom');

// 取消报名页面
Route::get('cancel/sign_up_from','StudentFlowOperateController@cancelSignUpFrom');

// 取消报名api
Route::post('cancel/sign_up','StudentFlowOperateController@cancelSignUp');

// 评价页面
Route::get('comment_from','StudentFlowOperateController@commentFrom');

// 学生评价api
Route::post('comment','StudentFlowOperateController@comment');