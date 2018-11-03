<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" >

    <title>取消报名</title>

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

<style>
    .ljbm{
        color: #fff !important;
    }
</style>

</head>

<body>

<!--取消报名-->
<form id="form_info">
<div class="qxbm py-3 py15 bg-white">
    <p class="text-danger">取消之前请仔细阅读这段规则：</p>
    <p class="text-danger f-18">取消此单将无法报名今天的职位</p>

    <ul class="qxbm_form mt-3">
        <li>
            <span>姓名</span>
            <input type="text" placeholder="请输入姓名" name="name">
        </li>
        <li>
            <span>性别</span>
            <select name="sex" id="sex_box">
                <option value="0" hidden>请选择</option>
                <option value="1">男</option>
                <option value="2">女</option>
            </select>
        </li>
        <li>
            <span>电话</span>
            <input type="text" placeholder="请输入电话" name="phone">
        </li>
        <li class="d-block textarea">
            <span>取消原因(不得低于100字)</span>
            <textarea placeholder="请输入取消原因" minlength="100" name="remark"></textarea>
        </li>
    </ul>
</div>


<!--底部浮动-->
<div class="h44"></div>


<!--浮动-->
<div class="footer fixed-bottom">
    <a class="ljbm" id="subBtn">提交并确认取消</a>
</div>
</form>

</body>
</html>
<script src="{{asset('js/jquery2.0.0.min.js')}}"></script>
<script>
    function getUrlParam(name){
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r!=null) return unescape(r[2]); return null;
    }


    var getUrlParam = getUrlParam('sign_up_id');
    $("#subBtn").click(function () {

        var txt = $("textarea").val().length;
        if (txt < 100) {
            alert("取消原因不得低于100字!");
        } else {
            $.post("/cancel/sign_up",$("#form_info").serialize()+"&sign_up_id="+getUrlParam,function (res) {
                alert(res.message);
            });
        }
    });
</script>
