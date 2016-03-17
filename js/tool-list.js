/**
 * Created by SexLiu on 2016/1/19.
 */
document.title = '工具管理-工具详情';
var tool = {};
var pageSize=10;//分页容量
var pageNum=1; //默认页数
//获取列表
$('#queryFrom').submit(function () {
    //获取搜索框内容
    tool.name = $('#name').val();
    tool.state = $('#state').val();
    // 提交表单
    $(this).ajaxSubmit({
        type: "POST",
        url: '../service/ToolAction.php?act=queryAll&',
        data: {
            'tool': tool,
            'pageSize': pageSize,
            'pageNum': pageNum
        },
        dataType: "json",
        success: function (data) {
            console.log(data);
            $('tbody tr').remove();
            for (var i = 0; i < data.length-1; i++) {
                if(data[i].state ==0 ){
                    data[i].state ='<span class="label label-success">空闲</span>';
                }else{
                    data[i].state ='<span class="label label-danger">已出借</span>';
                }
                $('tbody').append("<tr>" +
                "<td>"+data[i].name+"</td>" +
                "<td>"+data[i].state+"</td>" +
                "<td>"+data[i].remark+"</td>" +
                "<td>" +
                "<a href='#' onclick=deleteById("+data[i].id+")><span class='glyphicon glyphicon-remove'></span>删除</a>   " +
                "<a href='#' onclick=updateById("+data[i].id+") data-toggle='modal' data-target='#myModal'><span class='glyphicon glyphicon-edit'></span>修改</a>  " +
                "</td>" +
                "</tr>");
            }
            if(data.length==1){
                $('tbody').append("<tr><td colspan='4' class='text-center' style='color: red;'>没有符合条件的数据，请换个条件试试</td></tr>");
            }
            //生成分页导航
            $('#page-nav li').remove();
            for(var i=0;i<data[data.length-1].pageSum;i++){
                $('#page-nav').append("<li><a href='#' onclick='queryByPage("+(i+1)+")'>"+(i+1)+"</a></li>");
            }
        },
        error: function (data) {
            console.log(data);
            alert("getToolsList ajax error");
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
function addTool(){
    sessionStorage.action = 'add';//设置操作
    $('.modal-body').load('tool-edit.html');
}
//删除
function deleteById(id) {

    if(confirm('确实要删除该内容吗?')){
        $.ajax({
            type: "post",
            url: '../service/ToolAction.php?act=del&',
            data: {
                'toolId': id
            },
            dataType: "json",
            success: function (data) {
                $('#body').load('tool-list.html');
            },
            error: function () {
                alert("deleteById ajax error");
            }
        });
    }
}
//修改
function updateById(id) {
    sessionStorage.action = 'update';//设置操作
    sessionStorage.id = id;//设置成员id
    $('.modal-body').load('tool-edit.html');
}