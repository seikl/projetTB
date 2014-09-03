<?php $auth_realm = 'AP Tool'; require_once '../includes/authentification.php'; ?> <!DOCTYPE html>
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
                           <li><a href="selectModifModele.php">Modifier</a></li>                       
                           <li><a href="selectSupprModele.php">Supprimer</a></li>
                        </ul>
                         <p><b>G&eacute;rer les lignes de commandes (CLI)</b></p>
                        <ul class="nav nav-pills nav-justified">                       
                           <li><a href="ajoutCLI.php">Ajouter</a></li>
                           <li><a href="selectModifCLI.php">Modifier</a></li>                       
                           <li class="active"><a href="selectSupprCLI.php">Supprimer</a></li>
                        </ul>                      
                 </td>
                 
                 <td class="informations">                     
                     <ol class="breadcrumb">
                        <li><a href="accueilGestionBDD.php">Accueil gestion de la BDD</a></li> 
                        <li>Supprimer des commandes enregistr&eacute;es</li>
                    </ol>
                     <ol>
                        <table width="auto">
                        <tr><td width="auto">                          
                        <form id="selectSupprCLI" class="form-inline" role="form" action="selectSupprCLI.php" method="POST">
                            <div class="form-group">                                                           
                            <label for="name">Veuillez s&eacute;lectionner les commandes &agrave; supprimer:</label><br>
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
                           if (!isset($_POST['CLIAchoisir'])){                                                        
                               $CLIChoisies[0]=('0');
                               $initialisation=true;
                           }
                           else {
                               $CLIChoisies=$_POST['CLIAchoisir'];  
                               $initialisation=FALSE;
                           }                                                                                                                 

                           //Récupération de la liste des modèles
                           try
                           {                            
                                   $i =0;                                
                                   $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                   $resultatsModelesAP=$connexion->query("SELECT * FROM modeles ORDER BY nomFabricant,nomModele, versionFirmware;");                                 
                                   $resultatsModelesAP->setFetchMode(PDO::FETCH_OBJ);                                 

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
                           echo '<label for="name">Choix des commandes &agrave; Supprimer:</label><br>
                               <select multiple size="10" class="form-control" name="CLIAchoisir[]" onClick="this.form.submit();">';                    

                           //Pour afifcher la liste des AP à sélectionner
                           try
                           {
                               $i =0;
                               $listeCLIactuelles=null;
                               
                               $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                               if ($noModele=='0'){
                                   $resultatsListeCLI=$connexion->query("SELECT l.noCLI, l.ligneCommande, l.protocole, l.portProtocole, t.typeCommande, t.description "
                                           ."FROM typeCommandes t, lignesCommande l "
                                           ."WHERE l.notypeCommande=t.notypeCommande ORDER BY t.typeCommande,t.description;");
                               }
                               else{
                                   $resultatsListeCLI=$connexion->query("SELECT l.noCLI, l.ligneCommande, l.protocole, l.portProtocole, t.typeCommande, t.description "
                                           . "FROM typeCommandes t, lignesCommande l, modeles m "
                                           . "WHERE l.notypeCommande=t.notypeCommande AND l.noModeleAP=m.noModeleAP AND l.noModeleAP=".$noModele."; ORDER BY t.typeCommande,t.description;");
                               }                                    

                               $resultatsListeCLI->setFetchMode(PDO::FETCH_OBJ);                                 

                               while($ligne = $resultatsListeCLI->fetch() )
                               {     
                                   $noCLI=(string)$ligne->noCLI;
                                   $ligneCommande = (string)$ligne->ligneCommande;
                                   $protocole=(string)$ligne->protocole;
                                   $portProtocole=(string)$ligne->portProtocole;
                                   $typeCommande=(string)$ligne->typeCommande;
                                   $description=(string)$ligne->description;
                                   $ligneCommande = substr($ligneCommande,0,60);
                                   $description = substr($description,0,60);

                                   if (in_array($noCLI, $CLIChoisies)){
                                       echo '<option value="'.$noCLI.'" selected>'.$noCLI.' - '.$ligneCommande.'( protocole:'.strtoupper($protocole).'['.$portProtocole.'], '.$typeCommande.' - '.$description.')&nbsp;&nbsp;&nbsp;</option>';
                                       $listeCLIactuelles[$i]=array("noCLI" =>$noCLI, "ligneCommande"=>$ligneCommande, "protocole"=>$protocole,"portProtocole"=>$portProtocole, "typeCommande"=>$typeCommande, "description"=>$description);                                              
                                       
                                       $i++;                                                                                                             
                                   }
                                   else {
                                       if (strlen($description)>60){$resumeDescription=substr($description,0,30).' .. '.substr($description, (strlen($description)-30),strlen($description));}
                                       else {$resumeDescription=substr($description,0,60);}
                                        if (strlen($ligneCommande)>60){$resumeCLI=substr($ligneCommande,0,30).' .. '.substr($ligneCommande, (strlen($ligneCommande)-30),strlen($ligneCommande));}
                                       else {$resumeCLI=substr($ligneCommande,0,60);} 
                                       echo '<option value="'.$noCLI.'">'.$noCLI.' - '.$typeCommande.' - '.$resumeDescription.'('.$resumeCLI.' ['.strtoupper($protocole).':'.$portProtocole.'])&nbsp;&nbsp;&nbsp;</option>';
                                   }
                               }
                               $resultatsListeCLI->closeCursor();                                       
                               $resultatsDescription->closeCursor();                                                                          
                                }

                                catch(Exception $e)
                                {
                                        echo '</select></div></form></td></tr></table><li>Erreur lors du chargement</li></ol>';
                                        echo 'Erreur : '.$e->getMessage().'<br />';
                                        echo 'N° : '.$e->getCode();
                                }                                                                                                                                                       
                                echo '</select><br></td></tr></div></form>';                                                                                 
                            
                                $actionOnClick="$('#supprimerCLI').submit();";
                                $actionReset="location='selectSupprCLI.php'";

                                $textInfos= "&nbsp;";
                                if (!$initialisation){                                
                                    $textInfos ='<br>';                                    
                                    //vérification des choix effectués
                                    if ($listeCLIactuelles==null){                                        
                                        $textInfos .='<br><strong>Aucune commande s&eacute;lectionn&eacute;e.</strong>';
                                    }                            
                                    else {                                     
                                    $listeCLI=base64_encode(serialize($listeCLIactuelles));      
                                    $infoAvertissement = 'onsubmit="return confirm(\'Valider la suppression de ces commandes?\');"';
                                    $textInfos .='<input type="hidden" value="'.$listeCLI.'" name="listeCLI"/>';
                                    $textInfos .= '<table width="100%"><tr><td align="left"><input type="submit" class="btn btn-warning" value="Supprimer les commandes s&eacute;lectionn&eacute;s"/></td>';
                                    $textInfos .= '<td align="right"><input type="button" class="btn  btn-default" onclick="'.$actionReset.'" value="R&eacute;initialiser"/></td></tr></table>';
                                    }
                                }

                                echo '<tr><td align="right">';  
                                echo '<div class="form-group" id="validation">';                                
                                echo '<form id="supprimerCLI" '.$infoAvertissement.' class="form-inline" role="form" action="supprimerCLI.php" method="POST">';                                                                                

                                echo $textInfos;

                                echo '</form></div>';
                                echo '</td></tr></table>';
                     //echo "<br><br>infos recues: ".$infosRecues." --- modele en cours: ".$noModele." --- AP choisis: ".htmlspecialchars(print_r($CLIChoisies,true));
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