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
                           <li><a href="selectSupprModele.php">Supprimer</a></li>
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
                        <li>Ajouter un ou plusieurs AP</li>
                    </ol>
                   <ol>
                    <?php 
                        //connexion a la BDD et récupération de la liste des modèles
                        include '../includes/connexionBDD.php';                    
                        include '../includes/fonctionsUtiles.php';
                        
                        //Récupération nombre d'AP à ajouter
                        if (!isset($_POST['qtyAP'])){$qtyAP=0;} else {$qtyAP=$_POST['qtyAP'];}    
                        
                        //enregistrement des modèles d'AP dans un tableau pour affichage dans une liste
                        try
                        {            
                                $i=0;
                                $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                $resultatsAP=$connexion->query("SELECT * FROM modeles;");                                 
                                $resultatsAP->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet                                
                                
                                while( $ligne = $resultatsAP->fetch() ) // on récupère la liste des membres
                                {     
                                    $noModeleAP =(string)$ligne->noModeleAP;
                                    $nomModele =(string)$ligne->nomModele;
                                    $versionFirmware=(string)$ligne->versionFirmware;
                                    $nomFabricant=(string)$ligne->nomFabricant;
                                    $adrMACFabricant=(string)$ligne->adrMACFabricant; 
                                    $tabListeModeles[$i]=array("noModeleAP" =>$noModeleAP, "nomModele"=>$nomModele, "versionFirmware"=>$versionFirmware,"nomFabricant"=>$nomFabricant, "adrMACFabricant"=>$adrMACFabricant);
                                    $i++;
                                }
                            $resultatsAP->closeCursor(); // on ferme le curseur des résultats
                                                
                        }                                                
                        catch(Exception $e)
                        {
                                echo '</select></form><li> Erreur lors du chargement</li></ol>';
                                echo 'Erreur : '.$e->getMessage().'<br />';
                                echo 'N° : '.$e->getCode();
                        } 
                    ?>                        
                       
                       
                    <form id="ajoutAP" name="ajoutAP" class="form-inline" role="form" action="enregistrerAP.php" method="POST">
                        <div class="form-group">       

                            <table border="0" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nom de l'AP (d&eacute;faut: "AP-xx")</th>
                                    <th>Mod&egrave;le de l'AP</th>
                                    <th>Adresse IPv4</th>
                                    <th>SNMP <br>(d&eacute;faut: "public")</th>
                                    <th>Nom d'utilisateur</th>
                                    <th>Mot de passe</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for($i=0;$i<=$qtyAP;$i++){                                                                                                            
                                    echo '<tr>';
                                    echo '<td><input type="text" class="form-control" name="nomModele'.$i.'" id="nomModele'.$i.'" size="18" maxlength="25" placeholder="AP-'.$i.'"></td>';
                                    
                                    echo '<td><select class="form-control" id="noModeleAP'.$i.'" name="noModeleAP'.$i.'">';
                                    echo '<option value="">Choix du mod&egrave;le</option>';
                                    foreach ($tabListeModeles as $modele){
                                        echo '<option value="'.$modele["noModeleAP"].'">'.$modele["nomFabricant"].' '.$modele["nomModele"].' v.'.$modele["versionFirmware"].'</option>';                                                    
                                    }
                                    echo '</select></td>';
                                    
                                    echo '<td><span class="nowrap">';
                                    echo '<input type="text" class="form-control" name="IPgroupeA'.$i.'" id="IPgroupeA'.$i.'" size="1" maxlength="3" placeholder="192"><strong>.</strong>';
                                    echo '<input type="text" class="form-control" name="IPgroupeB'.$i.'" id="IPgroupeB'.$i.'" size="1" maxlength="3" placeholder="168"><strong>.</strong>';
                                    echo '<input type="text" class="form-control" name="IPgroupeC'.$i.'" id="IPgroupeC'.$i.'" size="1" maxlength="3" placeholder="1"><strong>.</strong>';
                                    echo '<input type="text" class="form-control" name="IPgroupeD'.$i.'" id="IPgroupeD'.$i.'" size="1" maxlength="3" placeholder="0">';
                                    echo '</span></td>'; 
                                    
                                    echo '<td><input type="text" class="form-control" name="snmpCommunity'.$i.'" id="snmpCommunity'.$i.'" size="10" maxlength="12" placeholder="public"></td>';
                                    echo '<td><input type="text" class="form-control" name="username'.$i.'" id="username'.$i.'" size="10" maxlength="20" placeholder="username"></td>';
                                    echo '<td><input type="password" class="form-control" name="password'.$i.'" id="password'.$i.'" size="10" maxlength="20" placeholder="password"></td>';
                                    echo '</tr>';
                                }
                                
                                ?>
                                <td colspan="6" align="right"><input type="submit" class="form-control" name="submit" id="submit" value="Enregistrer"/></td>
                            </tbody>
                            </table>                                    
                         </div>                             
                        </form>
                        <div>
                            <p>
                                <form id="ajoutQty" name="ajoutQty" class="form-inline" role="form" action="ajoutAP.php" method="POST">
                                    <?php $qtyAP++; echo '<input type="hidden" value="'.$qtyAP.'" name="qtyAP"/>';?>
                                    <input type="submit" class="btn btn-success" name="ajouterForm" id="ajouterForm" value="Ajouter un champ pour un AP"/>
                                </form>
                            </p>
                           
                           
                       </div>
                    </ol>
                 </td>
              </tr>
           </tbody>
        </table>
        
        

      </div><!-- /container -->


    <!-- Bootstrap core JavaScrip ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <script type="text/javascript">
  <?php
        echo'
        $(function()
        {
            $("#ajoutAP").validate(
              {                
                rules: 
                {'; 
        
                for($i=0;$i<=$qtyAP;$i++){  
                    echo'
                  noModeleAP'.$i.': 
                  {
                    required: true                   
                  },
                  IPgroupeA'.$i.': 
                  {
                    required: true,
                    range:[10,255] 
                  },
                  IPgroupeB'.$i.':
                  {
                    required: true,
                    range:[0,255] 
                  },  
                  IPgroupeC'.$i.':
                  {
                    required: true,
                    range:[0,255] 
                  }, 
                  IPgroupeD'.$i.': 
                  {
                    required: true,
                    range:[0,255] 
                  }, 
                  password'.$i.':
                  {
                    required: true
                  }';
                  if ($i<=$qtyAP){echo ',';};                    
                }
        echo'
                },
                errorElement: "divBelow",
                errorPlacement: function(error, element) {
                    error.insertAfter(element);                    
                }           
              });
        });';
        ?>
    </script>      
  </body>
</html>