#!/usr/bin/php
<?php

namespace MultiServer ;

use Functions\MainFunctions;
use Functions\newPHPClass;

//$b = new newPHPClass();
$a = new Start();
$a->StartLoop();

class Start {
    
    public function __construct() {
        echo "SOO";
    }
    
    public function StartLoop() {
        //$b = new MainFunctions();
        $this->CheckForConnection("19222");
        while(1) {
            if ($this->RECEIVE("19222")){
                echo "Good!";   
            }else{
                echo "No GOod!";
            }
            sleep(3);
        }
    }
    
    public $socket;
    public function CheckForConnection($port = 19132, $interface = "0.0.0.0") {
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        //socket_set_option($this->socket, SOL_SOCKET, SO_BROADCAST, 1); //Allow sending broadcast messages
        if(@socket_bind($this->socket, $interface, $port) === true){
            socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 0);
            $this->setSendBuffer(1024 * 1024 * 8)->setRecvBuffer(1024 * 1024 * 8);
        }else{
            echo("**** FAILED TO BIND TO " . $interface . ":" . $port . "!");
            echo("Perhaps a server is already running on that port?");
            exit(1);
        }
        socket_set_nonblock($this->socket);
        $from = 'uninitialized';
        //$port = 0;

        
        
        return true;
       /* if (!empty($buffer) && $buffer != ""){
            echo  bin2hex($buffer)."Success!";
            $buffer = "";
            return true;
        }else{
            echo "NO Message";
            return false;
        }*/
    }
    
    public function RECEIVE($port, $interface = "0.0.0.0") {
        if (!@socket_recvfrom($this->socket, &$buf, 65535, 0, &$interface, &$port)){
            
            return false;
        }else{
            echo '--'.$buf;
            return true;
        }
    }
    
    
    public function getSocket(){
        return $this->socket;
    }
    public function close(){
        socket_close($this->socket);
    }
    /**
     * @param string &$buffer
     * @param string &$source
     * @param int    &$port
     *
     * @return int
     */
    public function readPacket(&$buffer, &$source, &$port){
        return socket_recvfrom($this->socket, $buffer, 65535, 0, $source, $port);
    }
    /**
     * @param string $buffer
     * @param string $dest
     * @param int    $port
     *
     * @return int
     */
    public function writePacket($buffer, $dest, $port){
        return socket_sendto($this->socket, $buffer, strlen($buffer), 0, $dest, $port);
    }
    /**
     * @param int $size
     *
     * @return $this
     */
    public function setSendBuffer($size){
        @socket_set_option($this->socket, SOL_SOCKET, SO_SNDBUF, $size);
        return $this;
    }
    /**
     * @param int $size
     *
     * @return $this
     */
    public function setRecvBuffer($size){
        @socket_set_option($this->socket, SOL_SOCKET, SO_RCVBUF, $size);
        return $this;
    }
}



