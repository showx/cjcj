<?php 
echo "posix_getpid()=".posix_getpid().", posix_getppid()=".posix_getppid()."\n"; 

$pid = pcntl_fork(); 
if ($pid == -1) die("could not fork"); 
if ($pid) { 
    echo "pid=".$pid.", posix_getpid()=".posix_getpid().", posix_getppid()=".posix_getppid()."\n"; 
} else { 
    echo "pid=".$pid.", posix_getpid()=".posix_getpid().", posix_getppid()=".posix_getppid()."\n"; 
} 
?>