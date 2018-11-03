<?php
/**
 * Created by PhpStorm.
 * User: xuyutong
 * Date: 2018/7/20
 * Time: 11:13
 */
namespace App\Http\Controllers;

use App\Model\UserModel;
use Mockery\Exception;

/**
 * 公共方法
 * Class CommonController
 * @package App\Http\Controllers
 */
class CommonController extends Controller {

    const success = 200; //返回成功状态码
    const error = 100;  //返回失败状态码

    /**
     * 截取
     *
     * @param string $str 字符串
     *
     * @param string $start 开始
     *
     * @param string $length 长度
     *
     * @return string
     */
    public static function cutString($str, $start, $length)
    {
        if (mb_strlen($str) > $length) {
            return mb_substr($str, $start, $length) . "...";
        }
        return $str;
    }

    /**
     * 保留两位小数
     *
     * @param string $price 字符串
     *
     * @return string
     */
    public static function decimal($price)
    {
        return number_format($price, 2);
    }

    /**
     * 利用JSON数据返回错误数据时
     *
     * @param string $message 返回信息
     *
     * @param null $redirectUrl 路径
     *
     * @return mixed
     */
    public static function errorJson($message, $redirectUrl = null)
    {
        $result['code'] = self::error;
        $result['message'] = $message;

        if ($redirectUrl != null) {
            $result['redirectUrl'] = $redirectUrl;
        }
        return response()->json($result);
    }

    /**
     * 利用JSON数据返回成功数据时
     *
     * @param string $message 返回信息
     *
     * @param array $data 数组
     *
     * @param null $redirectUrl 地址路径
     *
     * @return mixed
     */

    public static function successJson($message, $data = [], $redirectUrl = null)
    {

        $result['code'] = self::success;
        $result['message'] = $message;

        if (!empty($data)) {
            $result['data'] = $data;
        }

        if ($redirectUrl != null) {
            $result['redirectUrl'] = $redirectUrl;
        }
        return response()->json($result);
    }

    /**
     * 获取登录人信息
     * @param $token
     * @return mixed
     */
    public static function getToken($token){
        if($token == null){
            throw new Exception('token为空认证失败');
        }
       $user =  UserModel::where('token',$token)->get()->toArray();
       if(empty($user)){
           throw new Exception('token认证失败');
       }
        return $user[0]['id'];
    }

    /**
     * 调用百度地图api把地址转化为经纬度
     * @param $address
     * @return array|null
     */
    public static function address($address){
        //获取地址经纬度坐标
        $result = array();
        $ak = 'uNcmOV4WxUlU3lEx1zFimKkd';
        $url ="http://api.map.baidu.com/geocoder/v2/?&output=json&address=".$address."&ak=".$ak;
        $data = file_get_contents($url);
        $data = str_replace('renderOption&&renderOption(', '', $data);
        $data = str_replace(')', '', $data);
        $data = json_decode($data,true);
        if (!empty($data) && $data['status'] == 0) {
            $result['lat'] = $data['result']['location']['lat'];
            $result['lng'] = $data['result']['location']['lng'];
            return $result;//返回经纬度结果
        }else{
            return null;
        }
    }
}