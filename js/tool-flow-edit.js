/**
 * Created by SexLiu on 2016/1/15.
 */
$(".modal-title").html("工具流动信息");
var tool = {};

//获取id
var id = sessionStorage.id;
var url = '';//初始化请求url
//通过id查询
function getToolById() {
    $.ajax({
        type: "GET",
        url: '../service/ToolAction.php?act=queryFlow&',
        data: {
            'flowId': id
        },
        dataType: "json",
        success: function (data) {
            $("[name='id']").val(data[0].id);
            $("[name='toolId']").val(data[0].toolId);
            $("[name='userId']").val(data[0].userId);
            $("[name='toolName']").val(data[0].toolName);
            $("[name='userName']").val(data[0].userName);
            $("[name='lendDate']").val(data[0].lendDate);
            $("[name='returnDate']").val(data[0].returnDate);
            if(sessionStorage.selToolId){
                $("[name='toolId']").val(sessionStorage.selToolId);
                $("[name='toolName']").val(sessionStorage.selToolName);
            }
            if(sessionStorage.selUserId){
                $("[name='userId']").val(sessionStorage.selUserId);
                $("[name='userName']").val(sessionStorage.selUserName);
            }
        },
        error: function () {
            alert("帮我找程序员欧巴修修我！");
        }
    });
}

if (sessionStorage.action == 'update') {
    $('.lendDate').addClass('hide');
    getToolById();
    url = '../service/ToolAction.php?act=updateFlow&';
}
else if (sessionStorage.action == 'add') {
    $('.returnDate').addClass('hide');
    if(sessionStorage.selToolId){
        $("[name='toolId']").val(sessionStorage.selToolId);
        $("[name='toolName']").val(sessionStorage.selToolName);
    }
    if(sessionStorage.selUserId){
        $("[name='userId']").val(sessionStorage.selUserId);
        $("[name='userName']").val(sessionStorage.selUserName);
    }
    url = '../service/ToolAction.php?act=addFlow&';
}

var options = {
    url: url,
    type: 'POST',
    beforeSubmit: showRequest,  //提交前处理
    success: showResponse  //处理完成
};
// 绑定表单提交事件处理器
$('#flowForm').submit(function () {
    if(confirm("确定提交吗？")){
        // 提交表单
        $(this).ajaxSubmit(options);
        sessionStorage.removeItem("selToolId");
        sessionStorage.removeItem("selUserId");
    }
    // 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false
    return false;
});
//提交前处理(表单验证)
function showRequest(formData, jqForm, options) {
    //console.log('提交前处理');
    //console.log(formData);
    if ($("[name='toolName']").val() == null || $("[name='toolName']").val() == '') {
        $("#error").removeClass('hidden');
        $("#error").html("请选择工具");
        $("[name='toolName']").focus();
        return false;
    }
    else if ($("[name='userName']").val() == null || $("[name='userName']").val() == '') {
        $("#error").removeClass('hidden');
        $("#error").html("请选择出借人");
        $("[name='userName']").focus();
        return false;
    }
    else  if ($("[name='lendDate']").val() == null || $("[name='lendDate']").val() == '') {
        $("#error").removeClass('hidden');
        $("#error").html("出借时间不能为空");
        $("[name='lendDate']").focus();
        return false;
    }
    else {
        $("#error").addClass('hidden');
        return true;
    }
    return true;
}
//处理完成
function showResponse(responseText, statusText) {
    //console.log(responseText);
    if (responseText == 1000) {
        $('.close').click();
        $('.modal-backdrop').remove();
        alert("提交成功");
        $('#body').load('../view/tool-flow.html');
    } else {
        console.log(responseText);
        alert(responseText);
    }
}

function selectUser(){
    sessionStorage.historyUrl='../view/tool-flow-edit.html';
    $('.modal-body').load("../view/select/selectUser.html");
}

function selectTool(){
    sessionStorage.historyUrl='../view/tool-flow-edit.html';
    $('.modal-body').load("../view/select/selectTool.html");
}

//function getDate(){
//    var d = new Date();
//    var YY = d.getFullYear();
//    var MM = d.getMonth() + 1;
//    var DD = d.getDate();
//
//    var date=YY+"-" +( MM>10 ? MM : ("0"+MM)) +"-" + DD;
//    return date;
//}
