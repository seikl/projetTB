<?php 
/**************************************************************************************************** 
 * Auteur: Sébastien Kleber (sebastien.kleber@heig-vd.ch) 
 * 
 * Description:
 * page d'enregistrement d'une ligne de commande avec les informations reçues depuis "ajoutCLI.php"
 * 
 * Paramètres reçus:
 * - ligneCommande: la ligne de commande
 * - protocole: le protocole
 * - portProtocole: le no de port
 * - modeleAP : le modèle pour lequel s'applqiue la ligne de commande
 * - choixAjoutDescription: permet de déterminer s'il faut enregistrer une nouvelle description
 * pour la commande ou s'il s'agit d'une description existante
 *  - typeCommande: contient les informaitons sur la description de la commande
 * 
 * Modifié le: 30.08.2014                                                                           *
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
                           <li><a href="selectModifModele.php">Modifier</a></li>                       
                           <li><a href="selectSupprModele.php">Supprimer</a></li>
                        </ul>
                         <p><b>G&eacute;rer les lignes de commandes (CLI)</b></p>
                        <ul class="nav nav-pills nav-justified">                       
                           <li class="active"><a href="ajoutCLI.php">Ajouter</a></li>
                           <li><a href="selectModifCLI.php">Modifier</a></li>                       
                           <li><a href="selectSupprCLI.php">Supprimer</a></li>
                        </ul>                      
                 </td>
                 
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
                            $boutonReinit = '<button class="btn btn-default" onclick="window.location.href = \'ajoutCLI.php\'">R&eacute;initialiser le formulaire</button>';
                            $boutonRetourSucces = '<button class="btn btn-success" onclick="window.location.href = \'../pagesGestionAP/choisirCommande.php\'">Afficher la liste des commandes</button>';

                            //Récupération des informations
                            if ($_POST) {    
                                $ligneCommande= $_POST['ligneCommande'];
                                $protocole= $_POST['protocole'];
                                $portProtocole= $_POST['portProtocole'];
                                $modeleAP= unserialize(base64_decode($_POST['modeleAP']));
                                $choixAjoutDescription = $_POST['choixAjoutDescription'];
                                $reqAjoutDescription=null;
                                $reqEnregistrement=true;

                                $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
                                
                                //Bloc pour vérifier si la commande saisie existe déjà pour le modèle choisi avec vérification si description existe ou non
                                if ($choixAjoutDescription == 'selection'){
                                    $typeCommande=unserialize(base64_decode($_POST['choixTypeCommande']));
                                    $notypeCommande=$typeCommande["notypeCommande"];
                                    $description=$typeCommande["description"];
                                    $typeCommande=$typeCommande["typeCommande"]; 
                                    
                                    $reqVerifCLI = $connexion->query('SELECT COUNT(noCLI) as nbCLIExistantes FROM '.$PARAM_nom_bd.'.lignesCommande '. 
                                                    'WHERE (ligneCommande LIKE "'.$ligneCommande.'" AND protocole = "'.$protocole.'" AND portProtocole="'.$portProtocole.'" AND noModeleAP="'.$modeleAP["noModeleAP"].'")'.
                                                    'OR (noModeleAP="'.$modeleAP["noModeleAP"].'" AND notypeCommande="'.$notypeCommande.'");');  
                                }
                                else {
                                    $reqVerifCLI = $connexion->query('SELECT COUNT(noCLI) as nbCLIExistantes FROM '.$PARAM_nom_bd.'.lignesCommande '. 
                                                        'WHERE (ligneCommande LIKE "'.$ligneCommande.'" AND protocole = "'.$protocole.'" AND portProtocole="'.$portProtocole.'" AND noModeleAP="'.$modeleAP["noModeleAP"].'");');                                                                                                                                                                                  
                                }                                                                                    
                                if ($reqVerifCLI!=false){  
                                    $reqVerifCLI->setFetchMode(PDO::FETCH_OBJ);
                                    while ($verifCommande = $reqVerifCLI->fetch()){$nbCLIExistantes=(string)$verifCommande->nbCLIExistantes;} 
                                    if ($nbCLIExistantes>0){
                                        echo "<p><strong> Cette commande existe d&eacute;j&agrave; pour ce mod&egrave;le <br>";
                                        echo "Veuillez modifier le formulaire.</strong>!<br>"; 
                                        $reqVerifCLI->closeCursor(); 
                                        $reqEnregistrement=FALSE;                                                                                                
                                    }
                                }                                                                                                                 
                                
                                
                                //requête d'enregistrement de la nouvelle commande
                                try
                                {                            
                                    $i =0;   
                                    
                                    //Bloc if pour vérifier si ajout ou sélection d'une commande
                                    if ((isset($_POST['choixTypeCommande'])) && ($choixAjoutDescription == 'selection') && $reqEnregistrement){                                                                                 
                                        $reqEnregistrement = $connexion->query("INSERT INTO lignesCommande (ligneCommande,protocole, portProtocole,noModeleAP,notypeCommande) VALUES ('".$ligneCommande."','".$protocole."','".$portProtocole."','".$modeleAP["noModeleAP"]."','".$notypeCommande."');");                                          
                                    }
                                    else if((isset($_POST['typeCommande'])) && ($choixAjoutDescription =='ajout')){
                                        $typeCommande=$_POST['typeCommande'];
                                        $description=$_POST['description'];
                                        //vérification si une description similaire existe déjà                                       
                                        $reqVerifDescription = $connexion->query('SELECT COUNT(notypeCommande) as nbDescriptionsExistantes FROM '.$PARAM_nom_bd.'.typeCommandes WHERE typeCommande LIKE "'.$typeCommande.'" AND description LIKE "'.$description.'";');
                                        if ($reqVerifDescription!=false){  
                                            $reqVerifDescription->setFetchMode(PDO::FETCH_OBJ);
                                            while ($verifCommande = $reqVerifDescription->fetch()){$nbDescriptionsExistantes=(string)$verifCommande->nbDescriptionsExistantes;} 
                                            if ($nbDescriptionsExistantes>0){
                                                echo "<p><strong> Cette description de commande existe d&eacute;j&agrave; <br>";
                                                echo "Veuillez modifier le formulaire ou en choisir une existante.</strong>!<br>"; 
                                                $reqVerifDescription->closeCursor(); 
                                                $reqEnregistrement=FALSE;                                                                                                
                                            }
                                        }
                                        if ($reqEnregistrement){
                                            $typeCommande=preg_replace("/'/i", "\'", $typeCommande);
                                            $description=preg_replace("/'/i", "\'", $description);                                        
                                            $reqAjoutDescription = $connexion->query("INSERT INTO ".$PARAM_nom_bd.".typeCommandes (typeCommande,description) VALUES ('".$typeCommande."','".$description."');");                                            
                                            $reqChoixDescription = $connexion->query("SELECT MAX(notypeCommande) as notypeCommande FROM ".$PARAM_nom_bd.".typeCommandes;");
                                            $reqChoixDescription->setFetchMode(PDO::FETCH_OBJ);
                                            while ($nouvelleCommande = $reqChoixDescription->fetch()){$notypeCommande=(string)$nouvelleCommande->notypeCommande;}                                       
                                            $reqChoixDescription->closeCursor();
                                            $reqAjoutDescription->closeCursor();                                
                                            $reqEnregistrement = $connexion->query("INSERT INTO ".$PARAM_nom_bd.".lignesCommande (ligneCommande,protocole, portProtocole,noModeleAP,notypeCommande) VALUES ('".$ligneCommande."','".$protocole."','".$portProtocole."','".$modeleAP["noModeleAP"]."','".$notypeCommande."');");                                                                              
                                        }
                                    }                         
                                    else {
                                        echo "<strong>Erreur avec les informations de la commande re&ccedil;ue. Veuillez corriger le formulaire.</strong><br>";                                                                     
                                    }
                                    
                                    echo "<table class='table'><tr><th colspan='2'>Informations re&ccedil;ues:</th></tr>";
                                    echo "<tr><td>Ligne de commande:&nbsp;</td><td>".$ligneCommande."</td></tr>";
                                    echo "<tr><td>Protocole et no de port:&nbsp;</td><td>".$protocole.": ".$portProtocole."</td></tr>";
                                    echo "<tr><td>Mod&egrave;le d'AP:&nbsp;</td><td>".$modeleAP["nomFabricant"]." ".$modeleAP["nomModele"]."firmware  (v.".$modeleAP["versionFirmware"].")</td></tr>";                                    
                                    echo "<tr><td>Type de commande:&nbsp;</td><td>".$typeCommande." ( ".substr($description,0,60)."...)</td></tr><br>";
                                    echo "<tr><td colspan='2'>-----------------------------------------------------------------------</td></tr></table>";                                    
                                    
                                    if (!$reqEnregistrement){                                                                                                                        
                                        echo "<br><p><strong> Probl&egrave;me lors de l'enregistrement</strong>!<br><br>";                                        
                                        echo $boutonRetour."&nbsp;&nbsp;&nbsp;&nbsp;".$boutonReinit."</p>";
                                    }
                                    else{
                                        echo "<p><strong> Enregistrement effect&eacute; avec succ&egrave;s</strong>!<br>";
                                        echo $boutonRetourSucces."</p>";
                                        $reqEnregistrement->closeCursor();
                                    }                                     
                                }
                                catch(Exception $e)
                                {
                                        echo 'Erreur : '.$e->getMessage().'<br>';
                                        echo 'N° : '.$e->getCode().'';
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
    <!-- Bootstrap core JavaScript ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->        
  </body>
</html>