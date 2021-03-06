/**
 * Created by SexLiu on 2016/1/15.
 */
document.title = '成员管理';
var user = {};
var pageSize=10;//分页容量
var pageNum=1; //默认页数
//获取列表
$('#queryFrom').submit(function () {
    //获取搜索框内容
    user.name = $('#name').val();
    user.sex = $('#sex').val();
    user.phone = $('#phone').val();
    // 提交表单
    $(this).ajaxSubmit({
        type: "POST",
        url: '../service/UserAction.php?act=queryAll&',
        data: {
            'user': user,
            'pageSize': pageSize,
            'pageNum': pageNum
        },
        dataType: "json",
        success: function (data) {
            console.log(data);
            //生成列表
            $('tbody tr').remove();
            for (var i = 0; i < data.length-1; i++) {
                $('tbody').append("<tr>" +
                "<td>"+data[i].name+"</td>" +
                "<td>"+data[i].sex+"</td>" +
                "<td>"+data[i].phone+"</td>" +
                "<td>"+data[i].shortPhone+"</td>" +
                "<td>"+data[i].address+"</td>" +
                "<td>" +
                "<a href='#' onclick='deleteUserById("+data[i].id+")'><span class='glyphicon glyphicon-remove'></span>删除</a>  " +
                "<a href='#' onclick='updateUserById("+data[i].id+")' data-toggle='modal' data-target='#myModal'><span class='glyphicon glyphicon-edit'></span>修改</a>" +
                "</td>" +
                "</tr>");
            }
            if(data.length==1){//
                $('tbody').append("<tr><td colspan='6' class='text-center' style='color: red;'>没有符合条件的数据，请换个条件试试</td></tr>");
            }
            //生成分页导航
            $('#page-nav li').remove();
            for(var i=0;i<data[data.length-1].pageSum;i++){
                $('#page-nav').append("<li><a href='#' onclick='queryByPage("+(i+1)+")'>"+(i+1)+"</a></li>");
            }
        },
        error: function (data) {
            console.log(data);
            alert("getUsersList ajax error");
        }
    });
    // 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false
    return false;
});

$('#queryFrom').submit();

function queryByPage(num){
    pageNum=num;
    $('#queryFrom').submit();
}

//新增
function addUser(){
    sessionStorage.action = 'add';//设置操作
    $('.modal-body').load('user-edit.html');
}
//删除
function deleteUserById(id) {
    if(confirm('确实要删除该内容吗?')){
        $.ajax({
            type: "post",
            url: '../service/UserAction.php?act=del&',
            data: {
                'userId': id
            },
            dataType: "json",
            success: function (data) {
                $('#body').load('user-list.html');
            },
            error: function () {
                alert("deleteUserById ajax error");
            }
        });
    }
}
//修改
function updateUserById(id) {
    sessionStorage.action = 'update';//设置操作
    sessionStorage.id = id;//设置成员id
    $('.modal-body').load('user-edit.html');
}