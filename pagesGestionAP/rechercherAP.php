<!DOCTYPE html>
<html lang="en">
  <head>
    <title>AP Manager</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <script type="text/javascript" src="../js/jquery-1.11.1.js"></script>
    <script>
        function load()
        {
            alert("Chargement en cours");
        }
    </script>
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
                           <li class="active"><a href="#">Rechercher des AP sur le r&eacute;seau</a></li>                       
                        </ul>
                        <p><b>Configurer les AP</b></p>
                        <ul class="nav nav-pills nav-stacked">                       
                           <li><a href="#">Appliquer une commande &agrave; un ou plusieurs AP</a></li>    
                        </ul> 
                 </td>  
            
                 <td class="informations">
                    <ol class="breadcrumb">
                        <li><a href="accueilGestionAP.php">Accueil gestion des AP</a></li>    
                        <li>Rechercher des AP sur le r&eacute;seau</li>
                    </ol>  
                     <ol>
                         
                    <form class="form-inline" role="form" action="rechercherAPResultat.php" method="POST">
                        <div class="form-group">       
                            
                            <label for="name">Veuillez s&eacute;lectionner le type de mat&eacute;riel &agrave; rechercher et saisir la plage d'adresses &agrave; scanner</label><br>
                            <select class="form-control" name="vendorMAC">
                            <option value="null">S&eacute;lection...&nbsp;&nbsp;&nbsp;</option>
                     
                     <?php
                        //connexion a la BDD et récupération de la liste des modèles
                        include '../includes/connexionBDD.php';                    
                        include '../includes/fonctionsUtiles.php';
                        
                        //récupération des infos enregistrées dans la BDD
                        try
                        {
                            
                                $i =0;
                                $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                $resultatsAP=$connexion->query("SELECT * FROM modeles;");                                 
                                $resultatsAP->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet                                
                                
                                while( $ligne = $resultatsAP->fetch() ) // on récupère la liste des membres
                                {     
                                    $nomModele=(string)$ligne->nomModele;
                                    $versionFirmware=(string)$ligne->versionFirmware;
                                    $nomFabricant=(string)$ligne->nomFabricant;
                                    $adrMACFabricant=(string)$ligne->adrMACFabricant; 
                                    echo '<option value="'.$adrMACFabricant.'">'.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.' (MAC: '.$adrMACFabricant.')&nbsp;&nbsp;&nbsp;</option>';
                                }
                            $resultatsAP->closeCursor(); // on ferme le curseur des résultats
                                                
                        }
                                                

                        catch(Exception $e)
                        {
                                echo '<li> Erreur lors du chargement</li></ol>';
                                echo 'Erreur : '.$e->getMessage().'<br />';
                                echo 'N° : '.$e->getCode();
                        }                        
                        
                     ?>    
                            </select>
                            </br></br>
                           
                           <input type="text" class="form-control" name="groupeA" size="3" maxlength="3" value="192"/>
                           <strong>.</strong>
                            <input type="text" class="form-control" name="groupeB" size="3" maxlength="3" value="168"/>
                            <strong>.</strong>
                           <input type="text" class="form-control" name="groupeC" size="3" maxlength="3" value="1"/>
                           <strong>.</strong>
                           <input type="text" class="form-control" name="groupeD" size="3" maxlength="3" value="0"/>
                           <strong>/</strong>
                           <input type="text" class="form-control" name="masque" size="2" maxlength="2" value="24"/>                               
                        
                        &nbsp;&nbsp;<button type="submit" class="btn btn-primary" onclick="load()">Rechercher</button>
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
  </body>
</html>