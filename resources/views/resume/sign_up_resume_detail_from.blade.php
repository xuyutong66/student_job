<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" >

    <title>简历详情</title>

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <script src="{{asset('js/jquery2.0.0.min.js')}}"></script>

    <link href="{{asset('icheck/skins/flat/green.css')}}" rel="stylesheet">
    <script src="{{asset('icheck/icheck.min.js')}}"></script>
<style>
    .mark_box{
        background: rgba(0,0,0,0.5);
        display: none;
    }
    .footer a{
        color: #fff !important;
    }
</style>
</head>

<body>


<!--确认报名-->
<div class="mark_box align-items-end flex-column-reverse" id="resumeBox">

    <div class="qrbm bg-white" id="info_box">
        <p class="ts my15 text-danger">正规职位不会收取费用，若收费请提高警惕 </p>
        <p class="d-block mt-2 py15">确认工作要求</p>
        <p class="d-flex qrbm_label py-2 text-secondary py15">
            <span>{{ isset($release->personnel_require) ? $release->personnel_require : "" }}</span>
        </p>

        <p class="d-block mt-2 py15">确认工作时间</p>
        <div class="date d-flex text-secondary py15">

            @if (!empty($release->work_data))
                @foreach($release->work_data as $value)
                    <p><input type="checkbox" checked name="gx"> {{ $value }}</p>
                @endforeach
            @else
                <p><input type="checkbox" checked name="gx"> {{ $release->work_time }}</p>
            @endif
        </div>

        <p class="d-block mt-2 py15">确认工作地址</p>
        <p class="f-12 text-secondary py15 mb-4">{{ isset($release->work_address) ? $release->work_address : "" }}</p>


        <div class="mark_btn">
            <a class="qx" id="cancel">取消</a>
            <a class="qrbm" id="subBtn">确认报名</a>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('input').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });
    });
</script>

<!--职位详情-->
<div class="zwxq mb-3">

    <div class="zwxq_item py15 mt-3">
        <span class="fl f-16 font-weight-bold">{{ isset($release->position_name) ? $release->position_name : "" }}</span>
        @if ($release->status == 2)
            <span class="fr f-16 text-danger" >商家已关闭该职位</span>
        @else
            @if (empty($data['sign_up_status']))
                @if($release->recurite_person_num <= $release->already_sign_up_num)
                    <span class="fr f-16 text-danger" >报名人员已满</span>
                @elseif($release->close_data <= now())
                    <span class="fr f-16 text-danger">职位已失效</span>
                @else
                    <span class="fr f-16 text-danger">报名中</span>
                @endif
            @else
                @if ($data['sign_up_status']->sign_up_status == 1)
                    <span class="fr f-16 text-danger">已报名</span>
                @endif
                @if ($data['sign_up_status']->sign_up_status == 2)
                    <span class="fr f-16 text-danger">待录取</span>
                @endif

                @if ($data['sign_up_status']->sign_up_status == 3)
                    <span class="fr f-16 text-danger">已录取</span>
                @endif

                @if ($data['sign_up_status']->sign_up_status == 4)
                    <span class="fr f-16 text-danger">已取消</span>
                @endif

                @if ($data['sign_up_status']->sign_up_status == 5)
                    <span class="fr f-16 text-danger">待评价</span>
                @endif

                @if ($data['sign_up_status']->sign_up_status == 6)
                    <span class="fr f-16 text-danger">学生已评价</span>
                @endif
            @endif
        @endif
    </div>
    <div class="zwxq_item py15 mt-1">
        <div class="zwxq_moeny pt-1">
                <strong class="text-danger f-18">{{ isset($release->position_money) ? $release->position_money : 0 }}</strong><span>元/天</span>
        </div>
        <div class="zxwq_label">
            @if (!empty($release)  && $release->settlement_type)
                @if($release->settlement_type == 1)
                    <span class="badge badge_span">日结</span>
                @endif
                @if($release->settlement_type == 2)
                    <span class="badge badge_span">周结</span>
                @endif
                @if($release->settlement_type == 3)
                    <span class="badge badge_span">月结</span>
                @endif
            @endif
            @if (!empty($release)  && $release->personnel_require)
                    <span class="badge badge_span">{{ $release->already_sign_up_num }}人</span>
            @endif
        </div>
    </div>

    <hr class="hrs">

    <div class="zwxq_item py15 d-block">
        <p class="address">{{ isset($release->work_address) ? $release->work_address : "" }}</p>
        <p class="date mt-1">
            开始：{{ isset($release->work_time) ? $release->work_time : "" }} -
            结束：{{ isset($release->close_data) ? $release->close_data : "" }}
        </p>
    </div>

    <hr class="hrs">

    <div class="zwxq_item py15 d-block">
        <div class="zwxq_title">
            <span class="f-16">职位描述</span>
        </div>
        <div class="zwxq_details mt-2 text-secondary">
            <p>{{ isset($release->position_describe) ? $release->position_describe : "" }}</p>
        </div>
    </div>

    <hr class="hrs">

    <div class="zwxq_item py15 d-block">
        <div class="zwxq_title">
            <span class="f-16">公司信息</span>
        </div>
        <div class="zwxq_details text-secondary">
            <p>{{ isset($data['user_info']->company_name) ? $data['user_info']->company_name : "" }}</p>
        </div>
    </div>

    <hr class="hrs">

    <div class="zwxq_item py15 d-block">
        <div class="zwxq_title">
            <span class="f-16">联系方式</span>
        </div>
        <div class="zwxq_details text-secondary">
            <p>联系人：{{ isset($data['user_info']->contacts_name) ? $data['user_info']->contacts_name : "" }}</p>
            <p>联系电话：{{ isset($data['user_info']->contact_phone) ? $data['user_info']->contact_phone : "" }}</p>
        </div>
    </div>
</div>
<!--底部浮动-->
<div class="h44"></div>

<!--浮动-->

<input type="hidden" name="position_id" value="{{ $release->id }}">
@if (empty($data['resume']))
    <div class="footer fixed-bottom">
        <a class="ljbm" href="/resume_from">请先完善简历</a>
    </div>
@else
    @if ($release->status == 2)
        <div class="footer fixed-bottom">
            <a style="background: grey !important;" class="ljbm">商家已关闭该职位</a>
        </div>
    @else
        @if (empty($data['sign_up_status']))
            @if($release->recurite_person_num <= $release->already_sign_up_num)
                <div class="footer fixed-bottom">
                    <a style="background: grey !important;" class="ljbm">报名人员已满</a>
                </div>
            @elseif($release->close_data <= now())
                <div class="footer fixed-bottom" style="background: grey !important;">
                    <a style="background: grey !important;" class="ljbm">职位已失效</a>
                </div>
            @else
                <div class="footer fixed-bottom">
                    <a class="ljbm" id="enteredBtn">立即报名</a>
                </div>
            @endif
        @else
            @if ($data['sign_up_status']->sign_up_status == 1)
                <div class="footer fixed-bottom">
                    <a href="/cancel/sign_up_from?sign_up_id={{ $data['sign_up_status']->id }}" class="qxbm">取消报名</a>
                </div>
            @endif

            @if ($data['sign_up_status']->sign_up_status == 2)
                <div class="footer fixed-bottom">
                    <a class="dlq">待录取</a>
                </div>
            @endif

            @if ($data['sign_up_status']->sign_up_status == 3)
                <div class="footer fixed-bottom">
                    <a class="dlq">已录取</a>
                </div>
            @endif

            @if ($data['sign_up_status']->sign_up_status == 4)
                <div class="footer fixed-bottom">
                    <a class="ljbm" id="enteredBtn">立即报名</a>
                </div>
            @endif

            @if ($data['sign_up_status']->sign_up_status == 5)
                <div class="footer fixed-bottom">
                    <a href="/comment_from?sign_up_id={{ $data['sign_up_status']->id }}" class="ljbm">评价</a>
                </div>
            @endif

            @if ($data['sign_up_status']->sign_up_status == 6)
                <div class="footer fixed-bottom">
                    <a class="dlq">学生已评价</a>
                </div>
            @endif
        @endif
    @endif
@endif

</body>
</html>
<script>


    //    立即报名
    $("#enteredBtn").click(function () {
        $("#resumeBox").css("display","flex");
    });

//    取消报名
    $("#cancel").click(function () {
        $("#resumeBox").hide();
    });

    $("#resumeBox").click(function (e) {
        if (!$(e.target).closest("#info_box").length) {
            $("#resumeBox").hide();
        }
    });

    //    确认报名
    $("#subBtn").click(function () {
        var position_id = $("input[name='position_id']").val();
        $.ajax({
            url: '/sign_up',
            type: 'post',
            data: {
                position_id: position_id,
                work_time_id: 2
            },
            dataType: 'json',
            success: function (res) {
                $("#resumeBox").hide();
                if(res.code == 200){
                    location.href='/sign_up_success_from';
                }else{
                    alert(res.message);
                    location.href='/sign_up_error_from';
                }


            }
        });
    });
</script>
