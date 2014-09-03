<?php 
/****************************************************************************************************
 * page qui effectuera la requête de suppression des AP reçus en paramètre depuis "selectSupprAP.php"
 * 
 * Reçoit en paramètre:
 * - un tableau contenant les informations sur les AP à supprimer (noAP, nomAP, etc.)
 *
 * Modifié le: 31.08.2014
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
                           <li class="active"><a href="selectSupprAP.php">Supprimer</a></li>
                        </ul>
                         <p><b>G&eacute;rer les mod&egrave;les enregistr&eacute;s</b></p>
                        <ul class="nav nav-pills nav-justified">                       
                           <li><a href="ajoutModele.php">Ajouter</a></li>
                           <li><a href="selectModifModele.php">Modifier</a></li>                       
                           <li><a href="selectSupprModele.php">Supprimer</a></li>
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
                        <li><a href="selectSupprAP.php">S&eacute;lection des AP &agrave; supprimer</a></li>                     
                        <li>R&eacute;sultat</li>
                    </ol>
                   <ol>
                        <?php
                            include '../includes/connexionBDD.php';
                            $boutonRetour = '<button class="btn btn-primary" onclick="history.back()">Revenir sur le formulaire</button>';
                            $boutonReinit = '<button class="btn btn-default" onclick="window.location.href = \'selectSupprAP.php\'">Revenir &agrave; la s&eacute;lection des AP</button>';
                            $boutonRetourSucces = '<button class="btn btn-success" onclick="window.location.href = \'../pagesGestionAP/accueilGestionAP.php\'">Afficher la liste des mod&egrave;les</button>';
                           
                            //Récupération des informations
                            if ($_POST) {       
                                $listeAP = unserialize(base64_decode($_POST['listeAP']));                                
                                
                                //suppression des AP
                                try
                                {                            
                                    $i =0;
                                    $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
                                    echo "<p>";
                                    foreach ($listeAP as $AP){
                                        $reqSuppressionAP = $connexion->query("DELETE FROM ".$PARAM_nom_bd.".accessPoints WHERE noAP='".$AP["noAP"]."';");                                   

                                        $infosAP=$AP["noAP"].' - '.$AP["nomAP"].' ('.$AP["adresseIPv4"].')';
                                        if ((!$reqSuppressionAP) ){ echo '<strong>>> Probl&egrave;me lors de la suppression de l\'AP: '.$infosAP.'</strong>!<br>';}
                                        else{ 
                                            echo '>>AP: '.$infosAP.' supprim&eacute; avec succ&egrave;s<br>';
                                            $i++;
                                        }
                                    }
                                    echo "</p><p><u>Nombre d'AP retir&eacute;(s): </u> <strong>".$i."</strong><br>";                                        
                                    echo "<br>------------------------------------------------------</p>";
                                    echo "<p>".$boutonRetourSucces."&nbsp;&nbsp;&nbsp;&nbsp;".$boutonRetour."</p>";
                                    $reqSuppressionAP->closeCursor();                                    
                                }                               
                                catch(Exception $e)
                                {
                                        echo '<tr><td>Erreur : '.$e->getMessage().'<br />';
                                        echo 'N° : '.$e->getCode().'</td></tr>';
                                }                                 
                            }
                            else {echo " <strong>Aucune information reçue. Veuillez remplir le formulaire.</strong><br>";}

                        ?>
                    </ol>
                 </td>
              </tr>
           </tbody>
        </table>                
      </div><!-- /container -->
    <!-- Bootstrap core JavaScript ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->        
  </body>
</html>