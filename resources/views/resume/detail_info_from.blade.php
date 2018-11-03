<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" >

    <title>基本信息</title>

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">



</head>

<body>

<form id="form_info">
<!--基本信息-->
<ul class="jbxx py15 mt-3">
    <li>
        <span>身高</span>
        @if (empty($detail_resume))
            <input type="text" placeholder="请输入身高" name="height">
        @else
            <input type="text" placeholder="请输入身高" name="height" value="{{ $detail_resume->height }}">
        @endif
    </li>
    <li>
        <span>体重</span>
        @if (empty($detail_resume))
            <input type="text" placeholder="请输入体重" name="weight">
        @else
            <input type="text" placeholder="请输入体重" name="weight" value="{{ $detail_resume->weight }}">
        @endif
    </li>
    <li>
        <span>健康证</span>
        <select name="health_certificate" id="">
            <option value="" hidden>请选择</option>
            <option value="1" @if (isset($detail_resume->health_certificate) && $detail_resume->health_certificate == 1) selected  @endif>有</option>
            <option value="2" @if (isset($detail_resume->health_certificate) && $detail_resume->health_certificate == 2) selected  @endif>无</option>
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
    $("#subBtn").click(function () {
        $.post("update/resume/detail",$("#form_info").serialize(),function (res) {
            alert(res.message);
            location.href='/resume_from';
        });
    });


</script>
