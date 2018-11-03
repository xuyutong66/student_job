<?php
/**
 * Created by PhpStorm.
 * User: xuyutong
 * Date: 2018/7/25
 * Time: 15:20
 */
namespace App\Http\Controllers;

use App\Model\ReleaseModel;
use App\Model\SignUpModel;
use App\Model\StudentInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 *  学生相关接口
 * Class StudentController
 */
class StudentController extends CommonController{
    /**
     * 职位列表
     * @param Request $request
     * @return array|mixed
     */
    public function positionList(Request $request){
        try {
            $token           = $request->get('token');
            $user_id         = CommonController::getToken($token);
            $position_name   = $request->get('position_name');
            $distances       = $request->get('distance');
            $settlement_type = $request->get('settlement_type');
            $now_address     = $request->get('now_address');
            $order_type      = $request->get('order_type');
            $model           = app()->make(ReleaseModel::class);

            if (isset($settlement_type) && $settlement_type != 0) {
                $model  = $model->where('settlement_type',$settlement_type);
            }

            if (isset($now_address)  && $now_address != "") {
                $model  = $model->where('work_address','like',"%{$now_address}%");
            }


            if ($order_type == 3) {
                $model  = $model->orderBy('position_money','desc');
            }

            if ($order_type == 2) {
                $model  = $model->orderBy('create_time','desc');
            }

            if ($order_type == 1) {
                if(!isset($distances) || $distances == null || $distance = ""){
                    return self::errorJson('参数异常!');
                }
                logger()->error('查询职位数据异常', ['exception' => $distances]);

                // 地址转化为经纬度
                $address  = CommonController::address($distances);
                $distance = 15;//范围（单位千米）
                $lat      = $address['lat'];
                $lng      = $address['lng'];
                define('EARTH_RADIUS', 6371);//地球半径，平均半径为6371km
                $dlng     = 2 * asin(sin($distance / (2 * EARTH_RADIUS)) / cos(deg2rad($lat)));
                $dlng     = rad2deg($dlng);
                $dlat     = $distance/EARTH_RADIUS;
                $dlat     = rad2deg($dlat);
                $squares  = array('left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
                    'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
                    'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
                    'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
                );

                $model    = $model->where('lat','<>', 0)
                    ->whereBetween('lat',[$squares['right-bottom']['lat'],$squares['left-top']['lat']])
                    ->whereBetween('lng',[$squares['left-top']['lng'],$squares['right-bottom']['lng']]);
            }

            if(isset($position_name) && $position_name != "" ) {
                $model     = $model->where('position_name','like',"%{$position_name}%");
                DB::table('search_history')->insert([
                    'user_id' => $user_id,
                    'name' => $position_name,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                $get_seaarch_name = DB::table('search_hot')->where('name',$position_name)->get()->toArray();
                if(empty($get_seaarch_name)){
                    DB::table('search_hot')->insert([
                        'name' => $position_name,
                        'num' => 1,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }else{
                    DB::table('search_hot')->where('name',$position_name)->update([
                        ['num' => $get_seaarch_name[0]->num],
                        ['updated_at' => Carbon::now()]
                    ]);
                }
            }

            $model     = $model->where('status',1);
            $resume    = $model->paginate(15);
            if(!empty($resume)){
                return self::successJson('查询职位成功!',$resume);
            }
            return self::errorJson('查询职位失败!');
        } catch (\Exception $exception) {

            logger()->error('查询职位数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 查询报名列表
     * @param Request $request
     * @return array|mixed
     */
    public function signUpList(Request $request){
        try {
            $token   = $request->get('token');
            $user_id = CommonController::getToken($token);
            $model   = app()->make(SignUpModel::class);

            $status  = $request->get('status');
            if (isset($status)) {
                $model  = $model->where('sign_up_status',$status);
            }

            $model   = $model->where('user_id',$user_id);
            $sign_up = $model->paginate(15);
            foreach ($sign_up as $key => $value){
                $sign_up[$key]['position'] = ReleaseModel::where('id',$value['position_id'])->first();
            }

            if(!empty($sign_up)){
                return self::successJson('查询报名列表成功!',$sign_up);
            }
            return self::errorJson('查询报名列表失败!');
        } catch (\Exception $exception) {

            logger()->error('查询报名列表数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 首页搜索
     * @param Request $request
     * @return array|mixed
     */
    public function searchHotHistory(Request $request){
        try {
            $token                  = $request->get('token');
            $user_id                = CommonController::getToken($token);
            $get_name               = DB::table('search_history')->where('user_id',$user_id)->get();
            $get_seaarch_name       = DB::table('search_hot')->orderBy('num','asc')->get();
            $data['search_history'] = $get_name;
            $data['search_hot']     = $get_seaarch_name;
            if(!empty($data)){
                return self::successJson('查询成功!',$data);
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
     * 删除历史搜索
     * @param Request $request
     * @return array|mixed
     */
    public function deleteHistorySearch(Request $request){
        try {
            $token   = $request->get('token');
            $user_id = CommonController::getToken($token);
            $delete_history_search = DB::table('search_history')->where('user_id',$user_id)->delete();
            if($delete_history_search == true){
                return self::successJson('删除成功!');
            }

        } catch (\Exception $exception) {

            logger()->error('评价数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 身份认证页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function studentInfoFrom(Request $request){
        $token   = $request->get('token');
        $user_id = CommonController::getToken($token);
        $request->session()->put('user_id',$user_id);
        $get_detail_info = StudentInfo::where('user_id',$user_id)->first();
        return view('user/identityConfirm',compact('get_detail_info'));
    }

    /**
     * 学生认证api
     * @param Request $request
     * @return array
     */
    public function createStudentInfo(Request $request){
        try {
            $user_id                         = $request->session()->get('user_id');
            $attributes['user_id']           = $user_id;
            $attributes['school']            = $request->get('school');
            $attributes['major']             = $request->get('major');
            $attributes['entre_school_year'] = $request->get('entre_school_year');
            $attributes['contact_phone']     = $request->get('contact_phone');
            $attributes['student_identity']  = $request->get('student_identity');

            if(!isset($attributes['school'])){
                return self::errorJson('学校不能为空!');
            }

            if(!isset($attributes['major'])){
                return self::errorJson('专业不能为空!');
            }

            if(!isset($attributes['entre_school_year'])){
                return self::errorJson('入学年份不能为空!');
            }

            if(!isset($attributes['contact_phone'])){
                return self::errorJson('联系方式不能为空!');
            }

            if(!isset($attributes['student_identity'])){
                return self::errorJson('学生证不能为空!');
            }

            $add_info = StudentInfo::create($attributes);
            if($add_info == true){
                return self::successJson('添加成功!');
            }

            return self::errorJson('添加失败!');
        } catch (\Exception $exception) {

            logger()->error('学生认证数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 修改学生信息
     * @param Request $request
     * @return array|mixed
     */
    public function updateStudentInfo(Request $request){
        try {
            $user_id                         = $request->session()->get('user_id');
            $attributes['school']            = $request->get('school');
            $attributes['major']             = $request->get('major');
            $attributes['entre_school_year'] = $request->get('entre_school_year');
            $attributes['contact_phone']     = $request->get('contact_phone');
            $attributes['student_identity']  = $request->get('student_identity');

            if(!isset($attributes['school'])){
                return self::errorJson('学校不能为空!');
            }

            if(!isset($attributes['major'])){
                return self::errorJson('专业不能为空!');
            }

            if(!isset($attributes['entre_school_year'])){
                return self::errorJson('入学年份不能为空!');
            }

            if(!isset($attributes['contact_phone'])){
                return self::errorJson('联系方式不能为空!');
            }

            if(!isset($attributes['student_identity'])){
                return self::errorJson('学生证不能为空!');
            }

            $add_info = StudentInfo::where('user_id',$user_id)->update($attributes);
            return self::successJson('修改成功!');

        } catch (\Exception $exception) {

            logger()->error('学生认证数据异常' . $exception->getMessage(), ['exception' => $exception]);

            return [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * 上传图片
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function uploadFile(Request $request){
        $res = $request->file('student_identity')->store('logo');
        $url = env('APP_URL').$res;
        if(isset($url)){
            return response()->json([
                'code' => 0,
                'message' => '上传成功',
                'data' => $url
            ]);
        }
        return self::errorJson('上传失败!');
    }
}