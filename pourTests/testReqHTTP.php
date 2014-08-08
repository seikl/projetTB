<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>AP Manager - Accueil</title>
    </head>
    <body>
        <?php
        
        $uri = "EmWeb_ns%3Asnmp%3A233=APADSS0L01&EmWeb_ns%3Asnmp%3A234.0*s=testWireshark&EmWeb_ns%3Asnmp%3A235=&EmWeb_ns%3Asnmp%3A236=&EmWeb_ns%3Asnmp%3A237=";
        
        $user="";
        $mdp="repuis";
        $adrIP ="172.16.1.29";
        
        //Ouverture d'un socket sur le port 80 (HTTP)
        $fp = fsockopen($adrIP, 70, $errno, $errstr, 1);

        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {  
            
            $crlf="\r\n";
            //requête HTTP
$requete = "POST /cfg/system.html HTTP/1.1
Host: 172.16.1.29
User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Authorization: Basic OnJlcHVpcw==
Connection: keep-alive
Content-Type: application/x-www-form-urlencoded
Content-Length: 154

EmWeb_ns%3Asnmp%3A233=APADSSOL01&EmWeb_ns%3Asnmp%3A234=unAutreTest&EmWeb_ns%3Asnmp%3A235=&EmWeb_ns%3Asnmp%3A236=sinfi%40lerepuis.ch&EmWeb_ns%3Asnmp%3A237=";
   
$out="";
$tabRequete= explode("\n", $requete);
     
foreach($tabRequete as $ligneReq)
{

    if (preg_match('/Host: /', $ligneReq)){$ligneReq="Host: ".$adrIP;}  
    if (preg_match('/Referer: /', $ligneReq)){$ligneReq="";}
    
    $out=$out.$ligneReq."\r\n";
}
            
            fwrite($fp, $out);          
            
            $reponse = '';

            $reponse .= fgets($fp);

            
            fclose($fp);
            echo substr($reponse,0,500);  
            
            if ($reponse != '')
                echo utf8_decode("<br>Requête envoyée avec succès.<br> Requeête: ".$out);
            else
                echo utf8_decode("<br>Erreur dans la commmande."); 

            //file_put_contents( 'c:\temp\outputAVAYA.html', $output );
        }

        ?>
    </body>
</html>
