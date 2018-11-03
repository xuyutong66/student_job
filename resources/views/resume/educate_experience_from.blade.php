<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" >

    <title>教育经历</title>

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

<style>
    input{
        outline: none;
    }
</style>

</head>

<body>


    @if (empty($education))
        <form id="form_info" data-type="1">
    @else
        <form id="form_info" data-type="2">
    @endif
<!--基本信息-->
<ul class="jbxx py15 mt-3">
    <li>
        <span>学校名称</span>
        @if (empty($education))
            <input type="text" placeholder="请输入学校名称" name="school_name">
        @else
            <input type="text" placeholder="请输入学校名称" name="school_name" value="{{ $education->school_name }}">
        @endif

    </li>
    <li>
        <span>入学年份</span>

        @if (empty($education))
            <input type="text" name="entre_school_year" id="enterYear" placeholder="请选择入学年份">
        @else
            <input type="month" id="month" name="entre_school_year" placeholder="请选择入学年份" value="{{ $education->entre_school_year }}">
        @endif
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
        var type = $("form").attr("data-type");
        if (type == 1) {
            $.post("create/educate",$("#form_info").serialize(),function (res) {
                alert(res.message);
                location.href='/resume_from';
            });
        } else if (type == 2) {
            $.post("update/ducational/experiences",$("#form_info").serialize(),function (res) {
                alert(res.message);
                location.href='/resume_from';
            });
        }
    });

    if ($("#enterYear").length > 0) {
        var textbox = document.getElementById('enterYear')
        textbox.onfocus = function (event) {
            this.type = 'month';
            this.focus();
        }
    }
</script>
