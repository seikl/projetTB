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
                        include '../includes/preperationRequete.php'; 
                        $delaiTimeout = 5;
                        
                        //Récupération des informations
                        if ($_POST) {                            
                            $tabCommandeChoisie= unserialize(base64_decode($_POST['commandeChoisie']));
                            $tabListeAP = unserialize(base64_decode($_POST['listeAP']));
                            $nomFichier='../fichiers/output.html';
                            
                            if (file_exists($nomFichier)){unlink ($nomFichier);}                            
                            file_put_contents( $nomFichier, 'R&eacute;sultats des requ&ecirc;tes ('.date('d M Y @ H:i:s').')<br>==========================================<br>');
                        }
                        else {echo " Probl&egrave;me &agrave; la r&eacute;ception de la commande.";}
                        
                        
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
                            
                            
                            //Ouverture d'un socket sur le port concerné
                            $fp = fsockopen($AP["adresseIPv4"], $tabCommandeChoisie["portProtocole"], $errno, $errstr, $delaiTimeout);                                
                            $erreur = $errno.' - '.$errstr;                                

                            if (!$fp) {
                                $texteErreur ='<tr class="danger"><td>'.$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].')';
                                $texteErreur = $texteErreur.'</td><td>'.$erreur;
                                $texteErreur= $texteErreur. '</td><td><strong>Not OK</strong></td></tr>';
                                echo $texteErreur;
                                file_put_contents($nomFichier, '<p><u>'.$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].')</u><br>'.$erreur.'</p>', FILE_APPEND); 
                            } 
                            else {                            
                            
                                //Préparation et envoi de la requête à transmettre en fonction du protocole (TELNET, SSH, HTTP, HTTPS, SNMP ou AUTRE)
                                switch (strtoupper($tabCommandeChoisie["protocole"])) {
                                    case "TELNET":
                                        $requete=requeteTELNET($AP["adresseIPv4"], $tabCommandeChoisie["ligneCommande"],$AP["username"],$AP["password"]);
                                        fwrite($fp, $requete);

                                        $reponse = '';
                                        while (!feof($fp)) {
                                            $reponse .= fgets($fp, 128);
                                        }                                        
                                        break;
                                    case "SSH":
                                        echo "requ&ecirc;te SSH";
                                        break;
                                    case "HTTP":
                                        $requete= requeteHTTP($AP["adresseIPv4"], $tabCommandeChoisie["ligneCommande"]);    
                                        fwrite($fp, $requete);
                                        $reponse = fgets($fp);                                        
                                        break;
                                    case "HTTPS":
                                        echo "requ&ecirc;te HTTPS";;
                                        break;
                                    case "SNMP":
                                        echo "requ&ecirc;te SNMP";
                                        break;      
                                    case "AUTRE":
                                        echo "requ&ecirc;te AUTRE";
                                        break;  
                                    default:
                                        $requete=$tabCommandeChoisie["ligneCommande"];
                                        break;
                                }
                                                                         
                                fclose($fp);                                                                
                                file_put_contents($nomFichier, '<p><u>'.$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].')</u><br>'.$reponse.'</p>', FILE_APPEND);                                
                                $reponse = substr($reponse,50,200);  

                                if ($reponse != ''){
                                    echo '<tr class="success"><td>'.$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].')';
                                    echo '</td><td>'.$reponse;                                          
                                    echo '</td><td><strong>OK</strong></td></tr>';
                                    
                                }
                                else{
                                    echo '<tr class="success"><td>'.$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].')';
                                    echo '</td><td> Pas de r&eacute;ponse re&ccedil;ue';                                          
                                    echo '</td><td><strong>OK</strong></td></tr>';                                    
                                }                           
                            }                                
                        }                             
                                                    
                        echo '</tbody></table>';                        
                        //echo "<br>-------------------------------------------------<br> commande choisie: ";//.htmlspecialchars(print_r($tabCommandeChoisie, true));
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