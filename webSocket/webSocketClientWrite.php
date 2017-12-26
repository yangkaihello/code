<?php
error_reporting(E_ALL);
set_time_limit(0);

#$port = 10004;
$port = 1935;
$ip = "219.138.135.11";
 
/*
 +-------------------------------
 *  @socket连接整个过程
 +-------------------------------
 *  @socket_create
 *  @socket_connect
 *  @socket_write
 *  @socket_read
 *  @socket_close
 +--------------------------------
 */
 


do{
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if ($socket < 0) {
	  echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
	}

	$result = socket_connect($socket, $ip, $port);
	if ($result < 0) {
	  echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error()) . "\n";
	}

		// ask for input  
	fwrite(STDOUT, "msg: ");  
	  
	// get input  
	$msg = "localhost:" . trim(fgets(STDIN));


		 
		$out = socket_write($socket,$msg, strlen($msg));
	socket_close($socket);

}while(true)
 

?>