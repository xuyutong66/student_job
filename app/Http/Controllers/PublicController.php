<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/16
 * Time: 20:52
 */
namespace App\Http\Controllers;

use App\Library\SendMessage;
use App\Model\CompanyInfoModel;
use App\Model\StudentInfo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Model\UserModel;
use App\Model\MessageModel;
use App\Model\WalletModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * 共用api
 * Class ApiController
 * @package App\Http\Controllers
 */
class PublicController extends CommonController{

    /**
     * 获取随机验证码
     * @return string
     */
    private function getRandCode()
    {
        return mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9);
    }

    /**
     * @desc   检验手机号码是否合法
     * @param string $phone 手机号码
     * @return int
     */
    private function checkPhoneNum($phone)
    {
        return preg_match('/^1[0-9]{10}$/', $phone);
    }

    /**
     * 发送消息
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function sendMessage(Request $request){
        $phone = $request->get('phone');

        if(!isset($phone) || $phone == null){
            return self::errorJson('手机号不能为空!');
        }

        if (!$this->checkPhoneNum($phone)) {
            throw new \Exception('手机号码不合法~' . $phone, 70001);
        }
        $code = $this->getRandCode();
        $sms  = SendMessage::sendSms($phone,$code);
        if($sms->Code == 'OK'){
            Cache::set($phone.'sign_key',$code,5);
            DB::table('message_phone_log')->insert([
                'phone'    => $phone,
                'code' => $code,
                'is_delete'   => 1,
                'create_time' => Carbon::now(),
            ]);
            return self::successJson('发送成功!');
        }else{
            return self::successJson('发送失败!');
        }
    }

    /**
     * 登录api
     * @param Request $request
     * @return array|mixed
     */
    public function login(Request $request){
        try {
            $username = htmlspecialchars($request->get('phone'));
            $password = htmlspecialchars($request->get('password'));
            $type     = $request->get('type');

            if(!isset($username) || $username == null){
                return self::errorJson('用户名不能为空!');
            }

            if(!isset($password) || $password == null){
                return self::errorJson('密码不能为空!');
            }

            if(!isset($type) || $type == null){
                return self::errorJson('登录类型不能为空!');
            }

            $user_name = UserModel::where(['phone' => $username,'type' => $type])->first();
            if(empty($user_name)){
                return self::errorJson('用户名不存在请先注册!');
            }else{
                $user_name = UserModel::where(['phone' => $username,'password' => $password,'type' => $type])->first();
                if(empty($user_name)){
                    return self::errorJson('用户名或密码错误!');
                }else{
                    $data['token'] = $user_name['token'];

                    if($user_name['type'] == 1){
                        $info = CompanyInfoModel::select('id','business_license','company_name')->where('user_id',$user_name['id'])->first();
                    }else{
                        $info = StudentInfo::select('id','name','header_img')->where('user_id',$user_name['id'])->first();
                    }
                    $data['info']  = $info;

                    return self::successJson('登录成功!',$data);
                }
            }

        } catch (\Exception $exception) {

            logger()->error('登录数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 注册api
     * @param Request $request
     * @return array
     */
    public function register(Request $request){
        try {
            $username = htmlspecialchars($request->get('phone'));
            $password = htmlspecialchars($request->get('password'));
            $type     = htmlspecialchars($request->get('type'));
            $code     = $request->get('code');

            if(!isset($username) || $username == null){
                return self::errorJson('用户名不能为空!');
            }

            if(!isset($password) || $password == null || $password == ""){
                return self::errorJson('密码不能为空!');
            }

            if(!isset($code) || $code == null){
                return self::errorJson('验证码不能为空!');
            }

            $sign_key = Cache::get($username.'sign_key');
            if(empty($sign_key) || $sign_key != $code){
                return self::errorJson('验证码错误!');
            }

            $user_name = UserModel::where(['phone' => $username,'type' => $type])->first();
            if(empty($user_name)){
                $data          = $request->all();
                $data['type']  = $type;
                $data['token'] = md5(md5($username).mt_rand(1,999).$sign_key);
                $user = UserModel::create($data);
                if($user == true){
                    return self::successJson('注册成功!',$user);
                }else{
                    return self::errorJson('注册失败!');
                }
            }else{
                return self::errorJson('用户名已存在!');
            }

        } catch (\Exception $exception) {

            logger()->error('注册数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }


    /**
     * 消息列表
     * @param Request $request
     * @return array|mixed
     */
    public function messageList(Request $request){
        try {
            $token        = $request->get('token');
            $user_id      = CommonController::getToken($token);
            $message_list = MessageModel::where('user_id',$user_id)->orwhere('type',2)->orderBy('create_time','desc')->get()->toArray();
            if(!empty($message_list)){
                return self::successJson('查询成功!',$message_list);
            }

            return self::successJson('查询成功!');

        } catch (\Exception $exception) {

            logger()->error('查询数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 设置密码
     * @param Request $request
     * @return array|mixed
     */
    public function setUpPassword(Request $request){
        try {
            $phone            = $request->get('phone');
            $code             = $request->get('code');
            $password = $request->get('password');
            $confirm_password = $request->get('confirm_password');

            if(!isset($phone)){
                return self::errorJson('手机号不能为空!');
            }

            if(!isset($code)){
                return self::errorJson('验证码不能为空!');
            }

            $sign_key = Cache::get($phone.'sign_key');
            if(empty($sign_key) || $sign_key != $code){
                return self::errorJson('验证码错误!');
            }

            if(!isset($password)){
                return self::errorJson('密码不能为空!');
            }

            $update_payment_password    = UserModel::where('phone',$phone)->update(['payment_password' => $password]);
            if($update_payment_password == true){
                return self::successJson('设置支付密码成功!');
            }

            return self::errorJson('设置支付密码失败!');

        } catch (\Exception $exception) {

            logger()->error('设置密码数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 设置支付密码
     * @param Request $request
     * @return array|mixed
     */
    public function setUpPayment(Request $request){
        try {
            $token    = $request->get('token');
            $user_id  = CommonController::getToken($token);
            $password = $request->get('payment_password');

            if(!isset($password)){
                return self::errorJson('密码不能为空!');
            }

            $update_password    = UserModel::where('id',$user_id)->update(['payment_password' => $password]);
            if($update_password == true){
                return self::successJson('设置密码成功!');
            }

            return self::errorJson('设置密码失败!');

        } catch (\Exception $exception) {

            logger()->error('设置密码数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 提现页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function putForwardFrom(Request $request){
        $user_id = $request->session()->get('user_id');
        if(!isset($user_id)){
            $token   = $request->get('token');
            $user_id = CommonController::getToken($token);
        }
        $request->session()->put('user_id',$user_id);
        $user    = UserModel::where('id',$user_id)->first();
        return view('wallet/put_forward_from',compact('user'));
    }

    /**
     *  提现页面
     *  商家和学生共用
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function putForwardListFrom(Request $request){
        $data         = [];
        $user_id      = $request->session()->get('user_id');
        if(!isset($user_id)){
            $token    = $request->get('token');
            $user_id  = CommonController::getToken($token);
        }
        $request->session()->put('user_id',$user_id);
        $get_user            = UserModel::find($user_id);
        $wallet_list         = WalletModel::where(['user_id' => $user_id])->get()->toArray();
        $data['money']       = $get_user['money'];
        $data['wallet_list'] = $wallet_list;

        if($get_user['type'] == 2){
            $company_info = CompanyInfoModel::where('user_id',$user_id)->first();
            $data['company_card'] = $company_info['company_card'];
        }

        return view('wallet/put_forward_list_from',compact('data'));
    }

    /**
     * 提现接口
     * @param Request $request
     * @return array
     */
    public function putForward(Request $request){
        try {
            $user_id        = $request->session()->get('user_id');
            $money          = $request->get('money');
            $able_money     = $request->get('able_money');
            $type           = $request->get('type');
            $account_number = $request->get('account_number');
            $update_money   = $able_money-$money;

            if(!isset($money) || $money == null){
                return self::errorJson('提现金额不能为空!');
            }


            if(!isset($type) || $type == null){
                return self::errorJson('提现类型不能为空!');
            }

            $user = UserModel::where('id',$user_id)->first();

            if($user['type'] == 1){
                $name = '工资支出';
            }

            if($user['type'] == 2){
                $name = '工资提现';
            }

            $data['user_id']        = $user_id;
            $data['name']           = isset($name) ? $name : null;
            $data['money']          = $money;
            $data['type']           = $type;
            $data['account_number'] = $account_number;
            $data['create_time']    = Carbon::now();
            $wallet                 = WalletModel::create($data);

            UserModel::where('id',$user_id)->update(['money' =>  $update_money]);
            if($wallet == true){
                return self::successJson('提现成功!');
            }

            return self::errorJson('提现失败!');

        } catch (\Exception $exception) {

            logger()->error('提现数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }
}