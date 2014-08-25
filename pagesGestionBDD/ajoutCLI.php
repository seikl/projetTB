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
                        <li>Ajouter une ligne de commande</li>
                    </ol>
                   <ol>
                    <?php
                        //connexion a la BDD et récupération de la liste des modèles et des descriptions
                        include '../includes/connexionBDD.php';  
                        try
                        {            
                            $i=0;
                            $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                            $resultatsAP=$connexion->query("SELECT * FROM modeles;");                                 
                            $resultatsAP->setFetchMode(PDO::FETCH_OBJ);                                 
                            $resultatsDescription=$connexion->query("SELECT * FROM typeCommandes;");                                 
                            $resultatsDescription->setFetchMode(PDO::FETCH_OBJ);    

                            while( $ligne = $resultatsAP->fetch()){ // on récupère la liste des modeles    
                                $noModeleAP =(string)$ligne->noModeleAP;$nomModele =(string)$ligne->nomModele;$versionFirmware=(string)$ligne->versionFirmware;
                                $nomFabricant=(string)$ligne->nomFabricant;$adrMACFabricant=(string)$ligne->adrMACFabricant; 
                                $tabListeModeles[$i]=array("noModeleAP" =>$noModeleAP, "nomModele"=>$nomModele, "versionFirmware"=>$versionFirmware,"nomFabricant"=>$nomFabricant, "adrMACFabricant"=>$adrMACFabricant);
                                $i++;
                            }
                            $resultatsAP->closeCursor(); // on ferme le curseur des résultats
                            
                            while( $ligne = $resultatsDescription->fetch()){ // on récupère la liste des descriptions   
                                $noTypeCommande= (string)$ligne->notypeCommande;$typeCommande =(string)$ligne->typeCommande;$description =(string)$ligne->description;
                                $tabListedescrioptions[$i]=array("notypeCommande"=>$noTypeCommande,"typeCommande" =>$typeCommande, "description"=>$description);
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
                       
                    <form id="ajoutCLI" name="ajoutCLI" class="form-inline" role="form" action="ajoutCLI.php" method="POST">
                        <div class="form-group">       

                            <table border="0" class="table">
                                <tr><td colspan="2">
                                    <strong class="obligatoire">*&nbsp;</strong><label for='ligneCommande'>Ligne de commande &agrave; transmettre:</label><br>
                                    <input type="textarea" class="form-control" name="ligneCommande" id="ligneCommande" placeholder="show system\r\n\quit\r\n">                                                                        
                                </td></tr>
                                <tr><td align="right">
                                    <strong class="obligatoire">*&nbsp;</strong><label for='protocole'>Choix du protocole</label><br>
                                    <select class="form-control" id="protocole" name="protocole"> 
                                        <option value="">Choix du protocole</option>';
                                        <option value="TELNET">TELNET</option>';
                                        <option value="HTTP">HTTP</option>';
                                        <option value="HTTPS">HTTPS</option>';
                                        <option value="SNMP">SNMP</option>';                                        
                                    </select>
                                </td><td>
                                    <strong class="obligatoire">*&nbsp;</strong><label for='portProtocole'>saisie du No de port</label><br>
                                    <input type="text" class="form-control" name="portProtocole" id="ligneCommande" size="3" maxlength="5" placeholder="23">  
                                </td></tr>
                                <tr><td colspan="2">
                                <strong class="obligatoire">*&nbsp;</strong><label for='noModeleAP'>Choix du mod&eagrave; auquel s'appliquera la commande</label><br>                                    
                                <?php
                                    echo '<select class="form-control" id="noModeleAP" name="noModeleAP">';
                                    echo '<option value="">Choix du mod&egrave;le</option>';
                                    foreach ($tabListeModeles as $modele){
                                        echo '<option value="'.$modele["noModeleAP"].'">'.$modele["nomFabricant"].' '.$modele["nomModele"].' v.'.$modele["versionFirmware"].'&nbsp;&nbsp;&nbsp;</option>';                                                    
                                    }
                                    echo '</select>';                                
                                ?>  
                                </td></tr>  
                                <tr><td colspan="2">                         
                                <strong class="obligatoire">*&nbsp;</strong><label for='choixDescription'>Choix ou ajout d'une description pour la commande</label><br>  
                                <input type="radio" name="choixDescription" value="on">    
                                <?php
                                    echo '<select class="form-control" id="notypeCommande" name="notypeCommande">';
                                    echo '<option value="">Choix de la description</option>';
                                    foreach ($tabListedescrioptions as $description){
                                        echo '<option value="'.$description["notypeCommande"].'">'.$description["typeCommande"].' ('.substr($description["description"],0,40).'...) &nbsp;&nbsp;&nbsp;</option>';                                                    
                                    }
                                    echo '</select>';                                
                                ?>  
                                </td></tr>
                                <tr><td>       
                                        ----------------------------------------------<br>
                                <input type="radio" name="ajoutDescription" value="off">    
                                <input type="text" name="typeCommande" size="40" maxlength="100" placeholder="Afficher les infos syst&eagrave">
                                </td><td>
                                    <strong class="obligatoire">*&nbsp;</strong><label for='typeCommande'>Nouveau type de commande</label><br>  
                                </td></tr>   
                                <tr><td>                          
                                <input type="text" name="description" size="40" maxlength="255" placeholder="Envoi un requ&circ;te TELNET pour obtenir le statut des Avaya AP-6">
                                </td><td>
                                    <label for='typeCommande'>Description d&eacute;aill&eacute;e de la nouvelle commande</label><br>  
                                </td></tr>                               
                                <tr><td align="right">
                                    <input type="submit" id="submit" class="btn btn-primary" value="Enregistrer"/>                           
                                </td><td>
                                        Tous les champs marqu&eacute;s d'une <strong class="obligatoire">*&nbsp;</strong>sont obligatoires.
                                </td></tr>
                            </table>                                    
                         </div>                             
                        </form>
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
            $("#ajoutModele").validate(
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