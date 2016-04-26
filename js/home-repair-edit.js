/**
 * Created by SexLiu on 2016/1/15.
 */
$(".modal-title").html("上门维修--报修信息");
var hr = {};

//获取id
var id = sessionStorage.id;
var url = '';//初始化请求url
//通过id查询
function getHRById() {
    $.ajax({
        type: "GET",
        url: '../service/HomeRepairAction.php?act=query&',
        data: {
            'hrId': id
        },
        dataType: "json",
        success: function (data) {
            $("[name='id']").val(data[0].id);
            $("[name='content']").val(data[0].content);
            $("[name='address']").val(data[0].address);
            $("[name='phone']").val(data[0].phone);
            $("[name='userId']").val(data[0].userId);
            $("[name='userName']").val(data[0].userName);
            $("[name='remark']").val(data[0].remark);
            if (data[0].state == '0') {
                $(":radio[value='0']").attr("checked", true);
            } else {
                $(":radio[value='1']").attr("checked", true);
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
    getHRById();
    url = '../service/HomeRepairAction.php?act=update&';
}
else if (sessionStorage.action == 'add') {
    $(".userName").addClass("hidden");
    if(sessionStorage.selUserId){
        $("[name='userId']").val(sessionStorage.selUserId);
        $("[name='userName']").val(sessionStorage.selUserName);
    }
    url = '../service/HomeRepairAction.php?act=add&';
}

var options = {
    url: url,
    type: 'POST',
    beforeSubmit: showRequest,  //提交前处理
    success: showResponse  //处理完成
};
// 绑定表单提交事件处理器
$('#hrForm').submit(function () {
    if(confirm("确定提交吗？")){
        // 提交表单
        $(this).ajaxSubmit(options);
        sessionStorage.removeItem("selUserId");
    }
    // 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false
    return false;
});
//提交前处理(表单验证)
function showRequest(formData, jqForm, options) {
    //console.log('提交前处理');
    //console.log(formData);

    if ($("[name='content']").val() == "") {
        $("#error").removeClass('hidden');
        $("#error").html("维修内容不能为空");
        $("[name='content']").focus();
        return false;
    }
    else if ($("[name='phone']").val() == "") {
        $("#error").removeClass('hidden');
        $("#error").html("联系电话不能为空");
        $("[name='phone']").focus();
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
        $('.close').click()
        $('.modal-backdrop').remove();
        alert("提交成功");
        $('#body').load('../view/home-repair-list.html');
    } else {
        alert("提交失败");
    }
}

function selectUser(){
    sessionStorage.historyUrl='../view/home-repair-edit.html';
    sessionStorage.selPow=2;

    $('.modal-body').load("../view/select/selectUser.html");
}
