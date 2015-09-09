/**
* jquery option选择
* date:2015.09.09
* Author:show(9448923@qq.com)
*/
(function($){

$.fn.optionclear  = function (options,callback){
	// var method = arguments[0];
	// console.log(method);
	var defaults = {
		"num":"10",
		"clearid":"",
		"hook":"",
		"backcolor":"#FFFAFA",
		"hovercolor":"#FF1493",
		"trig":"change",
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
	//apply
	function eachselect(dat)
	{
		// alert('haha');
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
					if (txt.indexOf(dat) >=0)
					{
						$(".ocze").append("<p class='getpp' v='"+val+"'>"+txt+"</p>");
						i++;
					}
				}
				
				
			})
			if($(".ocze").html() !="" )
			{
				$(".getpp").bind("click",function(event){
					 var e = $(this).attr("v");
					 // d(e);
					 setSelect(e);
					 // $(".ocze").css({"display":"none"});
					 $(".ocze").html("");
					 
				});
				$(".getpp").hover(function(){
					$(this).css({'background-color':opts.hovercolor,"cursor":"mouse"})
				},function(){
					$(this).css({'background-color':opts.backcolor})
				});
			}
			$(".ocze").css({"display":"block"});
		})
	}
	return this.each(function(){
		var $this = $(this);
		left = $this.position().left;

		$this.after("<div class='ocze'>haha</div>");
		$(".ocze").css({"background-color":opts.backcolor,"width":"100px","position":"absolute","display":"none"});//,"left":left+"px"

		$this.keyup(function(){
			var dat = $.trim($this.val());
			eachselect(dat);
		})
		
	})
	// this.call(eachselect);
	
}
})(jQuery)
