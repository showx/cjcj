<?php
// Print "tick" with a timestamp and optional suffix.
function do_tick($str = '') {
    list($sec, $usec) = explode(' ', microtime());
    printf("[%.4f] Tick.%s\n", $sec + $usec, $str);
}
register_tick_function('do_tick');

// Tick once before declaring so we have a point of reference.
do_tick('--start--');

// Method 1
declare(ticks=1);
while(1) sleep(1);

/* Output:
[1234544435.7160] Tick.--start--
[1234544435.7161] Tick.
[1234544435.7162] Tick.
[1234544436.7163] Tick.
[1234544437.7166] Tick.
*/

?>