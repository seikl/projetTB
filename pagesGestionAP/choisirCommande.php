<!DOCTYPE html>
<html lang="en">
  <head>
    <title>AP Tool</title>
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
                               $noCommandeChoisie=('0');
                               $commandeSelectionnee=false;
                           }
                           else {
                               $noCommandeChoisie=$_POST['commande'];
                               $commandeSelectionnee=true;
                               $initialisation=FALSE;
                           }
                           
                           //définir si état d'initialisation ou non
                           if (($APChoisis[0]==('0')) && $noCommandeChoisie==0){$initialisation=true;}                                                                                       

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

                           echo '</select><br><br></td></tr>';                                      
                           echo '<tr><td width="auto">';
                           echo '<label for="name">Choix des AP &agrave; contacter:</label><br>
                               <select multiple size="10" class="form-control" name="APAchoisir[]">';                    

                           //Pour afifcher la liste des AP à sélectionner
                           try
                           {

                               $i =0;
                               $listeAPactuels=null;
                               $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                               if ($noModele=='0'){
                                   $resultatsListeAP=$connexion->query("SELECT * FROM accessPoints a, modeles m WHERE a.noModeleAP=m.noModeleAP;");                                 
                               }
                               else{
                                   $resultatsListeAP=$connexion->query("SELECT * FROM accessPoints a, modeles m WHERE a.noModeleAP=m.noModeleAP AND a.noModeleAP =".$noModele.";");
                               }                                    

                               $resultatsListeAP->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet                                

                               while($ligne = $resultatsListeAP->fetch() ) // on récupère la liste des membres
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
                                       echo '<option value="'.$noAP.'" selected>'.$noAP.' - '.$nomAP.' ('.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.')&nbsp;&nbsp;&nbsp;</option>';                                       
                                       $listeAPactuels[$i]=array("noAP" =>$noAP, "nomAP"=>$nomAP, "adresseIPv4"=>$ip,"snmpCommunity"=>$snmpCommunity, "username"=>$username, "password"=>$password);       
                                       $i++;
                                   }
                                   else {
                                       echo '<option value="'.$noAP.'">'.$noAP.' - '.$nomAP.' ('.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.')&nbsp;&nbsp;&nbsp;</option>';  
                                   }
                               }
                               $resultatsListeAP->closeCursor(); // on ferme le curseur des résultats                                                                            
                           }

                           catch(Exception $e)
                           {
                                   echo '</select></div></form></td></tr></table><li>Erreur lors du chargement</li></ol>';
                                   echo 'Erreur : '.$e->getMessage().'<br />';
                                   echo 'N° : '.$e->getCode();
                           }                                                
                           echo '</select><br></td></tr>';
                           echo '<tr><td width="auto">';
                           echo '<label for="commande">Choix de la commande &agrave; appliquer:</label><br>
                               <select class="form-control" name="commande">';     

                            if ($noCommandeChoisie == '0'){                                
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
                                   $resultatsCLI=$connexion->query("SELECT * FROM modeles m, typesCommandes tc, lignesCommande lc
                                                                   WHERE lc.noTypesCommande = tc.noTypesCommande AND lc.noModeleAP = m.noModeleAP;");                                 
                               }
                               else{
                                   $resultatsCLI=$connexion->query("SELECT * FROM modeles m, typesCommandes tc, lignesCommande lc
                                                                   WHERE lc.noTypesCommande = tc.noTypesCommande AND lc.noModeleAP = m.noModeleAP AND lc.noModeleAP =".$noModele.";");
                               }

                               $resultatsCLI->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet                                
                               $commandeTrouvee=false;
                               
                               while( $ligne = $resultatsCLI->fetch() ) // on récupère la liste des membres
                               {     
                                   $typesCommande=(string)$ligne->typesCommande;
                                   $description=(string)$ligne->description;
                                   $noCLI=(string)$ligne->noCLI;
                                   $ligneCommande=(string)$ligne->ligneCommande;
                                   $protocole=(string)$ligne->protocole;
                                   $portProtocole=(string)$ligne->portProtocole;
                                   $noModeleAP=(string)$ligne->noModeleAP;
                                   $nomModele=(string)$ligne->nomModele;
                                   $nomFabricant=(string)$ligne->nomFabricant;
                                   $versionFirmware=(string)$ligne->versionFirmware;

                                   if ($noCommandeChoisie == $noCLI){                                
                                       echo '<option value="'.$noCLI.'" selected>'.$typesCommande.' ( protocole ['.$protocole.':'.$portProtocole.'], mod&egrave;le concern&eacute;: '.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.')&nbsp;&nbsp;&nbsp;</option>';
                                       $commandeTrouvee = true;
                                       $commandeChoisie=array("noCLI"=>$noCLI,"ligneCommande"=>$ligneCommande,"protocole"=>$protocole, "portProtocole"=>$portProtocole);
                                       $descriptionChoixCLI= $description;
                                   }
                                   else {
                                       echo '<option value="'.$noCLI.'">'.$typesCommande.' ( protocole ['.$protocole.':'.$portProtocole.'], mod&egrave;le concern&eacute;: '.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.')&nbsp;&nbsp;&nbsp;</option>';                             
                                   }

                               }
                               $resultatsCLI->closeCursor(); // on ferme le curseur des résultats    
                               if ($commandeSelectionnee && !$commandeTrouvee){$noCommandeChoisie=('0');} //pour déterminer si command sélectionnée est cohérente
                           }

                           catch(Exception $e)
                           {
                                   echo '</select></div></form></td></tr></table><li>Erreur lors du chargement</li></ol>';
                                   echo 'Erreur : '.$e->getMessage().'<br/>';
                                   echo 'N° : '.$e->getCode();
                           }                                                                                                   
                           echo '</select><br></td></tr></div></form>'; 
                           
                           
                            
                            echo '<tr><td valign="bottom">';                            
                            $actionOnClick="$('#selectioncommande').submit();";
                            $actionReset="location='choisirCommande.php'";
                            echo '<table width="100%"><tr><td align="left"><button class="btn btn-primary" onclick="'.$actionOnClick.'">Valider les choix</button></td>';
                            echo '<td align="right"><button class="btn" onclick="'.$actionReset.'">R&eacute;initialiser</button></td></tr></table>';
                            echo '</td></tr>';

                            $textValidation= "&nbsp;";
                            
                            if (!$initialisation){                                
                                $textValidation.='<br><br>----------------------------------------------------';
                                //vérification des choix effectués
                                if ($listeAPactuels==null){
                                    $textValidation.='<br><strong>Aucun AP s&eacute;lectionn&eacute;.</strong>';
                                }                            
                                if ($noCommandeChoisie=='0'){
                                    $textValidation.='<br><strong>Aucune commande s&eacute;lectionn&eacute;e.</strong>';
                                }
                                if ($noCommandeChoisie!='0' && $noModele=='0'){                                    
                                    $textValidation.='<br><strong>Attention au choix de la commande si les AP choisis sont de mod&egrave;les diff&eacute;rents.</strong>';
                                }
                                if ($noCommandeChoisie!='0' && $listeAPactuels!=null){ 
                                $listeAP=base64_encode(serialize($listeAPactuels));
                                $commandeChoisie=base64_encode(serialize($commandeChoisie));
                                $textValidation.='<br><br><u>Description de la commande: </u>'.$descriptionChoixCLI;
                                $textValidation.='<input type="hidden" value="'.$listeAP.'" name="listeAP"/>';                                
                                $textValidation.='<input type="hidden" value="'.$commandeChoisie.'" name="commandeChoisie"/>';
                                $textValidation.='<br><br><input type="submit" id="envoiRequete" class="btn btn-warning" onclick="$(';
                                $textValidation.="'#loading2'";
                                $textValidation.=').show();" value="Appliquer la commande"/>';
                                }
                            }
                            
                            echo '<tr><td align="right">';  
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