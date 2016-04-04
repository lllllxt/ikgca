/**
 * Created by SexLiu on 2016/1/15.
 */
$(".modal-title").html("活动公告--编辑公告");
//获取活动id
var id = sessionStorage.id;
//通过id查询
function getNoticeById() {
    $.ajax({
        type: "GET",
        url: '../service/NoticeAction.php?act=query&',
        data: {
            'noticeId': id
        },
        dataType: "json",
        success: function (data) {
            $("[name='id']").val(data[0].id);
            $("[name='title']").val(data[0].title);
            $("[name='content']").val(data[0].content);
        },
        error: function () {
            alert("getNoticeById ajax error");
        }
    });
}

var url = '';//初始化请求url
var data={};
if (sessionStorage.action == 'update') {
    getNoticeById();
    url = '../service/NoticeAction.php?act=update&';
}
else if (sessionStorage.action == 'add') {
    url = '../service/NoticeAction.php?act=add&';
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
$('#noticeForm').submit(function () {
    if(confirm("确定提交吗？")){
        // 提交表单
        $(this).ajaxSubmit(options);
    }
    // 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false
    return false;
});
//提交前处理(表单验证)
function showRequest(formData, jqForm, options) {
    //console.log('提交前处理');
    //console.log(formData);

    if ($("[name='title']").val() == "") {
        $("#error").removeClass('hidden');
        $("#error").html("标题不能为空");
        $("[name='title']").focus();
        return false;
    }
    else if ($("[name='content']").val() == "") {
        $("#error").removeClass('hidden');
        $("#error").html("内容不能为空");
        $("[name='content']").focus();
        return false;
    }
    else {
        $("#error").addClass('hidden');
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
        $('#body').load('../view/notice-list.html');
    } else {
        alert("提交失败");
    }
}
