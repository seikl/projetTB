<?php
/**************************************************************************************************** 
 * Auteur: Sébastien Kleber (sebastien.kleber@heig-vd.ch) 
 * 
 * Description:
 * page qui reçoit en paramètre une liste d'AP auxquels il faut transmettre une commande, affiche 
 * un résultat succint et enregistre les résultats plus complets dans un fichier HTML de sortie 
 * (ouput.html)
 * 
 * paramètre reçus de "choisirCommande.php":
 * - $tabCommandesChoisies: Le tableau contenant les commandes qui peuvent être envoyées
 * - $tabListeAP: Le tableau contenant la liste des AP à contacter
 * - $nbTrames: le nombre de "lignes" de la réponse du pérphéqieu réseau que l'on enregistre
 * 
 * Fait appel à "includes/envoiRequete.php" pour transmettre la requête correspondante à chaque
 *  pérphérique réseau à contacter
 *                                                                                                  *
 * Modifié le: 31.08.2014                                                                           *
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
                           <li class="active"><a href="choisirCommande.php">Appliquer une commande &agrave; un ou plusieurs AP</a></li>    
                        </ul> 
                 </td>                   
            
                 <td class="informations">
                    <ol class="breadcrumb">
                        <li><a href="accueilGestionAP.php">Accueil gestion des AP</a></li>    
                        <li><a href="choisirCommande.php">Appliquer une commande &agrave; un ou plusieurs AP</a></li>
                        <li>R&eacute;sultat des requ&ecirc;tes</li>
                    </ol>  
                     <ol>
                    <?php       
                    
                        //pour autoriser le script à s'exécuter pendant 10 secs
                         $delaiTimeout = 2;
                        set_time_limit($delaiTimeout);                    
                        date_default_timezone_set('Europe/Zurich');
                        include '../includes/envoiRequete.php'; 
                        include '../includes/fonctionsUtiles.php';
                        
                        
                        //Récupération des informations
                        if ($_POST) {                            
                            $tabCommandesChoisies= unserialize(base64_decode($_POST['commandesChoisies']));
                            $tabListeAP = unserialize(base64_decode($_POST['listeAP']));                            
                            $nbTrames=$_POST['nbTrames'];
                            $nomFichier='../fichiers/output.html';  
                            
                            foreach ($tabListeAP as $modeleAP){$listeModeles[]=$modeleAP["noModeleAP"];}
                            
                            //pour afichage uniquement des commandes qui ont été appliquées
                            $affichageCommandes='<table cellspacing="2"><tr><th align="left">Mod&egrave;le: </th><th align="left">Ligne de commande</th><th align="left">protocole:port</th></tr>';
                            foreach ($tabCommandesChoisies as $commande){                                 
                                if (in_array($commande["noModeleAP"], $listeModeles)){
                                    $affichageCommandes.= '<tr><td valign="top">'.$commande["noModeleAP"].' - ' .$commande["nomFabricant"].' '.$commande["nomModele"].' (Firmware v'.$commande["versionFirmware"].') </td>';
                                    $affichageCommandes.= '<td>';
                                    $tabCommande= explode("\n", $commande["ligneCommande"]);      
                                    foreach($tabCommande as $ligneReq){$affichageCommandes.='#'.$ligneReq.'<br>';}
                                    $affichageCommandes.= '</td><td>'.$commande["protocole"].':'.$commande["portProtocole"];
                                    $affichageCommandes.= '</tr>';
                                }
                            }
                            $affichageCommandes.='</table><br>';
                            
                            if (file_exists($nomFichier)){unlink ($nomFichier);}                            
                            file_put_contents($nomFichier, '<html><body>R&eacute;sultats des requ&ecirc;tes ('.date('d M Y @ H:i:s').')<br>'.
                                            'Commandes transmises: <br>'.$affichageCommandes.                                            
                                            '==========================================<br>');
                        }
                        else {echo " <strong>Probl&egrave;me &agrave; la r&eacute;ception de la commande.</strong>";}
                        
                        
                        echo '<table class="table table-hover" align="center" width="100%">
                            <caption> R&eacute;ponses re&ccedil;ues des requ&ecirc;tes transmises:</caption>
                            <thead>                            
                               <tr>';
                        echo "<th>No et IP de l'AP</th>
                            <th>D&eacute;but de la r&eacute;ponse</th>
                            <th>Connexion &eacute;tablie?</th>
                            </tr>
                            </thead>
                            <tbody>";                        
                        //parcours des AP
                        foreach ($tabListeAP as $AP){                                                                                      
                            $fp=true;
                            $reponse = '';
                            $erreur='';
                            $erreurDetectee=false;
                            $i=0;                                                               

                            //Préparation et envoi de la requête à transmettre en fonction du protocole (TELNET, HTTP, HTTPS, SNMP ou AUTRE)
                            switch (strtoupper($tabCommandesChoisies[$AP["noModeleAP"]]["protocole"])) {
                                case "TELNET":                                       
                                    $taille=1500;  
                                    //Ouverture d'un socket sur le port concerné
                                    $fp = @fsockopen($AP["adresseIPv4"], $tabCommandesChoisies[$AP["noModeleAP"]]["portProtocole"], $errno, $errstr, $delaiTimeout);                                      
                                    sleep(1); 
                                    $erreur .= $errno.' - '.$errstr;
                                    if (!$fp) {     
                                        $erreurDetectee=false;
                                    } 
                                    else{
                                        //Envoi de la requête
                                        $reponse = requeteTELNET($AP["adresseIPv4"], $tabCommandesChoisies[$AP["noModeleAP"]]["ligneCommande"], $AP["username"], $AP["password"], $fp,$taille,$nbTrames);
                                        fclose($fp);                                                    
                                    }
                                    //pour déterminer la partie de réponse qu'on récupère
                                    $debutExtraitRep=50;
                                    $finExtraitRep=200;                                    
                                    break;

                                case "SSH":
                                    echo "requ&ecirc;te SSH";
                                    break;

                                case "HTTP":
                                    $reponse = requeteHTTP($AP["adresseIPv4"], $tabCommandesChoisies[$AP["noModeleAP"]]["ligneCommande"], $tabCommandesChoisies[$AP["noModeleAP"]]["portProtocole"] ,$AP["username"], $AP["password"]);
                                    if(preg_match("/Erreur/i", $reponse)){$erreur=$reponse; $reponse='';$erreurDetectee=false;}
                                    //pour traiter les erreurs HTTP recues
                                        if((preg_match("/40/i", substr($reponse,0,3))) || 
                                            (preg_match("/50/i", substr($reponse,0,3))) ||
                                            (preg_match("/0/i", substr($reponse,0,1)))){
                                            $erreur=$reponse; $reponse='';$erreurDetectee=true;                                            
                                        }
                                    $debutExtraitRep=0;
                                    $finExtraitRep=200;
                                    break;                                        

                                case "HTTPS":
                                    $reponse = requeteHTTPS($AP["adresseIPv4"], $tabCommandesChoisies[$AP["noModeleAP"]]["ligneCommande"], $tabCommandesChoisies[$AP["noModeleAP"]]["portProtocole"] ,$AP["username"], $AP["password"]);
                                        if(preg_match("/Erreur/i", $reponse)){$erreur=$reponse; $reponse='';$erreurDetectee=false;}
                                        //pour traiter les erreurs HTTP recues
                                        if((preg_match("/40/i", substr($reponse,0,3))) || 
                                            (preg_match("/50/i", substr($reponse,0,3))) ||
                                            (preg_match("/0/i", substr($reponse,0,1)))){
                                            $erreur=$reponse; $reponse='';$erreurDetectee=true;                                            
                                        }

                                        $debutExtraitRep=0;
                                        $finExtraitRep=200;  
                                    break;
                                case "SNMP":
                                    try {      
                                        $timeout=1000000;
                                        $requete = snmprealwalk($AP["adresseIPv4"], $AP["snmpCommunity"],$tabCommandesChoisies[$AP["noModeleAP"]]["ligneCommande"],$timeout);
                                        $reponse = implode($requete);
                                    }
                                    catch(ErrorException $e)
                                    {        
                                        $reponse='';
                                        $erreurDetectee=true;
                                        $erreur= $e->getMessage();
                                    }   

                                    $debutExtraitRep=0;
                                    $finExtraitRep=128;                                         
                                    break;      
                                case "AUTRE":
                                    echo "requ&ecirc;te AUTRE";
                                    break;  
                                default:
                                    $requete=$tabCommandesChoisies[$AP["noModeleAP"]]["ligneCommande"];
                                    break;
                            }//fin du switchCase                                                                                                                                         

                            $extraitReponse = substr($reponse,$debutExtraitRep,$finExtraitRep);    
                             
                            $infosAP=$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].', mod&egrave;le: '.
                                    $tabCommandesChoisies[$AP["noModeleAP"]]["nomFabricant"].' '.$tabCommandesChoisies[$AP["noModeleAP"]]["nomModele"].' '.$tabCommandesChoisies[$AP["noModeleAP"]]["versionFirmware"].')';
                            $infosProtocole=' ( '.$tabCommandesChoisies[$AP["noModeleAP"]]["protocole"].':'.$tabCommandesChoisies[$AP["noModeleAP"]]["portProtocole"].')';  
                            if ($reponse != '' && !$erreurDetectee){
                                echo '<tr class="success"><td>'.$infosAP;
                                echo '</td><td>'.$extraitReponse;                                          
                                echo '</td><td><strong>OK</strong>'.$infosProtocole.'</td></tr>';
                                file_put_contents($nomFichier, '<p><u><b>'.$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].')</b></u><br>'.substr($reponse,0,($nbTrames*$finExtraitRep)).'</p>', FILE_APPEND);

                            }
                            else if ($erreur != '' && $erreurDetectee){
                                $extraitErreur = substr($erreur,$debutExtraitRep,$finExtraitRep);
                                echo '<tr class="warning"><td>'.$infosAP;                                
                                echo '</td><td>'.$extraitErreur;                                       
                                echo '</td><td><strong>OK</strong>'.$infosProtocole.'</td></tr>'; 
                                file_put_contents($nomFichier, '<p><u><b>'.$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].')</b></u><br>'.$erreur.'</p>', FILE_APPEND);                                
                            }                                
                            else {
                                $extraitErreur = substr($erreur,$debutExtraitRep,$finExtraitRep);
                                echo '<tr class="danger"><td>'.$infosAP;
                                $erreur .= '<br>( pas de r&eacute;ponse re&ccedil;ue)';
                                echo '</td><td>'.$extraitErreur;                                       
                                echo '</td><td><strong>Not OK</strong>'.$infosProtocole.'</td></tr>'; 
                                file_put_contents($nomFichier, '<p><u><b>'.$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].')</b></u><br>'.$erreur.'</p>', FILE_APPEND);
                            }                                                                                       
                        }                             
                                                    
                        echo '</tbody></table>'; 
                        file_put_contents($nomFichier, '<input type="button" value="Imprimer..." onClick="window.print()"></body></html>', FILE_APPEND);
                        //echo "<br>-------------------------------------------------<br> commande choisie: ";//.htmlspecialchars(print_r($tabCommandesChoisies, true));
                        //echo "<br><br> listeAP: ".htmlspecialchars(print_r($tabListeAP, true));                         
                        //echo '<br><br>'.stripcslashes(ereg_replace("(\r\n|\n|\r)", "[CR][LF]", $requete));                                          
                       ?>                        
                         <button class="btn btn-primary" onclick="window.open('../fichiers/output.html');">Afficher le fichier des r&eacute;ponses</button>                         
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
