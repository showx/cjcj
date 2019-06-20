<?php
pcntl_signal(SIGALRM, function () {
    echo 'Received an alarm signal !' . PHP_EOL;
}, false);

pcntl_alarm(1);

while (true) {
    pcntl_signal_dispatch();
    sleep(1);
}