<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>AP Manager - Accueil</title>
    </head>
    <body>
        <?php
        //Exemple d'interragation d'une borne avec la commande show system
        $fp = fsockopen("172.16.1.29", 23, $errno, $errstr, 30);

        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $out = "repuis\r\n";
            $out .= "show system\r\n";
            $out .= "quit\r\n";
            fwrite($fp, $out);

            $output = '';
            while (!feof($fp)) {
                $output .= fgets($fp, 128);
            }

            fclose($fp);

            //file_put_contents( 'c:\temp\outputAVAYA.txt', $output );

            echo utf8_decode($output);
        }

        ?>
    </body>
</html>
