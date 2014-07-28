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
                           <li><a href="#" class="active">Afficher la liste  de tous les AP inscrits</a></li>
                           <li><a href="#">Interroger un AP</a></li>                       
                        </ul>
                        <p><b>Configurer les AP</b></p>
                        <ul class="nav nav-pills nav-stacked">                       
                           <li><a href="#">Appliquer une commande &agrave; un ou plusieurs AP</a></li>    
                        </ul> 
                 </td>  
            
                 <td class="informations">
                     
                     <ol class="breadcrumb">
                        <li><a href="../pagesGestionAP/accueilGestionAP.php">Gestion des AP</a></li>                     
                        <li><a href="../pagesGestionAP/accueilGestionAP.php">Accueil</a></li>
                        <li>Afficher la liste  de tous les AP</li>
                    </ol>
                    <?php   
                        
                    
                        echo '
                            <table class="table table-condensed" align="center">                            
                            <caption> Liste des acces points enregistr&eacute;s</caption>
                            <thead>                            
                               <tr>';
                        echo "<th>Mod&egrave; d'AP</th>
                                  <th>Nom de l'AP</th>
                                  <th>Ping OK?</th>
                               </tr>
                            </thead>
                            <tbody>";                   
                    
                        //connexion a la BDD et récupération de la liste des modèles
                        include '../includes/connexionBDD.php';
                        require_once '../pourTests/hostPing.php';

                        try
                        {
                            
                                $i =0;
                                $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                $resultatsAP=$connexion->query("SELECT m.nomModele, m.nomFabricant, m.versionFirmware, a.nomAP, a.adresseIPv4 FROM accessPoints a, modeles m WHERE a.noModeleAP=m.noModeleAP;"); 
                                $resultatsAP->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
                                
                                
                                while( $ligne = $resultatsAP->fetch() ) // on récupère la liste des membres
                                {         
                                    $resultatPing = "inconnu";    
                                    $ip=(string)$ligne->adresseIPv4;
                                    exec("ping -n 2 -w 1 ".$ip,$reponse,$statut);


                                    if ($statut==0) {
                                        echo '<tr class="success">';
                                        $resultatPing = "OK";
                                    } else {
                                        echo '<tr class="danger">';
                                        $resultatPing = "Not OK";
                                    }
                                    echo '<td>'.(string)$ligne->nomFabricant.' '.(string)$ligne->nomModele.' (firmware '. (string)$ligne->versionFirmware.')</td>';
                                    echo '<td>'.(string)$ligne->nomAP.' ('.(string)$ligne->adresseIPv4.')</td>'; //TODO Créer lien pour inmterroger AP
                                    echo '<td> '.$resultatPing.' </td>';

                                    echo '</tr>';
                                }
                                $resultatsAP->closeCursor(); // on ferme le curseur des résultats
                                }

                        catch(Exception $e)
                        {
                                echo 'Erreur : '.$e->getMessage().'<br />';
                                echo 'N° : '.$e->getCode();
                        }


                        
                        echo '</tbody>
                         </table>  
                        ';
                            
                    ?>
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