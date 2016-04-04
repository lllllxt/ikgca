/**
 * Created by SexLiu on 2016/1/15.
 */
$(".modal-title").html("成员信息");
var user = {};

//获取id
var id = sessionStorage.id;
var url = '';//初始化请求url

//通过id查询
function getUserById() {
    $.ajax({
        type: "GET",
        url: '../service/UserAction.php?act=query&',
        data: {
            'userId': id
        },
        dataType: "json",
        success: function (data) {
            $("[name='id']").val(data[0].id);
            $("[name='name']").val(data[0].name);
            $("[name='birthDate']").val(data[0].birthDate);
            $("[name='address']").val(data[0].address);
            $("[name='phone']").val(data[0].phone);
            $("[name='shortPhone']").val(data[0].shortPhone);
            $("[name='qq']").val(data[0].qq);
            if (data[0].userImg == null || data[0].userImg == '') {
                data[0].userImg = '../images/userHead.ico';//设置默认头像
            }
            $("[name='userImg']").attr("src", data[0].userImg);
            if (data[0].sex == 'male') {
                $(":radio[value='male']").attr("checked", true);
            } else {
                $(":radio[value='female']").attr("checked", true);

            }
            if (data[0].power == 0) {
                $(":radio[value='0']").attr("checked", true);
            } else if (data[0].power == 1) {
                $(":radio[value='1']").attr("checked", true);
            } else if (data[0].power == 2) {
                $(":radio[value='2']").attr("checked", true);
            }else {
                $(":radio[value='3']").attr("checked", true);

            }
        },
        error: function () {
            alert("帮我找程序员欧巴修修我！");
        }
    });
}

if (sessionStorage.action == 'update') {
    getUserById();
    url = '../service/UserAction.php?act=update&';
}
else if (sessionStorage.action == 'add') {
    $('#imgForm').addClass("hidden");
    url = '../service/UserAction.php?act=add&';
}
else if (sessionStorage.action == 'query') {
    getUserById();
    $(".modal-body input").attr("disabled","disabled");
    $(".modal-body button").attr("disabled","disabled");

}

var options = {
    url: url,
    type: 'POST',
    beforeSubmit: showRequest,  //提交前处理
    success: showResponse  //处理完成
};
// 绑定表单提交事件处理器
$('#userForm').submit(function () {
        // 提交表单
        $(this).ajaxSubmit(options);
        // 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false
        return false;
});
//提交前处理(表单验证)
function showRequest(formData, jqForm, options) {
    if(confirm("确定提交吗？")){
        if ($("[name='name']").val() == "") {
            $("#error").removeClass('hidden');
            $("#error").html("姓名不能为空");
            $("[name='name']").focus();
            return false;
        }
        else if ($("[name='phone']").val() == "") {
            $("#error").removeClass('hidden');
            $("#error").html("手机不能为空");
            $("[name='phone']").focus();
            return false;
        }
        else {
            $("#error").addClass('hidden');
            return true;
        }
    }else{
        return false;
    }


}
//处理完成
function showResponse(responseText, statusText) {
    if (responseText) {
        $('.close').click();
        alert(responseText);
        $('#body').load('../view/user-list.html');
    } else {
        $("#error").removeClass('hidden');
        $("#error").html(responseText);
    }
}

/*--------------------------------------头像上传-----------------------*/
// 绑定表单提交事件处理器
$('#imgForm').submit(function () {
    if(confirm("确定上传头像吗？")){
        // 提交表单
        $(this).ajaxSubmit({
            url: "../service/UploadImage.php",
            type: 'POST',
            dataType : 'json',
            beforeSubmit:function(formData, jqForm, options) {
                //console.log(formData);
                return true;
             },
            success: function(data){
                console.log(data);
                $("[name='userImg']").attr("src", data.url);
                $("#upload_error").removeClass("hidden");
                $("#upload_error").html(data.msg);
            }
        });
    }
    // 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false
    return false;
});

function deleteImg(){
    if(confirm("确定要清除头像吗？")){
        $.ajax({
            url: "../service/UserAction.php?act=removeUserImg&",
            type: 'POST',
            data:{
                id : $("[name='id']").val()
            } ,
            success: function(data){
                //console.log(data);
                $("[name='userImg']").attr("src", "../images/userHead.ico");
                $("#upload_error").removeClass("hidden");
                $("#upload_error").html(data);
            }
        })
    }
}
/*--------------------------------------头像上传End-----------------------*/