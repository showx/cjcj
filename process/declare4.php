<?php
declare(ticks=1);
    // 开始时间
$time_start = time();
    // 检查是否已经超时
function check_timeout(){
    // 开始时间
    global $time_start;
    // 5秒超时
    $timeout = 5;
    if(time()-$time_start > $timeout){
        exit("超时{$timeout}秒\n");
    }
}
// Zend引擎每执行一次低级语句就执行一下check_timeout
register_tick_function('check_timeout');
// 模拟一段耗时的业务逻辑
while(1){
    echo "1\n";  //一直输出
    $num = 1;
    sleep(1);
}
// 模拟一段耗时的业务逻辑，虽然是死循环，但是执行时间不会超过$timeout=5秒
while(1){
    echo "2\n"; //没有到这步
    $num = 1;
    sleep(1);
}