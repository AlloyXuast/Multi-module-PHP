<?php

namespace Payments\Gaming;

class UnturnedRocketRCON
{
    private $socket;
    private $connected = false;
    public $errstr;

    function connect($ip, $port, $password, $timeout = 5)
    {
        try {
            $this->socket = fsockopen($ip, intval($port), $errno, $errstr, $timeout);
            if (!$this->socket) {
                $this->errstr = $errstr;
                return false;
            } else {
                $this->connected = true;
                $this->login($password);
                return true;
            }
        } catch (Exception $e) {
            var_dump(1228);
            die();
            $this->errstr = $e->getMessage();
            return false;
        }
    }

    private function login($password)
    {
        if (!$this->connected) return;
        fputs($this->socket, "login " . $password . "\r\n");
    }

    function send($command)
    {
        if (!$this->connected || !$command) return;
        fputs($this->socket, $command . "\r\n");
    }

    public function receive()
    {
        $buffer = fread($this->socket, 1024);
        return $buffer;
    }

    function __destruct()
    {
        $this->disconnect();
    }

    function disconnect()
    {
        if (!$this->connected) return;
        fputs($this->socket, "quit\r\n");
        $this->receive();
        fclose($this->socket);
        $this->connected = false;
    }
}
