<?php
    //pour autoriser le script à s'exécuter au-delà de 300 secondes
    set_time_limit(300);
    
    //scan du réseau avec l'adresse de début, de fin et la MAC à rechercher (source originale: https://github.com/Harvie/Programs/blob/master/php/mac_hack.phps, le 10.09.2014
    function quick_ipmac_scan($adrDebutLong, $adrFinLong) {  
        
        $getArpWindows= "arp -a";//pour Windows
        $getArpLinux= "arp -vn";//pour Linux

        $flushArp="ip -s -s neigh flush all";//pour Linux

        $pingLinux="ping -c 1 -W 1 ";//Pour Linux
        $pingWindows="ping -n 1 -w 1 ";//Pour windows  
        $arp=null;
        
      for($i=$adrDebutLong;$i<=$adrFinLong;$i++) {
        //Mega threaded ( This will open 255 processes ;))
        $ipAPinger=long2ip($i);
        $fp[$i] = popen($pingWindows.$ipAPinger, "r");       
        
        //pour éviter d'atteindre la limite de processus ouverts
        if ((($i % 16) == 0) && (($adrFinLong - $adrDebutLong) >= 512)){
            for ($j=$i;$j>=$i-15;$j--){					
                    pclose($fp[$j]);
            }
        }   
        //pour vider la table ARP du serveur
        if (($i % 256)==0){
            $arp .= shell_exec($getArpWindows);
            shell_exec($flushArp);
        }
      }
     
      /*
      for($i=$adrDebutLong;$i<=$adrFinLong;$i++) {
        while( $fp[$i] && !feof($fp[$i]) ) { fgets($fp[$i]); }
      } 
      */
      $arp .= shell_exec($getArpWindows);        
      $tableARP= explode("\n", $arp);
       
      //print_r($tableARP); //DEBUG
      return $tableARP;
    }     
    //--------------------------------------------
?>
