/** 
 * 表格控制器
 * 不是太长列，性能没影响
 * 暂时只隐藏显示列为主
 * Author:show(9448923@qq.com)
 * Date 2015.12.07
 * ==支付h5,拒绝ie==
 */
(function($){
  /**
   * 调试方法
   */
  function d(v,type)  
  {
    if(type == 2)
    {
      alert(v);
    }else{
      console.log(v);
    }
  }
  var methods = {
    //设置初始属性
    init:function(options)
    {
      	var $this = $(this);
			  var settings = $this.data('tableshow');
			  if(typeof(settings) == 'undefined'){
			  	var defaults = {
            'lie' : '1',  //隐藏第几列
            'table' : 'tablex', //隐藏和显示的按钮
            'hhide' : 'tt'    //隐藏域
			  	}
			  	settings = $.extend({},defaults,options);
			  	$this.data('tableshow',settings);
			  }else{
			  	settings = $.extend({},settings,options);
			  }
        obj  =  document.getElementById(settings.table);
        //rows是行 cells是列
        //隐列
        function  hide(cell)
        {
                for(i=0;i<obj.rows.length-1;i++)
                {
                    if(obj.rows[i].cells[cell])
                    {
                      obj.rows[i].cells[cell].style.display  =  "none";
                    }
                }
        }
        //显列
        function  show(cell)
        {
                for(i=0;i<obj.rows.length-1;i++)
                {
                    //block可能占据一行
                    if(obj.rows[i].cells[cell])
                    {
                      obj.rows[i].cells[cell].style.display  =  "table-cell";
                    }
                }
        }

        //改变table显示
        function change()
        {
          //总列数
          cells = obj.rows.item(0).cells.length; 
          hinput = $("#"+settings.hhide).val();
          //隐藏所有，显示要用的字段就可以了
          for(j=0;j<cells;j++)
          {
            hide(j);
          }
          if(hinput =='0' || hinput == '')
          {
            //值为空不处理
            // return '';
            for(j=0;j<cells;j++)
            {
              show(j);
            }
          }else{
            //计算要显示的列并显示 
            adata = hinput.split(",");
            adata.forEach(function (item, index, array) {
              show(item);
            });
          }

        }

        return this.each(function(){
             //刚加载也改变一下
             change();
          $(this).click(function(){
             change(); 
          });  
        });

    }
    
  }

  //设置tableshow插件
  $.fn.tableshow = function(){
		var method = arguments[0];
		if(methods[method])
		{
			method = methods[method];
			arguments = Array.prototype.slice.call(arguments,1);
		}else if(typeof(method) == 'object' || !method )
		{
			method = methods.init;
		}else{
			$.error('Method' + method + 'does not on jQuery.tableshow');
			return this;
		}
		return method.apply(this,arguments);
	}


})(jQuery)
