/**
 * Created by SexLiu on 2016/2/19.
 */
document.title = '修改密码';
//获取id
var userId = sessionStorage.loginId //登录用户的id

$('#changePasswordForm').submit(function () {
    // 提交表单
    $(this).ajaxSubmit({
        url: '../service/UserAction.php?act=changePwd&',
        type: "post",
        data: {
            'loginId':userId
        },
        dataType:'json',
        beforeSubmit: showRequest,  //提交前处理
        success: function(data) {
            console.log(data);
            $("#error").removeClass('hidden');
            $("#error").html(data.responseText);
        },
        error:function(data) {
            console.log(data);
            $("#error").removeClass('hidden');
            $("#error").html(data.responseText);
        }
    });
    // 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false
    return false;
});
//提交前处理(表单验证)
function showRequest(formData, jqForm, options) {
    //console.log(formData);

    if ($("[name='pwd']").val() == "") {
        $("#error").removeClass('hidden');
        $("#error").html("请输入旧密码");
        $("[name='pwd']").focus();
        return false;
    }
    else if ($("[name='newPwd']").val() == "") {
        $("#error").removeClass('hidden');
        $("#error").html("新密码不能为空");
        $("[name='newPwd']").focus();
        return false;
    }
    else if ($("[name='pwd']").val() == $("[name='rePassword']").val()) {
        $("#error").removeClass('hidden');
        $("#error").html("新密码不能与旧密码一样");
        $("[name='rePassword']").focus();
        return false;
    }
    else if ($("[name='newPwd']").val() != $("[name='rePassword']").val()) {
        $("#error").removeClass('hidden');
        $("#error").html("两次输入的密码不一致");
        $("[name='rePassword']").focus();
        return false;
    }
    else {
        $("#error").addClass('hidden');
        return true;
    }
    return true;
}
