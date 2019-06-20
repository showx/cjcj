<?php
    // 一次fork  
    $pid = pcntl_fork();
    if ( $pid < 0 ) {
      exit( ' fork error. ' );
    } else if( $pid > 0 ) {
      exit( ' parent process. ' );
    }
    // 将当前子进程提升会会话组组长 这是至关重要的一步 
    if ( ! posix_setsid() ) {
      exit( ' setsid error. ' );
    }
    // 二次fork
    $pid = pcntl_fork();
    if( $pid < 0 ){
      exit( ' fork error. ' );
    } else if( $pid > 0 ) {
      exit( ' parent process. ' );
    }
    
    // 真正的逻辑代码们 下面仅仅写个循环以示例
    for( $i = 1 ; $i <= 100 ; $i++ ){
      sleep( 1 );
      file_put_contents( 'daemon.log', $i, FILE_APPEND );
    }
?>