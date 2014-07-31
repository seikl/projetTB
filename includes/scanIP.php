<?php
    //scan du réseau avec l'adresse de début, de fin et la MAC à rechercher (source originale: https://github.com/Harvie/Programs/blob/master/php/mac_hack.phps, le 10.09.2014
    function quick_ipmac_scan($adrDebutLong, $adrFinLong) {       
        
      for($i=$adrDebutLong;$i<=$adrFinLong;$i++) {
        //Mega threaded ( This will open 255 processes ;))
        $ipAPinger=long2ip($i);
        //$fp[$i] = popen("ping -c1 -w1 ".$ipAPinger, "r");//Pour Linux
        $fp[$i] = popen("ping -n 1 -w 1 ".$ipAPinger, "r");//Pour windows
        //echo "<br>pour INFO, IP A PINGER: ".$ipAPinger. "  --- etat de 'i': ".$i;
        pclose($fp[$i]);
      }
      /*
      for($i=$adrDebutLong;$i<=$adrFinLong;$i++) {
        while( $fp[$i] && !feof($fp[$i]) ) { fgets($fp[$i]); }
      } 
       */
      
      //$arp = shell_exec("arp -vn"); //pour Linux
      $arp = shell_exec("arp -a"); //pour Windows            
      $tableARP= explode("\n", $arp);
       
      //print_r($tableARP); //DEBUG
      return $tableARP;
    }     
    //--------------------------------------------
?>
