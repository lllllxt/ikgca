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
                "<td><label><input type='checkbox' id='"+data[i].id+"'></label></td>" +
                "<td>"+data[i].name+"</td>" +
                "<td>"+data[i].sex+"</td>" +
                "<td>"+data[i].phone+"</td>" +
                "<td>"+data[i].shortPhone+"</td>" +
                "<td>"+data[i].address+"</td>" +
                "<td>" +
                "<a href='#' onclick='deleteByIds("+data[i].id+")'><span class='glyphicon glyphicon-remove'></span>删除</a>  " +
                "<a href='#' onclick='updateUserById("+data[i].id+")' data-toggle='modal' data-target='#myModal'><span class='glyphicon glyphicon-edit'></span>修改</a> " +
                "<a href='#' onclick='queryUserById("+data[i].id+")' data-toggle='modal' data-target='#myModal'><span class='glyphicon glyphicon-search'></span>查看</a> " +
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
            alert("我想见见程序员欧巴！");
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

//删除
function deleteByIds(id){
    var ids="-1";
    if(id){
        ids = id;
    }else{
        $("input[type='checkbox']").each(function () {
            if($(this).is(':checked')){
                ids = ids +","+ $(this).attr('id');
            }
        });
    }
    if(confirm('确实要删除该成员吗?')){
        $.ajax({
            type: "post",
            url: '../service/UserAction.php?act=delByIds&',
            data: {
                'Ids': ids
            },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $('#body').load('user-list.html');
            },
            error: function (data) {
                console.log(data);
                alert(data.responseText);
                $('#body').load('user-list.html');
            }
        });
    }
}
//新增
function addUser(){
    sessionStorage.action = 'add';//设置操作
    $('.modal-body').load('user-edit.html');
}
//修改
function updateUserById(id) {
    sessionStorage.action = 'update';//设置操作
    sessionStorage.id = id;//设置成员id
    $('.modal-body').load('user-edit.html');
}
//查看
function queryUserById(id){
    sessionStorage.action = 'query';
    sessionStorage.id = id;
    $('.modal-body').load('user-edit.html');
}
//下载
function download(){
    user.name = $('#name').val();
    user.sex = $('#sex').val();
    user.phone = $('#phone').val();
    $.ajax({
        type:"post",
        url:"../service/UserAction.php?act=queryAll&",
        data: {
            'user': user,
            'excel':"download"
        },
        success:function(data){
            console.log(data);
        }
    })
}