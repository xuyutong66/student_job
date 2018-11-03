<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" >

    <title>提现页面</title>

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <script src="{{asset('js/jquery2.0.0.min.js')}}"></script>

    <link href="{{asset('iCheck/skins/flat/green.css')}}" rel="stylesheet">
    <script src="{{asset('icheck/icheck.min.js')}}"></script>

</head>

<body>

<form id="account_form">
<!--基本信息-->
<ul class="jbxx py15 mt-3">
    <li>
        <span>提现金额</span>
        <input type="text" placeholder="可用余额：￥{{ isset($user['money']) ? $user['money'] : 0 }}" name="money">
        <input type="hidden" name="able_money" value="{{ isset($user['money']) ? $user['money'] : 0 }}">
    </li>
    <li>
        <span>提现方式</span>
    </li>
    <li class="alipay">
        <img src="images/ico_alipay.png" alt="">
        <p>
            <label for="ipay"></label>
            <input type="radio" id="ipay" name="type" value="1" checked>
        </p>
    </li>
    <li id="ipay_type">
        <span>支付宝账号</span>
        <input type="text" placeholder="请输入支付宝账号" name="account_number">
    </li>
    <li class="wxpay">
        <img src="images/ico_wxpay.png" alt="">
        <p>
            <label for="weipay"></label>
            <input type="radio" id="weipay" name="type" value="2"></p>
    </li>
    <li id="weipay_type" style="display: none;">
        <span>微信账号</span>
        <input type="text" placeholder="请输入微信账号" name="account_number">
    </li>

</ul>


<div class="py15 mt-3 btn-g">
    <input type="hidden" value="{{ $user->type }}" id="type">
    <button type="button" id="subBtn" class="btn btn-block f-14 bg-green">立即提现</button>
</div>
</form>
<script>
    $("input[type=radio]").click(function () {
        var account_type = $("input[type='radio']:checked").val();
        if (account_type == 1) {        //支付宝
            $("#ipay_type").show();
            $("#weipay_type").hide();
        } else if (account_type == 2) {            //微信
            $("#ipay_type").hide();
            $("#weipay_type").show();
        }
    });

    $("#subBtn").click(function () {
        var account_number = "";
        var able_money = Number($("input[name='able_money']").val());
        var money = Number($("input[name='money']").val());
        var account_type = $("input[type='radio']:checked").val();

        if (account_type == 1) {        //支付宝
            account_number =  $("#ipay_type input").val();
        } else if (account_type == 2) {            //微信
            account_number =  $("#weipay_type input").val();
        }

        if(account_number == null || account_number == ""){
            alert('账号不能为空!');
        }
        if (money <= 0) {
            alert("提现金额大于0！");
            return;
        }

        if (money > able_money) {
            alert("提现金额不能大于可用余额！");
            return;
        }
        var type = $('#type').val();
        $.ajax({
            url: 'put_forward',
            type: 'post',
            data: {
                account_number: account_number,
                type: account_type,
                money: money,
                able_money:able_money
            },
            dataType: 'json',
            success: function (res) {
                alert(res.message);
                if(type == 2){
                    location.href='/put_forward_list_from';
                }
                if(type == 1){
                    location.href='/company/wallet_from';
                }
            }
        });
    });

</script>
</body>
</html>
