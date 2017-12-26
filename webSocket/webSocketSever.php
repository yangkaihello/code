<?php

class WebSocketServer{
	public $domain;
	public $type;
	public $protocol;
	public $ip;
	public $port;
	public $curr;

	/*socket Á¬½Ó³Ø*/
	public $serv;

	public $master=1;
	public static $moreSocketWrite[];
	public static $moreSocketRead[] = $this->master;

	public function config($arr){
		$this->domain 	= $arr['domain'];
		$this->type 	= $arr['type'];
		$this->protocol = $arr['protocol'];
		$this->ip 		= $arr['ip'];
		$this->port 	= $arr['port'];
		$this->curr 	= $arr['curr'];

	}

	public function create(){
		try{
			$socket = socket_create($this->domain,$this->type,$this->protocol);
			if($socket == false){
				$err = socket_strerror(socket_last_error());
				throw new Exception($err);
			}
			$this->socket = $socket;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}

	public function bind(){
		try{
			if(!socket_bind($this->socket,$this->ip,$this->port)){
				$err = socket_strerror(socket_last_error());
				throw new Exception($err);
			}
		}catch(Exception $e){
			die($e->getMessage());
		}
		
	}

	public function listen(){
		try{
			if(!socket_listen($this->socket,$this->curr)){
				$err = socket_strerror(socket_last_error());
				throw new Exception($err);
			}
		}catch(Exception $e){
			die($e->getMessage());
		}
		
	}

	public function more($client){
		self::$moreSocket[] = $client;
		return self::$moreSocket;
	}


	public function write(){
		foreach($this->moreSocketWrite as $writeSock){
			socket_write($writeSock,$buf,strlen($buf));
		}
	}
}
$arr = [
	'domain' => AF_INET,
	'type' => SOCK_STREAM,
	'protocol' => SOL_TCP,
	'ip' => '0.0.0.0',
	'port' => '1935',
	'curr' => 5,
];

$socket = new WebSocketServer();
$socket->config($arr);
$socket->create();
$socket->bind();
$socket->listen();
$socket->more($socket->master);



do {
	$readLink  = $socket::$moreSocketRead; 
  	
  	foreach($readLink as $key=>$readSock){
  		if($sock == 1){
  			$msgSock = socket_accept($socket->serv);
  			
  			$socket->more($msgSock);

  			$buf = socket_read($msgSock,10000);
  			$buf = "user:{$buf}";
  			if(!empty($buf)){
  				$socket->write();
  			}else{
  				break;
  			}
  		}
  		
  	}

	  
} while (true);

?>