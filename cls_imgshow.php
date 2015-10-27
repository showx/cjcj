<?php
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
	public $ext = array("gif","jpg","webp","png"); //string
	public $type = 1;//1.输出目标文件　2.直接使用源文件
	public $path;  //图片文件夹
	//指定路径
	public function __construct($file,$path="")
	{

		if(empty($path))
		{
			$this->path = dirname(__FILE__)."/img/";
		}else{
			$this->path = $path;
		}

		$this->file = $this->path.$file;
		if(file_exists($this->file))
		{

			list($this->width, $this->height, $this->ext) = getimagesize($this->file);	
		}

	}
	/**
	* 生成指定缩略图
	*/
	public function thumb($w,$h,$model)
	{

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
	public function water()
	{

	}


}
