<?php
/**
 * 文件key-value缓存系统
 * 可进行所有读取
 * @author show<9448923@qq.com>
 * Date:2015.07.25
 */
class cls_fcache
{
    //一定要使用这个文件夹
    private $path = '/datacache/';

    private $filename = 'ca';

    private $indexfile;

    private $datafile;

    private $fp;

    private $fpc;

    protected $row = 1000;

    /**
    * 新建对象时
    * path指定路径
    */
    public  function __construct($name = '',$path= '' )
    {
        $this->setPath($path);
        if(!empty($name))
        {
            $this->filename = $name;
        }

        $this->indexfile = dirname(__FILE__).$this->path.$this->filename."_index.php";
        $this->datafile = dirname(__FILE__).$this->path.$this->filename."_cache.php";
        if(!file_exists($this->indexfile))
        {
            $this->_create();
        }
    }

    public function  setPath($path)
    {
        if(!empty($path))
        {
            $this->path = $path;
        }
    }
    /**
    * 创建缓存文件
    */
    public function _create()
    {
        $this->fp = fopen($this->indexfile,"wb+");
        $this->fpc = fopen($this->datafile,"wb+");
        @chmod($this->indexfile, 0777);
        //不排除不够权限创建
        if(!file_exists($this->indexfile))
        {
            return false;
            //die('文件缓存创建失败');
        }
        for($i=1;$i<=$this->row;$i++)
        {
            $pos = ($i-1) * 4096;
            fseek($this->fp,$pos);
            fwrite($this->fp, pack("l", 0));
        }
        rewind($this->fp);
        return $this->fp;
    }
    /**
    * 获取缓存
    */
    public function get()
    {
        
    }


}


class LinkList
{
    private $data;
    private $pre;
    private $next;
    private $size;
    public function __construct()
    {

    }
}