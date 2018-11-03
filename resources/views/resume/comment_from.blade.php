<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" >

    <title>评价</title>

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

</head>

<body>


<!--职位详情-->
<div class="zwxq mb-3">

    <div class="zwxq_item py15 d-block">
        <p class="f-16 font-weight-bold py-2">{{ isset($release->position_name) ? $release->position_name : "" }}</p>
        <p class="address">{{ isset($release->work_address) ? $release->work_address : "" }}</p>
        <p class="date mt-1">
            开始：{{ isset($release->create_time) ? $release->create_time : "" }} -
            结束：{{ isset($release->close_data) ? $release->close_data : "" }}
            {{ isset($release->work_time) ? $release->work_time : "" }}
        </p>
    </div>

    <div class="textarea">
        <textarea placeholder="请输入评价"></textarea>
    </div>

    <div class="btn-k">
        <a class="typeBtn active" data-type="1">满意</a>
        <a class="typeBtn" data-type="2">不满意</a>
    </div>


    <div class="py15 mt-3 btn-g">
        <button type="button" id="subBtn" class="btn btn-block f-14 bg-green">提交评价</button>
    </div>

</div>

</body>
</html>
<script src="{{asset('js/jquery2.0.0.min.js')}}"></script>
<script>
    $(".typeBtn").click(function () {
        $(this).addClass('active');
        $(this).siblings('.typeBtn').removeClass('active');
    });

    function getUrlParam(name){ 
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r!=null) return unescape(r[2]); return null;
    }

    var getUrlParam = getUrlParam('sign_up_id');
//    提交评价
    $("#subBtn").click(function () {
        var content = $("textarea").val();
        var type = $(".active").attr("data-type");
        $.post('comment',{
            feed_title: content,
            type: type,
            sign_up_id:getUrlParam
        },function (res) {
            alert(res.message);
        },'json');
    });
</script>
