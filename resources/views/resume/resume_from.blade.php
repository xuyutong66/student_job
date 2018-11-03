<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" >

    <title>简历</title>

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

<style>
    .edu_box,.work_box{
        flex-direction: column;
    }
</style>

</head>

<body>

<!--我的简历-->
<div class="wdjl pt-3 py15 bg-white">
    <div class="wdjl_item">
        <div class="title f-16"><span>基本信息</span></div>
        <div class="edit"><a href="basicInfoFrom"><span>编辑</span></a></div>
    </div>
    <div class="wdjl_item my-3">
        <div class="face">
            <img src="images/img_face.png" class="header_img">
            <span class="pt-2 name">暂无</span>
            <small class="text-secondary education_id">暂无</small>
        </div>
    </div>
</div>

<hr class="hrs">

<div class="wdjl pt-3 py15 bg-white">
    <div class="wdjl_item">
        <div class="title f-16"><span>教育经历</span></div>
        <div class="edit" id="educate"><a href="educateExperienceFrom"><span>编辑</span></a></div>
    </div>
    <div class="wdjl_item my-2 edu_box">
        <a href="educateExperienceFrom" class="djtj" id="addEdu">点击添加</a>
    </div>
</div>

<hr class="hrs">

<div class="wdjl pt-3 py15 bg-white">
    <div class="wdjl_item">
        <div class="title f-16"><span>工作经历</span></div>
        <div class="edit" id="work"><a href="workExperienceFrom"><span>编辑</span></a></div>
    </div>
    <div class="wdjl_item my-2 work_box">
        <a href="workExperienceFrom" class="djtj" id="addWork">点击添加</a>
    </div>
</div>


<hr class="hrs">

<div class="wdjl pt-3 py15 bg-white">
    <div class="wdjl_item">
        <div class="title f-16"><span>详细信息</span></div>
        <div class="edit"><a href="detailInfoFrom"><span>编辑</span></a></div>
    </div>
    <div class="wdjl_items my-2 text-secondary">
        <p>身高：<span class="height">暂无</span></p>
        <p>体重：<span class="weight">暂无</span></p>
        <p>健康证：<span class="health_certificate">暂无</span></p>
    </div>
</div>

</body>
</html>
<script src="{{asset('js/jquery2.0.0.min.js')}}"></script>
<script>
    $.get('get/detail_resume','',function (res) {
        if(res.code == 200) {
            var data = res.data;
            var eduObj = data.education;
            var workObj = data.work;
            if (data.resume) {
                if(data.resume.height != null){
                    $(".height").html(""+data.resume.height+"cm");
                }
                if(data.resume.weight != null){
                    $(".weight").html(""+data.resume.weight+"kg");
                }
                if(data.resume.education_id != null){
                    $(".education_id").html(data.resume.education_id);     //学历
                }
                if(data.resume.health_certificate != null){
                    if (data.resume.health_certificate == 1) {
                        $(".health_certificate").html("健康");
                    } else {
                        $(".health_certificate").html("不健康");
                    }
                }
            }

            if(data.user.name != null){
                $(".name").html(data.user.name);   //用户名
            }

            if(data.user.header_img != null){
                $(".header_img").attr("src",data.user.header_img);      //头像
            }

            //  教育经历
            if (eduObj && eduObj.length > 0) {
                $('#educate').show();
                for (var i=0;i<eduObj.length;i++) {
                    $("#addEdu").hide();
                    $(".edu_box").append('<div class="wdjl_items my-2 text-secondary eduInfo">\n' +
                        '            <p>学校名称：<span class="name_'+i+'">'+eduObj[i].school_name+'</span></p>\n' +
                        '            <p>入学年份：<span class="year_'+i+'">'+eduObj[i].entre_school_year+'</span></p>\n' +
                        '        </div>');
                    if (eduObj[i].school_name == null) {
                        $(".name_"+i+"").html("-");
                    }
                    if (eduObj[i].entre_school_year == null) {
                        $(".year_"+i+"").html("-");
                    }
                }
            } else {
                $('#educate').hide();
                $("#addEdu").show();
            }
            // 工作经历
            if (workObj && workObj.length > 0) {
                $('#work').show();
                for (var i=0;i<workObj.length;i++) {
                    $("#addWork").hide();
                    $(".work_box").append('<div class="wdjl_items my-2 text-secondary workInfo">\n' +
                        '            <p>工作内容：<span class="content_'+i+'">'+workObj[i].work_content+'</span></p>\n' +
                        '            <p>工作时间：<span class="time_'+i+'">'+workObj[i].create_time+'</span></p>\n' +
                        '        </div>');
                    if (workObj[i].work_content == null) {
                        $(".content_"+i+"").html("-");
                    }
                    if (workObj[i].create_time == null) {
                        $(".time_"+i+"").html("-");
                    }
                }
            } else {
                $('#work').hide();
                $("#addWork").show();
            }

        }
    })

</script>
