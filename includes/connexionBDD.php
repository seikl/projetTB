<?php
$PARAM_hote='localhost'; // le chemin vers le serveur
$PARAM_port='3306';
$PARAM_nom_bd='apmanagerdb'; // le nom de votre base de données
$PARAM_utilisateur='apmanager'; // nom d'utilisateur pour se connecter
$PARAM_mot_passe='apmanager01'; // mot de passe de l'utilisateur pour se connecter

try
{
        $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

        $resultats=$connexion->query("SELECT * FROM modeles"); // on va chercher tous les membres de la table qu'on trie par ordre croissant
        $resultats->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
        while( $ligne = $resultats->fetch() ) // on récupère la liste des membres
        {
                echo 'Mod&egrave;les : '.(string)$ligne->nomModele.'<br />'; // on affiche les membres
        }
        $resultats->closeCursor(); // on ferme le curseur des résultats
        }
 
catch(Exception $e)
{
        echo 'Erreur : '.$e->getMessage().'<br />';
        echo 'N° : '.$e->getCode();
}

?>

