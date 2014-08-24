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
                        <li><a href="#" onClick="history.back()">Ajouter un ou plusieurs AP</a></li>
                        <li>R&eacute;sultat</li>
                    </ol>
                   <ol>
                        <?php
                            include '../includes/connexionBDD.php';
                            $boutonRetour = '<button class="btn btn-primary" onclick="history.back()">Revenir sur le formulaire</button>';
                            $boutonReinit = '<button class="btn btn-default" onclick="window.location.href = \'ajoutAP.php\'">R&eacute;initialiser le formulaire</button>';
                            $boutonRetourSucces = '<button class="btn btn-success" onclick="window.location.href = \'../pagesGestionAP/accueilGestionAP.php\'">Afficher la liste des mod&egrave;les</button>';

                            
                            //Récupération des informations
                            if ($_POST) {  
                                $qtyAP=$_POST['qtyAP'];
                                echo "<table class='table'><tr><th'>Informations re&ccedil;ues:</th></tr><tr><td>";

                                
                                //Rcéupération des valeurs
                                for($i=0;$i<$qtyAP;$i++){                                     
                                    $adresseIPv4=$_POST['IPgroupeA'.$i].'.'.$_POST['IPgroupeB'.$i].'.'.$_POST['IPgroupeC'.$i].'.'.$_POST['IPgroupeD'.$i];
                                    if ($_POST['snmpCommunity'.$i]==""){ $snmpCommunity="public";}else{$snmpCommunity=$_POST['snmpCommunity'.$i];}                                    
                                    $tabInfosAP[$i]= array("nomAP" =>$_POST['nomAP'.$i],
                                                    "noModeleAP" =>$_POST['noModeleAP'.$i],
                                                    "adresseIPv4" =>$adresseIPv4,
                                                    "snmpCommunity" =>$snmpCommunity,
                                                    "username" =>$_POST['username'.$i],
                                                    "password" =>$_POST['password'.$i]);
                                    echo $tabInfosAP[$i]["nomAP"].' '.$tabInfosAP[$i]["noModeleAP"].' '.$tabInfosAP[$i]["adresseIPv4"].' '.$tabInfosAP[$i]["snmpCommunity"].' '.$tabInfosAP[$i]["username"].' ******<br>';
                                }   
                                echo "</td></tr>";
                                
                                echo "<tr><td>-----------------------------------------------------------------------</td></tr></table>";
                                
                                    
                                //Vérification si le une IP n'est pas déjà existante dnas la BDD sinon enregistrement des infos
                                try
                                {                            
                                    $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
                                     
                                    $nbErreurs=0;
                                    foreach ($tabInfosAP as $AP){ 
                                        $resultatsAP=$connexion->query("SELECT * FROM accessPoints a WHERE a.adresseIPv4 = '".$AP["adresseIPv4"]."';");
                                        $resultatsAP->setFetchMode(PDO::FETCH_OBJ);                                    
                                        if ($resultatsAP->fetch()){
                                            echo '<p><strong> L\'adresse IP de cet AP ('.$AP["nomAP"].' - '.$AP["adresseIPv4"].') existe d&eacute;j&agrave; pour un autre AP</strong>!<br>';
                                            echo "Veuillez effectuer les modifications n&eacute;cessaires</p>";
                                            $nbErreurs++;
                                        }
                                        else{                                                 
                                            $reqEnregistrement = $connexion->query("INSERT INTO accessPoints (nomAP, adresseIPv4,snmpCommunity,username,password,noModeleAP) VALUES ('".$AP["nomAP"]."','".$AP["adresseIPv4"]."','".$AP["snmpCommunity"]."','".$AP["username"]."','".$AP["password"]."','".$AP["noModeleAP"]."');");

                                            if (!$reqEnregistrement){
                                                echo '<p><strong> Probl&egrave;me lors de l\'enregistrement pour '.$AP["nomAP"].' - '.$AP["adresseIPv4"].' </strong>!<br>';
                                            }
                                            else{
                                                echo '<p> Enregistrement effect&eacute; avec succ&egrave;s pour '.$AP["nomAP"].' - '.$AP["adresseIPv4"].'<br>';                                          
                                            }                                                                                                                                    
                                        }
                                        $resultatsAP->closeCursor();
                                    }
                                    if ($nbErreurs>0){
                                                echo "<p><strong>".$nbErreurs." enregistrement(s)  n'ont pas pu &ecirc;tre effectu&eacute;(s)</strong>!<br>";
                                                echo "<p>".$boutonRetour."&nbsp;&nbsp;&nbsp;&nbsp;".$boutonReinit."</p>";
                                            }
                                            else{
                                                echo "<p><strong> Tous les enregistrement ont &eacute;t&eacute; effect&eacute; avec succ&egrave;s</strong>!<br>";
                                                echo "<p>".$boutonRetourSucces."</p>";                                            
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