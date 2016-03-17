/**
 * Created by SexLiu on 2016/1/13.
 */
function home(){
    $('#body').load('home.html');
}
function loadUserList() {
    $('#body').load('user-list.html');
}
function loadToolList() {
    $('#body').load('tool-list.html');
}
function loadToolFlowList() {
    $('#body').load('tool-flow.html');
}
function loadHomeRepairList() {
    $('#body').load('home-repair-list.html');
}
function loadNoticeList() {
    $('#body').load('notice-list.html');
}
function loadActivityList() {
    $('#body').load('activity-list.html');
}
function loadLottery() {
    $('#body').load('lottery.html');
}

//页面效果
$('#menu li').click(function(){
    $('#menu li').removeClass('active');
    $(this).addClass('active');
});
$('[onclick]').click(function(){
    $(".navbar-collapse").removeClass('in');
});
$(function () {
    home();
});

//判断是否登录，没登录就跳转到登录页
if(sessionStorage.loginId){
    $('#loginName').html(sessionStorage.loginName);
}else{
    window.location="../view/login.html";
}

function signOut(){
    sessionStorage.clear();
}

function changePassword(){
    $('#body').load('password-edit.html');
}