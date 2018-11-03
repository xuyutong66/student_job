<?php
use Illuminate\Support\Facades\Route;
/**
 * Created by PhpStorm.
 * User: xuyutong
 * Date: 2018/7/20
 * Time: 15:00
 */

/*
|--------------------------------------------------------------------------
| 公用api
|--------------------------------------------------------------------------
|
*/

// 短信接口
Route::post('send/message','PublicController@sendMessage');

// 登录
Route::post('login','PublicController@login');

// 注册
Route::post('register','PublicController@register');

// 消息列表
Route::get('message_list','PublicController@messageList');

// 设置密码
Route::post('set_up/password','PublicController@setUpPassword');

// 设置支付密码
Route::post('set_up/payment','PublicController@setUpPayment');

/*
|--------------------------------------------------------------------------
| 公用h5页面
|--------------------------------------------------------------------------
|
*/

// 提现页面
Route::get('put_forward_from','PublicController@putForwardFrom');

// 提现列表页面
Route::get('put_forward_list_from','PublicController@putForwardListFrom');

// 提现接口
Route::post('put_forward','PublicController@putForward');