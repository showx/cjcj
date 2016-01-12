#! /usr/bin/php -c /etc/php/php-cli.ini
<?php
/**
* 获取内容使用
* WEB抓虫，精确到下载图片 
* Author:show(9448923@qq.com)
* Date:2014.xx.xx
*/
date_default_timezone_set("PRC");
define('PHPSHOW', dirname(__FILE__));

//底下追加内容
$frame_test = '';

function preg($gui,$data,$a='',$url='',$off=PREG_PATTERN_ORDER)
{
        if($url)
        {
            $data = self::http_get($url);
            if(!$this->isutf8)
            {
              $data = self::getToUtf8($data,false);
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


function xx($id='')
{
	$file = file_get_contents("http://www.xx.com/?id=".$id);
	//静态的JS和CSS不考虑
	$img = '/<img.*?src=[\'\" ]?(.+?)[\'\" ]+.*?[\/|>]+/is'; 
	$imgs = preg($img,$file,'all');
	if($imgs)
	{
		$date = date("Ymd");
		if(!file_exists("./img/".$date))
		{
			@mkdir("./img/".$date."/"); //简单处理，放在同一文件夹即可
		}
		$path = "/img/".$date;
		foreach($imgs[1] as $key => $val)
		{
			//放在统一的文件夹里面就可以了
			//$name = basename($val);
			$name = pathinfo($val);
			$name = $name['basename'];
			$filepath = $path."/".$name;		

			//echo PHPSHOW.'__'.$filepath."\r\n";
			if($val)
			{
				if(!stristr($val,'http'))
				{
					$val = "http://www.xx.com".$val;
				}
				$tmp = file_get_contents($val);  //实际使用curl更好
				file_put_contents(PHPSHOW.$filepath, $tmp);
				$file = str_replace($val,$filepath,$file);
			}

		}
		
	}
	$file = str_replace('</body>', $GLOBALS['frame_test'].'</body>', $file);
	file_put_contents(PHPSHOW."/xb{$id}.html", $file);
}
$catalog =  array("","176","180","181","204");
foreach($catalog as $key => $val)
{
	xx($val);
}

