<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/30
 * Time: 23:23
 */
namespace App\Http\Controllers;

use App\Model\CompanyInfoModel;
use App\Model\ReleaseModel;
use App\Model\SignUpModel;
use App\Model\StudentInfo;
use App\Model\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Model\ResumeModel;
use Illuminate\Support\Facades\DB;

/**
 * 学生h5报名后按钮操作
 * Class StudentFlowOperateController
 * @package App\Http\Controllers
 */

class StudentFlowOperateController extends CommonController {
    /**
     * 报名详情页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function signUpDetailFrom(Request $request){
        $token        = $request->get('token');
        $user_id      = CommonController::getToken($token);
        $request->session()->put('user_id',$user_id);

        $position_id  = $request->get('position_id');
        if(!isset($position_id) || $position_id == null){
            return self::errorJson('职位id不能为空!');
        }

        $user        = UserModel::find($user_id);
        if($user['type'] == 1){
            $sign_up_status   = SignUpModel::where('id',$position_id)->first();
            $release     = ReleaseModel::where('id',$sign_up_status['position_id'])->first();
            $user_info   = CompanyInfoModel::where('user_id',$release['company_id'])->first();
            $data['user_info']      = $user_info;
            $data['sign_up_status'] = $sign_up_status;
            return view('resume/company_sign_up_detail_from',compact('release','data'));
        }else{
            $release     = ReleaseModel::where('id',$position_id)->first();
            $user_info   = CompanyInfoModel::where('user_id',$release['company_id'])->first();
            $days           = floor((strtotime($release->close_data) - strtotime($release->work_time))/(3600*24)) ;
            $work_day       = [];
            if($days >= 1){
                $work_day = $this->count($days,$release->work_time);
            }
            $release['work_data']  = $work_day;
            $resume         = ResumeModel::where('user_id',$user_id)->first();
            $sign_up_status        = SignUpModel::where([
                ['user_id',$user_id],
                ['position_id',$position_id]
            ])->first();
            $data['user_info']      = $user_info;
            $data['sign_up_status'] = $sign_up_status;
            $data['resume']         = $resume;
            return view('resume/
            ',compact('release','data'));
        }
    }

    /**
     * 统计天数
     * @param $days
     * @param $work_time
     * @param int $num
     * @param array $data
     * @return array
     */
    public function count($days,$work_time,$num = 0,$data = []){
        $num = $num+1;
        $data[] =  date("Y-m-d h:i:s",strtotime('+1 day',strtotime($work_time)));
        $now_time = date("Y-m-d h:i:s",strtotime('+1 day',strtotime($work_time)));
        if($days != $num){
            $data = $this->count($days,$now_time,$num,$data);
        }
        return $data;
    }

    /**
     * 学生报名api
     * @param Request $request
     * @return array
     */
    public function studentSignUp(Request $request){
        try {
            $user_id      = $request->session()->get('user_id');
            $student_info = StudentInfo::where('user_id',$user_id)->get()->toArray();
            if(empty($student_info)){
                return self::errorJson('请先上传证件!','sign_up_error_from');
            }

            $user = UserModel::find($user_id);
            if($user->status != 1){
                return self::errorJson('请等待后台审核学生信息！');
            }
            $data['user_id']        = $user_id;
            $data['position_id']    = $request->get('position_id');
            $data['work_time_id']   = $request->get('work_time_id');
            $data['create_time']    = Carbon::now();
            $data['sign_up_status'] = 1;
            if(!isset($data['position_id'])){
                return self::errorJson('职位不能为空!');
            }

            if(!isset($data['work_time_id'])){
                return self::errorJson('时间不能为空!');
            }
            $is_exit = SignUpModel::where([
                ['user_id',$user_id],
                ['position_id',$data['position_id']]
            ])->get()->toArray();
            if(!empty($is_exit) && $is_exit[0]['sign_up_status'] == 4){
                if($is_exit[0]['cancel_time'] == date('Y-m-d')){
                    return self::errorJson('今日不可报名!');
                }
            }

            $is_exits = SignUpModel::where([
                ['user_id',$user_id],
                ['position_id',$data['position_id']],
                ['sign_up_status',1]
            ])->get()->toArray();
            if(!empty($is_exits)){
                return self::errorJson('您已报名!');
            }

            if(!empty($is_exit)){
                $sign_up = SignUpModel::where([
                    ['user_id',$user_id],
                    ['position_id',$data['position_id']]
                ])->update(['sign_up_status' => 1,'company_status' => 1]);
            }else{
                $sign_up  = SignUpModel::create($data);
                $position = ReleaseModel::find($data['position_id']);
                ReleaseModel::where('id',$data['position_id'])->update(['already_sign_up_num' => $position['already_sign_up_num']+1 ]);
            }

            if($sign_up == true){
                return self::successJson('报名成功!');
            }
            return self::errorJson('报名失败!');
        } catch (\Exception $exception) {

            logger()->error('学生报名数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 报名失败页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function signUpErrorFrom(){
        return view('resume/sign_up_error_from');
    }

    /**
     * 报名成功页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function signUpSuccessFrom(){
        return view('resume/sign_up_success_from');
    }

    /**
     * 取消报名页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cancelSignUpFrom(){
        return view('resume/cancel_sign_up_from');
    }

    /**
     * 取消报名api
     * @param Request $request
     * @return array|mixed
     */
    public function cancelSignUp(Request $request){
        try {
            $user_id    = $request->session()->get('user_id');
            $sign_up_id = $request->get('sign_up_id');
            $remark     = $request->get('remark');
            if(!isset($sign_up_id) || $sign_up_id == null){
                return self::errorJson('报名id不能为空!');
            }

            if(!isset($remark) || $remark == null){
                return self::errorJson('取消原因不能为空!');
            }

            $update_sign_up  = SignUpModel::where('id',$sign_up_id)->update(['sign_up_status' => 4,'company_status' => 3,'remark' => $remark,'cancel_time' => Carbon::now()]);
            $get_position_id = SignUpModel::where('id',$sign_up_id)->first();
            $release         = ReleaseModel::where('id',$get_position_id['position_id'])->first();
            ReleaseModel::where('id',$get_position_id['position_id'])->update(['already_sign_up_num' => $release['already_sign_up_num'] - 1]);
            if($update_sign_up == true){
                return self::successJson('取消报名成功!');
            }
            return self::errorJson('取消报名失败!');
        } catch (\Exception $exception) {

            logger()->error('取消报名数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 评价页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function commentFrom(Request $request){
        $user_id     = $request->session()->get('user_id');
        $position_id = $request->get('sign_up_id');
        if(!isset($position_id)){
            return self::errorJson('职位id不能为空!');
        }
        $sign_up = SignUpModel::where('id',$position_id)->first();
        $release = ReleaseModel::where('id',$sign_up['position_id'])->first();
        return view('resume/comment_from',compact('release'));
    }

    /**
     * 学生评价api
     * @param Request $request
     * @return array|mixed
     */
    public function comment(Request $request){
        try {
            $user_id    = $request->session()->get('user_id');
            $sign_up_id = $request->get('sign_up_id');
            $feed_title = $request->get('feed_title');
            $type       = $request->get('type');

            if(!isset($type)){
                return self::errorJson('请选填评价满意度!');
            }

            if(!isset($feed_title)){
                return self::errorJson('评价信息不能为空!');
            }

            $update_sign_up = SignUpModel::where('id',$sign_up_id)->update(['sign_up_status' => 6,'feed_title' => $feed_title,'feed_status' => $type]);
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