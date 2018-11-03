<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" >

    <title>我的银行卡</title>

    <link rel="stylesheet" href="{{asset('style/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('style/style.css')}}">

</head>

<body>

<!--银行卡-->
<div class="yhks">
    <p class="text-secondary">中国银行</p>
    @if (empty($company_bank_card))
        <p class="kh">暂无</p>
    @else
        <p class="kh">{{ isset($company_bank_card->company_card) ? $company_bank_card->company_card : '暂无' }}</p>
    @endif

</div>

</body>
</html>
