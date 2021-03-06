<?php 
/**************************************************************************************************** 
 * Auteur: Sébastien Kleber (sebastien.kleber@heig-vd.ch) 
 * 
 * Description:
 * page d'accueil de la section "gestion des AP" qui affiche la liste des modèles enregistrés et
 * le nombre d'éléments réseaux qui y leurs sont liés
 *                                                                                                  *
 * Modifié le: 01.09.2014                                                                           *
 ***************************************************************************************************/

$auth_realm = 'AP Tool'; require_once '../includes/authentification.php'; ?> 
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>AP Tool</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <script type="text/javascript" src="../js/jquery-1.11.1.js"></script>
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
                <?php include '../includes/menus.php'; echo $menuPagesGestionAP; ?>
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
                     <ol>
                    <form id="selectionmodele" class="form-inline" role="form" action="choisirCommande.php" method="POST">
                        <div class="form-group">                              
                    <?php                                               
                        echo "
                            <table class='table table-bordered table-hover' width='70%' align='center'>                            
                            <thead>
                               <tr>
                                  <th>Nombre d'AP en fonction de leur mod&egrave;le respectif</th>
                               </tr>
                            </thead>
                            <tbody>";                   
                    
                        //connexion a la BDD et récupération de la liste des modèles
                        include '../includes/connexionBDD.php';
                        $actionOnClick="$('#selectionmodele').submit();";
                        try
                        {                            
                            $i =0;
                            $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                            $resultatsModeles=$connexion->query("SELECT m.noModeleAP, m.nomModele, m.nomFabricant, COUNT(a.noModeleAP)  as nombreAP, m.versionFirmware FROM modeles m LEFT OUTER JOIN accessPoints a ON a.noModeleAP=m.noModeleAP GROUP BY m.noModeleAP;");
                            $resultatsModeles->setFetchMode(PDO::FETCH_OBJ);                                 

                            while( $ligne = $resultatsModeles->fetch() )
                            {                                            
                                    $noModeleAP=(string)$ligne->noModeleAP;
                                    $radioChecked="document.getElementById('radio".$noModeleAP."').checked = true";
                                    $textCellule= (string)$ligne->nombreAP.'x '.(string)$ligne->nomFabricant.' '.(string)$ligne->nomModele.' (firmware '. (string)$ligne->versionFirmware.')';

                                    echo '<tr><td onmouseover="'.$radioChecked.'" onclick="'.$actionOnClick.'">';
                                    echo '<input id="radio'.$noModeleAP.'" type="radio" class="hidden" value="'.$noModeleAP.'" name="noModele">'.$textCellule;
                                    echo '</td></tr>';
                            }
                            $resultatsModeles->closeCursor(); // on ferme le curseur des résultats
                            }

                        catch(Exception $e)
                        {
                                echo '<tr><td>Erreur : '.$e->getMessage().'<br />';
                                echo 'N° : '.$e->getCode().'</td></tr>';
                        }


                        
                        echo '</tbody>
                         </table>  
                        ';                            
                    ?>
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