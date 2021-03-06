<?php 
/**************************************************************************************************** 
 * Auteur: Sébastien Kleber (sebastien.kleber@heig-vd.ch) 
 * 
 * Description:
 * page d'enregistrement des modifications effectuées sur un ou plusieurs periphériques réseaux 
 * avec les informations reçues depuis "modfierAP.php"
 * 
 * Paramètres reçus dans un tableaux contenant:
 * - noAP: le no d'identifiant de l'AP dans la BDD
 * - nomAP: Le nom du périhpérique réseau
 * - adresseIPv4: l'adresse IPv4 du périphérique
 * - noModeleAP: Le no de modèle correspondant
 * - snmpCommunity: la communauté SNMP du périphérique à enregistrer ("public" si champs vide)
 * - username: le nom d'utilisateur (champs vide possible)
 * - password: le mot de passe du périphérique à enregistrer
 *
 * Modifié le: 01.09.2014
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
                           <li class="active"><a href="selectModifAP.php">Modifier</a></li>                       
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
                           <li><a href="selectSupprCLI.php">Supprimer</a></li>
                        </ul>                      
                 </td>
                 
                 <td class="informations">                     
                    <ol class="breadcrumb">
                        <li><a href="accueilGestionBDD.php">Accueil gestion de la BDD</a></li> 
                        <li><a href="selectModifAP.php">Choix des AP &agrave; modifier</a></li> 
                        <li><a href="#" onClick="history.back();">Modifier des AP enregistr&eacute;s</a></li>                        
                        <li>R&eacute;sultat</li>
                    </ol>
                   <ol>
                        <?php
                            include '../includes/connexionBDD.php';
                            $boutonRetour = '<button class="btn btn-primary" onclick="history.back()">Revenir sur le formulaire</button>';
                            $boutonReinit = '<button class="btn btn-default" onclick="window.location.href = \'selectModifAP.php\'">Revenir &agrave; la s&eacute;lection des AP</button>';
                            $boutonRetourSucces = '<button class="btn btn-success" onclick="window.location.href = \'../pagesGestionAP/accueilGestionAP.php\'">Afficher la liste des mod&egrave;les</button>';

                            
                            //Récupération des informations
                            if ($_POST) {  
                                $qtyAP=$_POST['qtyAP'];
                                echo "<table class='table'><tr><th'>Informations re&ccedil;ues:</th></tr><tr><td>";

                                
                                //Rcéupération des valeurs
                                for($i=0;$i<$qtyAP;$i++){                                     
                                    $adresseIPv4=$_POST['IPgroupeA'.$i].'.'.$_POST['IPgroupeB'.$i].'.'.$_POST['IPgroupeC'.$i].'.'.$_POST['IPgroupeD'.$i];
                                    if ($_POST['snmpCommunity'.$i]==""){ $snmpCommunity="public";}else{$snmpCommunity=$_POST['snmpCommunity'.$i];}                                    
                                    $tabInfosAP[$i]= array("noAP" =>$_POST['noAP'.$i],
                                                    "nomAP" =>$_POST['nomAP'.$i],
                                                    "noModeleAP" =>$_POST['noModeleAP'.$i],
                                                    "adresseIPv4" =>$adresseIPv4,
                                                    "snmpCommunity" =>$snmpCommunity,
                                                    "username" =>$_POST['username'.$i],
                                                    "password" =>$_POST['password'.$i]);
                                    echo $tabInfosAP[$i]["nomAP"].' '.$tabInfosAP[$i]["noModeleAP"].' '.$tabInfosAP[$i]["adresseIPv4"].' '.$tabInfosAP[$i]["snmpCommunity"].' '.$tabInfosAP[$i]["username"].' ******<br>';
                                }   
                                echo "</td></tr>";
                                
                                echo "<tr><td>-----------------------------------------------------------------------</td></tr></table>";
                                
                                    
                                //Vérification si le une IP n'est pas déjà existante sinon enregistrement des infos
                                try
                                {                            
                                    $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
                                     
                                    $nbErreurs=0;
                                    foreach ($tabInfosAP as $AP){ 
                                        $resultatsAP=$connexion->query("SELECT * FROM accessPoints a WHERE a.adresseIPv4 = '".$AP["adresseIPv4"]."' AND a.noAP <> '".$AP["noAP"]."';");
                                        $resultatsAP->setFetchMode(PDO::FETCH_OBJ);                                    
                                        if ($resultatsAP->fetch()){
                                            echo '<p><strong>L\'adresse IP de cet AP ('.$AP["noAP"].' - '.$AP["nomAP"].' - '.$AP["adresseIPv4"].') existe d&eacute;j&agrave; pour un autre AP</strong>!<br>';
                                            echo "Veuillez effectuer les modifications n&eacute;cessaires</p>";
                                            $nbErreurs++;
                                        }
                                        else{                                                 
                                            $reqEnregistrement = $connexion->query("UPDATE ".$PARAM_nom_bd.".accessPoints SET nomAP='".$AP["nomAP"]."',adresseIPv4='".$AP["adresseIPv4"]."',snmpCommunity='".$AP["snmpCommunity"]."',username='".$AP["username"]."',password='".$AP["password"]."',noModeleAP='".$AP["noModeleAP"]."' WHERE noAP='".$AP["noAP"]."';");

                                            if (!$reqEnregistrement){
                                                echo '<p><strong> Probl&egrave;me lors de l\'enregistrement pour '.$AP["nomAP"].' - '.$AP["adresseIPv4"].' </strong>!<br>';
                                            }
                                            else{
                                                echo '<p> Enregistrement effect&eacute; avec succ&egrave;s pour '.$AP["nomAP"].' - '.$AP["adresseIPv4"].'<br>';  
                                                $resultatsAP->closeCursor();
                                                $reqEnregistrement->closeCursor();
                                            }                                                                                                                                    
                                        }                                        
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