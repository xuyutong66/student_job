<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" >

    <title>我的钱包</title>

    <link rel="stylesheet" href="{{asset('style/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('style/style.css')}}">



</head>

<body>

<!--我的钱包-->
<div class="wdqb">
    <span class="text-secondary">可用余额</span>
    <strong class="py-1">{{ isset($user->money) ? $user->money : 0 }}</strong>
    <a href="/put_forward_from?token={{ $user->token }}">充值</a>
</div>

<ul class="wdqb_list">
    <li class="yhk">
        <a href="/company/bank_card_from"><span>我的银行卡</span></a>
    </li>
    <li class="jyjl">
        <a href=""><span>交易记录</span></a>
    </li>
</ul>

</body>
</html>
