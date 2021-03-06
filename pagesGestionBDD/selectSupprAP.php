<?php 
/**************************************************************************************************** 
 * Auteur: Sébastien Kleber (sebastien.kleber@heig-vd.ch) 
 * 
 * Description:
 * page de sélection d'un ou plusieurs periphériques réseaux à supprimer. Transmet les 
 * sélections à "supprimerAP.php"
 * 
 *
 * Modifié le: 31.08.2014
 ***************************************************************************************************/
$auth_realm = 'AP Tool'; require_once '../includes/authentification.php'; ?> <!DOCTYPE html>
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
                           <li class="active"><a href="selectSupprAP.php">Supprimer</a></li>
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
                        <li>Supprimer des AP enregistr&eacute;s</li>
                    </ol>
                     <ol>
                        <table width="auto">
                        <tr><td width="auto">                          
                        <form id="selectSupprAP" class="form-inline" role="form" action="selectSupprAP.php" method="POST">
                            <div class="form-group">                                                           
                            <label for="name">Veuillez s&eacute;lectionner les AP &agrave; supprimer:</label><br>
                            <select class="form-control" id="noModele" name="noModele" onChange="this.form.submit()">
                     
                            <?php                                          
                           //connexion a la BDD et récupération de la liste des modèles
                           include '../includes/connexionBDD.php';                    
                           include '../includes/fonctionsUtiles.php';                          

                           $infosRecues ='Le n&eacute;ant';
                           $initialisation=true;
                           $infoAvertissement="";

                           //pour vérifier si valeurs déjà existantes dans le formulaire
                           if ($_POST) {                            
                               $infosRecues= htmlspecialchars(print_r($_POST, true));                           
                           }
                           if (!isset($_POST['noModele'])){                            
                               $noModele='0';
                               echo "<option value='0' selected>Tous les mod&egrave;les...&nbsp;&nbsp;&nbsp;</option>";
                           }
                           else {
                               $noModele = $_POST['noModele'];                            
                               echo "<option value='0'>Tous les mod&egrave;les...&nbsp;&nbsp;&nbsp;</option>";                               
                           }

                           //pour récupérer la lsite des AP déjà sélectionnés
                           if (!isset($_POST['APAchoisir'])){                                                        
                               $APChoisis[0]=('0');
                               $initialisation=true;
                           }
                           else {
                               $APChoisis=$_POST['APAchoisir'];  
                               $initialisation=FALSE;
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
                           }                        

                           echo '</select><br><br></td></tr>';                                      
                           echo '<tr><td width="auto">';
                           echo '<label for="name">Choix des AP &agrave; Supprimer:</label><br>
                               <select multiple size="10" class="form-control" name="APAchoisir[]" onClick="this.form.submit();">';                    

                           //Pour afifcher la liste des AP à sélectionner
                           try
                           {

                               $i =0;
                               $listeAPactuels=null;
                               $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                               if ($noModele=='0'){
                                   $resultatsListeAP=$connexion->query("SELECT * FROM accessPoints a, modeles m WHERE a.noModeleAP=m.noModeleAP ORDER BY a.nomAP, a.adresseIPv4;");                                 
                               }
                               else{
                                   $resultatsListeAP=$connexion->query("SELECT * FROM accessPoints a, modeles m WHERE a.noModeleAP=m.noModeleAP AND a.noModeleAP =".$noModele." ORDER BY a.nomAP, a.adresseIPv4;");
                               }                                    

                               $resultatsListeAP->setFetchMode(PDO::FETCH_OBJ);                                 

                               while($ligne = $resultatsListeAP->fetch() ) 
                               {     
                                   $noAP=(string)$ligne->noAP;
                                   $nomAP=(string)$ligne->nomAP;
                                   $ip=(string)$ligne->adresseIPv4;
                                   $username=(string)$ligne->username;
                                   $password=(string)$ligne->password;
                                   $snmpCommunity=(string)$ligne->snmpCommunity;
                                   $nomFabricant=(string)$ligne->nomFabricant;
                                   $nomModele=(string)$ligne->nomModele;
                                   $versionFirmware=(string)$ligne->versionFirmware;   
                                   $adrMACFabricant =(string)$ligne->adrMACFabricant;
                                   //$noModeleAP =(string)$ligne->noModeleAP;

                                   if (in_array($noAP, $APChoisis)){
                                       echo '<option value="'.$noAP.'" selected>'.$noAP.' - '.$nomAP.' ('.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.', IP: '.$ip.')&nbsp;&nbsp;&nbsp;</option>';                                       
                                       $listeAPactuels[$i]=array("noAP" =>$noAP, "nomAP"=>$nomAP, "adresseIPv4"=>$ip,"snmpCommunity"=>$snmpCommunity, "username"=>$username, "password"=>$password);       
                                       $i++;
                                   }
                                   else {
                                       echo '<option value="'.$noAP.'">'.$noAP.' - '.$nomAP.' ('.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.', IP: '.$ip.')&nbsp;&nbsp;&nbsp;</option>';  
                                   }
                               }
                               $resultatsListeAP->closeCursor();                                                                            
                                }

                                catch(Exception $e)
                                {
                                        echo '</select></div></form></td></tr></table><li>Erreur lors du chargement</li></ol>';
                                        echo 'Erreur : '.$e->getMessage().'<br />';
                                        echo 'N° : '.$e->getCode();
                                }                                                                                                                                                       
                                echo '</select><br></td></tr></div></form>';                                                                                 
                            
                                $actionOnClick="$('#supprimerAP').submit();";
                                $actionReset="location='selectSupprAP.php'";

                                $textInfos= "&nbsp;";
                                if (!$initialisation){                                
                                    $textInfos ='<br>';                                    
                                    //vérification des choix effectués
                                    if ($listeAPactuels==null){                                        
                                        $textInfos .='<br><strong>Aucun AP s&eacute;lectionn&eacute;.</strong>';
                                    }                            
                                    else {                                     
                                    $listeAP=base64_encode(serialize($listeAPactuels));      
                                    $infoAvertissement = 'onsubmit="return confirm(\'Valider la suppression de ces AP?\');"';
                                    $textInfos .='<input type="hidden" value="'.$listeAP.'" name="listeAP"/>';
                                    $textInfos .= '<table width="100%"><tr><td align="left"><input type="submit" class="btn btn-warning" value="Supprimer les AP s&eacute;lectionn&eacute;s"/></td>';
                                    $textInfos .= '<td align="right"><input type="button" class="btn  btn-default" onclick="'.$actionReset.'" value="R&eacute;initialiser"/></td></tr></table>';
                                    }
                                }

                                echo '<tr><td align="right">';  
                                echo '<div class="form-group" id="validation">';                                
                                echo '<form id="supprimerAP" '.$infoAvertissement.' class="form-inline" role="form" action="supprimerAP.php" method="POST">';                                                                                

                                echo $textInfos;

                                echo '</form></div>';
                                echo '</td></tr></table>';
                     //echo "<br><br>infos recues: ".$infosRecues." --- modele en cours: ".$noModele." --- AP choisis: ".htmlspecialchars(print_r($APChoisis,true));
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