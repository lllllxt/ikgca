/**
 * Created by SexLiu on 2016/1/15.
 */
$(".modal-title").html("工具信息");
var tool = {};

//获取id
var id = sessionStorage.id;
var url = '';//初始化请求url
//通过id查询
function getToolById() {
    $.ajax({
        type: "GET",
        url: '../service/ToolAction.php?act=query&',
        data: {
            'toolId': id
        },
        dataType: "json",
        success: function (data) {
            $("[name='id']").val(data[0].id);
            $("[name='name']").val(data[0].name);
            $("[name='remark']").val(data[0].remark);
            if (data[0].state == '0') {
                $(":radio[value='0']").attr("checked", true);
            } else {
                $(":radio[value='1']").attr("checked", true);
            }
        },
        error: function () {
            alert("getToolById ajax error");
        }
    });
}

if (sessionStorage.action == 'update') {
    getToolById();
    url = '../service/ToolAction.php?act=update&';
}
else if (sessionStorage.action == 'add') {
    url = '../service/ToolAction.php?act=add&';
}

var options = {
    url: url,
    type: 'POST',
    beforeSubmit: showRequest,  //提交前处理
    success: showResponse  //处理完成
};
// 绑定表单提交事件处理器
$('#toolForm').submit(function () {
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

    if ($("[name='name']").val() == "") {
        $("[name='error']").removeClass('hidden');
        $("[name='error']").html("工具名称不能为空");
        $("[name='name']").focus();
        return false;
    }
    else {
        $("[name='error']").addClass('hidden');
        return true;
    }
    return true;
}
//处理完成
function showResponse(responseText, statusText) {
    console.log(responseText);
    if (responseText == 1000) {
        alert("提交成功");
        $('.close').click();
        $('#body').load('../view/tool-list.html');
    } else {
        alert("骗我呢，你有修改信息吗？");
    }
}
