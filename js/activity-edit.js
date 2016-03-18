/**
 * Created by SexLiu on 2016/1/15.
 */
$(".modal-title").html("活动公告--编辑活动");
//获取id
var id = sessionStorage.id;
//通过id查询
function getActivityById() {
    $.ajax({
        type: "GET",
        url: '../service/ActivityAction.php?act=query&',
        data: {
            'activityId': id
        },
        dataType: "json",
        success: function (data) {
            //console.log(data);
            $("[name='id']").val(data[0].id);
            $("[name='userName']").val(data[0].userName);
            $("[name='title']").val(data[0].title);
            $("[name='content']").val(data[0].content);
            $("[name='startDate']").val(data[0].startDate);
            $("[name='endDate']").val(data[0].endDate);
            $("[name='deadline']").val(data[0].deadline);
            $("[name='leaderId']").val(data[0].leaderId);
            $("[name='leaderName']").val(data[0].leaderName);
            $("[name='acAddress']").val(data[0].acAddress);
            $("[name='fee']").val(data[0].fee);
            //$("[name='signInList']").val(data[0].signInList);
            if(data[0].acState==0){
                $(":radio[value='0']").attr("checked", true);
            }else if(data[0].acState==1){
                $(":radio[value='1']").attr("checked", true);
            }else if(data[0].acState==2){
                $(":radio[value='2']").attr("checked", true);
            }else if(data[0].acState==3){
                $(":radio[value='3']").attr("checked", true);
            }else if(data[0].acState==4){
                $(":radio[value='4']").attr("checked", true);
            }

            if(sessionStorage.selUserId){
                $("[name='leaderId']").val(sessionStorage.selUserId);
                $("[name='leaderName']").val(sessionStorage.selUserName);
            }
        },
        error: function () {
            alert("getActivityById ajax error");
        }
    });
}

var url = '';//初始化请求url
var data={};
if (sessionStorage.action == 'update') {
    getActivityById();
    url = '../service/ActivityAction.php?act=update&';
}
else if (sessionStorage.action == 'add') {
    $(".userName").addClass("hidden");
    if(sessionStorage.selUserId){
        $("[name='leaderId']").val(sessionStorage.selUserId);
        $("[name='leaderName']").val(sessionStorage.selUserName);
    }
    url = '../service/ActivityAction.php?act=add&';
    data={
      'userId' : sessionStorage.loginId //登录用户的id
    };
}

var options = {
    url: url,
    type: 'POST',
    data : data,
    beforeSubmit: showRequest,  //提交前处理
    success: showResponse  //处理完成
};
// 绑定表单提交事件处理器
$('#activityForm').submit(function () {
    if(confirm("确定提交吗？")){
        // 提交表单
        $(this).ajaxSubmit(options);
        sessionStorage.clear();
    }
    // 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false
    return false;
});
//提交前处理(表单验证)
function showRequest(formData, jqForm, options) {
    //console.log('提交前处理');
    //console.log(formData);

    if ($("[name='title']").val() == "") {
        $("[name='error']").removeClass('hidden');
        $("[name='error']").html("标题不能为空");
        $("[name='title']").focus();
        return false;
    }
    else if ($("[name='content']").val() == "") {
        $("[name='error']").removeClass('hidden');
        $("[name='error']").html("内容不能为空");
        $("[name='content']").focus();
        return false;
    }
    else {
        $("[name='error']").addClass('hidden');
        return true;
    }
    return true;
}
//处理完成
function showResponse(responseText, statusText,data) {
    console.log(data);
    if (responseText == 1000) {
        $('.close').click();
        alert("提交成功");
        $('#body').load('../view/activity-list.html');
    } else {
        alert("提交失败");
    }
}


function selectUser(){
    sessionStorage.historyUrl='../view/activity-edit.html';
    $('.modal-body').load("../view/select/selectUser.html");
}
