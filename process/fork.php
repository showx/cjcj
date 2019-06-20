<?php
pcntl_signal(SIGTERM,  function($signo) {
    echo "\n This signal is called. [$signo] \n";
    Status::$state = -1;
});

class Status{
    public static $state = 0;
}

$pid = pcntl_fork();
if ($pid == -1) {
    die('could not fork');
}

if($pid) {
    // parent
} else {
    while(true) {
        // Dispatching...
        pcntl_signal_dispatch();
        if(Status::$state == -1) {
            // Do something and end loop.
            break;
        }
        
        for($j = 0; $j < 2; $j++) {
            echo '.';
            sleep(1);
        }
        echo "\n";
    }
    
    echo "Finish \n";
    exit();
}

$n = 0;
while(true) {
    $res = pcntl_waitpid($pid, $status, WNOHANG);
    
    // If the child process ends, then end the main process.
    if(-1 == $res || $res > 0)
        break;
    
    // Send a signal after 5 seconds..
    if($n == 5)
        posix_kill($pid, SIGTERM);

    echo $n;
    $n++;
    
    sleep(1);
}