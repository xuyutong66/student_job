<?php
/**
 * Created by PhpStorm.
 * User: xuyutong
 * Date: 2018/7/27
 * Time: 21:12
 */
namespace App\Http\Controllers;

use App\Model\CompanyInfoModel;
use App\Model\ReleaseModel;
use App\Model\SignUpModel;
use App\Model\UserModel;
use App\Model\WalletModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * 商家相关接口
 * Class BusinessController
 * @package App\Http\Controllers
 */
class BusinessController extends CommonController
{

    /**
     * 添加商家营业执照
     * @param Request $request
     * @return array|mixed
     */
    public function uploadLicense(Request $request){
        try {
            $token           = $request->get('token');
            $user_id         = CommonController::getToken($token);
            $data['user_id'] = $user_id;

            $res = $request->file('business_license')->store('logo');
            $url = env('APP_URL').$res;

            if(isset($url)){
                $data['business_license'] = $url;
                $business_license         = CompanyInfoModel::create($data);
                return response()->json([
                    'code' => 200,
                    'message' => '上传成功',
                    'data' => $business_license
                ]);
            }

            return self::errorJson('上传失败!');

        } catch (\Exception $exception) {

            logger()->error('上传数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 补全商家信息
     * @param Request $request
     * @return array|mixed
     */
    public function createBusinessInfo(Request $request){
        try {
            $token                    = $request->get('token');
            $user_id                  = CommonController::getToken($token);
            $data['company_name']     = $request->get('company_name');
            $data['contacts_name']    = $request->get('contacts_name');
            $data['contact_phone']    = $request->get('contact_phone');
            $data['industry']         = $request->get('industry');
            $data['company_address']  = $request->get('company_address');
            $data['industry_profile'] = $request->get('industry_profile');

            if(!isset($data['company_name'])){
                return self::errorJson('公司名称不能为空!');
            }

            if(!isset($data['contacts_name'])){
                return self::errorJson('姓名不能为空!');
            }

            if(!isset($data['contact_phone'])){
                return self::errorJson('联系人不能为空!');
            }

            if(!isset($data['industry'])){
                return self::errorJson('行业不能为空!');
            }

            if(!isset($data['company_address'])){
                return self::errorJson('公司地址不能为空!');
            }

            if(!isset($data['industry_profile'])){
                return self::errorJson('行业简介不能为空!');
            }

            $add_business_license    = CompanyInfoModel::where('user_id',$user_id)->update($data);
            if($add_business_license == true){
                return self::successJson('商家信息添加成功!');
            }

            return self::errorJson('商家信息添加失败!');

        } catch (\Exception $exception) {

            logger()->error('商家信息添加数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 发布职位
     * @param Request $request
     * @return array|mixed
     */
    public function releasePosition(Request $request){
        try {
            $token    = $request->get('token');
            $user_id  = CommonController::getToken($token);
            $company  = UserModel::where('id',$user_id)->first();
            if($company->status != 1){
                return self::errorJson('请等待后台审核公司信息!');
            }
            $attributes['company_id']     = $user_id;
            $attributes['position_name']  = $request->get('position_name');
            if(!isset($attributes['position_name'])){
                return self::errorJson('职位名称不能为空!');
            }

            $attributes['position_type']  = $request->get('position_type');
            if(!isset($attributes['position_type'])){
                return self::errorJson('职位类型不能为空!');
            }

            $attributes['position_money'] = $request->get('position_money');
            if(!isset($attributes['position_money'])){
                return self::errorJson('职位薪资不能为空!');
            }

            $attributes['work_address']   = $request->get('work_address');
            if(!isset($attributes['work_address'])){
                return self::errorJson('工作地址不能为空!');
            }

            $attributes['position_describe'] = $request->get('position_describe');
            if(!isset($attributes['position_describe'])){
                return self::errorJson('职位描述不能为空!');
            }

            $address             = CommonController::address($request->get('work_address'));
            $attributes['lng']   = $address['lng'];
            $attributes['lat']   = $address['lat'];
            $release_position    = ReleaseModel::create($attributes);
            if($release_position == true){
                return self::successJson('发布职位成功!',$release_position);
            }
            return self::successJson('发布职位失败!');
        } catch (\Exception $exception) {

            logger()->error('发布职位数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 补全发布职位信息
     * @param Request $request
     * @return array|mixed
     */
    public function createPositionInfo(Request $request){
        try {
            $position_id = $request->get('position_id');
            $token       = $request->get('token');
            $company_id  = CommonController::getToken($token);
            $attributes['recurite_person_num'] = $request->get('recurite_person_num');
            if(!isset($attributes['recurite_person_num'])){
                return self::errorJson('招聘人数不能为空!');
            }

            $attributes['settlement_type']     = $request->get('settlement_type');
            if(!isset($attributes['settlement_type'])){
                return self::errorJson('结算方式不能为空!');
            }

            $attributes['personnel_require']   = $request->get('personnel_require');
            if(!isset($attributes['personnel_require'])){
                return self::errorJson('人员要求不能为空!');
            }

            $attributes['phone']               = $request->get('phone');
            if(!isset($attributes['phone'])){
                return self::errorJson('联系电话不能为空!');
            }

            $attributes['name']                = $request->get('name');
            if(!isset($attributes['name'])){
                return self::errorJson('联系人不能为空!');
            }

            $attributes['work_time']           = $request->get('work_time');
            if(!isset($attributes['work_time'])){
                return self::errorJson('工作时间不能为空!');
            }

            $attributes['close_data']          = $request->get('close_data');
            if(!isset($attributes['close_data'])){
                return self::errorJson('截止日期不能为空!');
            }

            $other                             = $request->get('other');
            if(empty($other)){
                return self::errorJson('其他不能为空!');
            }

            $attributes['other']          = $other;
            $add_release_position_info    = ReleaseModel::where('company_id',$company_id)->where('id',$position_id)->update($attributes);
            if($add_release_position_info == true){
                return self::successJson('职位信息填写成功!');
            }
            return self::errorJson('职位信息填写失败!');
        } catch (\Exception $exception) {

            logger()->error('职位信息填写数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 工作邀约列表
     * @param Request $request
     * @return array|mixed
     */
    public function getWorkInviteList(Request $request){
        try {
            $token   = $request->get('token');
            $user_id = CommonController::getToken($token);
            $release = ReleaseModel::where('company_id',$user_id)->paginate(15);
            if(!empty($release)){
                return self::successJson('查询成功!',$release);
            }
            return self::errorJson('查询失败!');
        } catch (\Exception $exception) {

            logger()->error('查询数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 取消职位
     * @param Request $request
     * @return array|mixed
     */
    public function cancelPosition(Request $request){
        try {
            $token        = $request->get('token');
            $user_id      = CommonController::getToken($token);
            $position_id  = $request->get('position_id');
            if(!isset($position_id) || $position_id == null){
                return self::errorJson('职位id不能为空!');
            }

            $delete_position = ReleaseModel::where('id',$position_id)->update(['status' => 2]);
            $release         = ReleaseModel::where('id',$position_id)->first();
            $get_sign_up_person = SignUpModel::where([
                ['position_id',$position_id],
                ['sign_up_status',3]
            ])->get()->toArray();
            if(!empty($get_sign_up_person)){
                foreach ($get_sign_up_person as $key => $val){
                    $user = UserModel::where('id',$val['user_id'])->first();
                    UserModel::where('id',$val['user_id'])->update(['money' => $user['money']+$release['position_money']*0.1]);
                }
            }
            if(!empty($delete_position)){
                return self::successJson('取消职位成功!',$delete_position);
            }
            return self::errorJson('取消职位失败!');
        } catch (\Exception $exception) {

            logger()->error('取消职位数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 工作状态列表
     * @param Request $request
     * @return array|mixed
     */
    public function getPositionStatus(Request $request){
        try {
            $data        = [];
            $token       = $request->get('token');
            $status      = $request->get('status');
            $user_id     = CommonController::getToken($token);
            $release     = ReleaseModel::select('id')->where('company_id',$user_id)->get()->toArray();
            $release_ids = array_flatten($release);
            if(isset($status)){
                $sign_up_list = SignUpModel::whereIn('position_id',$release_ids)->where('sign_up_status',$status)->paginate(15);
                if(!empty($sign_up_list)){
                    foreach ($sign_up_list as $key => $value){
                        $sign_up_list[$key]['position'] = ReleaseModel::where('id',$value['position_id'])->first();
                    }
                }

            }else{
                $sign_up_list = SignUpModel::whereIn('position_id',$release_ids)->paginate(15);
                if(!empty($sign_up_list)){
                    foreach ($sign_up_list as $key => $value){
                        $sign_up_list[$key]['position'] = ReleaseModel::where('id',$value['position_id'])->first();
                    }
                }
            }
            if(!empty($sign_up_list)){
                return self::successJson('查询成功!',$sign_up_list);
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
     * 查询提现列表
     * @param Request $request
     * @return array|mixed
     */
    public function getWalletList(Request $request){
        try {
            $token           = $request->get('token');
            $user_id         = CommonController::getToken($token);
            $get_wallet_list = WalletModel::where('user_id',$user_id)->get()->toArray();
            if(!empty($get_wallet_list)){
                return self::successJson('查询提现列表成功!',$get_wallet_list);
            }
            return self::successJson('查询提现列表成功!');

        } catch (\Exception $exception) {

            logger()->error('查询提现数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 反馈信息
     * @param Request $request
     * @return array|mixed
     */
    public function feedback(Request $request){
        try {
            $token              = $request->get('token');
            $user_id            = CommonController::getToken($token);
            $content            = $request->get('content');
            $data['user_id']    = $user_id;
            $data['feed_title'] = $content;
            $data['feed_time']  = Carbon::now();

            if(!isset($content)){
                return self::errorJson('反馈信息不能为空!');
            }

            $feedback      = DB::table('feedback')->insert($data);
            if($feedback == true){
                return self::successJson('反馈成功!');
            }

            return self::successJson('反馈失败!');

        } catch (\Exception $exception) {

            logger()->error('反馈数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 编辑公司信息页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateCompanyInfoFrom(){
        return view('business/update_company_info');
    }

    /**
     * 公司信息页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function companyInfoFrom(Request $request){
        $token   = $request->get('token');
        $user_id = CommonController::getToken($token);
        $request->session()->put('user_id',$user_id);
        return view('business/company_info');
    }

    /**
     * 公司信息详情api
     * @param Request $request
     * @return array|mixed
     */
    public function getCompanyDetailInfo(Request $request){
        try {
            $user_id      = $request->session()->get('user_id');
            $company_info = CompanyInfoModel::where('user_id',$user_id)->first();
            if(empty($company_info)){
                return self::errorJson('查询公司信息失败!');
            }

            return self::successJson('查询公司信息成功!',$company_info);

        } catch (\Exception $exception) {

            logger()->error('查询公司数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 商家钱包页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function companyWalletFrom(Request $request){
        $user_id = $request->session()->get('user_id');
        if(!isset($user_id)){
            $token   = $request->get('token');
            $user_id = CommonController::getToken($token);
        }

        $request->session()->put('user_id',$user_id);
        $user = UserModel::where('id',$user_id)->first();
        return view('business/company_wallet',compact('user'));
    }

    /**
     * 商家银行卡页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function companyBankCardFrom(Request $request){
        $user_id           = $request->session()->get('user_id');
        $company_bank_card = CompanyInfoModel::where('user_id',$user_id)->first();
        return view('business/company_bank_card',compact('company_bank_card'));
    }
}
