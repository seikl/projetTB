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
                           <li class="active"><a href="selectModifModele.php">Modifier</a></li>                       
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
                        <li><a href="#" onclick="history.back()">Ajouter un mod&egrave;le d'AP</a></li>
                        <li>R&eacute;sultat</li>
                    </ol>
                   <ol>
                        <?php
                            include '../includes/connexionBDD.php';
                            
                            
                            //Récupération des informations
                            if ($_POST) {                            
                                $nomModele= $_POST['nomModele'];
                                $versionFirmware= $_POST['versionFirmware'];
                                $nomFabricant= $_POST['nomFabricant'];
                                $adrMACFabricant = $_POST['adrMACFabricant1'].':'.$_POST['adrMACFabricant2'].':'.$_POST['adrMACFabricant3'];

                                $boutonRetour = '<button class="btn btn-primary" onclick="history.back()">Revenir sur le formulaire</button>';
                                $boutonReinit = '<button class="btn btn-default" onclick="window.location.href = \'ajoutModele.php\'">R&eacute;initialiser le formulaire</button>';
                                $boutonRetourSucces = '<button class="btn btn-success" onclick="window.location.href = \'../pagesGestionAP/accueilGestionAP.php\'">Afficher la liste des mod&egrave;les</button>';
                                
                                echo "<table class='table'><tr><th colspan='2'>Informations re&ccedil;ues:</th></tr>";
                                echo "<tr><td>Nom du mod&egrave;le:&nbsp;</td><td>".$nomModele."</td></tr>";
                                echo "<tr><td>Version du firmware:&nbsp;</td><td>".$versionFirmware."</td></tr>";
                                echo "<tr><td>Nom du fabricant:&nbsp;</td><td>".$nomFabricant."</td></tr>";
                                echo "<tr><td>Adresse MAC du Fabricant:&nbsp;</td><td>".$adrMACFabricant."</td></tr>";
                                echo "<tr><td colspan='2'>-----------------------------------------------------------------------</td></tr></table>";
                                
                                //Vérification si le modèle existe déjà avec cette version de firmware
                                try
                                {                            
                                    $i =0;
                                    $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                    $resultatsModeles=$connexion->query("SELECT * FROM modeles m WHERE m.nomModele = '".$nomModele."' AND  m.versionFirmware = '".$versionFirmware."';");
                                    $resultatsModeles->setFetchMode(PDO::FETCH_OBJ);                                    
                                    if ($resultatsModeles->fetch()){
                                        echo "<p><strong> Ce mod&egrave;le existe d&eacute;j&agrave; avec la m&ecirc;me version de firmware</strong>!<br>";
                                        echo "Veuillez effectuer les modifications n&eacute;cessaires</p>";
                                        echo "<p>".$boutonRetour."&nbsp;&nbsp;&nbsp;&nbsp;".$boutonReinit."</p>";
                                    }
                                    else{                                        
                                        $reqEnregistrement = $connexion->query("INSERT INTO modeles (nomModele, versionFirmware,nomFabricant,adrMACFabricant) VALUES ('".$nomModele."','".$versionFirmware."','".$nomFabricant."','".$adrMACFabricant."');");
                                       
                                        if (!$reqEnregistrement){
                                            echo "<p><strong> Probl&egrave;me lors de l'enregistrement</strong>!<br>";
                                            echo "<p>".$boutonRetour."&nbsp;&nbsp;&nbsp;&nbsp;".$boutonReinit."</p>";
                                        }
                                        else{
                                            echo "<p><strong> Enregistrement effect&eacute; avec succ&egrave;s</strong>!<br>";
                                            echo "<p>".$boutonRetourSucces."</p>";   
                                            $reqEnregistrement->closeCursor();
                                            $resultatsModeles->closeCursor();
                                        }                                          
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