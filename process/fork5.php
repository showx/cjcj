<?php
set_error_handler(function(){});
pcntl_signal(SIGALRM,'sig_handler',false);
print  'Your error returned was (Code):'.pcntl_get_last_error().PHP_EOL;
print  'Your error info:'.pcntl_strerror(pcntl_get_last_error()).PHP_EOL;