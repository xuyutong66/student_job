<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" >

    <title>公司信息</title>

    <link rel="stylesheet" href="{{asset('style/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('style/style.css')}}">

</head>

<body>

<p class="py15 bg-white pt-3 text-danger">审核中</p>
<ul class="xx_list">

    <li class="face">
        <span>执照</span>
        <a href=""></a>
    </li>
    <li>
        <span>企业简称</span>
        <span class="text-secondary company_name"></span>
    </li>
    <li>
        <span>姓名</span>
        <span class="text-secondary contacts_name"></span>
    </li>
    <li>
        <span>联系电话</span>
        <span class="text-secondary contact_phone"></span>
    </li>
    <li>
        <span>企业地址</span>
        <span class="text-secondary company_address"></span>
    </li>
    <li class="face">
        <span>所属行业</span>
        <a href=""></a>
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
            $(".contacts_name").html(res.data.contacts_name);         //姓名
            $(".contact_phone").html(res.data.contact_phone);         //联系电话
            $(".company_address").html(res.data.company_address);         //企业地址
        }
    },'json');
</script>
