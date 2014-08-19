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
                 <td width="30%">                       
                      &nbsp;
                 </td>               
                 <td>                           
                    <ul class="nav nav-tabs nav-justified">
                     <li><a href="../pagesGestionAP/accueilGestionAP.php">Gestion des AP</a></li>
                     <li class="active"><a href="../pagesGestionBDD/accueilGestionBDD.php">Gestion des enregistrements de la BDD</a></li>
                     <li><a href="#">Configuration syst&egrave;me</a></li>
                    </ul>
                    <br>           
                 </td>
              </tr>
              <tr>
                 <td width="30%" class="leftmenu">
                        <p><b>G&eacute;rer les enregistrments des AP</b></p>
                        <ul class="nav nav-pills nav-justified">                       
                           <li><a href="ajoutAP.php">Ajouter</a></li>
                           <li><a href="selectModifAP.php">Modifier</a></li>                       
                           <li><a href="supprimerAP.php">Supprimer</a></li>
                        </ul>
                         <p><b>G&eacute;rer les mod&egrave;les enregistr&eacute;s</b></p>
                        <ul class="nav nav-pills nav-justified">                       
                           <li><a href="ajoutModele.php">Ajouter</a></li>
                           <li><a href="selectModifModele.php">Modifier</a></li>                       
                           <li><a href="supprimerModele.php">Supprimer</a></li>
                        </ul>
                         <p><b>G&eacute;rer les lignes de commandes (CLI)</b></p>
                        <ul class="nav nav-pills nav-justified">                       
                           <li><a href="ajoutCLI.php">Ajouter</a></li>
                           <li><a href="selectModifCLI.php">Modifier</a></li>                       
                           <li><a href="supprimerCLI.php">Supprimer</a></li>
                        </ul>                      
                 </td>                   
                 <td class="informations">                     
                    <ol class="breadcrumb">
                        <li><a href="accueilGestionBDD.php">Accueil gestion de la BDD</a></li> 
                        <li><a href="supprimerModele.php">Supprimer un mod&egrave;le</a></li>
                        <li>R&eacute;sultat</li>
                    </ol>
                   <ol>
                        <?php
                            include '../includes/connexionBDD.php';
                            
                            
                            //Récupération des informations
                            if ($_POST) {       
                                $noModeleAP = $_POST['noModeleAP'];
                                
                                $boutonRetour = '<button class="btn btn-default" onclick="window.location.href = \'supprimerModele.php\'">Revenir &agrave; la s&eacute;lection</button>';
                                $boutonRetourSucces = '<button class="btn btn-success" onclick="window.location.href = \'../pagesGestionAP/accueilGestionAP.php\'">Afficher la liste des mod&egrave;les</button>';
                                                                
                                //Vérification si le modèle existe 
                                try
                                {                            
                                    $i =0;
                                    $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                    $reqSuppressionAP = false;// $connexion->query("DELETE FROM ".$PARAM_nom_bd.".accessPoints WHERE noModeleAP='".$noModeleAP."';");                                   
                                    $reqSuppressionModele = $connexion->query("DELETE FROM modeles WHERE noModeleAP='".$noModeleAP."';");
                                    
                                    if ((!$reqSuppressionAP) && (!$reqSuppressionModele)){ echo "<p><strong> Probl&egrave;me lors de l'envoi de la requ&ecirc;te</strong>!<br><p>".$boutonRetour."</p>";}
                                    else{
                                        if (!$reqSuppressionAP) {echo "<p><strong> Pas d'AP &agrave; supprimer</strong>!</p>";}
                                        else{$reqSuppressionAP->closeCursor();}
                                        
                                        echo "<p><strong> Effacement effect&eacute; avec succ&egrave;s</strong>!<br>";
                                        
                                        $reqSuppressionModele->closeCursor();
                                    }

                                }
                                

                                catch(Exception $e)
                                {
                                        echo '<tr><td>Erreur : '.$e->getMessage().'<br />';
                                        echo 'N° : '.$e->getCode().'</td></tr>';
                                }                                
echo "<H1>".$noModeleAP."</h1>";
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