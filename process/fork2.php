<?php
if (! function_exists('pcntl_fork')) die('PCNTL functions not available on this PHP installation');
//获取每个进程
$xx = posix_getpid();
echo "pid:".$xx."\n";
// echo "x:".$x."\n";

for ($x = 1; $x <= 3; $x++) {
   switch ($pid = pcntl_fork()) {
      case -1:
         die('Fork failed');
         break;
      case 0:
        $xx = posix_getpid();
        // print $x."[fork_id:".$pid."|子进程pid:".$xx."]-FORK: Child #{$x}\n";
        print "[子进程:".$xx." x:".$x."]-FORK: Child #{$x}\n";
        //  generate_fatal_error(); // Undefined function
        break;

      default:
         print "[子进程：".$pid."|父进程:".$xx." x:{$x}]-parent\n";
         pcntl_waitpid($pid, $status);
         break;
   }
}
if($pid==0)
{
    print "[子进程:".$xx." x:{$x}]-Done!----------------------\n\n";
}else{
    print "[最后一次fork_id:".$pid."|进程:".$xx." x:{$x}]-Done!----------------------\n\n";
}

//搜索10419的产生过程
// pid:10419
// [子进程：10420|父进程:10419 x:1]-parent
// [子进程:10420 x:1]-FORK: Child #1
// [子进程：10421|父进程:10420 x:2]-parent
// [子进程:10421 x:2]-FORK: Child #2
// [子进程：10422|父进程:10421 x:3]-parent
// [子进程:10422 x:3]-FORK: Child #3
// [子进程:10422 x:4]-Done!----------------------

// [最后一次fork_id:10422|进程:10421 x:4]-Done!----------------------

// [子进程：10423|父进程:10420 x:3]-parent
// [子进程:10423 x:3]-FORK: Child #3
// [子进程:10423 x:4]-Done!----------------------

// [最后一次fork_id:10423|进程:10420 x:4]-Done!----------------------

// [子进程：10424|父进程:10419 x:2]-parent
// [子进程:10424 x:2]-FORK: Child #2
// [子进程：10425|父进程:10424 x:3]-parent
// [子进程:10425 x:3]-FORK: Child #3
// [子进程:10425 x:4]-Done!----------------------

// [最后一次fork_id:10425|进程:10424 x:4]-Done!----------------------

// [子进程:10426 x:3]-FORK: Child #3
// [子进程：10426|父进程:10419 x:3]-parent
// [子进程:10426 x:4]-Done!----------------------

// [最后一次fork_id:10426|进程:10419 x:4]-Done!----------------------

?>