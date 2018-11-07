<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/30
 * Time: 23:29
 */

namespace App\Http\Controllers;

use App\Model\CompanyInfoModel;
use App\Model\ReleaseModel;
use App\Model\SignUpModel;
use App\Model\StudentInfo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * 商家h5流程操作
 * Class BusinessFlowOperateController
 * @package App\Http\Controllers
 */
class BusinessFlowOperateController extends CommonController{
    /**
     * 工作邀约详情页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function workInviteDetailFrom(Request $request){
        $token   = $request->get('token');
        $user_id = CommonController::getToken($token);
        $request->session()->put('user_id',$user_id);
        $position_id  = $request->get('position_id');
        if(!isset($position_id) || $position_id == null){
            return self::errorJson('职位id不能为空!');
        }

        $position                 = ReleaseModel::find($position_id);
        $company_name             = CompanyInfoModel::where('user_id',$position['company_id'])->first();
        $position['company_name'] = $company_name['company_name'];
        $data                     = SignUpModel::where('position_id',$position_id)->get()->toArray();
        if(!empty($data)){
            foreach ($data as $key => $value){
                if ($value['sign_up_status'] == 1){
                    SignUpModel::where('user_id',$value['user_id'])->where('position_id',$position_id)->update(['sign_up_status' => 2]);
                }
                $student_info = StudentInfo::where('user_id',$value['user_id'])->first();
                $sign_list[$key]['header_img']     = $student_info['header_img'];
                $sign_list[$key]['name']           = $student_info['name'];
                $sign_list[$key]['sign_up_status'] = $value['sign_up_status'];
                $sign_list[$key]['company_status'] = $value['company_status'];
                $sign_list[$key]['id']             = $value['id'];
                $sign_list[$key]['user_id']        = $value['user_id'];
                $sign_list[$key]['position_id']    = $value['position_id'];
            }
        }
        return view('release/position_invite_detail',compact('position','sign_list'));
    }

    /**
     * 商家录取学生api
     * @param Request $request
     * @return array|mixed
     */
    public function employmentStudent(Request $request){
        try {
            $user_id     = $request->get('user_id');
            $position_id = $request->get('id');
            if(!isset($position_id) || $position_id == null){
                self::errorJson('职位id不能为空!');
            }

            if(!isset($user_id) || $user_id == null){
                self::errorJson('用户id不能为空!');
            }

            $position    = ReleaseModel::find($position_id);
            if($position['recurite_person_num'] < count($position['already_sign_up_num'])){
                self::errorJson('招聘人数已满!');
            }

            $updateStatus = SignUpModel::where('user_id',$user_id)->where('position_id',$position_id)->update(['sign_up_status' => 3,'company_status' => 3]);
            if($updateStatus == true){
                return self::successJson('录取成功!');
            }
            return self::errorJson('录取失败!');
        } catch (\Exception $exception) {

            logger()->error('录取数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 确认完成工作api
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function confirmComplete(Request $request){
        try {
            $user_id     = $request->get('user_id');
            $position_id = $request->get('id');
            if(!isset($position_id) || $position_id == null){
                self::errorJson('职位id不能为空!');
            }

            if(!isset($user_id) || $user_id == null){
                self::errorJson('用户id不能为空!');
            }

            $updateStatus = SignUpModel::where(['user_id' => $user_id,'position_id' => $position_id])->update(['sign_up_status' => 5,'company_status' => 4]);
            if($updateStatus == true){
                return self::successJson('确认完成成功!');
            }
            return self::errorJson('确认完成失败!');
        } catch (\Exception $exception) {

            logger()->error('确认完成数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 结算api
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function settlement(Request $request){
        try {
            $user_id     = $request->get('user_id');
            $position_id = $request->get('id');
            if(!isset($position_id) || $position_id == null){
                self::errorJson('职位id不能为空!');
            }

            if(!isset($user_id) || $user_id == null){
                self::errorJson('用户id不能为空!');
            }

            $updateStatus = SignUpModel::where(['user_id' => $user_id,'position_id' => $position_id])->update(['company_status' => 5]);
            if($updateStatus == true){
                return self::successJson('结算成功!');
            }
            return self::errorJson('结算失败!');
        } catch (\Exception $exception) {

            logger()->error('结算数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 商家评价页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function companyCommentFrom(Request $request){
        $position_id  = $request->get('position_id');
        if(!isset($position_id) || $position_id == null){
            return self::errorJson('职位id不能为空!');
        }

        $position  = ReleaseModel::find($position_id);
        $data      = SignUpModel::where('position_id',$position_id)->get()->toArray();
        if(!empty($data)){
            foreach ($data as $key => $value){
                $student_info = StudentInfo::where('user_id',$value['user_id'])->first();
                $sign_list[$key]['name']  = $student_info['name'];
                $sign_list[$key]['id']    = $value['user_id'];
            }
        }
        return view('business/company_comment',compact('position','sign_list'));
    }

    /**
     * 商家评价api
     * @param Request $request
     * @return array|mixed
     */
    public function companyComment(Request $request){
        try {
            $data    = $request->get('data');
            $praise  = $request->get('praise');
            foreach ($data as $key => $val){
                SignUpModel::where('id',$val['id'])->update(['level' => $val['level'],'praise' => $praise,'company_status' => 6]);
            }
            return self::successJson('评价成功!');
        } catch (\Exception $exception) {

            logger()->error('评价数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }
}