<?php
/**
 * php数据库处理类
 * Author:show(9448923@qq.com)
 * 目标：让数据处理更简单
 * Date:2015.04.05
 */
class cls_dbdao{
    protected $dbs;
    protected $_tableName = 'table';
    protected $_primaryKey = 'id';
    protected $fields;
    public $track,$debug;
    private $_containers;
    private $sql;
    public function __construct()
    {
        $this->_containers[] = 'mysql';
        $this->_containers[] = 'mysqli';
        // $this->_containers[] = 'mssql';
    }
    /** 切换多个驱动 */
    public function change($obj,$newval)
    {
        $title = $obj->title;
        foreach($this->_containers as $container)
        {
            if(!($obj instanceof $container))
            {
                $object = new $container;
                $object->title = $title;
                foreach($newval as $key=>$val)
                {
                    $object->$key = $val;
                }
                $object->get();
            }
        }
    }
    /**
     * 设置表名
     * @param  [type] $tb [description]
     * @return [type]     [description]
     */
    public function __setTable($tb)
    {
        $this->_tableName = $tb;
    }
    public function __clone()
    {
        $this->tabs = '1';
    }
    /**
     * 设置主键
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function __setPrimaryKey($id)
    {
        $this->_tableName = $id;   
    }
    /**
     * 选择字段
     * @param  string $string [description]
     * @return [type]         [description]
     */
    public function select($string = '*')
    {
        $this->sql = ''; //使用这种，sql不可以自定义
        $this->fields[] = "select {$string}";
        return $this;
    }
    public function __setsql($sql)
    {
        $this->sql = $sql;
    }
    /**
     * 从哪个表
     * @param  [type] $table [description]
     * @return [type]        [description]
     */
    public function from($table)
    {
        if($table)
        {
            self::__setTable($table);
            $this->_tableName = $table;
        }
        $this->fields[] = " from ".$this->_tableName;     
        return $this;   
    }
    public function accepVisitor($visitor)
    {
        $visitor->visitDB($this);
    }
    public function objget(db_base $cd)
    {

    }
    /**
     * 数量限制
     * @return [type] [description]
     */
    public function limit($li)
    {
        $this->fields[] = " limit ".$li;
        return $this;
    }
    /**
     * 左括号
     * @return [type] [description]
     */
    public function leftq()
    {
        $this->fields[] = " ( ";
        return $this;
    }
    /**
     * 右括号
     * @return [type] [description]
     */
    public function rightq()
    {
        $this->fields[] = " ) ";
        return $this;
    }
    /**
     * 左连接
     * @param  [type] $tb [description]
     * @return [type]     [description]
     */
    public function ljoin($tb)
    {
        $this->fields[] = " left join ".$tb;
        return $this;
    }
    /**
     * 右连接
     * @param  [type] $tb [description]
     * @return [type]     [description]
     */
    public function rjoin($tb)
    {
        $this->fields[] = " right join ".$tb;
        return $this;
    }
    /**
     * 查找
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function where($data)
    {
        $this->fields[] = " where ".$data;
        return $this;
    }
    /**
     * 链接条件 
     * @return [type] [description]
     */
    public function on()
    {
        $this->fields[] = " on ".$tb;
        return $this;
    }
    /**
     * 添加向日志
     * @param [type] $track [description]
     */
    public function addTrack($track)
    {
        $this->track[] = $track;
    }
    /**
     * 输出sql日志
     * @return [type] [description]
     */
    public function getTrackList()
    { 
        foreach($this->track as $num=>$track)
        {
            $output .= "<p>".$track."</p>";
        }
        call_user_func(array("log","write"),$output);
        return $output;
    }
    /**
     * mysql防注入
     * @param  [type] $str [description]
     * @return [type]      [description]
     */
    public function safe($str)
    {
        $str = mysql_real_escape_string($str);
        return $str;
    }
    /**
     * 获取数据
     * 单向语句
     * @return [type] [description]
     */
    public function get()
    {
        if($this->sql!='')
        {
            return '';
        }
        if($this->fields)
        {
            $sql = '';
            foreach($this->fields as $key=>$val)
            {
                $sql .= $val;
            }
            // $sql = $sql." from ".$this->_tableName;
        }else{ 
            $sql = "select * form ".$this->_tableName;
        }
        // echo "<!--".$sql."-->";
        return $sql;
    }
    public function getOne()
    {
        $sql = self::get();
        $data = '';// getone 取一条
        return $data;
    }
    /**
     * 直接输出sql语句 
     * 方便使用
     * @return [type] [description]
     */
    public function getSql()
    {
        $sql = self::get();
        echo $sql;  
    }
    /**
     * 获取所有数据
     * @return [type] [description]
     */
    public function getArray()
    {
        $sql = self::get();
        $data = ;// get_all 获取所有
        return $data;
    }

}