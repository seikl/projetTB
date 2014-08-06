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
                           <li><a href="choisirCommande.php">Appliquer une commande &agrave; un ou plusieurs AP</a></li>    
                        </ul> 
                 </td>  
            
                 <td class="informations">
                     
                     <ol class="breadcrumb">
                        <li><a href="#" class="active">Accueil gestion des AP</a></li>                         
                    </ol>
                    <?php   
                        
                    
                        echo "
                            <table class='table table-striped' width='60%' align='center'>                            
                            <thead>
                               <tr>
                                  <th>Nombre d'AP en fonction de leur mod&egrave;le respectif</th>
                               </tr>
                            </thead>
                            <tbody>";                   
                    
                        //connexion a la BDD et récupération de la liste des modèles
                        include '../includes/connexionBDD.php';

                        try
                        {
                            
                                $i =0;
                                $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                $resultatsModeles=$connexion->query("SELECT DISTINCT m.nomModele, m.nomFabricant, COUNT(a.noModeleAP)  as nombreAP, m.versionFirmware FROM modeles m, accessPoints a WHERE a.noModeleAP=m.noModeleAP GROUP BY a.noModeleAP;"); // on va chercher tous les membres de la table qu'on trie par ordre croissant
                                $resultatsModeles->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
                                
                                
                                while( $ligne = $resultatsModeles->fetch() ) // on récupère la liste des membres
                                {                                      
                                        echo '<tr><td>'.(string)$ligne->nombreAP.'x '.(string)$ligne->nomFabricant.' '.(string)$ligne->nomModele.' (firmware '. (string)$ligne->versionFirmware.')</td></tr>'; // on affiche les membres
                                }
                                $resultatsModeles->closeCursor(); // on ferme le curseur des résultats
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