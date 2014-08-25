<?php
    //pour autoriser le script à s'exécuter au-delà de 300 secondes
    set_time_limit(300);
    
    //scan du réseau avec l'adresse de début, de fin (source originale: https://github.com/Harvie/Programs/blob/master/php/mac_hack.phps, le 10.08.2014
    function quick_ipmac_scan($adrDebutLong, $adrFinLong) {  
        
        $getArpWindows= "arp -a";//pour Windows
        $getArpLinux= "arp -vn";//pour Linux
        $getHostnameLinux = "host";//pour Linux
        
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
      /*
      for($i=$adrDebutLong;$i<=$adrFinLong;$i++) {
        while( $fp[$i] && !feof($fp[$i]) ) { fgets($fp[$i]); }
      } 
      */
      //pour récupérer la liste de IP, des hôtes et des MAC des AP qui ont répondu
      $i=0;
      $tableARP = shell_exec($getArpLinux);       
      $tableARPHosts = explode("\n", $tableARP);
      $tableARP=null;
      
      foreach ($tableARPHosts as $host){
          if (preg_match("/ether/i", $host)){              
              $ip = strstr($host, ' ', true);
              $adresseMAC = strstr($host,'ether');$adresseMAC=  preg_replace("/ether/i", "", $adresseMAC);$adresseMAC = substr($adresseMAC, 0, 17);
              $hostname = shell_exec($getHostnameLinux.' '.$ip);$hostname= strstr($hostname,'pointer '); $hostname = substr($hostname,7);  
              $tableARP[$i] = array("adresseIP"=>$ip,"adresseMAC"=>$adresseMAC,"hostname"=>$hostname);
              $i++;
          }          
      }    
      //print_r($tableARP); //DEBUG
      return $tableARP;
    }     
    //--------------------------------------------
?>