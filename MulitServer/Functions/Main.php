<?php

namespace Functions;

class MainFunctions {
    
    public function StartService() {
        
    }
    
    public function CheckForConnection($port) {
        //$port = 19222;
        //Unused at the Current Moment
        //$spc = socket_create(AF_INET, SOCK_STREAM ,SOL_TCP);
        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        // Bind the source address
        socket_bind($sock, "127.0.0.1");
        $a = socket_accept($sock);
        $b = socket_read($a, "\0");
        // Connect to destination address
        //socket_connect($sock, '127.0.0.1', $port);
        if (socket_listen($sock)  == TRUE){
            echo "Success!";
            return true;
        }else{
            echo "NO Message";
            return false;
        }
    }
}
