<?php
$ini_array = parse_ini_file("loginInfo.ini");

$_user_ = '';
$_password_ = $ini_array["mdp"];

$PARAM_hote=$ini_array["hote"]; // le chemin vers le serveur
$PARAM_port=$ini_array["port"];
$PARAM_nom_bd=$ini_array["nom_bd"];// le nom de votre base de données
$PARAM_utilisateur=$ini_array["utilisateur"]; // nom d'utilisateur pour se connecter
$PARAM_mot_passe=$ini_array["mot_passe"];// mot de passe de l'utilisateur pour se connecter
?>