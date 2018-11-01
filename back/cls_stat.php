<?PHP
/**
* 简单web统计
* Author:show(9448923@qq.com)
*/
class cls_stat{
    public $remote_host; //ip
    public $referer;
    public $user_agent;
    public $request;
    public $pid = "";
    public function __construct($pid='',$stat=false)
    {
        if($stat == false)
        {
            if(!empty($pid))
            {
                $this->pid = $pid;
            }
            if (isset($_SERVER['REMOTE_ADDR']))
                $this->remote_host    = $_SERVER['REMOTE_ADDR'];
            else
                $this->remote_host    = "-";
            if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                $this->remote_host = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (isset($_SERVER['HTTP_REFERER']))
                $this->referer    = $_SERVER['HTTP_REFERER'];
            else
                $this->referer    = "-";
            if (isset($_SERVER['HTTP_USER_AGENT']))
                $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
            else
                $this->user_agent = "-";
            $this->request    = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        }
        
    }
    /**
    * 存放
    */
    public function putfile($allinfos)
    {
            $tj_time = time();
            $path = dirname(__FILE__)."/stat/".date('Y-m-d', $tj_time)."/";
            if( !is_dir($path) ) {
                mkdir($path, 0777);
            }
            if(!empty($this->pid))
            {
                $tdfilename = $path.$this->pid.'.log';
            }else{
                $tdfilename = $path.date('Y-m-d-H', $rtime).'.log';
            }
            file_put_contents($tdfilename, $allinfos."\n", FILE_APPEND|LOCK_EX);
    }
    /**
    * 设置pid的值
    */
    public function setPid($pid="")
    {
        if(!empty($pid))
        {
            $this->pid = $pid;    
        }
    }
    /**
    * 记录日志
    */
    public function trackPageView()
    {
        $infos['pid'] = $this->pid;
        // $infos['wh']        = req::item("s",""); //分辨率
        $infos['ip']        = $this->remote_host;
        $infos['uid']       = md5($infos['ip'].$_SERVER['HTTP_USER_AGENT'].','.$_SERVER['HTTP_ACCEPT'].','.$_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $infos['agent']     = $this->user_agent;
        $infos['refer']     = $this->referer;
        $infos['rtime']     = time();
        $infos['request'] = $this->request;
        $allinfo = json_encode($infos);
        self::putfile($allinfo);
    }
    /**
    * 处理相关数据
    * DAYOFMONTH
    * 处理日期相关
    * 这里提取一个思想，不移动文件夹
    * 移动去bak也是一样的。
    */
    public function chuDay($date="")
    {
        $path = dirname(__FILE__)."/stat/".$date."/";
        $dh = dir( $path );
        while( $filename = $dh->read() )
        {
            if( !preg_match('/\.log/', $filename) ) continue;
            $fp = fopen( $path.'/'.$filename,'r');
            $pid = basename($filename,'.log');
            $result['uv'] = $result['pv']  = 0;
            $tmp = $ip = array();
            while($data = fgets($fp))
            {
                $data = json_decode($data,true);
                $uhash = md5($data['ip'].$data['agent']);
                $ip[$data['ip']] = 1;
                if(!isset($tmp[$uhash]))
                {
                    $tmp[$uhash] = 1;
                    $result['uv']++;
                    $result['pv']++;
                }else{
                    $result['pv']++;
                }
                //可加上phone的统计
            }
            $result['ip'] = count($ip);
            fclose($fp);
            $date = strftime("%Y%m%d",strtotime($date));
            $sql = "insert into sj_stat(`pid`,`ip`,`pv`,`uv`,`date`) values('{$pid}','{$result['ip']}','{$result['pv']}','{$result['uv']}','{$date}') ";
              //db处理  不让重复提交

        }

    }


}