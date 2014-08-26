<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>AP Manager - Accueil</title>
    </head>
    <body>
        <?php
       
        $request = new HTTP_Request2('https://someserver.com/somepath/something',
    HTTP_Request2::METHOD_POST);

$request->setConfig(array(
    'ssl_verify_peer'   => FALSE,
    'ssl_verify_host'   => FALSE
))

        ?>
    </body>
</html>
