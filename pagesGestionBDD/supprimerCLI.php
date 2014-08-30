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
                           <li><a href="selectSupprModele.php">Supprimer</a></li>
                        </ul>
                         <p><b>G&eacute;rer les lignes de commandes (CLI)</b></p>
                        <ul class="nav nav-pills nav-justified">                       
                           <li><a href="ajoutCLI.php">Ajouter</a></li>
                           <li><a href="selectModifCLI.php">Modifier</a></li>                       
                           <li class="active"><a href="selectSupprCLI.php">Supprimer</a></li>
                        </ul>                      
                 </td>
                 
                 <td class="informations">                     
                    <ol class="breadcrumb">
                        <li><a href="accueilGestionBDD.php">Accueil gestion de la BDD</a></li> 
                        <li><a href="selectSupprCLI.php">S&eacute;lection des commandes &agrave; supprimer</a></li>                     
                        <li>R&eacute;sultat</li>
                    </ol>
                   <ol>
                        <?php
                            include '../includes/connexionBDD.php';
                            $boutonRetour = '<button class="btn btn-primary" onclick="history.back()">Revenir sur le formulaire</button>';
                            $boutonReinit = '<button class="btn btn-default" onclick="window.location.href = \'selectSupprCLI.php\'">Revenir &agrave; la s&eacute;lection des AP</button>';
                            $boutonRetourSucces = '<button class="btn btn-success" onclick="window.location.href = \'../pagesGestionAP/choisirCommande.php\'">Appliquer une commande</button>';
                           
                            //Récupération des informations
                            if ($_POST) {       
                                $listeCLI = unserialize(base64_decode($_POST['listeCLI']));                                
                                
                                //suppression des AP
                                try
                                {                            
                                    $i =0;
                                    $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
                                    echo "<p>";
                                    foreach ($listeCLI as $CLI){
                                        $reqSuppressionCLI = $connexion->query("DELETE FROM ".$PARAM_nom_bd.".lignesCommande WHERE noCLI='".$CLI["noCLI"]."';");  
                                        
                                        $infosCLI=$CLI["noCLI"].' - '.$CLI["ligneCommande"].'( protocole:'.strtoupper($CLI["protocole"]).'['.$CLI["portProtocole"].'], <br>'.$CLI["typeCommande"].' - '.$CLI["description"].')';

                                        if ((!$reqSuppressionCLI) ){ echo '<strong>>> Probl&egrave;me lors de la suppression de la commande: '.$infosCLI.'</strong>!<br><br>';}
                                        else{ 
                                            echo '>>Commande: '.$infosCLI.' supprim&eacute;e avec succ&egrave;s<br><br>';
                                            $i++;
                                        }
                                    }
                                    echo "</p><p><u>Nombre de commande(s) retir&eacute;e(s): </u> <strong>".$i."</strong><br>";                                        
                                    echo "<br>------------------------------------------------------";
                                    echo "<p>".$boutonRetourSucces."&nbsp;&nbsp;&nbsp;&nbsp;".$boutonRetour."</p>";
                                    $reqSuppressionCLI->closeCursor();                                    
                                }                               
                                catch(Exception $e)
                                {
                                        echo 'Erreur : '.$e->getMessage().'<br>';
                                        echo 'N° : '.$e->getCode().'<br>';
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