<?php
namespace MultiServer\Functions;

use Start;

class NewConnection {
    private $start;
    private $ip;
    private $port;
    private $data;
    private $temp;
    private $end;
    private $serverid = "6379626572746563"; //cybertec
    private $magic = "00ffff00fefefefefdfdfdfd12345678"; //Pocketmine Magic

    public function __construct(Start $s, $ip, $port, $data, $end) {
        $this->start = $s;
        $this->ip = $ip;
        $this->port = $port;
        $this->data = $data;
        $this->end = $end;
    }
    
    public function NewClient($port, $ip) {
        $this->start->connected[$ip][$port] = 0;
    }
    
    public function Process(){
        if (isset($this->temp[$ip][$port])){
            if ($this->temp[$ip][$port] == 0){
                $buffer = $this->magic.$this->serverid."04F5FFFC97"."FFFF";
                $this->start->writePacket($this->start->fsocket, $buffer, $this->ip, $port);
                $this->temp[$ip][$port] = 1;
            }
        }else{
            $buffer = $this->magic.$this->serverid."0"."FFFF";
            $this->start->writePacket($this->start->fsocket, $buffer, $this->ip, $port);
            $this->temp[$ip][$port] = 0;
        }
    }


//put your code here
}
