<?php
/**
 * 代码format
 * Author:show(9448923@qq.com)
 * Date:2016.1.11
 */
class cls_format
{
    /**
     * json to array
     */
    public function jtoa($a)
    {
        $a = json_decode($a,true);
        echo "====================\n";
        echo "array(\n";
        foreach($a as $key=>$val)
        {
               self::garr($val,"\$arr['$key']"); 
        }
       echo ")";

    }
    public function jtoc($a)
    {
        foreach($a as $key=>$val)
        {
               self::garr($val,"\$arr[$key]"); 
        }
    }
    /**
     * 数组配置文件式
     */    
    public function xarr($arr,$e='')
    {
        if(is_array($arr))
        {
            foreach($arr as $k=>$v)
            {
              $kk = $e."['".$k."']";
              self::xarr($v,$kk);
            }
        }else{
            echo $e."=\"".$arr."\";\n";
        }
    }  
    /**
     * 一行输出数组
     */
    public function harr($arr)
    {
        if(is_array($arr))
        {
            echo "array(";
            foreach($arr as $k=>$v)
            {
                if(!is_array($v))
                {
                    echo "\"{$k}\"=>\"{$v}\",";
                }
                self::harr($v);
            } 
            echo "),\n";
        }
    }
    /*
     * json to array
     */
    public function garr($arr,$i=1)
    {
        $str = str_repeat(" ",$i);
        $str2 = str_repeat(" ",$i+1);
        if(is_array($arr))
        {
            echo "\n$str array(\n";
            foreach($arr as $k=>$v)
            {
                echo $str2.'"'.$k."\"=>";
                if(is_array($v))
                {
                    $j = $i+2;
                }else{
                    $j = $i;
                }
                self::garr($v,$j);
            }   
            echo $str."),\n";
        }else{
            echo '"'.$arr.'",'."\n";
        }
    }

}
