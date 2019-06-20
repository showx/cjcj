<?php
declare(ticks=1);
pcntl_signal(SIGINT, function(){
    exit("Get signal SIGINT and exit\n");
});
echo "Ctl + c or run cmd : kill -SIGINT " . posix_getpid(). "\n" ;
while(1){
    $num = 1;
}