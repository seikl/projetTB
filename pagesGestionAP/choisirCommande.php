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
                        <table width="80%">
                        <tr><td width="75%">                           
                        <form id="selectioncommande" class="form-inline" role="form" action="choisirCommande.php" method="POST">
                            <div class="form-group">                                                           
                            <label for="name">Trier par mod&egrave;le</label><br>
                            <select class="form-control" id="selectNoModele" name="noModele" onChange="this.form.submit()">
                            

                        <?php                                          
                           //connexion a la BDD et récupération de la liste des modèles
                           include '../includes/connexionBDD.php';                    
                           include '../includes/fonctionsUtiles.php';                          

                           $infosRecues ='Le n&eacute;ant';

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
                           }     

                           //pour récupérer la commande choisis
                           if (!isset($_POST['commande'])){                                                        
                               $noCommandeChoisie=('0');
                           }
                           else {
                               $noCommandeChoisie=$_POST['commande'];                            
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
                                       }
                                       else {
                                           echo '<option value="'.$noModeleAP.'">'.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.'&nbsp;&nbsp;&nbsp;</option>';  
                                       }
                                   }
                               $resultatsModelesAP->closeCursor(); // on ferme le curseur des résultats                                                                            
                           }

                           catch(Exception $e)
                           {
                                   echo '</select></form></td></tr></table><li>Erreur lors du chargement</li></ol>';
                                   echo 'Erreur : '.$e->getMessage().'<br />';
                                   echo 'N° : '.$e->getCode();
                           }                        

                           echo '</select><br><br></td><td>&nbsp;</td></tr>';                                      
                           echo '<tr><td width="75%">';
                           echo '<label for="name">Choix des AP &agrave; contacter:</label><br>
                               <select multiple class="form-control" name="APAchoisir[]">';                    

                           //Pour afifcher la liste des AP à sélectionner
                           try
                           {

                               $i =0;
                               $listeAPactuels=null;
                               $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                               if ($noModele=='0'){
                                   $resultatsListeAP=$connexion->query("SELECT m.nomModele, m.nomFabricant, m.versionFirmware, a.noAP, a.nomAP, a.adresseIPv4, m.adrMACFabricant FROM accessPoints a, modeles m WHERE a.noModeleAP=m.noModeleAP;");                                 
                               }
                               else{
                                   $resultatsListeAP=$connexion->query("SELECT m.nomModele, m.nomFabricant, m.versionFirmware, a.noAP, a.nomAP, a.adresseIPv4, m.adrMACFabricant FROM accessPoints a, modeles m WHERE a.noModeleAP=m.noModeleAP AND a.noModeleAP =".$noModele.";");
                               }                                    

                               $resultatsListeAP->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet                                

                               while($ligne = $resultatsListeAP->fetch() ) // on récupère la liste des membres
                               {     
                                   $noAP=(string)$ligne->noAP;
                                   $nomAP=(string)$ligne->nomAP;
                                   $ip=(string)$ligne->adresseIPv4;
                                   $nomFabricant=(string)$ligne->nomFabricant;
                                   $nomModele=(string)$ligne->nomModele;
                                   $versionFirmware=(string)$ligne->versionFirmware;   
                                   $adrMACFabricant =(string)$ligne->adrMACFabricant;
                                   //$noModeleAP =(string)$ligne->noModeleAP;

                                   if (in_array($noAP, $APChoisis)){
                                       echo '<option value="'.$noAP.'" selected>'.$noAP.' - '.$nomAP.' ('.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.')&nbsp;&nbsp;&nbsp;</option>';
                                       $listeAPactuels[$i][0]=$noAP;
                                       $listeAPactuels[$i][1]=$nomAP;
                                       $listeAPactuels[$i][2]=$ip;
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
                                   echo '<li>Erreur lors du chargement</li></ol>';
                                   echo 'Erreur : '.$e->getMessage().'<br />';
                                   echo 'N° : '.$e->getCode();
                           }                                                
                           echo '</select><br></td>';
                           echo '</td><td>&nbsp;</td></tr>';
                           echo '<tr><td width="75%"><br>';
                           echo '<label for="name">Choix de la commande &agrave; appliquer:</label><br>
                               <select class="form-control" name="commande" onChange="this.form.submit()">';     

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
                                   $resultatsCLI=$connexion->query("SELECT tc.typesCommande, tc.description, lc.noCli, m.noModeleAP, m.nomModele, m.nomFabricant, m.versionFirmware 
                                                                   FROM modeles m, typesCommandes tc, lignesCommande lc
                                                                   WHERE lc.noTypesCommande = tc.noTypesCommande AND lc.noModeleAP = m.noModeleAP;");                                 
                               }
                               else{
                                   $resultatsCLI=$connexion->query("SELECT tc.typesCommande, tc.description, lc.noCli, m.noModeleAP, m.nomModele, m.nomFabricant, m.versionFirmware 
                                                                   FROM modeles m, typesCommandes tc, lignesCommande lc
                                                                   WHERE lc.noTypesCommande = tc.noTypesCommande AND lc.noModeleAP = m.noModeleAP AND lc.noModeleAP =".$noModele.";");
                               }

                               $resultatsCLI->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet                                

                               while( $ligne = $resultatsCLI->fetch() ) // on récupère la liste des membres
                               {     
                                   $typesCommande=(string)$ligne->typesCommande;
                                   $description=(string)$ligne->description;
                                   $noCLI=(string)$ligne->noCli;
                                   $noModeleAP=(string)$ligne->noModeleAP;
                                   $nomModele=(string)$ligne->nomModele;
                                   $nomFabricant=(string)$ligne->nomFabricant;
                                   $versionFirmware=(string)$ligne->versionFirmware;

                                   if ($noCommandeChoisie == $noCLI){                                
                                       echo '<option value="'.$noCLI.'" selected>'.$typesCommande.' ('.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.')&nbsp;&nbsp;&nbsp;</option>';  
                                   }
                                   else {
                                       echo '<option value="'.$noCLI.'">'.$typesCommande.' ('.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.')&nbsp;&nbsp;&nbsp;</option>';                                        
                                   }

                               }
                               $resultatsCLI->closeCursor(); // on ferme le curseur des résultats                                                                            
                           }

                           catch(Exception $e)
                           {
                                   echo '</select></form></td></tr></table><li>Erreur lors du chargement</li></ol>';
                                   echo 'Erreur : '.$e->getMessage().'<br />';
                                   echo 'N° : '.$e->getCode();
                           }                                                                                                   
                           echo '</td>';
                           echo '<td valign="bottom">&nbsp;<button class="btn btn-primary" onclick="this.form.submit()">Valider les choix</button></div></form></td></tr>';                                                                                                                                                                                            

                            echo '<tr><td>';
                            echo '<form id="appliquerCommande" class="form-inline" role="form" action="appliquerCommande.php" method="POST">                                                      
                           <div class="form-group" id="validation">';
                            
                           echo "<br>Liste des AP choisis: <br>";
                           if ($listeAPactuels!=null){
                               foreach ($listeAPactuels as $ap){
                               echo $ap[0].'- '.$ap[1].' (IP: '.$ap[2].')<br>';
                               }
                           }
                           echo '</td><td>';

                           echo 'commande s&eacute;lectionn&eacute;e <br>:'.$noCommandeChoisie.'</td></tr>';//TODO trouver un moyen d'afficher la VRAIE commande actuelle
                           echo '<tr><td>&nbsp;';                           
                           echo '</td><td col>';
                           echo '<button class="btn btn-warning" onclick="this.form.submit()">Appliquer la commande</button>';
                           
                           echo '</div></form></td></tr></table>';
                     //echo "<br><br>infos recues: ".$infosRecues." --- modele en cours: ".$noModele." --- AP choisis: ".htmlspecialchars(print_r($APChoisis,true));
                     
                     echo'</ol></td></tr></tbody></table>';                        
    ?>                        

      </div><!-- /container -->     
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>