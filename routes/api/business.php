<?php
/**
 * Created by PhpStorm.
 * User: xuyutong
 * Date: 2018/7/27
 * Time: 21:12
 */
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 商家api路由文件
|--------------------------------------------------------------------------
*/

// 添加商家营业执照
Route::post('upload/business/licese','BusinessController@uploadLicense');

// 补全商家信息
Route::post('add/business/info','BusinessController@createBusinessInfo');

// 商家发布职位
Route::post('company/release/position','BusinessController@releasePosition');

// 补全发布职位信息
Route::post('company/add_position_info','BusinessController@createPositionInfo');

// 工作邀约列表
Route::get('company/release/list','BusinessController@getWorkInviteList');

// 商家取消职位
Route::post('cancel/company/position','BusinessController@cancelPosition');

// 商家职位状态列表
Route::post('company/release/status','BusinessController@getPositionStatus');

// 提现列表
Route::get('wallet/list','BusinessController@getWalletList');

// 意见反馈
Route::post('feedback','BusinessController@feedback');

/*
|--------------------------------------------------------------------------
| 商家我的页面h5路由文件
|--------------------------------------------------------------------------
*/

// 编辑公司信息页面
Route::get('update/company_info_from','BusinessController@updateCompanyInfoFrom');

// 公司信息页面
Route::get('company/info_from','BusinessController@companyInfoFrom');

// 公司信息详情api
Route::get('get/company_info/detail','BusinessController@getCompanyDetailInfo');

// 商家钱包页面
Route::get('company/wallet_from','BusinessController@companyWalletFrom');

// 商家银行卡页面
Route::get('company/bank_card_from','BusinessController@companyBankCardFrom');