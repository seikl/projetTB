<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>AP Manager - Accueil</title>
    </head>
    <body>
        <?php

        
// Initialize session and set URL.
// must set $url first. Duh...
$url="https://aptool";
$http = curl_init($url);
// do your curl thing here
$result = curl_exec($http);
$http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
curl_close($http);


echo "reponse: <br>";
echo $http_status;;


        ?>
    </body>
</html>
