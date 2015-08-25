<?php
/**
* 采集类
* Author:show
*/ 
// echo '开始采集';
error_reporting(E_ALL);
header("Content-type: text/html; charset=utf-8");    
class btget{
	public $dirname,$url,$path;
	public $content;
	public $hr;
	public $styleurl;
	
	public function __construct($url = '')
	{
		$this->path =  dirname(__FILE__);
		$this->url = $url;
		if( PHP_SAPI == 'cli' )
		{
			$this->hr = "\r\n";
		}else{
			$this->hr = "<br/>";
		}
		$this->hr = "\n"; // 这里强制\n
	}
	public function setStyleUrl($url='')
	{
		$this->styleurl = $url;
	}
	/**
	* 现在只能采/根
	*/
	public function start()
	{
		//采集本页面

		$dir = pathinfo($this->url);
		if(!stristr($this->url,"http"))
		{
			$this->dirname = '';
		}else{
			$this->dirname = $dir['dirname'];	
		}
		$name = $dir['basename'];
		// $name = basename($this->url); // $name = pathinfo($this->url);
		// if(!file_exists($name))
		// {
		// 	file_put_contents($name, $index);	//为了方便 
			$index = file_get_contents($this->url);
		// }else{
			// $index = file_get_contents($name);
		// }
		$this->content = $index;
		//下载JS 
		self::getJs();
		self::getCss();
		//img也要下载下来。
		//下载完之后，处理页面链接之类的
		self::pagereplace();
		file_put_contents($name, $this->content);	
	}
	/**
	* 替换相关链接
	*/
	public function pagereplace()
	{
		$this->content = str_replace($this->dirname,"",$this->content);
		$this->content = preg_replace('/<a(.*?)href="(.*?)"/is','<a$1href="#"',$this->content);
		$img = '/<img.*?src=[\'\" ]?(.+?)[\'\" ]+.*?[\/|>]+/is'; 
		if($this->styleurl)
		{
			$this->content = str_replace($this->styleurl, "", $this->content);
		}

	}
	/**
	* 获取CSS
	*/
	public function getCss()
	{
		$content = $this->content;


		preg_match_all('/<link[^>]+?href="([^> && ^" ]*)?"[^>]*?>/is',$content,$tt,PREG_PATTERN_ORDER);
		// var_dump($tt);exit();
		self::putfile($tt);
		//下载之后，检查有没image需要替换
		if($tt['1'])
		{
			foreach($tt['1'] as $key=>$val)
			{
				self::cssimg($val);
			}
		}
		 

	}

	public function cssimg($val)
	{
		//有可能为ico,所以要检查一下
				if(stristr($val,'css')) 
				{
					$url = str_replace($this->dirname,'',$val);
					//替换styleurl相关
					if($this->styleurl)
					{
						$url = str_replace($this->styleurl, "", $url);
					}

					if(substr($url,0,1)=='/')
					{
						$url = substr($url,1);
					}
					$path = explode("/", $url);
					$c = count($path) - 1;
					unset($path[$c]);
					$data = file_get_contents($url);
					//../images/logo.png
					//background:url的情况也有
					preg_match_all('/[background-image|background]:url\((.*)?\)/isU',$data,$cssimage,PREG_PATTERN_ORDER);
					$cssimage = array_unique($cssimage['1']);
					var_dump($cssimage);
					foreach($cssimage as $img)
					{
						$img = str_replace("../", "", $img,$count);
						if($count)
						{
							$a = $path;
							for($i=0;$i<$count;$i++)
							{
								array_pop($a);
								$dimg = '/';
								foreach($a as $p)
								{
									$dimg .= $p."/";
								}
								$dimg .= $img;
								self::dir_create($this->path.$dimg);
								// echo $this->dirname.$dimg."<br/>\n";
								$filec = file_get_contents($this->dirname.$dimg);
								file_put_contents($this->path.$dimg, $filec);
							}
						}else{
							$filec = file_get_contents($img);
							if($this->styleurl)
							{
								$img = str_replace($this->styleurl, "", $img);
							}
							self::dir_create($this->path.$img);
							file_put_contents($this->path.$img, $filec);
						}
						
					}
				}
	}

	/**
	* 替换JS
	*/
	public function getjs()
	{
		$content = $this->content;
		preg_match_all('/<script[^>]+?src="([^> && ^"]*)?"[^>]*?><\/script>/is',$content,$tt,PREG_PATTERN_ORDER);
		self::putfile($tt);
	}
	/**
	* 存放文件
	*/
	public function putfile($tt)
	{
		if(!empty($tt['0']))
		{
			foreach($tt['1'] as $key=>$val)
			{
				$url = str_replace($this->dirname,'',$val);
				//替换styleurl相关
				if($this->styleurl)
				{
					$url = str_replace($this->styleurl, "", $url);
				}
				$path = $this->path.$url;
				// $path = $url;
				// echo $path."\n<br/>";
				self::dir_create($path);
				if(!stristr($val,'http'))
				{
					$val = $this->dirname.$val;
				}
				$data = file_get_contents($val);
				file_put_contents($path,$data);
			}
		}
	}
	public function setString($string)
	{
		if(!empty($string))
		{
			$this->content = $string;
		}
	}

	public function setUrl($url='',$isutf8 = false)
	{
		$this->content = file_get_contents($url);
		if($isutf==true)
		{
			$this->content = self::getToUtf8($this->content);	
		}
	}
	public function select($who)
	{
		$this->selector = substr($who,0,1);
		if($this->selector=='#')
		{
			$who = str_replace("#","",$who);
			$this->selector = "id=['|\"]{$who}['|\"]";
		}elseif($this->selector=='.')
		{
			$who = str_replace(".","",$who);
			$this->selector ="class=['|\"]{$who}['|\"]";
		}
		return $this;
	}

	public function getToUtf8($content,$get_url=false)
    {
        if($get_url)
        {
            $content = self::http_get($content);    
        }
        $content = mb_convert_encoding($content,'UTF-8','GB2312');
        // $iconv = new cls_doiconv();
        // $content=$iconv->Convert("GB2312","UTF8",$content);
        return $content;
    }

	public function getContent()
	{
		
		$biao = "<(\w+)\s[^>]*?{$this->selector}[^>]*?>"; //\s?
		$str = "/{$biao}/isU";
		// echo $str."<br/>";
		

		$biaozhi = self::preg($str,$this->content,'all');
		

		$biaozhi['0'] = array_unique($biaozhi['0']);
		foreach($biaozhi['0'] as $kkk =>$vvv)
		{
			$btmp[$kkk] = $biaozhi['1'][$kkk];	
		}
		$biaozhi['1'] = $btmp;
		
		$biaozhi['0'] = array_values($biaozhi['0']);
		$biaozhi['1'] = array_values($biaozhi['1']);

		// var_dump($biaozhi);
		// exit();

		$cou = count($biaozhi['0']);
		for($x=0;$x<$cou;$x++)
		{
			$biaozhiqian = $biaozhi['0'][$x];
			$biaozhi2 = $biaozhi['1'][$x];
			// echo $biaozhiqian."===".$biaozhi2."$$$$$$$".$this->hr;
			// echo "--------------------------------------".$this->hr;
			self::bu($biaozhiqian,$biaozhi2);
			
		}

		/*
		echo "======================================<br/>";
		// $shi2 = "<div>(.*?)<\/div>";
		preg_match_all("/{$shi2}/is",$this->content,$t2);
		var_dump($t2);
		echo 'why';
		*/
	}

	public function bu($biaozhiqian,$biaozhi)
	{
		// echo "===".$biaozhiqian.$this->hr;
		$shi = "<{$biaozhi}\s[^>]*?{$this->selector}(.*)<\/{$biaozhi}>";
		// $shi2 = "<{$biaozhi}\s[^>]*?{$this->selector}(.*?)<\/{$biaozhi}>";
		// echo $shi."<br/>";
		preg_match_all("/{$shi}/is",$this->content,$t);
		//preg_quote
		$t = $t[0][0];
		// var_dump($t);exit();
		// $shu = preg_split("/{$this->selector}/",$t);
		$shu = split($biaozhiqian,$t);
		// var_dump($shu);exit();
		if(empty($shu['0']))
		{
			unset($shu['0']);
		}
		$shu = array_values($shu);
		$len = count($shu);
		foreach($shu as $kk=>$vv)
		{
			for($i=$kk;$i<$len;$i++)
			{
				$cc[$kk] .= $biaozhiqian.$shu[$i];
			}
		}	
		// var_dump($cc);exit();
		foreach($cc as $ck=>$cv)
		{
			self::fx($cv);
		}
		// echo 'why';
	}
	public function fx($t)
	{
		$datalen = preg_split("/</",$t);
		unset($datalen['0']);

		$datalen = array_values($datalen);
		preg_match_all('/<\/?([\w])+.*?>/is',$t,$tt);
		$tt = $tt['0'];
		// var_dump($tt);
		foreach($tt as $key=>$val)
		{
			$huan[$key] = self::preg("/<([\/?|\w]+).[^>]*?/is",$val);
		}
		$j = 0;
		while(1)
		{
			if($j>10)  //大于10次，最大限度了
			{
				break;
			}else{
				$j++;
			}
			$huan = self::jsbz($huan);	
			if(!is_array($huan))
			{
				break;
			}
		}
		// var_dump($j,$huan);exit();  

		//出现img,font 单结束的情况

		if(!is_array($huan))
		{
			for($i=0;$i<=$huan;$i++)
			{
				$string .= "<".$datalen[$i];
			}
			echo "--------------------------------------".$this->hr;
			echo "查找出数据：".$string;
		}
		
	}

	public function jsbz($huan)
	{
		$what = array("img","real","adpos");
		$c = count($huan);
		$keys = array_keys($huan);

		//第一个为标准 
		for($i=0;$i<=$c;$i++)
		{
			$key = $keys[$i];
			$key2 = $keys[$i+1];

			if(in_array($huan[$key],$what))
			{
				unset($huan[$key]);
			}

			if(substr($huan[$key2],0,1)=="/")
			{
				$aa = str_replace("/","",$huan[$key2]);
				// echo $huan[$i]."======".$aa.$hr;
				if($huan[$key]==$aa && !empty($huan[$key2]))
				{
					// echo $huan[$key]."======".$huan[$key2].$this->hr;
					if($key!=0)
					{
						unset($huan[$key],$huan[$key2]);	
					}else{
						// $jie = $huan[$key2];
						$jie = $key2;
					}

				}
			}
		}
		if($jie)
		{
			// echo 'one'.$hr;
			return $jie;
		}else{
			// echo 'two'.$hr;
			return $huan;
		}
	}
	/**
     * 正则辅助函数
     * @param  [type] $gui  [description]
     * @param  [type] $data [description]
     * @param  string $a    [description]
     * @param  off PREG_OFFSET_CAPTURE|PREG_SET_ORDER|PREG_PATTERN_ORDER 
     * @return [type]       [description]
     */
    public function preg($gui,$data,$a='',$url='',$off=PREG_PATTERN_ORDER)
    {
        if($url)
        {
            $data = self::http_get($url);
            if(!$this->isutf8)
            {
              $data = util::getToUtf8($data,false);
            }
        }
        if($a=='all')
        {
            preg_match_all($gui,$data,$return,$off);  
        }else{
            preg_match($gui,$data,$return);  
        }
        if(isset($return[1]))
        {
            if($a=='all')
            {
                return $return;
            }else{
                return trim($return[1]);   
            }
        }else{
            return '';
        }
        
    }
    /**
    * web检测目录，没有并创建
    */
    public function dir_create($path, $mode = 0777)
    {
        if(is_dir($path)) return true;
        $path = self::dir_path($path);
        $temp = explode('/', $path);
        $cur_dir = '/';  //这里使用/，从硬盘地址开始
        $max = count($temp) - 1;
        for($i=0; $i < $max; $i++)
        {
            $cur_dir .= $temp[$i];
            // echo $cur_dir."\n";
            if( is_dir($cur_dir)  ) {
                $cur_dir .= '/';
                continue;
            }
            if(stristr($cur_dir,"."))
            {
            	continue;
            }
            echo "$$$".$cur_dir."=====\n<br/>";
            if($cur_dir)
            {
            	  if( mkdir($cur_dir) ) 
            	  {
                chmod($cur_dir, $mode);
               }
               $cur_dir .= '/';
            }
            
        }
        return is_dir($path);
    }
    public function dir_path($path)
    {
        $path = str_replace('\\', '/', $path);
        if(substr($path, -1) != '/') $path = $path.'/';
        return $path;
    }


}

/*
$cj = new cj();
$cj->setString('
<div id="woshiid">
  <div class="p1" >
  <div>这是一个DIV</div>
  <div>这是一个DIV2</div>
  <div class="p3">abcde<p>asldjfk</p>fg3</div>
  <div class="p1" >abcd<div>我不好</div>efg4</div>
  <p class="p1" >abcd<div>你好啊</div>efg4</p>
  <p>abcdefg</p>
</div>
<div class="p3">
  <div>haha</div>
  <p>asldfjalsj<span>aaa</span></p>
</div>
</div>
');
$cj->select(".p1")->getContent();
$cj->select("#woshiid")->getContent();
// $cj->setUrl("http://mobile.pconline.com.cn/668/6686077.html",true);
// $cj->setUrl("a.html"); //网络差，下载本地试就行了 http://mobile.pconline.com.cn/668/6686077.html
// $cj->select(".content")->getContent();
*/