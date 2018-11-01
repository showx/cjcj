<?php
/**
* 采集淘宝相关
* 批量采集图片
* Author:show(9448923@qq.com)
*/
/**
* 下载内容页
*/
function dcurl($url, $proxy=null) 
{ 
    $curl = curl_init();
    $header = array();
    $header[0]  = "Accept: text/xml,application/xml,application/xhtml+xml,"; 
    $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5"; 
    $header[] = "Cache-Control: max-age=0"; 
    $header[] = "Connection: keep-alive"; 
    $header[] = "Keep-Alive: 300"; 
    $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7"; 
    $header[] = "Accept-Language: en-us,en;q=0.5";
    //重点cookie ,自己获取个
    $header[] = "";
    $header[] = "Pragma: ";
    if (!is_null($proxy)) {
        curl_setopt ($curl, CURLOPT_PROXY, $proxy); 
    }
    curl_setopt($curl, CURLOPT_URL, $url); 
    curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)'); 
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header); 
    curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.com'); 
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate'); 
    curl_setopt($curl, CURLOPT_AUTOREFERER, true); 
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    $html = curl_exec($curl);
    curl_close($curl); 
    return $html;
}
/**
* 下载分开处理，没那么杂乱
*/
function getU($url,$refer)
{
        $user_agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.2; zh-CN; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_REFERER, $refer);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
       
        $result = curl_exec($ch);
        $errno  = curl_errno($ch);
        $infos = curl_getinfo($ch); 
        
        curl_close($ch);
        $h = $infos['redirect_url'];
        return $h;
}


$sql = "select";  //获取库里淘宝的内容
$result = db::g($sql);

foreach($result as $key=>$val)
{

        $header = get_headers($val['click_url'],1);
        $refer =  $header['Location'];

        $tu = explode("tu=",$refer);
        $tu = $tu['1'];
        $url = urldecode($tu);
        $zurl = getU($url,$refer);

        $h = dcurl($zurl);
        $img = '<img id="J_ImgBooth" alt=".*?" src="(.*)?".*?/>'; //获取图片
        preg_match($img,$h,$h);

        if($h)
        {
            $p = strpos($h['1'],"jpg");
            $h = substr($h['1'],0,$p);
            $h = "http:".$h."jpg";

            echo $h."\n";

            $sql= "update";//更新操作
            db::query($sql);
        }
        

}