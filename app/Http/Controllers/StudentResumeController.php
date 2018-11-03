<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/30
 * Time: 23:18
 */

namespace App\Http\Controllers;

use App\Model\StudentInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Model\ResumeModel;
use App\Model\WorkExperienceModel;
use App\Model\EducationalExperienceModel;
use Illuminate\Support\Facades\DB;

/**
 * 学生h5简历相关
 * Class StudentResumeController
 * @package App\Http\Controllers
 */
class StudentResumeController extends CommonController{
    /**
     * 简历页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resumeFrom(Request $request){
        $user_id     = $request->session()->get('user_id');
        if(!isset($user_id)){
            $token   = $request->get('token');
            $user_id = CommonController::getToken($token);
        }

        $request->session()->put('user_id',$user_id);
        $resume       = StudentInfo::where('user_id',$user_id)->first();
        if(empty($resume)){
            return view('user/identityConfirm');
        }
        return view('resume/resume_from');
    }

    /**
     * 查看简历详情api
     * @param Request $request
     * @return array|mixed
     */
    public function getResumeDetail(Request $request){
        try {
            $user_id      = $request->session()->get('user_id');
            $student_info = StudentInfo::where('user_id',$user_id)->first();
            $resume       = ResumeModel::where('user_id',$user_id)->first();
            $data['user'] = $student_info;
            if(!empty($resume)){
                if($resume->education_id  == 1){
                    $resume->education_id = "本科";
                }

                if($resume->education_id  == 2){
                    $resume->education_id = "中专";
                }

                $education         = EducationalExperienceModel::where('resume_id',$resume['id'])->get()->toArray();
                $work              = WorkExperienceModel::where('resume_id',$resume['id'])->get()->toArray();
                $data['resume']    = $resume;
                $data['education'] = $education;
                $data['work']      = $work;
            }

            return self::successJson('查询成功!',$data);

        } catch (\Exception $exception) {

            logger()->error('查询简历数据异常!' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 基本信息页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function basicInfoFrom(Request $request){
        $user_id      = $request->session()->get('user_id');
        $user_info    = StudentInfo::where('user_id',$user_id)->first();
        $basic_resume = ResumeModel::where('user_id',$user_id)->first();
        return view('resume/basic_info_from',compact('user_info','basic_resume'));
    }

    /**
     * 修改基本信息api
     * @param Request $request
     * @return array
     */
    public function updateBasicInfo(Request $request){
        try {
            $user_id                = $request->session()->get('user_id');
            $data['name']           = $request->get('name');
            $result['sex']          = $request->get('sex');
            $result['education_id'] = $request->get('education_id');
            $updatee_user_info      =  StudentInfo::where('user_id',$user_id)->update($data);
            if(isset($result['sex']) || isset($result['education_id'])){
                $check_resume       = ResumeModel::where('user_id',$user_id)->get()->toArray();
                if(empty($check_resume)){
                    $result['user_id']   = $user_id;
                    $update_basic_resume = ResumeModel::create($result);
                }else{
                    $update_basic_resume = ResumeModel::where('user_id',$user_id)->update($result);
                }
            }

            return self::successJson('修改简历成功!');

        } catch (\Exception $exception) {

            logger()->error('修改简历数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 教育经历页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function educateExperienceFrom(Request $request){
        $user_id = $request->session()->get('user_id');
        $resume  = ResumeModel::where('user_id',$user_id)->get()->toArray();
        if(!empty($resume)){
            $education = EducationalExperienceModel::where('resume_id',$resume[0]['id'])->first();
        }
        return view('resume/educate_experience_from',compact('education'));
    }

    /**
     * 添加教育经历api
     * @param Request $request
     * @return array|mixed
     */
    public function createEducateExperience(Request $request){
        try {
            $attributes = $request->all();
            $user_id    = $request->session()->get('user_id');
            if(isset($attributes['enter_school_year']) || isset($attributes['school_name'])){
                $resume = ResumeModel::where('user_id',$user_id)->get()->toArray();
                if(empty($resume)){
                    $data['user_id']           = $user_id;
                    $add_resume                = ResumeModel::create($data);
                    $attributes['resume_id']   = $add_resume['id'];
                    $attributes['create_time'] = Carbon::now();
                    $attributes['update_time'] = Carbon::now();
                    EducationalExperienceModel::create($attributes);
                }else{
                    $attributes['resume_id']   = $resume[0]['id'];
                    $attributes['create_time'] = Carbon::now();
                    $attributes['update_time'] = Carbon::now();
                    EducationalExperienceModel::create($attributes);
                }
            }
            return self::successJson('创建成功');

        } catch (\Exception $exception) {

            logger()->error('创建简历数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 修改教育经历api
     * @param Request $request
     * @return array|mixed
     */
    public function updateEducateExperiences(Request $request){
        try {
            $user_id        = $request->session()->get('user_id');
            $attributes     = $request->all();
            $student_resume = ResumeModel::where('user_id',$user_id)->first();
            EducationalExperienceModel::where('resume_id',$student_resume['id'])->update($attributes);
            return self::successJson('修改简历成功');

        } catch (\Exception $exception) {

            logger()->error('修改简历数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 工作经历页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function workExperienceFrom(Request $request){
        $user_id = $request->session()->get('user_id');
        $resume  = ResumeModel::where('user_id',$user_id)->get()->toArray();
        $work    = WorkExperienceModel::where('resume_id',$resume[0]['id'])->first();
        return view('resume/work_experience_from',compact('work'));
    }

    /**
     * 添加工作经历api
     * @param Request $request
     * @return array|mixed
     */
    public function createWorkExperience(Request $request){
        try {
            $attributes = $request->all();
            $user_id    = $request->session()->get('user_id');
            if(isset($attributes['work_content']) || isset($attributes['work_year_id'])){
                $resume = ResumeModel::where('user_id',$user_id)->get()->toArray();
                if(empty($resume)){
                    $data['user_id']         = $user_id;
                    $add_resume              = ResumeModel::create($data);
                    $attributes['resume_id'] = $add_resume['id'];
                    WorkExperienceModel::create($attributes);

                }else{
                    $attributes['resume_id'] = $resume[0]['id'];
                    WorkExperienceModel::create($attributes);
                }
            }

            return self::successJson('创建成功');

        } catch (\Exception $exception) {

            logger()->error('创建简历数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 修改工作经历api
     * @param Request $request
     * @return array|mixed
     */
    public function updateWorkExperiences(Request $request){
        try {
            $user_id    = $request->session()->get('user_id');
            $attributes = $request->all();
            $get_resume = ResumeModel::where('user_id',$user_id)->first();
            WorkExperienceModel::where('resume_id',$get_resume['id'])->update($attributes);
            return self::successJson('修改简历成功');

        } catch (\Exception $exception) {

            logger()->error('修改简历数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 详情信息页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detailInfoFrom(Request $request){
        $user_id       = $request->session()->get('user_id');
        $detail_resume = ResumeModel::where('user_id',$user_id)->first();
        return view('resume/detail_info_from',compact('detail_resume'));
    }

    /**
     * 修改详情信息api
     * @param Request $request
     * @return array
     */
    public function updateDetailInfo(Request $request){
        try {
            $user_id          = $request->session()->get('user_id');
            $result['height'] = $request->get('height');
            $result['weight'] = $request->get('weight');
            $result['health_certificate'] = $request->get('health_certificate');
            if(isset($result['height']) || isset($result['weight']) || isset($result['health_certificate'])){
                $check_resume = ResumeModel::where('user_id',$user_id)->get()->toArray();
                if(empty($check_resume)){
                    $result['user_id']   = $user_id;
                    $update_basic_resume = ResumeModel::create($result);
                }else{
                    $updatee_resume      =  ResumeModel::where('user_id',$user_id)->update($result);
                }
            }

            return self::successJson('修改简历成功!');

        } catch (\Exception $exception) {

            logger()->error('修改简历数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }
}