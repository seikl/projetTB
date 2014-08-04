<!DOCTYPE html>
<html lang="en">
  <head>
    <title>AP Manager</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <script type="text/javascript" src="../js/jquery-1.11.1.js"></script>                
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
                     <li class="active"><a href="../pagesGestionAP/accueilGestionAP.php">Gestion des AP</a></li>
                     <li><a href="../pagesGestionBDD/accueilGestionBDD.php">Gestion des enregistrements de la BDD</a></li>
                     <li><a href="#">Configuration syst&egrave;me</a></li>
                    </ul>
                    <br>           
                 </td>
              </tr>
              <tr>
                 <td width="30%" class="leftmenu">
                        <p><b>Informations sur les AP</b></p>
                        <ul class="nav nav-pills nav-stacked">                       
                            <li><a href="afficherListeAP.php">Afficher la liste  de tous les AP inscrits</a></li>
                           <li><a href="rechercherAP.php">Rechercher des AP sur le r&eacute;seau</a></li>                       
                        </ul>
                        <p><b>Configurer les AP</b></p>
                        <ul class="nav nav-pills nav-stacked">                       
                           <li class="active"><a href="#">Appliquer une commande &agrave; un ou plusieurs AP</a></li>    
                        </ul> 
                 </td>  
            
                 <td class="informations">
                    <ol class="breadcrumb">
                        <li><a href="accueilGestionAP.php">Accueil gestion des AP</a></li>    
                        <li>Appliquer une commande &agrave; un ou plusieurs AP</li>
                    </ol>  
                     <ol>
                         
                    <form id="form" class="form-inline" role="form" action="appliquerCommande.php" method="POST">
                        <div class="form-group">       
                            
                            <label for="name">Veuillez s&eacute;lectionner les AP concern&eacute;s et/ou trier selon le type de mat&eacute;riel, puis choisir la commande &agrave; leur appliquer:</label><br>
                            <select class="form-control" id="noModele" name="noModele" onChange="document.getElementById('noModele').submit()">
                            
                     
                     <?php                                          
                        //connexion a la BDD et récupération de la liste des modèles
                        include '../includes/connexionBDD.php';                    
                        include '../includes/fonctionsUtiles.php';
                                                
                        if (!isset($_POST['noModele'])){                            
                            $noModele='0';
                            echo "<option value='0' selected>Trier par mod&egrave;les...&nbsp;&nbsp;&nbsp;</option>";
                        }
                        else {
                            $noModele = $_POST['noModele'];   
                            echo "<option value='0'>Trier par mod&egrave;les...&nbsp;&nbsp;&nbsp;</option>";
                        }
                            

                        try
                        {
                            
                                $i =0;
                                $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                $resultatsModelesAP=$connexion->query("SELECT * FROM modeles;");                                 
                                $resultatsModelesAP->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet                                
                                
                                while( $ligne = $resultatsModelesAP->fetch() ) // on récupère la liste des membres
                                {     
                                    $noModeleAP=(string)$ligne->noModeleAP;
                                    $nomModele=(string)$ligne->nomModele;
                                    $versionFirmware=(string)$ligne->versionFirmware;
                                    $nomFabricant=(string)$ligne->nomFabricant;
                                    $adrMACFabricant=(string)$ligne->adrMACFabricant; 
                                    if ($noModeleAP==$noModele){
                                        echo '<option value="'.$noModeleAP.'" selected>'.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.'&nbsp;&nbsp;&nbsp;</option>';
                                    }
                                    else {
                                        echo '<option value="'.$noModeleAP.'">'.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.'&nbsp;&nbsp;&nbsp;</option>';  
                                    }
                                }
                            $resultatsModelesAP->closeCursor(); // on ferme le curseur des résultats
                                                
                        }

                        catch(Exception $e)
                        {
                                echo '</select></form><li>Erreur lors du chargement</li></ol>';
                                echo 'Erreur : '.$e->getMessage().'<br />';
                                echo 'N° : '.$e->getCode();
                        }                        
                     ?>    
                            </select>                                                                                        
                    </div>
                    </form>
                         <br>
                    <form id="form" class="form-inline" role="form" action="appliquerCommande.php" method="POST">
                        <div class="form-group">    
                            <table>
                                <tr>
                                    <td>
                                        <label for="name">Mutiple Select list</label><br>
                                        <select multiple class="form-control" name="APAchoisir[]">
                                           <option>1</option>
                                           <option>2</option>
                                           <option>3</option>
                                           <option>4</option>
                                           <option>5</option>
                                        </select>
                                    </td>
                                    <td>
                                        &nbsp;&nbsp;<input type="submit" id="submit" class="btn btn-primary" value="Ajouter -->"/><br><br> 
                                        &nbsp;&nbsp;<input type="submit" id="submit" class="btn btn-primary" value="<-- Retirer"/> 
                                    <td>
<?php
                        if (!isset($_POST['APAchoisir'])){                            
                            
                            echo "Pas d'AP choisis";
                        }
                        else {
                            
                            echo "Liste des AP choisis: <br>";
                            
                            foreach ($_POST['APAchoisir'] as $ap){
                                echo $ap."<br>";                          
                            }
                        }
?>


                                    </td>
                                </tr>                                                                        
                            </table>
                        </div>
                    </form>

                     </ol> 
                 </td>
              </tr>
           </tbody>
        </table>
        
        

      </div><!-- /container -->     


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>