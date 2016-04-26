/**
 * Created by SexLiu on 2016/1/19.
 */
document.title = '工具管理-工具流动';
var flow = {};
var pageSize=10;//分页容量
var pageNum=1; //默认页数
//获取列表
$('#queryFrom').submit(function () {
    //获取搜索框内容
    flow.toolName = $('#toolName').val();
    flow.userName = $('#userName').val();
    flow.state = $('#state').val();
    // 提交表单
    $(this).ajaxSubmit({
        type: "POST",
        url:'../service/ToolAction.php?act=queryAllFlow&',
        data: {
            'flow': flow,
            'pageSize': pageSize,
            'pageNum': pageNum
        },
        dataType: "json",
        success: function (data) {
            console.log(data);
            $('tbody tr').remove();
            for (var i = 0; i < data.length-1; i++) {
                if(data[i].returnDate ==null || data[i].returnDate =="0000-00-00"){
                    data[i].returnDate ="<span class='label label-danger'>未归还</span>";
                }else{
                    data[i].returnDate ="<span class='label label-success'>"+data[i].returnDate+"</span>";
                }
                $('tbody').append("<tr>" +
                "<td>"+data[i].toolName+"</td>" +
                "<td>"+data[i].userName+"</td>" +
                "<td>"+data[i].lendDate+"</td>" +
                "<td>"+data[i].returnDate+"</td>" +
                "<td>" +
                "<a href='#' onclick=deleteById("+data[i].id+")><span class='glyphicon glyphicon-remove'></span> 删除</a>   " +
                "<a href='#' onclick=updateById("+data[i].id+") data-toggle='modal' data-target='#myModal'><span class='glyphicon glyphicon-log-in'></span> 归还</a>  " +
                "</td>" +
                "</tr>");
            }
            if(data.length==1 || data==""){
                $('tbody').append("<tr><td colspan='5' class='text-center' style='color: red;'>没有符合条件的数据，请换个条件试试</td></tr>");
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

//新增
function addFlow(){
    sessionStorage.action = 'add';//设置操作
    $('.modal-body').load('tool-flow-edit.html');
}
//删除
function deleteById(id) {

    if(confirm('确实要删除该内容吗?')){
        $.ajax({
            type: "post",
            url: '../service/ToolAction.php?act=delFlow&',
            data: {
                'flowId': id
            },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $('#body').load('tool-flow.html');
            },
            error: function (data) {
                alert(data.responseText+"");
                $('#body').load('tool-flow.html');
            }
        });
    }
}
//修改
function updateById(id) {
    sessionStorage.action = 'update';//设置操作
    sessionStorage.id = id;//设置成员id
    $('.modal-body').load('tool-flow-edit.html');
}