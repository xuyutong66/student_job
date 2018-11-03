<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" >

    <title>工作邀约详情</title>

    <link rel="stylesheet" href="{{asset('style/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('style/style.css')}}">

</head>

<body>

<!--消息-->
<div class="xx_zw">
    <div class="d-flex justify-content-between">
        <strong class="position_name">{{ isset($position['position_name']) ? $position['position_name'] : "" }}</strong>
        <strong class="text-danger position_money">{{ isset($position['position_money']) ? $position['position_money'] : "" }}<span>元/天</span></strong>
    </div>
    <div class="d-flex">
        <p class="text-bt f-12">需要人数<span class="text-dark px-2 recurite_person_num">{{ isset($position['recurite_person_num']) ? $position['recurite_person_num'] : "" }}</span>  已报名<span class="text-dark pl-2 already_sign_up_num">{{ isset($position['already_sign_up_num']) ? $position['already_sign_up_num'] : "" }}</span></p>
    </div>
    <hr>
    <div class="d-flex">
        <p class="text-bt f-12">方式
            <span class="text-dark px-2 settlement_type">
                    @if (!empty($position)  && $position->settlement_type)
                    @if($position->settlement_type == 1)
                        日结
                    @endif
                    @if($position->settlement_type == 2)
                        周结
                    @endif
                    @if($position->settlement_type == 3)
                        月结
                    @endif
                @endif
            </span>
            地址<span class="text-dark pl-2 work_address">{{ isset($position['work_address']) ? $position['work_address'] : "" }}</span></p>
    </div>
    <div class="d-flex">
        <p class="text-bt f-12">工作时间<span class="text-dark px-2 work_time">{{ isset($position['work_time']) ? $position['work_time'] : "" }}</span></p>
    </div>
</div>



<div class="xx_zw">
    <div class="d-flex justify-content-between">
        <span class="text-bt">职位描述</span>
    </div>
    <p class="f-12">招聘方为：{{ isset($position['company_name']) ? $position['company_name'] : "" }}</p>
    <p class="f-12 position_describe">{{ isset($position['position_describe']) ? $position['position_describe'] : "" }}</p>

</div>



<div class="xx_zw">
    <div class="d-flex justify-content-between">
        <span class="text-bt">申请人员</span>
    </div>
    <ul class="xx_zw_item mt-2" id="persons">
        @if (!empty($sign_list))
            @foreach($sign_list as $val)
                <li>
                    <div class="face"><img src="{{ $val['header_img'] }}"></div>
                    <div class="name">
                        <p>{{ $val['name'] }}</p>
                        <p class="star_1"></p>
                    </div>
                    <div class="link">
                        @if ($val['sign_up_status'] == 1)
                            <span class="ty" data-id="{{ $val['position_id'] }}" data-user="{{ $val['user_id'] }}" onclick="agreeBtn(this)">同意</span>
                        @endif

                        @if ($val['sign_up_status'] == 2)
                                <span class="ty" data-id="{{ $val['position_id'] }}" data-user="{{ $val['user_id'] }}" onclick="agreeBtn(this)">同意</span>
                        @endif

                        @if ($val['sign_up_status'] == 3)
                                <span class="ty" >已同意</span>
                        @endif

                        @if ($val['sign_up_status'] == 4)
                                <span class="ty" >对方已取消</span>
                        @endif

                        @if ($val['sign_up_status'] == 5)
                                <span class="ty" >学生待评价</span>
                        @endif

                        @if ($val['sign_up_status'] == 6)
                                <span class="ty" >学生已评价</span>
                        @endif

                        @if ($val['sign_up_status'] == 7)
                                <span class="ty" >已完成工作</span>
                        @endif

                        @if ($val['sign_up_status'] == 8)
                                <span class="ty" >已结算</span>
                        @endif

                        @if ($val['sign_up_status'] == 9)
                                <span class="ty" >待评价</span>
                        @endif

                        @if ($val['sign_up_status'] == 10)
                                <span class="ty" >已评价</span>
                        @endif
                    </div>
                </li>
            @endforeach
        @endif
    </ul>
</div>

</body>
</html>
<script src="{{asset('js/jquery2.0.0.min.js')}}"></script>
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