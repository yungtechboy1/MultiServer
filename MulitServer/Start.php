#!/usr/bin/php
<?php



//$b = new newPHPClass();
//
include 'Functions/Main.php';

$a = new Start();
$a->StartLoop();
class Start {
    private $main;
    public $fsocket;
    public $bsocket;
    public $data;
    public $connected;


    public function __construct() {
        $this->main = new MultiServer\Functions\Main();
        $this->main->a();
        echo "SOO";
        $this->CheckForConnection("19222");
        $this->bsocket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    }
    
    public function StartLoop() {
        $this->CheckForConnection("19222");
        while(1) {
            if ($this->RECEIVEFRONT("19222")){
                echo "Good!\n";   
            }else{
                echo "No GOod!\n";
            }
            sleep(1);
        }
    }
    

    public function CheckForConnection($port = 19132, $interface = "0.0.0.0") {
        $this->fsocket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        //socket_set_option($this->fsocket, SOL_SOCKET, SO_BROADCAST, 1); //Allow sending broadcast messages
        if(@socket_bind($this->fsocket, $interface, $port) === true){
            socket_set_option($this->fsocket, SOL_SOCKET, SO_REUSEADDR, 0);
            $this->setSendBuffer(1024 * 1024 * 8)->setRecvBuffer(1024 * 1024 * 8);
        }else{
            echo("**** FAILED TO BIND TO " . $interface . ":" . $port . "!");
            echo("Perhaps a server is already running on that port?");
            exit(1);
        }
        socket_set_nonblock($this->fsocket);
        $from = 'uninitialized';
        
        $this->bsocket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        //socket_set_option($this->fsocket, SOL_SOCKET, SO_BROADCAST, 1); //Allow sending broadcast messages
        if(@socket_bind($this->bsocket, $interface, 19120) === true){
            socket_set_option($this->bsocket, SOL_SOCKET, SO_REUSEADDR, 0);
            $this->setSendBuffer(1024 * 1024 * 8)->setRecvBuffer(1024 * 1024 * 8);
        }else{
            echo("**** FAILED TO BIND TO " . $interface . ":" . $port . "!");
            echo("Perhaps a server is already running on that port?");
            exit(1);
        }
        socket_set_nonblock($this->bsocket);
        $from = 'uninitialized';
        //$port = 0;

        
        
        return true;
    }
    
    public function RECEIVEFRONT($port, $interface = "0.0.0.0") {
        if (!@socket_recvfrom($this->fsocket, &$buf, 65535, 0, &$interface, &$port)){
            
            return false;
        }else{
            echo "Received connection from remote address $interface and remote port $port" . PHP_EOL;
            //$this->writePacket($buf, "0.0.0.0", "19132");
            if ($port == 19132){
                $this->writePacket($this->bsocket, $buf , $interface, 19132);
            }
            return true;
        }
    }
    public function RECEIVEBACKEND($port, $interface = "0.0.0.0") {
        if (!@socket_recvfrom($this->fsocket, &$buf, 65535, 0, &$interface, &$port)){
            
            return false;
        }else{
            $pid = ord($buf{0});
            echo '-'.$pid.'-'.$buf;
            $this->setRecvBuffer(substr($buf, 1));
            $this->setSendBuffer(substr($buf, 1));
            $file = 'testudp.txt';
            // Open the file to get existing content
            $current = file_get_contents($file);
            // Append a new log line(s) to file
            $current .= "New Connection " . date('Y-m-d H:i:s') . " \n" . bin2hex($buf) . "\n";
            // Write the contents back to the file
            file_put_contents($file, $current);
            echo "Received connection from remote address $interface and remote port $port" . PHP_EOL;
            return true;
        }
    }
    
    public $connections;
    public function PlayerConnectMain($port,$ip) {
        $this->connections[$port] = $ip;
    }
    
    
    public function getSocket(){
        return $this->fsocket;
    }
    public function close(){
        socket_close($this->fsocket);
    }
    /**
     * @param string &$buffer
     * @param string &$source
     * @param int    &$port
     *
     * @return int
     */
    public function readPacket(&$buffer, &$source, &$port){
        return socket_recvfrom($this->fsocket, $buffer, 65535, 0, $source, $port);
    }
    /**
     * @param string $buffer
     * @param string $dest
     * @param int    $port
     *
     * @return int
     */
    public function writePacket($socket,$buffer, $dest, $port){
        return socket_sendto($socket, $buffer, strlen($buffer), 0, $dest, $port);
    }
    
    public function CreateNewConnection($srcaddress, $srcport) {
        
    }
    
    /**
     * @param int $size
     *
     * @return $this
     */
    public function setSendBuffer($size){
        @socket_set_option($this->fsocket, SOL_SOCKET, SO_SNDBUF, $size);
        return $this;
    }
    /**
     * @param int $size
     *
     * @return $this
     */
    public function setRecvBuffer($size){
        @socket_set_option($this->fsocket, SOL_SOCKET, SO_RCVBUF, $size);
        return $this;
    }
}



