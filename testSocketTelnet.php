<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>AP Manager - Accueil</title>
    </head>
    <body>
        <?php
        //Exemple d'interragation d'une borne avec la commande show system
        $fp = fsockopen("192.168.98.36", 23, $errno, $errstr, 30);

        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $out = "abcd1234\r\n";
            $out .= "show system\r\n";
            $out .= "quit\r\n";
            fwrite($fp, $out);

            $output = '';
            while (!feof($fp)) {
                $output .= fgets($fp, 128);
            }

            fclose($fp);

            //file_put_contents( 'c:\temp\outputAVAYA.txt', $output );

            echo $output;
        }

        ?>
    </body>
</html>
