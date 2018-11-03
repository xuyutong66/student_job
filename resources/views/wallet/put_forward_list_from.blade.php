<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" >

    <title>提现列表</title>

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

</head>

<body>

<!--我的钱包-->
<div class="wdqb mt-3">
    <span>可用余额</span>
    <strong>{{ isset($data['money']) ? $data['money'] : 0 }}</strong>
    <a href="put_forward_from">提现</a>
</div>


<ul class="wdqb_list">
    @foreach($data['wallet_list'] as $val)
        <li>
            <div class="wdqb_title">
                <span>{{ $val['name'] }}</span>
                <small class="text-secondary">{{ $val['create_time'] }}</small>
            </div>
            <div class="wdqb_je">
                <span class="text-danger">-{{ $val['money'] }}</span>
            </div>
        </li>
    @endforeach
</ul>

</body>
</html>
