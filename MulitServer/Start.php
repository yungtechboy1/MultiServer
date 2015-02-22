#!/usr/bin/php -q
<?php

use Functions\MainFunctions;

$a = new Start();
$a->StartLoop();

class Start {
    
    public function StartLoop() {
        $b = new MainFunctions();
        while(1) {
            if ($b->CheckForConnection("19222")){
                echo "Good!";   
            }else{
                echo "No GOod!";
            }
            sleep(3);
        }
    }
}



