<?php 
/**************************************************************************************************** 
 * Auteur: Sébastien Kleber (sebastien.kleber@heig-vd.ch) 
 * 
 * Description:
 * page de sélection et de formulaire de modification d'un modèle de periphérique réseau. Une fois 
 * la sélection effectuée la page s'appelle elle-même avec en paramètres l'identifiant du No de modèle
 * de la BDD pour afficher le contenu dans des champs de texte éditables. les modifications sont 
 * transmises à "modifierModele.php".
 * 
 * paramètre reçu après sélection d'un modèle:
 * - noModele:  l'identifiant du modèle dans la BDD
 *
 * Modifié le: 27.08.2014
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
                        <li>Modifier un mod&egrave;le d'AP</li>
                    </ol>
                     <ol>
                         
                        <form id="selectModifModele" class="form-inline" role="form" action="selectModifModele.php" method="POST">
                            <div class="form-group">                                                           
                            <label for="name">Veuillez s&eacute;lectionner le mod&egrave;le &agrave; modifier:</label><br>
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

                                          $resultatsModelesAP=$connexion->query("SELECT * FROM modeles ORDER BY nomFabricant,nomModele, versionFirmware;");                                 
                                          $resultatsModelesAP->setFetchMode(PDO::FETCH_OBJ);                                                                           
                                          
                                          while( $ligne = $resultatsModelesAP->fetch() ) 
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
                                      $resultatsModelesAP->closeCursor();                                                                            
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
                                    <form id="modifModele" name="modifModele" class="form-inline" role="form" action="modifierModele.php" method="POST">
                                        <div class="form-group">       

                                            <table border="0" class="table">
                                                <tr><td align="right">
                                                    <input type="hidden" name="noModeleAP" value="'.$modeleChoisi["noModeleAP"].'">
                                                    <input type="text" class="form-control" name="nomModele" id="nomModele" size="25" maxlength="25" value="'.$modeleChoisi["nomModele"].'">
                                                </td><td>
                                                    <strong class="obligatoire">*&nbsp;</strong><label for=\'modifNomModele\'>Nom du mod&egrave;le (par ex. AP-6 ou RT66CU)</label><br>
                                                </td></tr>
                                                <tr><td align="right">
                                                    <input type="text" class="form-control" name="versionFirmware" id="versionFirmware" size="8" maxlength="8" value="'.$modeleChoisi["versionFirmware"].'">
                                                </td><td>
                                                    <strong class="obligatoire">*&nbsp;</strong><label for=\'versionFirmware\'>Version du firmware (par ex. 2.4.11)</label><br>
                                                </td></tr>
                                                <tr><td align="right">
                                                    <input type="text" class="form-control" name="nomFabricant" id="nomFabricant" size="20" maxlength="20"  value="'.$modeleChoisi["nomFabricant"].'">
                                                </td><td>
                                                    &nbsp;&nbsp;&nbsp;<label for=\'nomFabricant\'>Nom du fabricant (par ex. Avaya)</label><br>
                                                </td></tr>';
                                  
                                            $adrMACFabricant1 = substr($modeleChoisi["adrMACFabricant"],0,2);
                                            $adrMACFabricant2 = substr($modeleChoisi["adrMACFabricant"],3,2);
                                            $adrMACFabricant3 = substr($modeleChoisi["adrMACFabricant"],6,2);
                                            
                                            echo  '<tr><td align="right">
                                                    <input type="text" class="form-control" name="adrMACFabricant1" id="adrMACFabricant1" size="2" maxlength="2" value="'.$adrMACFabricant1.'"><strong>:</strong>
                                                    <input type="text" class="form-control" name="adrMACFabricant2" id="adrMACFabricant2" size="2" maxlength="2" value="'.$adrMACFabricant2.'"><strong>:</strong>
                                                    <input type="text" class="form-control" name="adrMACFabricant3" id="adrMACFabricant3" size="2" maxlength="2" value="'.$adrMACFabricant3.'">
                                                </td><td>
                                                    <strong class="obligatoire">*&nbsp;</strong><label for=\'adrMACFabricant\'>Adresse MAC du fabricant (par ex. 00:11:22)</label><br>
                                                </td></tr>                                 
                                                <tr><td  align="right">
                                                    <input type="submit" id="submit" class="btn btn-primary" value="Enregistrer"/>                           
                                                </td><td>
                                                        Tous les champs marqu&eacute;s d\'une <strong class="obligatoire">*&nbsp;</strong>sont obligatoires.
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
    
    <script type="text/javascript">
        $(function()
        {
            $("#modifModele").validate(
              {                
                rules: 
                {            
                  nomModele: 
                  {
                    required: true                   
                  },
                  versionFirmware: 
                  {
                    required: true
                  },
                  adrMACFabricant1: 
                  {
                    required: true
                  },  
                  adrMACFabricant2: 
                  {
                    required: true
                  }, 
                  adrMACFabricant3: 
                  {
                    required: true
                  }                   
                },
                errorElement: "divRight",
                errorPlacement: function(error, element) {
                    error.insertAfter(element);                    
                }                
              });
        });
    </script>      
  </body>
</html>