<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" >

    <title>身份认证</title>

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">



</head>
<style>
    #changeImg{
        margin-top: 8px;
        position: relative;
        text-align: center;
    }
    .Info_box{
        position: absolute;
        text-align: center;
        top:50px;
        left:100px;
    }
    input{
        outline: none;
    }
    .img_bg{
        max-width: 290px;
    }

</style>

<body>
@if (empty($get_detail_info))
    <form id="info_data" data-type="1">
@else
    <form id="info_datas" data-type="2">
@endif

<!--基本信息-->

    <ul class="jbxx py15 mt-3">
        <li>
            <span>学校</span>
            @if (empty($get_detail_info))
                <input type="text" placeholder="请输入学校" name="school" >
            @else
                <input type="text" placeholder="请输入学校" name="school" value="{{ $get_detail_info->school }}">
            @endif
        </li>
        <li>
            <span>专业</span>
            @if (empty($get_detail_info))
                <input type="text" placeholder="请输入学校" name="major" >
            @else
                <input type="text" placeholder="请输入学校" name="major" value="{{ $get_detail_info->major }}">
            @endif
        </li>
        <li>
            <span>入学年份</span>
            <label for="month">
            @if (empty($get_detail_info))
            <input type="text" name="entre_school_year" id="enterYear" placeholder="请选择入学年份">
            @else
            <input type="month" id="month" name="entre_school_year" placeholder="请选择入学年份" value="{{ $get_detail_info->entre_school_year }}">
            @endif
            </label>
        </li>
        <li>
            <span>联系方式</span>
            @if (empty($get_detail_info))
                <input type="text" placeholder="请输入联系方式" name="contact_phone">
            @else
                <input type="text" placeholder="请输入联系方式" name="contact_phone" value="{{ $get_detail_info->contact_phone }}">
            @endif
        </li>
    </ul>
<div class="xsz py15 mt-3">
    <p>学生证</p>
    <input type="file" name="student_identity" hidden id="image" accept="image/*">
    <div id="changeImg">
        @if (empty($get_detail_info))
            <img src="images/img_zz.png" class="img_bg">
        @else
            <img src="{{ $get_detail_info->student_identity }}" class="img_bg">
            <input type="hidden" id="images_box" value="{{ $get_detail_info->student_identity }}">
        @endif
        @if (empty($get_detail_info))
            <div class="Info_box">
                <img src="images/ico_photo.png" width="60">
                <p>请上传学生证</p>
            </div>
        @endif
    </div>

</div>


<div class="py15 mt-3 btn-g">
    <button type="button" class="btn btn-block f-14 bg-green" onclick="SubmitBtn();">保存</button>
</div>

</form>



</body>
</html>
<script src="{{asset('js/jquery2.0.0.min.js')}}"></script>
<script src="{{asset('js/ajaxfileupload.js')}}"></script>
<script>
    $("#changeImg").click(function(){
        $("#image").trigger("click");
    });
    $("body").on('change propertychange','#image',function () {
        var file = $(this).val();
        if (file.length > 0){
            $.ajaxFileUpload({
                url: 'uploadFile',
                secureuri: false,
                fileElementId: 'image',
                dataType: 'json',
                success: function (res) {
                    if (res.code == 0) {
                        $(".Info_box").hide();
                        $(".img_bg").attr("src",res.data);
                        $("#images_box").val(res.data);
                    } else {
                        alert(res.message);
                    }
                }
            });
        } else {
            $(".Info_box").show();
            $(".img_bg").attr("src","images/img_zz.png");
            $("#images_box").val('');
        }
    });

    //  提交
    function SubmitBtn() {
        var student_identity = $("#images_box").val();    //图片名

        if ($("form").attr("data-type") == 1) {

            $.post('add/student_info',$("form").serialize()+"&student_identity="+student_identity,function (res) {
                alert(res.message);
            },'json');

        } else if ($("form").attr("data-type") == 2){
            $.post('update/student_info',$("form").serialize()+"&student_identity="+student_identity,function (res) {
                alert(res.message);
            },'json');
    }
    }
if ($("#enterYear").length > 0) {
    var textbox = document.getElementById('enterYear')
    textbox.onfocus = function (event) {
        this.type = 'month';
        this.focus();
    }
}

$("label").click(function () {
    $("#month").trigger('focus');
});
</script>