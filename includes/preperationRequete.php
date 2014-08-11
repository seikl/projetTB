<?php
    //pour prépararer une requête HTTP
    function requeteHTTP($adresseIP, $requete)
    {               
        $out="";
        $tabRequete= explode("\n", $requete);
        
        foreach($tabRequete as $ligneReq)
        {
            if (preg_match('/Host: /', $ligneReq)){$ligneReq="Host: ".$adresseIP;}  
            if (preg_match('/Referer: /', $ligneReq)){$ligneReq="";}

            $out=$out.$ligneReq."\r\n";
        }

        return $out;
    }
    //--------------------------------------------    
    
    //pour prépararer une requête TELNET
    function requeteTELNET($adresseIP, $requete, $user, $mdp)
    {               
        $out="";
        
        if ($user!=""){$out=$out.$user."\r\n";}
        if ($mdp!=""){$out=$out.$mdp."\r\n";}
        
        $tabRequete= explode("\n", $requete);        
        foreach($tabRequete as $ligneReq)
        {
            if (preg_match('/Host: /', $ligneReq)){$ligneReq="Host: ".$adresseIP;}  
            if (preg_match('/Referer: /', $ligneReq)){$ligneReq="";}

            $out=$out.$ligneReq."\r\n";
        }

        return $out;
    }
    //--------------------------------------------    
    
     
?>