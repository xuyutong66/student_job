<?php
/**
 * Created by PhpStorm.
 * User: xuyutong
 * Date: 2018/7/26
 * Time: 21:00
 */

use  Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 学生版供安卓调用api
|--------------------------------------------------------------------------
*/

// 职位列表
Route::post('position/list','StudentController@positionList');

// 报名列表
Route::post('sign_up/list','StudentController@signUpList');

// 搜索热门历史
Route::get('search','StudentController@searchHotHistory');

// 删除历史搜索
Route::post('delete/search','StudentController@deleteHistorySearch');

/*
|--------------------------------------------------------------------------
| 学生版h5身份认证
|--------------------------------------------------------------------------
*/

// 学生信息页面
Route::get('student_info_from','StudentController@studentInfoFrom');

// 添加学生信息api
Route::post('add/student_info','StudentController@createStudentInfo');

// 修改学生信息api
Route::post('update/student_info','StudentController@updateStudentInfo');

// 上传图片
Route::post('uploadFile','StudentController@uploadFile');