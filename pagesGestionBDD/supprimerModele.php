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
                        <li>Supprimer un mod&egrave;le d'AP</li>
                    </ol>
                     <ol>
                         
                        <form id="supprimerModele" class="form-inline" role="form" action="supprimerModele.php" method="POST">
                            <div class="form-group">                                                           
                            <label for="name">Veuilllez s&eacute;lectionner le mod&egrave;le &agrave; supprimer:</label><br>
                            <select class="form-control" id="noModele" name="noModele" onChange="this.form.submit()">
                     
                            <?php                                          
                               //connexion a la BDD et récupération de la liste des modèles
                               include '../includes/connexionBDD.php';                    
                               include '../includes/fonctionsUtiles.php';                     

                               //pour vérifier si valeurs déjà existantes dans le formulaire
                               if (!isset($_POST['noModele'])){                            
                                   $noModele='0';
                                   echo "<option value='0' selected>Choix du mod&egrave;le...&nbsp;&nbsp;&nbsp;</option>";
                               }
                               else {
                                   $noModele = $_POST['noModele'];                            
                                   echo "<option value='0'>Tous les mod&egrave;les...&nbsp;&nbsp;&nbsp;</option>"; 
                               }

                                  //Récupération de la liste des modèles
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
                                                  $modeleChoisi=array("noModeleAP" =>$noModeleAP, "nomModele"=>$nomModele, "versionFirmware"=>$versionFirmware,"nomFabricant"=>$nomFabricant, "adrMACFabricant"=>$adrMACFabricant);
                                              }
                                              else {
                                                  echo '<option value="'.$noModeleAP.'">'.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.'&nbsp;&nbsp;&nbsp;</option>';                                                    
                                              }
                                          }
                                      $resultatsModelesAP->closeCursor(); // on ferme le curseur des résultats                                                                            
                                  }

                                  catch(Exception $e)
                                  {
                                          echo '</select></div></form></td></tr></table><li>Erreur lors du chargement</li></ol>';
                                          echo 'Erreur : '.$e->getMessage().'<br />';
                                          echo 'N° : '.$e->getCode();
                                          break;
                                  }                        

                                  echo '</select><br></div></form>';   
                                  
                                  if ($noModele!= 0){
                                  echo "<br>------------------------------------------------<br>";
                                  echo'                                  
                                    <form id="confirmSupprimerModele" name="confirmSupprimerModele" class="form-inline" role="form" action="confirmSupprimerModele.php" method="POST">
                                        <div class="form-group">       
                                            <table border="0" class="table">
                                                <tr><td align="right">
                                                    <input type="hidden" name="noModeleAP" value="'.$modeleChoisi["noModeleAP"].'"/>
                                                    <input type="hidden" name="nomFabricant" value="'.$modeleChoisi["nomFabricant"].'"/>
                                                    <input type="hidden" name="versionFirmware" value="'.$modeleChoisi["versionFirmware"].'"/>
                                                    <input type="hidden" name="nomModele" value="'.$modeleChoisi["nomModele"].'"/>
                                                    Nom du mod&egrave;le:<br>                                                    
                                                </td><td>
                                                    '.$modeleChoisi["nomModele"].'                                                    
                                                </td></tr>
                                                <tr><td align="right">
                                                    Version du firmware<br>
                                                </td><td>
                                                    '.$modeleChoisi["versionFirmware"].'                                                   
                                                </td></tr>
                                                <tr><td align="right">
                                                    Nom du fabricant<br>
                                                </td><td>
                                                    '.$modeleChoisi["nomFabricant"].'
                                                </td></tr>
                                                <tr><td align="right">
                                                    Adresse MAC du fabricant:<br>
                                                </td><td>
                                                    '.$modeleChoisi["adrMACFabricant"].'
                                                </td></tr>                                 
                                                <tr><td  align="right" colspan="2">
                                                    <input type="submit" id="submit" class="btn btn-warning" value="Valider la suppression"/>                           
                                                </td></tr>
                                            </table>                                    
                                         </div>                             
                                        </form>';                                                                                                                                
                                  }                                  
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