<?php
    //pour éviter les boucles infinies si le serveur ne ferme pas la connexion
    //source: http://php.net/manual/fr/function.feof.php, consulté le 29.08.2014
    function safe_feof($fp, $start = NULL) {
     $start = microtime(true);
     return feof($fp);
    }

    //*************pour envoyer une requête HTTP (utilise cURL)
    function requeteHTTP($adresseIP, $requete, $user, $mdp)
    {               
        $out="";
        $boolReqPOST=false;
       
        $tabRequete= explode("\n", $requete);
        //vérifications du contenu pour déterminer la requête
        if(preg_match("/POST/i", $tabRequete[0])){$boolReqPOST=true;}
        $ligne=$tabRequete[0];$url= strstr($ligne,'/');$url='http://'.$adresseIP.strstr($url,' HTTP',true);        
        //pour les données
        $valeurs=end($tabRequete);
        $userPassword=$user.":".$mdp;
        
        $curl = curl_init();        
        //préparation de la req. HTTP
        curl_setopt_array($curl, array(
            CURLOPT_FRESH_CONNECT=>true,
            CURLOPT_RETURNTRANSFER => false,    
            CURLOPT_UNRESTRICTED_AUTH=>true,
            CURLOPT_FOLLOWLOCATION=>true,
            CURLOPT_HEADER=>true,
            CURLOPT_USERPWD=>$userPassword,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT=>5,            
            CURLOPT_URL => $url
        ));
        ;
        if ($boolReqPOST){
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,$valeurs);
        }
        echo "<br>URL: ".$url;
        echo "<br>BoolReqPOST: ".$boolReqPOST;

        echo "<br>VALEURS: ".$valeurs;
        echo "<br>USER PASSWORD: ".$userPassword;
        
        //test de l'envoi
        if(!curl_exec($curl)){
            $out = curl_getinfo($curl);
            $out = "Erreur: ".curl_error($curl) . " - Code: ". curl_errno($curl);
            return $out;
        }
        else{
            //$info = curl_getinfo($curl);
            //$out= $resultat;       
            //foreach ($resultat as $ligneResutat){$out.=$ligneResutat;}        
            $out = strip_tags($out,'<br>|<p>|<i>|</i>|<input>');
            curl_close($curl);
            return $out;             
        }       
    }
    //--------------------------------------------    
    
    //*************pour envoyer une requête TELNET (utilise les sockets)
    function requeteTELNET($adresseIP, $requete, $user, $mdp, $socket,$taille,$nbTrames)
    {                
        $out="";         
        if ($user!=""){fwrite($socket, $user."\r\n");$out .= fgets($socket,$taille);}                                        
        if ($mdp!=""){fwrite($socket, $mdp."\r\n");$out .= fgets($socket,$taille);}                        
    
        fwrite($socket, $requete);
        while (!feof($socket)) {
            $out .= fgets($socket,$taille);                                                                                    
            fwrite($socket, "\r\n");
            $nbTrames--;
            if ($nbTrames==0){break;} 
        } 
        fwrite($socket, "quit\r\n");
        return $out;
    }
    //--------------------------------------------    
    
     
?>
