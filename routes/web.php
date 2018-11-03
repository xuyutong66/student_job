<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {

   return view('welcome');

});

// 学生版公用h5/api路由文件
require 'api/api.php';

// 商家api/h5
require 'api/business.php';

//商家流程操作路由文件
require 'api/business_flow_operate.php';

// 学生安卓api/身份认证路由文件
require 'api/student.php';

// 简历相关h5路由文件
require 'api/student_resume.php';

//学生流程操作路由文件
require 'api/student_flow_operate.php';

