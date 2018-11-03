<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" >

    <title>评价</title>

    <link rel="stylesheet" href="{{asset('style/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('style/style.css')}}">

</head>

<body>

<!--评价-->
<div class="pj bg-white pt-3 py15">
    <div class="pj_title d-flex justify-content-between">
        <p class="f-16">{{ isset($position->position_name) ? $position->position_name : "" }}</p>
        <p class="text-danger f-16">{{ isset($position->position_money) ? $position->position_money : "" }}元/天</p>
    </div>
    <p class="dz f-12 text-muted">
        <span>{{ isset($position->work_address) ? $position->work_address : "" }}</span>
        <span>{{ isset($position->work_time) ? $position->work_time : "" }}-{{ isset($position->close_data) ? $position->close_data : "" }}</span>
    </p>
    <hr>

    <p class="btn-k">
        <a class="active" onclick="Commit(1);">满意</a>
        <a onclick="Commit(2);">不满意</a>
    </p>

</div>


<ul class="pj_list">
    @if (!empty($sign_list))
        @foreach($sign_list as $val)
            <li>
                <span data-id="{{ $val['id'] }}">{{ $val['name'] }}</span>
                <div class="star">
                    <span class="" onclick="star(this);"></span>
                    <span class="" onclick="star(this);"></span>
                    <span class="" onclick="star(this);"></span>
                    <span class="" onclick="star(this);"></span>
                    <span class="" onclick="star(this);"></span>
                </div>
            </li>
        @endforeach
    @endif
</ul>

</body>
</html>
<script src="{{asset('js/jquery2.0.0.min.js')}}"></script>
<script>
    function star(e) {
        var num;
        $(e).addClass('active');
        $(e).prevAll('span').addClass('active');
        $(e).nextAll('span').removeClass('active');

        var a = $(e).parent('.star');
        a.each(function () {
            num = a.find(".active").length;
        });
        // console.log(num)
    }
    
    function Commit(type) {
        var data = new Array();
        $(".star").each(function () {
            var num = $(this).find(".active").length;
            var id = $(this).siblings("span").attr("data-id");
            var obj = {id: id,level: num};
            data.push(obj);
        });
        console.log(data)

        $.post('company/comment',{
            data: data,
            praise: type
        },function (res) {

        },'json');
    }

</script>
