<?php
/**************************************************************************************************** 
 * Auteur: Sébastien Kleber (sebastien.kleber@heig-vd.ch) 
 * 
 * Description:
 * page d'accueil de la section "gestion des AP" qui affiche toutes la liste des éléments réseaux
 * enregistrés avec indication s'ils ont pû être contactés (PING) ou non. Il est possible de 
 * sélectionner (lien vers "interrogerAP.php") chacun d'entre-eux individuellement pour en obtenir 
 * plus d'informations.
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
                            <li class="active"><a href="afficherListeAP.php">Afficher la liste  de tous les AP inscrits</a></li>
                           <li><a href="rechercherAP.php">Rechercher des AP sur le r&eacute;seau</a></li>                      
                        </ul>
                        <p><b>Configurer les AP</b></p>
                        <ul class="nav nav-pills nav-stacked">                       
                           <li><a href="choisirCommande.php">Appliquer une commande &agrave; un ou plusieurs AP</a></li>    
                        </ul> 
                 </td>                 
            
                 <td class="informations">
                     
                     <ol class="breadcrumb">
                        <li><a href="../pagesGestionAP/accueilGestionAP.php">Accueil gestion des AP</a></li>                     
                        <li>Afficher la liste  de tous les AP inscrits</li>
                    </ol>
                    <?php                                             
                        echo '
                            <table class="table table-condensed table-hover" align="center">                            
                            <caption> Liste des acces points enregistr&eacute;s</caption>
                            <thead>                            
                               <tr>';
                        echo "<th>No et nom de l'AP</th>
                            <th>Mod&egrave; d'AP</th>
                            <th>Ping OK?</th>
                            </tr>
                            </thead>
                            <tbody>";                   
                    
                        //connexion a la BDD et récupération de la liste des modèles
                        include '../includes/connexionBDD.php';
                        $pingWindows="ping -n 1 -w 1 ";//Pour windows 
                        $pingLinux="ping -c 1 -w 1 ";//Pour Linux

                        try
                        {                            
                                $i =0;
                                $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                $resultatsAP=$connexion->query("SELECT * FROM accessPoints a, modeles m WHERE a.noModeleAP=m.noModeleAP ORDER BY a.nomAP, a.adresseIPv4;");                                 
                                $resultatsAP->setFetchMode(PDO::FETCH_OBJ);                                 
                                
                                while( $ligne = $resultatsAP->fetch() ) 
                                {         
                                    $resultatPing = "inconnu";    
                                    $statut=0;                                    
                                    
                                    $noAP=(string)$ligne->noAP;
                                    $nomAP=(string)$ligne->nomAP;
                                    $ip=(string)$ligne->adresseIPv4;
                                    $snmpCommunity=(string)$ligne->snmpCommunity;
                                    $nomFabricant=(string)$ligne->nomFabricant;
                                    $adrMACFabricant=(string)$ligne->adrMACFabricant;
                                    $nomModele=(string)$ligne->nomModele;
                                    $versionFirmware=(string)$ligne->versionFirmware; 
                                    

                                    $tabInfosAP= array("noAP" =>$noAP, "nomAP" => $nomAP, "nomFabricant" =>$nomFabricant, "adrMACFabricant" =>$adrMACFabricant, "nomModele" => $nomModele, "versionFirmware" =>$versionFirmware, "adresseIPv4" =>$ip, "snmpCommunity"=>$snmpCommunity);                                                                        
                                    exec($pingLinux.$ip,$reponse,$statut);//pour windows

                                    if ($statut==0) {
                                        echo '<tr class="success">';
                                        $resultatPing = "OK";
                                        
                                    } else {
                                        echo '<tr class="danger">';
                                        $resultatPing = "Not OK";
                                    }
                                    $infosAP=base64_encode(serialize($tabInfosAP));
                                    echo '<td><a href="interrogerAP.php?infosAP='.$infosAP.'">'.$noAP.' > '.$nomAP.' ('.$ip.')</a></td>'; //TODO Créer lien pour inmterroger AP                                    
                                    echo '<td>'.$nomFabricant.' '.$nomModele.' (firmware '.$versionFirmware.')</td>';
                                    echo '<td> '.$resultatPing.' </td>';

                                    echo '</tr>';
                                }
                        $resultatsAP->closeCursor(); // on ferme le curseur des résultats
                        
                        
                        }

                        catch(Exception $e)
                        {
                                echo '<tr><td colspan="3">';
                                echo 'Erreur : '.$e->getMessage().'<br />';
                                echo 'N° : '.$e->getCode();
                                echo '</td></tr>';
                        }
                    echo '</tbody></table>';
                            
                    ?>
                 </td>
              </tr>
           </tbody>
        </table>
        
        

      </div><!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
        <!-- Placed at the end of the document so the pages load faster -->    
  </body>
</html>