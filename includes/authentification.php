<?php

//script pour l'authentificaton:
//récupéré sur http://php.net/manual/en/features.http-auth.php, consulté le 30.08.2014

$_user_ = '';
$_password_ = 'admin';

$_pageHTML_='<!DOCTYPE html>
<html lang="fr"><head>
    <title>AP Tool</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <script type="text/javascript" src="../js/jquery-1.11.1.js"></script>
    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
  </head>

  <body><br>';


session_start();

$url_action = (empty($_REQUEST['action'])) ? 'logIn' : $_REQUEST['action'];
$auth_realm = (isset($auth_realm)) ? $auth_realm : '';

if (isset($url_action)) {
    if (is_callable($url_action)) {
        call_user_func($url_action);
    } else {
        echo $_pageHTML_;        
        echo '<p>&nbsp;&nbsp;<b>Cete fonction n\'existe pas. Requ&ecirc;te annul&eacute;e</b></p></body></html>';
    };
};

function logIn() {
    global $auth_realm;
    global $_pageHTML_;

    if (!isset($_SESSION['username'])) {
        if (!isset($_SESSION['login'])) {
            $_SESSION['login'] = TRUE;
            header('WWW-Authenticate: Basic realm="'.$auth_realm.'"');
            header('HTTP/1.0 401 Unauthorized');
            echo $_pageHTML_;
            echo '<p>&nbsp;&nbsp;<b>Login invalide</b><br>';
            echo '<p>&nbsp;&nbsp;<a href="?action=logOut">R&eacute;essayer</a></p></body></html>';
            exit;
        } else {
            $user = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
            $password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
            $result = authenticate($user, $password);
            if ($result == 0) {
                $_SESSION['username'] = $user;
            } else {
                session_unset($_SESSION['login']);
                errMes($result);
                echo $_pageHTML_;
                echo '&nbsp;&nbsp;<a href="">Nouvel essai</a></p></body></html>';
                exit;
            };
        };
    };
}

function authenticate($user, $password) {
    global $_user_;
    global $_password_;
    global $_pageHTML_;

    if (($user == $_user_)&&($password == $_password_)) { return 0; }
    else { return 1; };
}

function errMes($errno) {
    global $_pageHTML_;
    switch ($errno) {
        case 0:
            break;
        case 1:
            echo $_pageHTML_;
            echo '<p><b>&nbsp;&nbsp;Mot de passe incorrect</b><br>';
            break;
        default:
            echo $_pageHTML_;
            echo '<p><b>&nbsp;&nbsp;Erreur inconnue</b></p><br>';
    };
}

function logOut() {
    global $_pageHTML_;
    
    session_destroy();
    if (isset($_SESSION['username'])) {
        session_unset($_SESSION['username']);
        echo $_pageHTML_;
        echo "<b>&nbsp;&nbsp;Vous avez bien &eacute;t&eacute; d&eacute;connect&eacute;</b>";
        echo '<p>&nbsp;&nbsp;<a href="?action=logIn">Se reconnecter</a></p></body></html>';
    } else {
        header("Location: ?action=logIn", TRUE, 301);
    };
    if (isset($_SESSION['login'])) { session_unset($_SESSION['login']); };
    exit;
}

?>