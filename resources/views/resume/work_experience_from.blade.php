<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" >

    <title>工作经验</title>

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">



</head>

<body>

    @if (empty($work))
        <form id="form_info" data-type="1">
    @else
        <form id="form_info" data-type="2">
    @endif

<!--基本信息-->
<ul class="jbxx py15 mt-3">
    <li>
        <span>工作内容</span>
        @if (empty($work))
            <input type="text" placeholder="请输入工作内容" name="work_content">
        @else
            <input type="text" placeholder="请输入工作内容" name="work_content" value="{{ $work->work_content }}">
        @endif
    </li>
    <li>
        <span>工作经验</span>
        <select name="work_year_id" id="selectBox">
                <option value="" hidden>请选择工作经验</option>
                <option value="1">1年以下</option>
                <option value="2">1~3年</option>
                <option value="3">3~5年</option>
                <option value="4">5~10年</option>
                <option value="5">10年以上</option>
            @if (empty($work))
                <input type="hidden" id="work_year_id" value="">
            @else
                <input type="hidden" id="work_year_id" value="{{ $work->work_year_id }}">
            @endif
        </select>
    </li>
</ul>


<div class="py15 mt-3 btn-g">
    <button type="button" id="subBtn" class="btn btn-block f-14 bg-green">保存</button>
</div>
</form>

</body>
</html>
<script src="{{asset('js/jquery2.0.0.min.js')}}"></script>
<script>
    var arr = new Array();
    $("#subBtn").click(function () {
        var type = $("form").attr("data-type");
        if (type == 1) {
            $.post("create/work",$("#form_info").serialize(),function (res) {
                alert(res.message);
                location.href='/resume_from';
            });
        } else if (type == 2) {
            $.post("update/work/experiences",$("#form_info").serialize(),function (res) {
                alert(res.message);
                location.href='/resume_from';
            });
        }
    });

$(function () {
    var Id = $("#work_year_id").val();
    $("#selectBox option").each(function () {
        arr.push($(this).val());
    });

    if (arr.indexOf(Id) > 0) {
        $("option[value="+Id+"]").attr('selected',true);
    }
});


</script>
