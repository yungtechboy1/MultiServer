<?php

use Start;
use MultiServer\Functions\NewConnection;
include 'Functions\NewConnection.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Processor
 *
 * @author carlt_000
 */
class Processor {
    //put your code here
    private $start;
    
    public function __construct(Start $s) {
        $this->start = $s;
    }
    
    /**
     * Used to Process Packets and Decide where to send the Packets to.
     * 
     * @param type $data Packet Data
     * @param type $ip Packet IP
     * @param type $port Packet Port
     */
    public function ProcessPacket($data, $ip, $port,$end) {
        if ($this->IsNewConnection($data) == true){
            $nc = new MultiServer\Functions\NewConnection($this->start, $ip, $port, $data, $end);
        }
    }
    
    public function IsNewConnection($data) {
        $a = str_split($data, 16);
        if ($a[0] == "00ffff00fefefefefdfdfdfd12345678"){
            return true;
        }
        return false;
    }
}
