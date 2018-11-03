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

<form id="form_info" >
<!--基本信息-->
<ul class="jbxx py15 mt-3">
    <li class="face">
        <span>头像</span>
        @if (empty($user_info))
            <a href=""><img src="images/img_face.png"></a>
        @else
            <a href=""><img src="{{ $user_info->header_img }}"></a>
        @endif
    </li>
    <li>
        <span>姓名</span>
        @if (empty($user_info))
            <input type="text" placeholder="请输入姓名" name="name">
        @else
            <input type="text" placeholder="请输入姓名" name="name" value="{{ $user_info->name }}">
        @endif
    </li>
    <li>
        <span>性别</span>
        <select name="sex" id="">
            <option value="" hidden>请选择性别</option>
            <option value="1" @if (isset($basic_resume->sex) && $basic_resume->sex == 1) selected  @endif>男</option>
            <option value="2" @if (isset($basic_resume->sex) && $basic_resume->sex == 2) selected  @endif>女</option>
        </select>
    </li>
    <li>
        <span>学历</span>
        <select name="education_id" id="">
            <option value="" hidden>请选择学历</option>
            <option value="2" @if (isset($basic_resume->education_id) && $basic_resume->education_id == 2) selected  @endif>中专</option>
            <option value="1" @if (isset($basic_resume->education_id) && $basic_resume->education_id == 1) selected  @endif>本科</option>
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
        $.post("update/resume",$("#form_info").serialize(),function (res) {
            alert(res.message);
            location.href='/resume_from';
        });
    });
</script>
