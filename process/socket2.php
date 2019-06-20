<?php
$host = '0.0.0.0';
$port = 9999;
// 创建一个tcp socket
$listen_socket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
// 将socket bind到IP：port上
socket_bind( $listen_socket, $host, $port );
// 开始监听socket
socket_listen( $listen_socket );
// 进入while循环，不用担心死循环死机，因为程序将会阻塞在下面的socket_accept()函数上
while( true ){
  // 此处将会阻塞住，一直到有客户端来连接服务器。阻塞状态的进程是不会占据CPU的
  // 所以你不用担心while循环会将机器拖垮，不会的 
  $connection_socket = socket_accept( $listen_socket );
  // 当accept了新的客户端连接后，就fork出一个子进程专门处理
  $pid = pcntl_fork();
  // 在子进程中处理当前连接的请求业务
  if( 0 == $pid ){
    // 向客户端发送一个helloworld
    $msg = "helloworld\r\n";
    socket_write( $connection_socket, $msg, strlen( $msg ) );
    // 休眠5秒钟，可以用来观察时候可以同时为多个客户端提供服务
    echo time().' : a new client'.PHP_EOL;
    sleep( 5 );
    socket_close( $connection_socket );
    exit;
  }
}
socket_close( $listen_socket );