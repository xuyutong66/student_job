<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" >

    <title>编辑公司信息</title>

    <link rel="stylesheet" href="{{asset('style/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('style/style.css')}}">

</head>

<body>

<ul class="xx_list">

    <li class="face">
        <span>头像</span>
        <a href=""><img src="/images/img_face.png" class="company_logo"></a>
    </li>
    <li>
        <span>昵称</span>
        <span class="text-secondary company_name"></span>
    </li>

</ul>


</body>
</html>
<script src="{{asset('js/jquery2.0.0.min.js')}}"></script>
<script>
    $.get('/get/company_info/detail',{},function (res) {
        if (res.code == 200) {
            console.log("success")
            $(".company_name").html(res.data.company_name);         //企业简称
            $(".company_logo").attr("src",res.data.company_logo);       //logo
        }
    },'json');
</script>