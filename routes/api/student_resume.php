<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/30
 * Time: 22:58
 */

/*
|--------------------------------------------------------------------------
| 学生h5简历相关
|--------------------------------------------------------------------------
*/

// 简历页面
Route::get('resume_from','StudentResumeController@resumeFrom');

// 查看简历详情api
Route::get('get/detail_resume','StudentResumeController@getResumeDetail');

// 基本信息页面
Route::get('basicInfoFrom','StudentResumeController@basicInfoFrom');

// 教育经历页面
Route::get('educateExperienceFrom','StudentResumeController@educateExperienceFrom');

// 工作经历页面
Route::get('workExperienceFrom','StudentResumeController@workExperienceFrom');

// 详情信息页面
Route::get('detailInfoFrom','StudentResumeController@detailInfoFrom');

// 添加教育经历api
Route::post('create/educate','StudentResumeController@createEducateExperience');

// 添加工作经历api
Route::post('create/work','StudentResumeController@createWorkExperience');

// 修改基本信息api
Route::post('update/resume','StudentResumeController@updateBasicInfo');

// 修改教育经历api
Route::post('update/ducational/experiences','StudentResumeController@updateEducateExperiences');

// 修改工作经历api
Route::post('update/work/experiences','StudentResumeController@updateWorkExperiences');

// 修改详情信息api
Route::post('update/resume/detail','StudentResumeController@updateDetailInfo');