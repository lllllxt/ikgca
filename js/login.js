/**
 * Created by SexLiu on 2016/2/7.
 */
if(localStorage.accounts){
    $('#phone').val(localStorage.accounts);
}
$('#login-form').submit(function () {
    // 提交表单
    $(this).ajaxSubmit({
        url: "../service/UserAction.php?act=login&",
        type: 'GET',
        dataType:'json',
        beforeSubmit: showRequest,  //提交前处理
        success: function(data) {
            //console.log(data);
            if($("#checkbox_rm").prop("checked")){
                localStorage.accounts=$('#phone').val();
            }
            sessionStorage.loginName=data.name;
            sessionStorage.loginId=data.id;
            window.location="../view/index.html";
        },  //处理完成
        error:function(data){
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

    if ($("[name='phone']").val() == "") {
        $("#error").removeClass('hidden');
        $("#error").html("账号不能为空");
        $("[name='phone']").focus();
        return false;
    }
    else if ($("[name='password']").val() == "") {
        $("#error").removeClass('hidden');
        $("#error").html("密码不能为空");
        $("[name='password']").focus();
        return false;
    }
    else {
        $("#error").addClass('hidden');
        return true;
    }
    return true;
}