<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>AP Manager - Accueil</title>
    </head>
    <body>
        <?php
        //Exemple d'interragation d'une borne avec une requête HTTP
        $fp = fsockopen("172.16.1.29", 80, $errno, $errstr, 30);

        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $out = "GET /cfg/splash.html HTTP/1.1\r\n";
            $out .= "Accept: text/html, application/xhtml+xml, */*\r\n";
            $out .= "Host: 172.16.1.29\r\n";
            $out .= "DNT: 1\r\n";
            $out .= "Connection: Keep-Alive\r\n";
            $out .= "Authorization: Basic OnJlcHVpcw==\r\n\r\n";
            $out .= "quit\r\n";

            fwrite($fp, $out);

            $output = '';
            while (!feof($fp)) {
                $output .= fgets($fp, 128);
            }

            fclose($fp);

            //file_put_contents( 'c:\temp\outputAVAYA.html', $output );

            echo substr($output,0,20);
        }

        ?>
    </body>
</html>
