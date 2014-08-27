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
                                
                                
                                try
                                {                            
                                    $i =0;
                                    $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
                                    
                                    //Bloc if pour vérifier si ajout ou sélection d'une commande
                                    if ((isset($_POST['choixTypeCommande'])) && ($choixAjoutDescription == 'selection')){
                                        $typeCommande=unserialize(base64_decode($_POST['choixTypeCommande'])); 
                                        $reqEnregistrement = $connexion->query("INSERT INTO lignesCommande (ligneCommande,protocole, portProtocole,noModeleAP,notypeCommande) VALUES ('".$ligneCommande."','".$protocole."','".$portProtocole."','".$modeleAP["noModeleAP"]."','".$typeCommande["notypeCommande"]."');");
                                        $description=$typeCommande["description"];
                                        $typeCommande=$typeCommande["typeCommande"];                                        
                                    }
                                    else if((isset($_POST['typeCommande'])) && ($choixAjoutDescription =='ajout')){
                                        $typeCommande=$_POST['typeCommande'];
                                        $description=$_POST['description'];
                                        //vérification si une description similaire existe déjà
                                        
                                        $reqVerifDescription = $connexion->query("SELECT *  FROM ".$PARAM_nom_bd.".typeCommandes WHERE typeCommande LIKE '".$typeCommande."' AND description LIKE '".$description."';");
                                        if ($reqVerifDescription->rowCount()>0){
                                            echo "<p><strong> Cette description de commande existe d&eacute;j&eagrave; <br>";
                                            echo "Veuillez modifier le formulaire ou en choisir une existante.</strong>!<br>";   
                                            $reqVerifDescription;
                                            
                                        }
                                        else {
                                            $typeCommande=preg_replace("/'/i", "\'", $typeCommande);
                                            $description=preg_replace("/'/i", "\'", $description);                                        
                                            $reqAjoutDescription = $connexion->query("INSERT INTO ".$PARAM_nom_bd.".typeCommandes (typeCommande,description) VALUES ('".$typeCommande."','".$description."');");                                        

                                            $reqChoixDescription = $connexion->query("SELECT MAX(notypeCommande) as notypeCommande FROM ".$PARAM_nom_bd.".typeCommandes;");
                                            $reqChoixDescription->setFetchMode(PDO::FETCH_OBJ);
                                            while ($nouvelleCommande = $reqChoixDescription->fetch()){$notypeCommande=(string)$nouvelleCommande->notypeCommande;}                                       
                                            $reqChoixDescription->closeCursor();
                                            $reqAjoutDescription->closeCursor();                                          
                                            $reqEnregistrement = $connexion->query("INSERT INTO lignesCommande (ligneCommande,protocole, portProtocole,noModeleAP,notypeCommande) VALUES ('".$ligneCommande."','".$protocole."','".$portProtocole."','".$modeleAP["noModeleAP"]."','".$notypeCommande."');");                                                                              
                                        }
                                    }                         
                                    else {
                                        echo "<strong>Erreur avec la description de la commande re&ccedil;ue. Veuillez corriger le formulaire.</strong><br>".$boutonRetour;                                                                     
                                    }
                                    
                                    echo "<table class='table'><tr><th colspan='2'>Informations re&ccedil;ues:</th></tr>";
                                    echo "<tr><td>Ligne de commande:&nbsp;</td><td>".$ligneCommande."</td></tr>";
                                    echo "<tr><td>Protocole et no de port:&nbsp;</td><td>".$protocole.": ".$portProtocole."</td></tr>";
                                    echo "<tr><td>Mod&egrave;le d'AP:&nbsp;</td><td>".$modeleAP["nomFabricant"]." ".$modeleAP["nomModele"]."firmware  (v.".$modeleAP["versionFirmware"].")</td></tr>";
                                    echo "<tr><td>Type de commande:&nbsp;</td><td>".$typeCommande." ( ".substr($description,0,60)."...)</td></tr>";
                                    echo "<tr><td colspan='2'>-----------------------------------------------------------------------</td></tr></table>";                                    
                                    

                                    if (!$reqEnregistrement){                                                                                
                                        echo "<p><strong> Probl&egrave;me lors de l'enregistrement</strong>!<br>";
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