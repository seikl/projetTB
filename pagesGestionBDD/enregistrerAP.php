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
                           <li><a href="selectSupprModele.php">Supprimer</a></li>
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
                        <li><a href="#" onClick="history.back()">Ajouter un ou plusieurs AP</a></li>
                        <li>R&eacute;sultat</li>
                    </ol>
                   <ol>
                        <?php
                            include '../includes/connexionBDD.php';
                            $boutonRetour = '<button class="btn btn-primary" onclick="history.back()">Revenir sur le formulaire</button>';
                            $boutonReinit = '<button class="btn btn-default" onclick="window.location.href = \'ajoutModele.php\'">R&eacute;initialiser le formulaire</button>';
                            $boutonRetourSucces = '<button class="btn btn-success" onclick="window.location.href = \'../pagesGestionAP/accueilGestionAP.php\'">Afficher la liste des mod&egrave;les</button>';

                            
                            //Récupération des informations
                            if ($_POST) {  
                                $qtyAP=$_POST['qtyAP'];
                                echo "<table class='table'><tr><th'>Informations re&ccedil;ues:</th></tr>";

                                
                                //Rcéupération des valeurs
                                for($i=0;$i<=$qtyAP;$i++){                                     
                                    $adresseIPv4=$_POST['IPgroupeA'.$i].'.'.$_POST['IPgroupeB'.$i].'.'.$_POST['IPgroupeC'.$i].'.'.$_POST['IPgroupeD'.$i];
                                    $tabInfosAP[$i]= array("nomAP" =>$_POST['nomAP'.$i],
                                                    "noModeleAP" =>$_POST['noModeleAP'.$i],
                                                    "adresseIPv4" =>$adresseIPv4,
                                                    "snmpCommunity" =>$_POST['snmpCommunity'.$i],
                                                    "username" =>$_POST['username'.$i],
                                                    "password" =>$_POST['password'.$i]);

                                }   
                                echo "<tr><td>";print_r($tabInfosAP);echo"</td></tr>";
                                
                                echo "<tr><td>-----------------------------------------------------------------------</td></tr></table>";
                                
                                    
                                //Vérification si le modèle existe déjà avec cette version de firmware
                                try
                                {                            
                                    $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                    foreach ($tabInfosAP as $AP){ 
                                        $resultatsAP=$connexion->query("SELECT * FROM accessPoints a WHERE a.adresseIPv4 = '".$AP["adresseIPv4"]."';");
                                        $resultatsAP->setFetchMode(PDO::FETCH_OBJ);                                    
                                        if ($resultatsAP->fetch()){
                                            echo "<p><strong> Ce mod&egrave;le (".$AP["nomAP"].") existe d&eacute;j&agrave; avec la m&ecirc;me adresse IP</strong>!<br>";
                                            echo "Veuillez effectuer les modifications n&eacute;cessaires</p>";
                                            echo "<p>".$boutonRetour."&nbsp;&nbsp;&nbsp;&nbsp;".$boutonReinit."</p>";
                                        }
                                        else{                                        
                                            $reqEnregistrement = $connexion->query("INSERT INTO accessPoints (nomAP, adresseIPv4,snmpCommunity,username,password,noModeleAP) VALUES ('".$AP["nomAP"]."','".$AP["adresseIPv4"]."','".$AP["snmpCommunity"]."','".$AP["username"]."','".$AP["password"]."','".$AP["noModeleAP"]."');");

                                            if (!$reqEnregistrement){
                                                echo "<p><strong> Probl&egrave;me lors de l'enregistrement</strong>!<br>";
                                                echo "<p>".$boutonRetour."&nbsp;&nbsp;&nbsp;&nbsp;".$boutonReinit."</p>";
                                            }
                                            else{
                                                echo "<p><strong> Enregistrement effect&eacute; avec succ&egrave;s</strong>!<br>";
                                                echo "<p>".$boutonRetourSucces."</p>";                                            
                                            }                                                                                                                                    
                                        }
                                        $resultatsAP->closeCursor();
                                    }
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
    <!-- Bootstrap core JavaScrip ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->        
  </body>
</html>