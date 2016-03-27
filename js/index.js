/**
 * Created by SexLiu on 2016/1/13.
 */
function home() {
    openLoading();
    $('#body').load('home.html');
}
function loadUserList() {
    openLoading();
    $('#body').load('user-list.html');
}
function loadToolList() {
    openLoading();
    $('#body').load('tool-list.html');
}
function loadToolFlowList() {
    openLoading();
    $('#body').load('tool-flow.html');
}
function loadHomeRepairList() {
    openLoading();
    $('#body').load('home-repair-list.html');
}
function loadNoticeList() {
    openLoading();
    $('#body').load('notice-list.html');
}
function loadActivityList() {
    openLoading();
    $('#body').load('activity-list.html');
}
function loadLottery() {
    openLoading();
    $('#body').load('lottery.html');
}

//页面效果
$('#menu li').click(function () {
    $('#menu li').removeClass('active');
    $(this).addClass('active');
});
$('[onclick]').click(function () {
    $(".navbar-collapse").removeClass('in');
});
$(function () {
    home();
});

//判断是否登录，没登录就跳转到登录页
if (sessionStorage.loginId) {
    $('#loginName').html(sessionStorage.loginName);
} else {
    window.location = "../view/login.html";
}

function signOut() {
    sessionStorage.clear();
}

function changePassword() {
    $('#body').load('password-edit.html');
}

function openLoading() {
    $('#loading').removeClass("hidden");
}
function closeLoading() {
    $('#loading').addClass("hidden");
}