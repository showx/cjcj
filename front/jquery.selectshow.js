/**
* jquery option选择
* input搜索值，查找出select中的值
* version 2.0
* date:2015.09.14
* Author:show(9448923@qq.com)
*/

(function($){
	var d = function(result)
	{
		console.log(result);
	}
	var selectx = function()
	{
           
	}
	var methods = {
		init:function(options)
		{
			var $this = $(this);
			var settings = $this.data('selectshow');
			if(typeof(settings) == 'undefined'){
				var defaults = {
					"sid":'sid',
					"width":"300px",
					doSomeEvent:function(){}
				}
				settings = $.extend({},defaults,options);
				$this.data('selectshow',settings);
			}else{
				settings = $.extend({},settings,options);
			}
			return this.each(function(options){

			            var source = $("#"+settings.sid);
			            var selected = source.find("option[selected]");
			            var options = $("option", source);
			            if(!selected.val())
			            {
			              selected = source.find("option:first");
			            }
			            $(this).after("<div id='target"+settings.sid+"' style='display:inline-block;height:15px;width:"+settings.width+"'></div>");
			            source.hide();  //隐藏原来的
			            $("#target"+settings.sid).append('<dl id="targets'+settings.sid+'" class="dropdown"></dl>');
			            var w = parseInt(settings.width)-8+"px";
			            $("#targets"+settings.sid).append('<dt><input style="width:'+w+'" type="text" autoComplete="Off" id="x'+settings.sid+'" name="x" value="' + selected.text() + ' " />' +
			                '<span class="value">' + selected.val() + 
			                '</span></dt>')
			            $("#targets"+settings.sid).append('<dd><ul></ul></dd>')

			            options.each(function(){
			                $("#target"+settings.sid+" dd ul").append('<li><a href="#">' + 
			                    $(this).text() + '<span class="value">' + 
			                    $(this).val() + '</span></a></li>');
			            });
                  //普通的录入 录入的值也要查询
			            $("#x"+settings.sid).keyup(function(){
                    v = $(this).val();
                    source.val(v);
			            })
			            //双击出现下拉
			           $("#x"+settings.sid).dblclick(function() {
			                $("#targets"+settings.sid+" dd ul").toggle(); //.dropdown dd input
			                // $(this).focus(function(){
			                // 	$(this).val("");
			                // })
			                if($("#targets"+settings.sid+" dd ul").is(":hidden"))
			                {
			                	$(this).unbind("keyup");
			                }else{
			                	$(this).val("");
			                	$(this).bind("keyup",function(){
			                		var v = $(this).val();
			                		// d(v);
			                		var tmpc = 0;

			                		var o = {};
		                			o.scroll = true;
		                			o.padding = true;
		                			o.margin = true;
		                			o.border = true;

			                		$("#targets"+settings.sid+" dd ul a").each(function(i,data){
			                			// tmp = $(this).children("span").text();
			                			
			                			// txt = $(this).text();  //.fliter(".value")
			                			txt = $(this).html();
			                			var re = /<span class="value">(.+)<\/span>/;
			                			txt = txt.replace(re,"");
			                			//test
			                			// d(txt);
			                			// d("====");
			                			// d(v);
			                			// d(tmpc);
			                			if(txt.indexOf(v)>-1 && tmpc==0) //txt.indexOf(v)>0
			                			{
			                				$(this).css({'background-color':'#ffffff',"cursor":"mouse"})
			                				// d(data);
			                				tmpc = i;

				                			scro = $(this).parent().offset(); //scrollTop scrollLeft
				                			var s = $("#targets"+settings.sid+" dd ul").scrollTop();
				                			var t = scro.top + s - 43;

				                			$("#targets"+settings.sid+" dd ul").scrollTop(t);

			                			}else{
			                				$(this).css({'background-color':'#C5C0B0',"cursor":"mouse"})
			                			}
			                			// d($(this));
			                		})
			                	})

			                }
			            });
			            $("#targets"+settings.sid+" dd ul li a").click(function() {
			            	 tt = $(this).children("span");
			            	 tt.remove();
			                var text = $(this).html();
			                $(this).append(tt);
			                // d(text);
			                $("#targets"+settings.sid+" dt input").val(text);
			                $("#targets"+settings.sid+" dd ul").hide();

			                var source = $("#"+settings.sid);
			                source.val($(this).find("span.value").html())
			            });
			            $(document).bind('click', function(e) {
			                    var $clicked = $(e.target);
			                    if (! $clicked.parents().hasClass("dropdown"))
			                        $("#targets"+settings.sid+" dd ul").hide();
			                });
				

			});
		},
		destory:function(options){
			return $(this).each(function(){
				var $this = $(this);
				$this.removeData('selectshow');
			})
		},
		val:function(options){
			d("val");
			var settings = $(this).data('selectshow');
			var someValue = this.eq(0).html();
			return someValue;
		}
	}
	$.fn.selectshow = function(){
		var method = arguments[0];
		if(methods[method])
		{
			method = methods[method];
			arguments = Array.prototype.slice.call(arguments,1);
		}else if(typeof(method) == 'object' || !method )
		{
			method = methods.init;
		}else{
			$.error('Method' + method + 'does not on jQuery.selectshow');
			return this;
		}
		return method.apply(this,arguments);
	}

})(jQuery)
