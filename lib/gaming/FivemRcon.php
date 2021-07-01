<?php

namespace Payments\Gaming;

class FivemRcon{
	private $serverIp;
	private $serverPort;
	private $password;
	private $socket;
	private $lastPing;

	public function __construct($serverIp,$serverPort,$serverPassword){
		$this->serverIp = $serverIp;
		$this->serverPassword = $serverPassword;
		$this->serverPort = $serverPort;
	}
	public function connect(){
		$this->socket = fsockopen("udp://$this->serverIp", $this->serverPort, $errno, $errstr, 5);
		if(!$this->socket){
			return false;
		}
		return true;
	}
	public function command($str){
		$this->send("rcon ".$this->serverPassword." $str");
	}
    private function send($str) {
        fwrite($this->socket, "\xFF\xFF\xFF\xFF$str\x00");
    }
    public function getResponse(){
    	stream_set_timeout($this->socket, 0, 7e5);
        $s = '';
	    $start = microtime(true);
        do {
        	$read = fread($this->socket, 9999);
			$s .= substr($read, strpos($read, "\n") + 1);
    		if (!isset($end)) {
    			$end = microtime(true);
    		}
			$info = stream_get_meta_data($this->socket);
		}
		while (!$info["timed_out"]);

		$this->lastPing = round(($end - $start) * 1000);
        return $s;
    }
}
