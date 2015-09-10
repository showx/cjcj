/*
 调用方式：
$("#myform").captchashow({ //表单
label:'.yanzheng', //显示验证码的东西
inputtext:'.yz', //验证输入的input
});
*/
/*
* Author: show (9448923@qq.com)
* DATE:2013.1.24
*/
(function($) {
$.fn.captchashow = function(options) {
var opts = $.extend({}, $.fn.captchashow.defaults, options);
var code,tmp;
$form = $(this),
tmp = createCode();
$(opts.label).html(tmp);
$(opts.label).attr("ss",tmp);
this.find("input[type=submit]").attr('disabled','true');
$yz = $('#yz');
$yz[0].addEventListener('keyup', yzs, false);

function yzs(){
if(this.value == $(opts.label).attr('ss')){
opts.onSuccess();
}else{
$form.find("input[type=submit]").attr('disabled','true');
}
}
}
$.fn.captchashow.defaults = {
label:'.yanzheng',
inputtext:'.yz',
onSuccess: function() {
// Enable the submit button:
$form.find("input[type=submit]").removeAttr('disabled');
return;
}
}
function createCode(){
code = new Array();
var codeLength = 4;
var selectChar = new Array(2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z');
for(var i=0;i&lt;codeLength;i++) { var charIndex = Math.floor(Math.random()*32); code +=selectChar[charIndex]; } return code; }; })(jQuery);
