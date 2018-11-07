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
        .footer a{
            color: #fff !important;
        }
    </style>
</head>

<body>

<!--职位详情-->
<div class="zwxq mb-3">

    <div class="zwxq_item py15 mt-3">
        <span class="fl f-16 font-weight-bold">{{ isset($release->position_name) ? $release->position_name : "" }}</span>
        @if ($release->status == 2)
            <span class="fr f-16 text-danger" >您已关闭该职位</span>
        @else
            @if ($data['sign_up_status']->company_status == 1)
                <span class="fr f-16 text-danger">待确认</span>
            @elseif($data['sign_up_status']->company_status == 3)
                <span class="fr f-16 text-danger">已完成</span>
            @elseif($data['sign_up_status']->company_status == 4)
                <span class="fr f-16 text-danger">已结算</span>
            @elseif($data['sign_up_status']->company_status == 5)
                <span class="fr f-16 text-danger">待评价</span>
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
    @if ($release->status == 2)
        <div class="footer fixed-bottom">
            <a style="background: grey !important;" class="ljbm">您已关闭该职位</a>
        </div>
    @else
        @if ($data['sign_up_status']->company_status == 1)
            <div class="footer fixed-bottom">
                <a class="qxbm" data-id="{{ $release->id  }}" data-user="{{ $data['sign_up_status']->user_id }}" onclick="agreeBtn(this)">同意</a>
            </div>
        @elseif($data['sign_up_status']->company_status == 3)
            <div class="footer fixed-bottom">
                <a class="dlq">已完成</a>
            </div>
        @elseif($data['sign_up_status']->company_status == 4)
            <div class="footer fixed-bottom">
                <a class="dlq">已结算</a>
            </div>
        @elseif($data['sign_up_status']->company_status == 5)
            <div class="footer fixed-bottom">
                <a class="dlq" href="/company/comment_from?position_id={{ $release->id }}">待评价</a>
            </div>
        @endif
    @endif
</body>
</html>
<script>
    function agreeBtn(obj) {
        var id = $(obj).attr("data-id");
        var user_id = $(obj).attr("data-user");
        $.post('/company/admissions',{
            id: id,
            user_id: user_id
        },function (res) {
            alert(res.message);
        },'json');
    }
</script>