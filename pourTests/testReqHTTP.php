<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>AP Manager - Accueil</title>
    </head>
    <body>
        <?php
        
        $uri = "EmWeb_ns%3Asnmp%3A233=APADSS0L01&EmWeb_ns%3Asnmp%3A234.0*s=testWireshark&EmWeb_ns%3Asnmp%3A235=&EmWeb_ns%3Asnmp%3A236=&EmWeb_ns%3Asnmp%3A237=";
        
        $adrIP ="172.16.1.29";
        
        //Ouverture d'un socket sur le port 80 (HTTP)
        $fp = fsockopen($adrIP, 80, $errno, $errstr, 30);

        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {  
            //requête HTTP
            $out = "POST /cfg/system.html HTTP/1.1
            Accept: text/html, application/xhtml+xml, */*
            Referer: /cfg/system.html
            Accept-Language: fr-FR
            User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko
            Content-Type: application/x-www-form-urlencoded
            Accept-Encoding: gzip, deflate
            Host: ".$adrIP."
            Content-Length: 158
            DNT: 1
            Connection: Keep-Alive
            Cache-Control: no-cache
            Authorization: Basic OnJlcHVpcw==

            EmWeb_ns%3Asnmp%3A233=APADSSOL01&EmWeb_ns%3Asnmp%3A234=testWireshark&EmWeb_ns%3Asnmp%3A235=&EmWeb_ns%3Asnmp%3A236.0*s=sinfi@lerepuis.ch&EmWeb_ns%3Asnmp%3A237=";
            
            fwrite($fp, $out);          
            
            $reponse = '';

            $reponse .= fgets($fp);

            
            fclose($fp);
            echo substr($reponse,0,500);  
            
            if ($reponse != '')
                echo utf8_decode("<br>Requête envoyée avec succès.");
            else
                echo utf8_decode("<br>Erreur dans la commmande."); 

            //file_put_contents( 'c:\temp\outputAVAYA.html', $output );
        }

        ?>
    </body>
</html>
