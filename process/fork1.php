<?php
$xx = posix_getpid();
echo $xx."\n";
$pid = pcntl_fork();
//父进程和子进程都会执行下面代码
if ($pid == -1) {
    //错误处理：创建子进程失败时返回-1.
     die('could not fork');
} else if ($pid) {
    //这里是父进程
    echo "fu\n";
     //父进程会得到子进程号，所以这里是父进程执行的逻辑
     pcntl_wait($status); //等待子进程中断，防止子进程成为僵尸进程。
} else {
    //子进程id
    $xx = posix_getpid();
    echo "zi:".$xx."\n";
     //子进程得到的$pid为0, 所以这里是子进程执行的逻辑。
     echo $pid."ok\n";
}
echo "进程".$pid."每个进程都要执行的\n";
?>