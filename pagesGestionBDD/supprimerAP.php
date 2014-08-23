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

                                        if ((!$reqSuppressionAP) ){ echo '<strong>>> Probl&egrave;me lors de la suppression de l\'AP: '.$AP["noAP"].' - '.$AP["nomAP"].' ('.$AP["adresseIPv4"].')</strong>!<br>';}
                                        else{ 
                                            echo '>>AP: '.$AP["noAP"].' - '.$AP["nomAP"].' ('.$AP["adresseIPv4"].') supprim&eacute; avec succ&egrave;s<br>';
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