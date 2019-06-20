<?php
echo "Blocking SIGHUP signal\n";
pcntl_sigprocmask(SIG_BLOCK, array(SIGHUP));

echo "Sending SIGHUP to self\n";
posix_kill(posix_getpid(), SIGHUP);

echo "Waiting for signals\n";
$info = array();
pcntl_sigwaitinfo(array(SIGHUP), $info);
?>