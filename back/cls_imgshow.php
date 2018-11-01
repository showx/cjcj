<?php
//方便调试
function d($ver)
{
	echo '<pre>';
	var_dump($ver);
}
/**
 * 图像处理使用库
 * 生成缩略图
 * 生成指定宽高,可按模式进行栽剪
 * Author:show(9448923@qq.com)
 * Date:2015.10.26
 */
Class imgshow
{
	//图片文件
	public $file;
	//目标文件，可以是本图片
	public $dfile ;  
	//图片宽度
	public $width;
	//图片高度
	public $height;
	//固定高宽，太大会内在溢出
	public $guwidth = 10000;
	public  $guheight = 10000;
	//图片加文字
	public $text;
	//使用模式
	public $model = 1;
	//类型 string
	public $ext = array("gif","jpg","webp","png"); 
	//1.输出目标文件　2.直接使用源文件
	public $type = 1;
	//图片文件夹
	public $path;  
	//读取到力图片内容
	public $img;
	//检测到出错情况
	public $wrong = false;  

	//指定路径
	public function __construct($file,$path="")
	{

		if(empty($path))
		{
			$this->path = dirname(__FILE__)."/img/";
		}else{
			$this->path = $path;
		}
		$this->file = $this->path."/".$file;
		$tmp = getimagesize($this->file);
		d($tmp);
		if(file_exists($this->file))
		{
			//有一定性能 $this->ext (int) 也是常量
			list($this->width, $this->height, $this->ext) = getimagesize($this->file);	//imagex imagey  ,$html,$bits,$channel,$mime
		}

		if($this->width >= $this->guwidth || $this->height >= $this->guheight)
		{
			$this->wrong = true;
			return '';
		}

		$func = array("jpg"=>"imagecreatefromjpeg","png"=>"imagecreatefrompng","gif"=>"imagecreatefromgif");
		//if($mime== 'image/jpeg')
		switch($this->ext) {  //imagetypes 
			case IMAGETYPE_JPEG :
			$this->ext='jpg';
			break;
			case IMAGETYPE_PNG :
			$this->ext='png';
			break;
			case IMAGETYPE_GIF :
			$this->ext='gif';
			default:  
			$this->ext = 'none';
		}
		$this->img =  call_user_func($func[$this->ext],$this->file);
		d($img);

	}
	/**
	* 生成指定缩略图
	*/
	public function thumb($w,$h,$model)
	{

	}
	/**
	* 输出或保存图像
	*/
	public function display($out=false)
	{
		if($out)
		{
			switch($this->ext) {
			case 'jpg' :
				@imagejpeg($this->img,$this->dfile,100);
				break;
			case 'png' :
				@imagepng($this->img,$this->dfile);
				break;
			case 'gif' :
				@imagegif($this->img,$this->dfile);
				break;
			default:
				break;
			}
		}else{
			switch($this->ext) {
				case 'jpg' :
					header('Content-type: image/jpeg');
					imagejpeg($this->img);
					break;
				case 'png' :
					header('Content-type: image/png');
					imagepng($this->img);
					break;
				case 'gif' :
					header('Content-type: image/gif');
					imagegif($this->img);
					break;
				default:
					break;
			}
		}

		
	}

	/**
	* 设置宽高
	*/
	public function setGu($width,$height)
	{
		$this->guwidth = $width;
		$this->guheight = $height;
	}
	/**
	* 设置模式
	*/
	public function setModel($model=1)
	{
		$this->model = $model;
	}

	/**
	* 添加水印专用
	*/
	public function water($logo)
	{

	}


}
