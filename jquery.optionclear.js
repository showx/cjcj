/**
* jquery option选择
* input搜索值，查找出select中的值
* version 1.0
* date:2015.09.09
* Author:show(9448923@qq.com)
*/
(function($){

$.fn.optionclear  = function (options,callback){
	// var method = arguments[0];
	// console.log(method);
	var defaults = {
		"num":"50",
		"clearid":"",
		"hook":"",
		"backcolor":"#FFFF99",
		"hovercolor":"#FF1493",
		"trig":"change",
		"width":"200px",
	}
	var opts = $.extend({},defaults,options || {});
	//debug
	function d(result)
	{
		console.log(result);
	}
	function hook()
	{
		if(callback)
		{
			// callback();
		}
	}
	function setSelect(e)
	{
		var ht = $("#"+opts.clearid);
		ht.each(function(){
			$(this).children("option").each(function(){
			 	var val = $(this).val();
			 	if(val==e)
			 	{
			 		// $(this).focus();
			 		$(this).attr("selected","selected");
			 		// $(this).blur();
			 		if(opts.trig)
			 		{
			 		$("#"+opts.clearid).trigger(opts.trig);	
			 		}
			 		// $("#"+opts.clearid).trigger("change");
			 	}else{
			 		$(this).removeAttr("selected");
			 	}
			});
			
		 })
		hook();
	}
	function eachselect(dat)
	{
		var ht = $("#"+opts.clearid);
		ht.each(function(){
			$(".ocze").html("");
			var i = 1;
			$(this).children("option").each(function(){
				if(i>opts.num)
				{
					return false;
				}
				var txt = $(this).text();
				var val = $(this).val();
				if(dat!="")
				{
					//字母转换大小写比较
					txt = txt.toLowerCase();
					dat = dat.toLowerCase();
					if (txt.indexOf(dat) >=0)
					{
						$(".ocze").append("<p class='getpp' v='"+val+"'>"+txt+"</p>");
						i++;
					}
				}
			});
			if($(".ocze").html() !="" )
			{
				$(".getpp").bind("click",function(event){
					 var e = $(this).attr("v");
					 setSelect(e);
					 $(".ocze").html("");
					 $(".ocze").css({"display":"none"});
				});
				$(".getpp").hover(function(){
					$(this).css({'background-color':opts.hovercolor,"cursor":"mouse"})
				},function(){
					$(this).css({'background-color':opts.backcolor})
				});
				$("#"+opts.clearid).blur(function(e){
					$("#"+opts.clearid).trigger("blurEvent");
				});
				$(".ocze").css({"display":"block"});
			}else{
				$(".ocze").css({"display":"none"});
			}
			

		});
		//主要控制键盘的上下操作就行了
		
	}
	
	_keydown = function()
	{
		this.e = "";
		var keys = {
		UP:38,
		DOWN:40,
		ESC:27   //返回的时候关闭窗口
		};
		this.chu = function(e){
			keypress = this.e.keyCode;
			switch(keypress)
			{
				case keys.UP:
					this.e.preventDefault();
					console.log("up");
					break;
				case keys.DOWN:
					this.e.preventDefault();
					console.log("down");
					break;
				case keys.ESC:
					$(".ocze").css({"display":"none"});
					this.e.preventDefault();
					break;
			}
		}
		
	}
	setE = function(e)
	{
		if(e)
		{
			this.e = e;	
		}
		
	}
	return this.each(function(){
		var $this = $(this);
		left = $this.position().left;

		$this.after("<div class='ocze'>haha</div>");
		$(".ocze").css({"background-color":opts.backcolor,"width":opts.width,"position":"absolute","display":"none","height":"100px","overflow":"auto"});//,"left":left+"px"

		$this.keyup(function(e){
			var dat = $.trim($this.val());
			eachselect.call("_keydown",dat);
			var keydown = new _keydown();
			var ev = new setE(e);
			keydown.chu.apply(ev);

		})
		
	})
	// this.call(eachselect);
	
}
})(jQuery)
