<?php
/**************************************************************************************************** 
 * Auteur: Sébastien Kleber (sebastien.kleber@heig-vd.ch) 
 * 
 * Description:
 * script PHP contenant toutes les fonctions nécessaires à l'envoi des différents types de requêtes
 * 
 *  * paramètres:
 * - $adresseIP: L'adresse IP du périphérique réseau
 * - $requete: la commande à transmettre
 * - $noPort: le no de port TCP/UDP
 * - $user: le nom d'utilisateur du périphérique
 * - $mdp: le mot de passe du périphérique
 * - ($nbTrames): Le nombre de trames que l'on souhaite récupérer dans la réponse
 *                                                                                                  *
 * Modifié le: 31.08.2014                                                                           *
 ***************************************************************************************************/  

    //pour éviter les boucles infinies si le serveur ne ferme pas la connexion (NON UTIILISE POUR LE MOMENT)
    //source: http://php.net/manual/fr/function.feof.php, consulté le 29.08.2014
    function safe_feof($fp, $start = NULL) {
     $start = microtime(true);
     return feof($fp);
    }

    //*************pour envoyer une requête HTTP (utilise cURL)
    function requeteHTTP($adresseIP, $requete,$noPort, $user, $mdp)
    {               
        $out="";
        $boolReqPOST=false;
       
	//création d'un tableau avec chaque ligne de la commande enregistrée dans une cellule
        $tabRequete= explode("\n", $requete);
        //vérification du contenu du 1er indice pour déterminer le type de requête (POST OU GET)
        if(preg_match("/POST/i", $tabRequete[0])){$boolReqPOST=true;}
        $ligne=$tabRequete[0];$url= strstr($ligne,'/');$url='http://'.$adresseIP.strstr($url,' HTTP',true);        
        //pour les données
        $valeurs=end($tabRequete);
        $userPassword=$user.":".$mdp;
        
        $curl = curl_init();        
        //préparation de la req. HTTP
        curl_setopt_array($curl, array(
            CURLOPT_FRESH_CONNECT=>true,
            CURLOPT_RETURNTRANSFER => true,    
            CURLOPT_UNRESTRICTED_AUTH=>true,
            CURLOPT_FOLLOWLOCATION=>true,
            CURLOPT_HEADER=>true,
            CURLOPT_PORT=>$noPort,
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
        $out = curl_exec($curl);
        //test de l'envoi
        if(empty($out)){
            $out = curl_getinfo($curl);
            $out = "Erreur: ".curl_error($curl) . " - Code: ". curl_errno($curl);
            curl_close($curl);
            return $out;
        }
        else{            
            $info = curl_getinfo($curl);
            $out = "<strong>".$info["http_code"]."</strong>-".$out;
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
        $erreurLevee=false;
        if ($user!=""){fwrite($socket, $user."\r\n");$out .= fgets($socket,$taille);}                                        
        if ($mdp!=""){fwrite($socket, $mdp."\r\n");$out .= fgets($socket,$taille);}                        
    
        fwrite($socket, $requete."\r\n");
        //pour éviter la boucle si requête d'interruption (par exemple après reboot )
        if (preg_match("/reboot/i", $requete) ||
            preg_match("/restart/i", $requete)||
            preg_match("/reset/i", $requete)){            
                $out="Requête d'interruption transmise.";
        }
        else {
            while (!feof($socket)) {
                    $out .= fgets($socket,$taille);                                                                                    
                    fwrite($socket, "\r\n");
                }
                $nbTrames--;
                if ($nbTrames==0){break;} 
        } 
        fwrite($socket, "quit\r\n");
        return $out;
    }
    //--------------------------------------------    
    
    //*************pour envoyer une requête HTTPS (utilise cURL)
    function requeteHTTPS($adresseIP, $requete,$noPort, $user, $mdp)
    {               
        $out="";
        $boolReqPOST=false;
       
        $tabRequete= explode("\n", $requete);
        //vérifications du contenu pour déterminer la requête
        if(preg_match("/POST/i", $tabRequete[0])){$boolReqPOST=true;}
        $ligne=$tabRequete[0];$url= strstr($ligne,'/');$url='https://'.$adresseIP.strstr($url,' HTTP',true);        
        //pour les données
        $valeurs=end($tabRequete);
        $userPassword=$user.":".$mdp;
        
        $curl = curl_init();        
        //préparation de la req. HTTP
        curl_setopt_array($curl, array(
            CURLOPT_FRESH_CONNECT=>true,
            CURLOPT_RETURNTRANSFER => true,    
            CURLOPT_UNRESTRICTED_AUTH=>true,
            CURLOPT_FOLLOWLOCATION=>true,
            CURLOPT_HEADER=>true,
            CURLOPT_PORT=>$noPort,
            CURLOPT_USERPWD=>$userPassword,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT=>5,  
            CURLOPT_SSL_VERIFYPEER=>false,
            CURLOPT_SSL_VERIFYHOST=>2,
            CURLOPT_URL => $url
        ));
        ;
        if ($boolReqPOST){
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,$valeurs);
        }      
        $out = curl_exec($curl);
        //test de l'envoi
        if(empty($out)){
            $out = curl_getinfo($curl);
            $out = "Erreur: ".curl_error($curl) . " - Code: ". curl_errno($curl);
            curl_close($curl);
            return $out;
        }
        else{            
            $info = curl_getinfo($curl);
            $out = "<strong>".$info["http_code"]."</strong>-".$out;
            $out = strip_tags($out,'<br>|<p>|<i>|</i>|<input>');
            curl_close($curl);
            return $out;             
        }       
    }
    //--------------------------------------------    
    
     
?>
