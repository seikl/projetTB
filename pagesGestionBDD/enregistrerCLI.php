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
                        <li><a href="#" onclick="history.back()">Ajouter une ligne de commande</a></li>
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
                                $ligneCommande= $_POST['ligneCommande'];
                                $protocole= $_POST['protocole'];
                                $portProtocole= $_POST['portProtocole'];
                                $noModeleAP= $_POST['noModeleAP'];
                                $choixAjoutDescription = $_POST['choixAjoutDescription'];
                                
                                if ((isset($_POST['choixTypeCommande'])) && ($choixAjoutDescription == 'selection')){
                                    $typeCommande=unserialize(base64_decode($_POST['choixTypeCommande'])); 
                                    echo "commande selection";
                                }
                                else if((isset($_POST['typeCommande'])) && ($choixAjoutDescription =='ajout')){
                                    echo "commande ajout";                                                                        
                                }                         
                                else {" <strong>Erreur avec la description de la commande re&ccedil;ue. Veuillez corriger le formulaire.</strong><br>".$boutonRetour;}
                                
                                
                                /*
                                echo "<table class='table'><tr><th colspan='2'>Informations re&ccedil;ues:</th></tr>";
                                echo "<tr><td>Nom du mod&egrave;le:&nbsp;</td><td>".$nomModele."</td></tr>";
                                echo "<tr><td>Version du firmware:&nbsp;</td><td>".$versionFirmware."</td></tr>";
                                echo "<tr><td>Nom du fabricant:&nbsp;</td><td>".$nomFabricant."</td></tr>";
                                echo "<tr><td>Adresse MAC du Fabricant:&nbsp;</td><td>".$adrMACFabricant."</td></tr>";
                                echo "<tr><td colspan='2'>-----------------------------------------------------------------------</td></tr></table>";                                
                                */
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