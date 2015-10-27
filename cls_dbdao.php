<?php
/**
 * php���ݿ⴦����
 * Author:show(9448923@qq.com)
 * Ŀ�꣺�����ݴ������
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
    /** �л�������� */
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
     * ���ñ���
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
     * ��������
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function __setPrimaryKey($id)
    {
        $this->_tableName = $id;   
    }
    /**
     * ѡ���ֶ�
     * @param  string $string [description]
     * @return [type]         [description]
     */
    public function select($string = '*')
    {
        $this->sql = ''; //ʹ�����֣�sql�������Զ���
        $this->fields[] = "select {$string}";
        return $this;
    }
    public function __setsql($sql)
    {
        $this->sql = $sql;
    }
    /**
     * ���ĸ���
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
     * ��������
     * @return [type] [description]
     */
    public function limit($li)
    {
        $this->fields[] = " limit ".$li;
        return $this;
    }
    /**
     * ������
     * @return [type] [description]
     */
    public function leftq()
    {
        $this->fields[] = " ( ";
        return $this;
    }
    /**
     * ������
     * @return [type] [description]
     */
    public function rightq()
    {
        $this->fields[] = " ) ";
        return $this;
    }
    /**
     * ������
     * @param  [type] $tb [description]
     * @return [type]     [description]
     */
    public function ljoin($tb)
    {
        $this->fields[] = " left join ".$tb;
        return $this;
    }
    /**
     * ������
     * @param  [type] $tb [description]
     * @return [type]     [description]
     */
    public function rjoin($tb)
    {
        $this->fields[] = " right join ".$tb;
        return $this;
    }
    /**
     * ����
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function where($data)
    {
        $this->fields[] = " where ".$data;
        return $this;
    }
    /**
     * �������� 
     * @return [type] [description]
     */
    public function on()
    {
        $this->fields[] = " on ".$tb;
        return $this;
    }
    /**
     * �������־
     * @param [type] $track [description]
     */
    public function addTrack($track)
    {
        $this->track[] = $track;
    }
    /**
     * ���sql��־
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
     * mysql��ע��
     * @param  [type] $str [description]
     * @return [type]      [description]
     */
    public function safe($str)
    {
        $str = mysql_real_escape_string($str);
        return $str;
    }
    /**
     * ��ȡ����
     * �������
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
        $data = '';// getone ȡһ��
        return $data;
    }
    /**
     * ֱ�����sql��� 
     * ����ʹ��
     * @return [type] [description]
     */
    public function getSql()
    {
        $sql = self::get();
        echo $sql;  
    }
    /**
     * ��ȡ��������
     * @return [type] [description]
     */
    public function getArray()
    {
        $sql = self::get();
        $data = ;// get_all ��ȡ����
        return $data;
    }

}