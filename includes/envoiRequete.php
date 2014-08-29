<?php
    //pour éviter les boucles infinies si le serveur ne ferme pas la connexion
    //source: http://php.net/manual/fr/function.feof.php, consulté le 29.08.2014
    function safe_feof($fp, $start = NULL) {
     $start = microtime(true);
     return feof($fp);
    }

    //pour prépararer une requête HTTP
    function requeteHTTP($adresseIP, $requete)
    {               
        $out="";
        $tabRequete= explode("\n", $requete);
        
        foreach($tabRequete as $ligneReq)
        {
            $checkCRLF=true;
            if (preg_match('/Host: /', $ligneReq)){$ligneReq="Host: ".$adresseIP;}  
            if (preg_match('/Referer: /', $ligneReq)){$ligneReq="";$checkCRLF=false;}
            
            if ($checkCRLF){$out.=$ligneReq."\r\n";};            
        }
        return $out;
    }
    //--------------------------------------------    
    
    //pour prépararer une requête TELNET - OBSOLETE
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
