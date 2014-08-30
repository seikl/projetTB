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

  <body onunload="$('#loading2').hide();">>
      <br><br>
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
                           <li class="active"><a href="rechercherAP.php">Rechercher des AP sur le r&eacute;seau</a></li>                      
                        </ul>
                        <p><b>Configurer les AP</b></p>
                        <ul class="nav nav-pills nav-stacked">                       
                           <li><a href="choisirCommande.php">Appliquer une commande &agrave; un ou plusieurs AP</a></li>    
                        </ul> 
                 </td>                   
            
                 <td class="informations">
                    <ol class="breadcrumb">
                        <li><a href="accueilGestionAP.php">Accueil gestion des AP</a></li>    
                        <li>Rechercher des AP sur le r&eacute;seau</li>
                    </ol>  
                     <ol>
                         
                    <form id="CIDRform" name="CIDRform" class="form-inline" role="form" action="rechercherAPResultat.php" method="POST">
                        <div class="form-group">       
                            
                            <label for="name">Veuillez s&eacute;lectionner le type de mat&eacute;riel &agrave; rechercher et saisir la plage d'adresses &agrave; scanner</label><br>
                            <select class="form-control" name="infosModele">
                            
                     
                     <?php                                          
                        //connexion a la BDD et récupération de la liste des modèles
                        include '../includes/connexionBDD.php';                    
                        include '../includes/fonctionsUtiles.php';
                        
                        $infoModele = array("adrMACFabricant"=>'non indiqu&eacute;',"noModeleAP"=>'0');
                        $infoModele = base64_encode(serialize($infoModele));
                        echo '<option value="'.$infoModele.'">S&eacute;lection...&nbsp;&nbsp;&nbsp;</option>';
                        //récupération des infos enregistrées dans la BDD
                        try
                        {
                            
                                $infoModele=null;
                                $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                $resultatsAP=$connexion->query("SELECT * FROM modeles;");                                 
                                $resultatsAP->setFetchMode(PDO::FETCH_OBJ);                                 
                                
                                while( $ligne = $resultatsAP->fetch() ) // on récupère la liste des membres
                                {     
                                    $noModeleAP=(string)$ligne->noModeleAP;
                                    $nomModele=(string)$ligne->nomModele;
                                    $versionFirmware=(string)$ligne->versionFirmware;
                                    $nomFabricant=(string)$ligne->nomFabricant;
                                    $adrMACFabricant=(string)$ligne->adrMACFabricant; 
                                    $infoModele = array("adrMACFabricant"=>$adrMACFabricant,"noModeleAP"=>$noModeleAP);
                                    $infoModele = base64_encode(serialize($infoModele));
                                    echo '<option value="'.$infoModele.'">'.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.' (MAC: '.$adrMACFabricant.')&nbsp;&nbsp;&nbsp;</option>';
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
                            </select>
                            </br></br>
                            <table border="0">
                                <tr><td>
                                    <input type="text" class="form-control" name="groupeA" id="groupeA" size="3" maxlength="3" value="192">
                                </td><td>
                                    <strong>.</strong>
                                </td><td>
                                     <input type="text" class="form-control" name="groupeB" id="groupeB" size="3" maxlength="3" value="168">
                                </td><td>
                                     <strong>.</strong>
                                </td><td>
                                    <input type="text" class="form-control" name="groupeC" size="3" maxlength="3" value="1">
                                </td><td>
                                    <strong>.</strong>
                                </td><td>
                                    <input type="text" class="form-control" name="groupeD" size="3" maxlength="3" value="0">
                                </td><td>
                                    &nbsp;<strong class="indication">/</strong>&nbsp;
                                </td><td>
                                    <input type="text" class="form-control" name="masque" size="2" maxlength="2" value="24">                               
                                </td><td>
                                    &nbsp;&nbsp;<input type="submit" id="submit" class="btn btn-primary" value="Rechercher" onclick="$('#loading2').show();"/>                           
                                </td><td>
                                        <div id="loading2" style="display:none;" ><img class="img" src="../images/search-loader-circle2.gif" height="34" width="34" alt=""/>&nbsp;Recherche en cours...</div>
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
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript">
         (function (d) {
           d.getElementById('CIDRform').onsubmit = function () {
             d.getElementById('submit').style.display = 'none';
             d.getElementById('loading2').style.display = 'show';
           };
         }(document));
     </script>    
    <script type="text/javascript">
        $(function()
        {
            $("#CIDRform").validate(
              {                
                rules: 
                {            
                  groupeA: 
                  {
                    required: true,
                    range:[10,255]                    
                  },
                  groupeB: 
                  {
                    required: true,
                    range:[0,255]
                  },
                  groupeC: 
                  {
                    required: true,
                    range:[0,255]
                  },
                  groupeD: 
                  {
                    required: true,
                    range:[0,255]
                  },
                  masque:
                  {
                    required: true,
                    range:[8,32]
                  }                   
                },
                errorElement: "divBelow",
                errorPlacement: function(error, element) {
                    error.insertAfter(element);                    
                }                
              });
        });
    </script>     
     
  </body>
</html>