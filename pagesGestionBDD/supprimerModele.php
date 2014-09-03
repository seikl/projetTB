<?php 
/****************************************************************************************************
 * page qui effectuera la requête de suppression d'un modèle de périphérique réseau.
 * 
 * Reçoit en paramètre:
 * - un tableau contenant les informations sur le modèle à supprimer:
 *  (noModeleAP, nomFabricant, etc.)
 * 
 *  NB: Tous les périphériques réseaux (noAP) et lignes de commandes qui y sont rattachés (noCLI) sont
 * automatiquement supprimés.
 * 
 * Modifié le: 28.08.2014
 ***************************************************************************************************/
$auth_realm = 'AP Tool'; require_once '../includes/authentification.php'; ?> 
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>AP Tool</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <script type="text/javascript" src="../js/jquery-1.11.1.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/additional-methods.js"></script>
    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
  </head>

  <body>
      <p align="right"><br><a href="?action=logOut">LOGOUT</a>&nbsp;&nbsp;&nbsp;</p>
      <br>
    <div class="container-fluid">        

        <table border="0" width="90%" align="center">
           <tbody>
              <tr>
                <?php include '../includes/menus.php'; echo $menuPagesGestionBDD; ?>
              <tr>
                 <td width="30%" class="leftmenu">
                        <p><b>G&eacute;rer les enregistrments des AP</b></p>
                        <ul class="nav nav-pills nav-justified">                       
                           <li><a href="ajoutAP.php">Ajouter</a></li>
                           <li><a href="selectModifAP.php">Modifier</a></li>                       
                           <li><a href="selectSupprAP.php">Supprimer</a></li>
                        </ul>
                         <p><b>G&eacute;rer les mod&egrave;les enregistr&eacute;s</b></p>
                        <ul class="nav nav-pills nav-justified">                       
                           <li><a href="ajoutModele.php">Ajouter</a></li>
                           <li><a href="selectModifModele.php">Modifier</a></li>                       
                           <li class="active"><a href="selectSupprModele.php">Supprimer</a></li>
                        </ul>
                         <p><b>G&eacute;rer les lignes de commandes (CLI)</b></p>
                        <ul class="nav nav-pills nav-justified">                       
                           <li><a href="ajoutCLI.php">Ajouter</a></li>
                           <li><a href="selectModifCLI.php">Modifier</a></li>                       
                           <li><a href="selectSupprCLI.php">Supprimer</a></li>
                        </ul>                      
                 </td>
                 
                 <td class="informations">                     
                    <ol class="breadcrumb">
                        <li><a href="accueilGestionBDD.php">Accueil gestion de la BDD</a></li> 
                        <li><a href="selectSupprModele.php">Supprimer un mod&egrave;le</a></li>
                        <li>R&eacute;sultat</li>
                    </ol>
                   <ol>
                        <?php
                            include '../includes/connexionBDD.php';
                            
                            
                            //Récupération des informations
                            if ($_POST) {       
                                $noModeleAP = $_POST['noModeleAP'];
                                $nomFabricant = $_POST['nomFabricant'];
                                $versionFirmware = $_POST['versionFirmware'];
                                $nomModele = $_POST['nomModele'];
                                
                                $boutonRetour = '<button class="btn btn-default" onclick="window.location.href = \'selectSupprModele.php\'">Revenir &agrave; la s&eacute;lection</button>';
                                $boutonRetourSucces = '<button class="btn btn-success" onclick="window.location.href = \'../pagesGestionAP/accueilGestionAP.php\'">Afficher la liste des mod&egrave;les</button>';
                                                                
                                //Vérification si le modèle existe 
                                try
                                {                            
                                    $i =0;
                                    $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                    $reqNombreCLI = $connexion->query("SELECT * FROM ".$PARAM_nom_bd.".lignesCommande WHERE noModeleAP='".$noModeleAP."';");
                                    $reqNombreAP = $connexion->query("SELECT * FROM ".$PARAM_nom_bd.".accessPoints WHERE noModeleAP='".$noModeleAP."';"); 
                                    $listeCLI = $reqNombreCLI->fetchAll();
                                    $listeAP = $reqNombreAP->fetchAll();
                                    $reqSuppressionCLI = $connexion->query("DELETE FROM ".$PARAM_nom_bd.".lignesCommande WHERE noModeleAP='".$noModeleAP."';");                                     
                                    $reqSuppressionAP = $connexion->query("DELETE FROM ".$PARAM_nom_bd.".accessPoints WHERE noModeleAP='".$noModeleAP."';");                                   
                                    $reqSuppressionModele = $connexion->query("DELETE FROM ".$PARAM_nom_bd.".modeles WHERE noModeleAP='".$noModeleAP."';");
                                    
                                    if ((!$reqSuppressionAP) && (!$reqSuppressionModele) && (!$reqSuppressionCLI)){ echo "<p><strong> Probl&egrave;me lors de l'envoi de la requ&ecirc;te</strong>!<br><p>".$boutonRetour."</p>";}
                                    else{                                      
                                        echo "<p><strong> Suppression du mod&egrave;le \"".$nomFabricant." ".$nomModele." (".$versionFirmware.")\" effect&eacute;e avec succ&egrave;s</strong>!<br>";
                                        echo "<p><u>Nombre d'AP retir&eacute;(s): ".$reqSuppressionAP->rowCount()."</u><br> ";
                                        foreach ($listeAP as $AP){
                                            echo '>> '.$AP["noAP"].' - '.$AP["nomAP"].' ('.$AP["adresseIPv4"].')<br>';                                            
                                        }                                        
                                        echo "</p>";
                                        echo "<p><u>Nombre de lignes de commande retir&eacute;e(s): ".$reqSuppressionCLI->rowCount()."</u><br> ";
                                        foreach ($listeCLI as $CLI){
                                            echo '>> '.$CLI["noCLI"].' - '.$CLI["ligneCommande"].' ('.$CLI["protocole"].'[:'.$CLI["portProtocole"].'])<br>';                                            
                                        }                                        
                                        echo "<br>------------------------------------------------------</p>";
                                        echo "<p>".$boutonRetourSucces."&nbsp;&nbsp;&nbsp;&nbsp;".$boutonRetour."</p>";
                                        $reqSuppressionModele->closeCursor();
                                        $reqSuppressionCLI->closeCursor();
                                        $reqSuppressionAP->closeCursor();
                                        $reqNombreAP->closeCursor();
                                        $reqNombreCLI->closeCursor();
                                    }

                                }                               
                                catch(Exception $e)
                                {
                                        echo '<tr><td>Erreur : '.$e->getMessage().'<br />';
                                        echo 'N° : '.$e->getCode().'</td></tr>';
                                }                                
                            }
                            else {echo " <strong>Aucune information reçue. Veuillez corriger la s&eacute;lection.</strong><br>";}

                        ?>
                    </ol>
                 </td>
              </tr>
           </tbody>
        </table>                
      </div><!-- /container -->
    <!-- Bootstrap core JavaScrip ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->        
  </body>
</html>