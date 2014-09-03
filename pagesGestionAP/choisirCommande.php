<?php $auth_realm = 'AP Tool'; require_once '../includes/authentification.php'; ?> <!DOCTYPE html>
<html lang="en">
  <head>
    <title>AP Tool</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <script type="text/javascript" src="../js/jquery-1.11.1.js"></script>                
    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
  </head>

  <body onunload="$('#loading2').hide();">
      <p align="right"><br><a href="?action=logOut">LOGOUT</a>&nbsp;&nbsp;&nbsp;</p>
      <br>
    <div class="container-fluid">        

        <table border="0" width="90%" align="center">
           <tbody>
              <tr>
                <?php include '../includes/menus.php'; echo $menuPagesGestionAP; ?> 
              <tr>
                 <td width="30%" class="leftmenu">
                        <p><b>Informations sur les AP</b></p>
                             <ul class="nav nav-pills nav-stacked">                       
                            <li><a href="afficherListeAP.php">Afficher la liste  de tous les AP inscrits</a></li>
                           <li><a href="rechercherAP.php">Rechercher des AP sur le r&eacute;seau</a></li>                      
                        </ul>
                        <p><b>Configurer les AP</b></p>
                        <ul class="nav nav-pills nav-stacked">                       
                           <li class="active"><a href="choisirCommande.php">Appliquer une commande &agrave; un ou plusieurs AP</a></li>    
                        </ul> 
                 </td> 
                 
                 <td class="informations">
                    <ol class="breadcrumb">
                        <li><a href="accueilGestionAP.php">Accueil gestion des AP</a></li>    
                        <li>Appliquer une commande &agrave; un ou plusieurs AP</li>
                    </ol>  
                     <ol>
                        <table width="auto">
                        <tr><td width="auto">                           
                        <form id="selectioncommande" class="form-inline" role="form" action="choisirCommande.php" method="POST">
                            <div class="form-group">                                                           
                            <label for="name">Trier par mod&egrave;le</label><br>
                            <select class="form-control" id="selectNoModele" name="noModele" onChange="this.form.submit()">
                            

                        <?php                                          
                           //connexion a la BDD et récupération de la liste des modèles
                           include '../includes/connexionBDD.php';                    
                           include '../includes/fonctionsUtiles.php';                          

                           $infosRecues ='Le n&eacute;ant';
                           $commandeSelectionnee=FALSE;
                           $initialisation=true;

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
                           }
                           else {
                               $APChoisis=$_POST['APAchoisir'];  
                               $initialisation=FALSE;
                           }     

                           //pour récupérer la commande choisis
                           if (!isset($_POST['commande'])){                                                        
                               $noTypeCommandeChoisie=('0');
                               $commandeSelectionnee=false;
                           }
                           else {
                               $noTypeCommandeChoisie=$_POST['commande'];
                               $commandeSelectionnee=true;
                               $initialisation=FALSE;
                           }
                           
                           //définir si état d'initialisation ou non
                           if (($APChoisis[0]==('0')) && $noTypeCommandeChoisie==0){$initialisation=true;}                                                                                       

                           //Récupération de la liste des modèles
                           try
                           {                            
                                   $i =0;                                
                                   $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                   $resultatsModelesAP=$connexion->query("SELECT * FROM modeles;");                                 
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
                               $resultatsModelesAP->closeCursor(); // on ferme le curseur des résultats                                                                            
                           }

                           catch(Exception $e)
                           {
                                   echo '</select></div></form></td></tr></table><li>Erreur lors du chargement des mod&egrave; AP</li></ol>';
                                   echo 'Erreur : '.$e->getMessage().'<br />';
                                   echo 'N° : '.$e->getCode();
                           }                        

                           echo '</select><br><br></td></tr>'; 
                           
 echo '<tr><td width="auto">';
                           echo '<label for="commande">Choix de la commande &agrave; appliquer:</label><br>
                               <select class="form-control" name="commande" onChange="this.form.submit()">';     

                            if ($noTypeCommandeChoisie == '0'){                                
                                echo '<option value="0" selected>Liste des commandes disponibles...&nbsp;&nbsp;&nbsp;</option>'; 
                            }
                            else {
                                echo '<option value="0">Liste des commandes disponibles...&nbsp;&nbsp;&nbsp;</option>';
                            }                        
                           //Récupération de la liste des commandess
                           try
                           {                            
                               $i =0;                                
                               $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                               if ($noModele=='0'){
                                   $resutatsTypeCommande=$connexion->query("SELECT tc.notypeCommande, tc.typeCommande, tc.description FROM modeles m, typeCommandes tc, lignesCommande lc
                                                                   WHERE lc.notypeCommande = tc.notypeCommande AND lc.noModeleAP = m.noModeleAP GROUP BY tc.notypeCommande;");                                 
                               }
                               else{
                                   $resutatsTypeCommande=$connexion->query("SELECT * FROM modeles m, typeCommandes tc, lignesCommande lc
                                                                   WHERE lc.notypeCommande = tc.notypeCommande AND lc.noModeleAP = m.noModeleAP AND lc.noModeleAP =".$noModele."
                                                                    GROUP BY tc.notypeCommande;");
                               }

                               $resutatsTypeCommande->setFetchMode(PDO::FETCH_OBJ);                                 
                               $commandeTrouvee=false;
                               
                               //pour afficher les types de commandes disponibles
                               while( $ligne = $resutatsTypeCommande->fetch() ) // on récupère la liste des membres
                               {         
                                   $notypeCommande=(string)$ligne->notypeCommande;
                                   $typeCommande=(string)$ligne->typeCommande;
                                   $description=(string)$ligne->description;

                                   //si un type de commande a été choisi, on recherche toutes les lignes de commandes y correspondant 
                                   if ($noTypeCommandeChoisie == $notypeCommande){                                
                                        echo '<option value="'.$notypeCommande.'" selected> '.$notypeCommande.' - '.$typeCommande.' ( description: '.substr($description,0,40).')&nbsp;&nbsp;&nbsp;</option>';
                                        $commandeTrouvee = true;
                                        $descriptionChoixCLI=$description;
                                   }
                                   else {
                                       if (strlen($description)>60){$resumeDescription=substr($description,0,30).' .. '.substr($description, (strlen($description)-30),strlen($description));}
                                       else {$resumeDescription=substr($description,0,60);}
                                       echo '<option value="'.$notypeCommande.'"> '.$notypeCommande.' - '.$typeCommande.' ( description: '.$resumeDescription.')&nbsp;&nbsp;&nbsp;</option>';
                                   }

                               }
                               
                               $resutatsTypeCommande->closeCursor(); // on ferme le curseur des résultats    
                               
                               if ($commandeTrouvee == true){
                                    //pour enregistrer chaque ligne de commande correspondant à cette descption et au modèle d'AP
                                    $resutatsCLI=$connexion->query("SELECT lc.noCLI, lc.ligneCommande,lc.protocole, lc.portProtocole, m.noModeleAP,m.nomModele, m.nomFabricant, m.versionFirmware FROM modeles m, lignesCommande lc WHERE lc.notypeCommande=".$noTypeCommandeChoisie." AND lc.noModeleAP = m.noModeleAP;");                                        
                                    $resutatsCLI->setFetchMode(PDO::FETCH_OBJ); 
                                    while( $ligneCLI = $resutatsCLI->fetch()){
                                        $noCLI=(string)$ligneCLI->noCLI;
                                        $ligneCommande=(string)$ligneCLI->ligneCommande;
                                        $protocole=(string)$ligneCLI->protocole;
                                        $portProtocole=(string)$ligneCLI->portProtocole;
                                        $noModeleAP=(string)$ligneCLI->noModeleAP;
                                        $nomModele=(string)$ligneCLI->nomModele;
                                        $nomFabricant=(string)$ligneCLI->nomFabricant;
                                        $versionFirmware=(string)$ligneCLI->versionFirmware; 

                                        $commandesChoisies[$noModeleAP]=array("noCLI"=>$noCLI,"noModeleAP"=>$noModeleAP, "nomModele"=>$nomModele,"nomFabricant"=>$nomFabricant,"versionFirmware"=>$versionFirmware,"ligneCommande"=>$ligneCommande,"protocole"=>$protocole, "portProtocole"=>$portProtocole);
                                    }
                                    $resutatsCLI->closeCursor();
                               }
                               if ($commandeSelectionnee && !$commandeTrouvee){$noTypeCommandeChoisie=('0');} //pour déterminer si command sélectionnée est cohérente
                           }
                           catch(Exception $e)
                           {
                                   echo '</select></div></form></td></tr></table><li>Erreur lors du chargement des descriptions de commandes</li></ol>';
                                   echo 'Erreur : '.$e->getMessage().'<br/>';
                                   echo 'N° : '.$e->getCode();
                           }                                                                                                   
                           echo '</select><br></td></tr>';
                               
                           echo '<tr><td width="auto">';
                           echo '<label for="name">Choix des AP &agrave; contacter:</label><br>
                               <select multiple size="10" class="form-control" name="APAchoisir[]">';                    

                           //Pour afifcher la liste des AP à sélectionner
                           try
                           {

                               $i =0;
                               $listeAPactuels=null;
                               $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                               if (($noModele=='0') && ($noTypeCommandeChoisie=='0')){
                                   $resultatsListeAP=$connexion->query("SELECT * FROM accessPoints a, modeles m WHERE a.noModeleAP=m.noModeleAP ORDER BY a.nomAP, a.adresseIPv4;");                                 
                               }
                               else if (($noModele=='0') && ($noTypeCommandeChoisie!='0')){                                                                            
                                    $resultatsListeAP=$connexion->query("SELECT * FROM lignesCommande l1, accessPoints a, modeles m
                                                                WHERE l1.ligneCommande IN (SELECT l2.ligneCommande from lignesCommande l2 where l2.notypeCommande=".$noTypeCommandeChoisie.")
                                                                AND a.noModeleAP=l1.noModeleAP AND a.noModeleAP=m.noModeleAP ORDER BY a.nomAP, a.adresseIPv4;");                                                                      
                               }
                               else {
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
                                   $noModeleAP=(string)$ligne->noModeleAP;
                                   $nomFabricant=(string)$ligne->nomFabricant;
                                   $nomModele=(string)$ligne->nomModele;
                                   $versionFirmware=(string)$ligne->versionFirmware;   
                                   $adrMACFabricant =(string)$ligne->adrMACFabricant;

                                   if (in_array($noAP, $APChoisis)){
                                       echo '<option value="'.$noAP.'" selected>'.$noAP.' - '.$nomAP.' ('.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.', IP: '.$ip.')&nbsp;&nbsp;&nbsp;</option>';                                       
                                       $listeAPactuels[$i]=array("noAP" =>$noAP, "nomAP"=>$nomAP,"noModeleAP"=>$noModeleAP,"adresseIPv4"=>$ip,"snmpCommunity"=>$snmpCommunity, "username"=>$username, "password"=>$password);       
                                       $i++;
                                   }
                                   else {
                                       echo '<option value="'.$noAP.'">'.$noAP.' - '.$nomAP.' ('.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.', IP: '.$ip.')&nbsp;&nbsp;&nbsp;</option>';  
                                   }
                               }
                               $resultatsListeAP->closeCursor(); // on ferme le curseur des résultats                                                                            
                           }

                           catch(Exception $e)
                           {
                                   echo '</select></div></form></td></tr></table><li>Erreur lors du chargement des AP</li>';
                                   echo 'Erreur : '.$e->getMessage().'<br />';
                                   echo 'N° : '.$e->getCode();
                           }                                                
                           echo '</select><br></td></tr>';
                          echo '</div></form>'; 



                        echo '<tr><td valign="bottom">';                            
                        $actionOnClick="$('#selectioncommande').submit();";
                        $actionReset="location='choisirCommande.php'";
                        echo '<table width="100%"><tr><td align="left"><button class="btn btn-primary" onclick="'.$actionOnClick.'">V&eacute;rifier les choix</button></td>';
                        echo '<td align="right"><button class="btn  btn-default" onclick="'.$actionReset.'">R&eacute;initialiser</button></td></tr></table>';
                        echo '</td></tr>';

                        $textValidation= "&nbsp;";

                        if (!$initialisation){                                
                            $textValidation.='<br><br>----------------------------------------------------';
                            //vérification des choix effectués
                            if ($listeAPactuels==null){
                                $textValidation.='<br><strong>Aucun AP s&eacute;lectionn&eacute;.</strong>';
                            }                            
                            if ($noTypeCommandeChoisie=='0'){
                                $textValidation.='<br><strong>Aucune commande s&eacute;lectionn&eacute;e.</strong>';
                            }
                            if ($noTypeCommandeChoisie!='0' && $listeAPactuels!=null){ 
                                $listeAP=base64_encode(serialize($listeAPactuels));
                                $tabCommandesChoisies=base64_encode(serialize($commandesChoisies));
                                $textValidation.='<br><br><u>Description de la commande choisie:</u> '.$descriptionChoixCLI;
                                $textValidation.='<br><br><u>Lignes de commande correspondantes &agrave; cette description:</u><br>';
                                $textValidation.='<table class="table table-striped"><tr><th>Mod&egrave;le: </th><th>Ligne de commande</th><th>protocole:NoPort</th></tr>';
                                foreach ($commandesChoisies as $commande){ 
                                    $textValidation.= '<tr><td valign="top">'.$commande["nomFabricant"].' '.$commande["nomModele"].' (Firmware v'.$commande["versionFirmware"].'</td>';
                                    $textValidation.= '<td>';
                                    $tabCommande= explode("\n", $commande["ligneCommande"]);      
                                    foreach($tabCommande as $ligneReq){$textValidation.='#'.$ligneReq.'<br>';}
                                    $textValidation.= '</td><td>'.$commande["protocole"].':'.$commande["portProtocole"];
                                    $textValidation.= '</tr>';
                                }
                                $textValidation.='</table><br><br>';
                                $textValidation.='<input type="hidden" value="'.$listeAP.'" name="listeAP"/>';                                
                                $textValidation.='<input type="hidden" value="'.$tabCommandesChoisies.'" name="commandesChoisies"/>';
                                $textValidation.= '<div id="envoiRequete" style="display:block;">Nombre de trames &agrave; r&eacute;cu&eacute;p&eacute;rer:&nbsp;';
                                $textValidation.='<select class="form-control" id="nbTrames" name="nbTrames">';
                                for ($i=0;$i<=500;$i+=10){$textValidation.='<option value="'.$i.'" ';if($i==50){$textValidation.='selected';} $textValidation.= '>'.$i.'&nbsp;&nbsp;&nbsp;</option>';}
                                $textValidation.= '</select>';
                                $textValidation.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" id="submitrequete" class="btn btn-warning" onclick="$(';
                                $textValidation.="'#loading2'";
                                $textValidation.=').show();" value="Appliquer la commande"/></div>';
                            }
                        }

                        echo '<tr><td align="left">';  
                        echo '<div class="form-group" id="validation">'; 
                        echo '<form id="appliquerCommande" class="form-inline" role="form" action="appliquerCommande.php" method="POST">';                                                                                

                        echo $textValidation;

                        echo '</form></div>';
                        echo '<div id="loading2" style="display:none;" ><img class="img" src="../images/reqSender-loader.gif" height="34" width="34" alt=""/>&nbsp;Envoi des requ&ecirc;tes en cours...</div>';

                        echo '</td></tr></table>';
                        //echo "<br><br>infos recues: ".$infosRecues." --- modele en cours: ".$noModele." --- AP choisis: ".htmlspecialchars(print_r($APChoisis,true));                                            
    ?>      
                    </ol>
                 </td>
              </tr>
           </tbody>
        </table>                                
      </div><!-- /container -->     
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster --> 
    <script type="text/javascript">
         (function (d) {
           d.getElementById('validation').onsubmit = function () {
             d.getElementById('envoiRequete').style.display = 'none';
             d.getElementById('loading2').style.display = 'show';
           };
         }(document));
     </script>    
  </body>
</html>