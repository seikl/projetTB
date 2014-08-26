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
                                $tabListeDescriptions[$i]=array("notypeCommande"=>$noTypeCommande,"typeCommande" =>$typeCommande, "description"=>$description);
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
                       
                       <form id="ajoutCLI" name="ajoutCLI" class="form-inline" role="form" action="enregistrerCLI.php" method="POST">
                        <div class="form-group">       
                            <table border="0" class="table">
                                <tr><td colspan="2">
                                    <strong class="obligatoire">*&nbsp;</strong><label for='ligneCommande'>Ligne de commande &agrave; transmettre:</label><br>
                                    <textarea rows="4" cols="50" class="form-control" form="ajoutCLI" name="ligneCommande" id="ligneCommande" placeholder="show system\r\n\quit\r\n"></textarea>                                                                        
                                </td></tr>
                                <tr><td align="left" colspan="2"><p>
                                    <strong class="obligatoire">*&nbsp;</strong><label for='protocole'>Choix du protocole et saisie du No de port</label><br>
                                    <select class="form-control" id="protocole" name="protocole"> 
                                        <option value="">Choix du protocole &nbsp;&nbsp;&nbsp;</option>';
                                        <option value="TELNET">TELNET&nbsp;&nbsp;&nbsp;</option>';
                                        <option value="HTTP">HTTP&nbsp;&nbsp;&nbsp;</option>';
                                        <option value="HTTPS">HTTPS&nbsp;&nbsp;&nbsp;</option>';
                                        <option value="SNMP">SNMP&nbsp;&nbsp;&nbsp;</option>';                                        
                                    </select><strong>:</strong>                                                                    
                                    <input type="text" class="form-control" name="portProtocole" id="ligneCommande" size="3" maxlength="5" placeholder="23">  
                                        </p>
                                </td></tr>
                                <tr><td colspan="2">
                                <strong class="obligatoire">*&nbsp;</strong><label for='noModeleAP'>Choix du mod&egrave;le auquel s'appliquera la commande</label><br>                                    
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
                                <strong class="obligatoire">*&nbsp;</strong><label for='choixAjoutDescription'>Choix ou ajout d'une description pour la commande</label><br>  
                                <input type="radio" name="choixAjoutDescription" id="choixDescription" value="selection" class="form-control" checked/>    
                                <?php
                                    echo '<select class="form-control skip" id="choixTypeCommande" name="choixTypeCommande" onChange="document.getElementById(\'choixDescription\').checked=true;">';
                                    echo '<option value="">Choix de la description</option>';
                                    foreach ($tabListeDescriptions as $description){
                                        $tabDescription=array("notypeCommande"=>$description["notypeCommande"],"typeCommande"=>$description["typeCommande"],"description"=>substr($description["description"],0,60));
                                        $tabDescription= base64_encode(serialize($tabDescription));
                                        echo '<option value="'.$tabDescription.'">'.$description["typeCommande"].' ('.substr($description["description"],0,40).'...) &nbsp;&nbsp;&nbsp;</option>';                                                    
                                    }
                                    echo '</select>';                                
                                ?>  
                                </td></tr>
                                <tr><td colspan="2">       
                                    <strong class="obligatoire">*&nbsp;</strong><label for='typeCommande'>OU  saisie d'un nouvel intitul&eacute; de commande</label><br> 
                                </td></tr>
                                <tr><td>
                                    <input type="radio" name="choixAjoutDescription" id="ajoutDescription" value="ajout" class="form-control"/>
                                </td><td>
                                    <input type="text" name="typeCommande" id="typeCommande" size="41" maxlength="100" class="form-control skip" placeholder="Afficher les infos syst&egrave;me" onClick="document.getElementById('ajoutDescription').checked=true;">                                    
                                </td></tr>
                                <tr><td>
                                       &nbsp; 
                                </td><td>
                                    <label for='typeCommande'>Description d&eacute;taill&eacute;e:</label><br>
                                    <textarea name="description" rows="2" cols="40" maxlength="255" class="form-control" placeholder="Envoie une requ&ecirc;te TELNET pour obtenir le statut g&eacute;n&eacute;ral"></textarea>
                                </td></tr>                               
                                <tr><td align="right">
                                        &nbsp;            
                                </td><td>
                                        <input type="submit" id="submit" class="btn btn-primary" value="Enregistrer"/>
                                        Tous les champs marqu&eacute;s d'une <strong class="obligatoire">*</strong>&nbsp;sont obligatoires.
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
            $("#ajoutCLI").validate(
              {         
                ignore:'.skip',                          
                rules: 
                { 
                    ligneCommande: 
                    {
                      required: true                   
                    },
                    protocole: 
                    {
                      required: true
                    },
                    portProtocole: 
                    {
                      required: true,
                      range:[1,65535]
                    }, 
                    noModeleAP: 
                    {
                      required: true
                    },
                    notypeCommande: 
                    {
                      required: true                   
                    },
                    typeCommande:
                    {
                      required: true                   
                    }                            
                },
                errorElement: "divRight",
                errorPlacement: function(error, element) {
                    error.insertAfter(element);                    
                }                
              });
            //permet de décider quel formulaire (ajout d'une description ou choix d'une existante) sera obligatoire
            $('#choixDescription').change(function() 
            {
              if($(this).is(":checked")) 
              {
                $('#choixTypeCommande').removeClass('skip');
                $('#typeCommande').addClass('skip');
              }
              else
              {
                $('#typeCommande').removeClass('skip');
                $('#choixTypeCommande').addClass('skip');
              }
              validator.resetForm();
            });

            $('#ajoutDescription').change(function() 
            {
              if($(this).is(":checked")) 
              {
                alert('');
                $('#typeCommande').removeClass('skip');
                $('#choixTypeCommande').addClass('skip');          

              }
              else
              {
                $('#choixTypeCommande').removeClass('skip');
                $('#typeCommande').addClass('skip');
              }
              validator.resetForm();
            });              
        });
    </script>      
  </body>
</html>