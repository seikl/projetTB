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
      <br><br>
    <div class="container-fluid">        

        <table border="0" width="90%" align="center">
           <tbody>
              <tr>
                <?php include '../includes/menus.php'; echo $menuPagesGestionBDD; ?>                  
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
                                        echo "<p>Nombre d'AP retir&eacute;(s): ".$reqSuppressionAP->rowCount()." <br> (liste: <br> ";print_r($listeAP);echo ")</p>";
                                        echo "<p>Nombre de lignes de commande retir&eacute;e(s): ".$reqSuppressionCLI->rowCount()." <br> (liste: <br> ";print_r($listeCLI);echo ")</p>";
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