<?php
    //pour autoriser le script à s'exécuter au-delà de 300 secondes
    set_time_limit(300);
    
    //scan du réseau avec l'adresse de début, de fin (source originale: https://github.com/Harvie/Programs/blob/master/php/mac_hack.phps, le 10.08.2014
    function quick_ipmac_scan($adrDebutLong, $adrFinLong) {  
        
        $getArpWindows= "arp -a";//pour Windows
        $getArpLinux= "arp -va";//pour Linux
        
        $pingWindows="ping -n 1 -w 1 ";//Pour windows 
        $pingLinux="ping -c 1 -w 1 ";//Pour Linux

        
      for($i=$adrDebutLong;$i<=$adrFinLong;$i++) {
        //Mega threaded ( This will open 255 processes ;))
        $ipAPinger=long2ip($i);
        $fp[$i] = popen($pingLinux.$ipAPinger, "r");       
        
        //pour éviter d'atteindre la limite de processus ouverts simultanément
        if ((($i % 16) == 0) && (($adrFinLong - $adrDebutLong) >= 512)){
            for ($j=$i;$j>=$i-15;$j--){					
                    pclose($fp[$j]);
            }
        }   
      }
      sleep(3);
      /*
      for($i=$adrDebutLong;$i<=$adrFinLong;$i++) {
        while( $fp[$i] && !feof($fp[$i]) ) { fgets($fp[$i]); }
      } 
      */
      $tableARP = shell_exec($getArpLinux);  
      $tableARP = explode("\n", $tableARP);
      //print_r($tableARP); //DEBUG
      return $tableARP;
    }     
    //--------------------------------------------
?>