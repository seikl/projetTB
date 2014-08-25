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
      <br><br>
    <div class="container-fluid">        

        <table border="0" width="90%" align="center">
           <tbody>
              <tr>
                <?php include '../includes/menus.php'; echo $menuPagesGestionAP; ?> 
            
                 <td class="informations">
                    <ol class="breadcrumb">
                        <li><a href="accueilGestionAP.php">Accueil gestion des AP</a></li>    
                        <li><a href="choisirCommande.php">Appliquer une commande &agrave; un ou plusieurs AP</a></li>
                        <li>R&eacute;sultat des requ&ecirc;tes</li>
                    </ol>  
                     <ol>
                    <?php       
                    
                        //pour autoriser le script à s'exécuter au-delà de 10 secondes
                        set_time_limit(10);                    
                        date_default_timezone_set('Europe/Zurich');
                        include '../includes/preperationRequete.php'; 
                        include '../includes/fonctionsUtiles.php';
                        $delaiTimeout = 5;
                        
                        //Récupération des informations
                        if ($_POST) {                            
                            $tabCommandeChoisie= unserialize(base64_decode($_POST['commandeChoisie']));
                            $tabListeAP = unserialize(base64_decode($_POST['listeAP']));
                            $nomFichier='../fichiers/output.html';
                            
                            if (file_exists($nomFichier)){unlink ($nomFichier);}                            
                            file_put_contents( $nomFichier, '<html><body>R&eacute;sultats des requ&ecirc;tes ('.date('d M Y @ H:i:s').')<br>==========================================<br>');
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
                                                        
                            //Ouverture d'un socket sur le port concerné
                            $fp = @fsockopen($AP["adresseIPv4"], $tabCommandeChoisie["portProtocole"], $errno, $errstr, $delaiTimeout);                                
                            $erreur = $errno.' - '.$errstr;                                
                            $reponse = '';
                            $i=0;
                            if ((!$fp) && ((strtoupper($tabCommandeChoisie["protocole"]))!= "SNMP")) {
                                $texteErreur ='<tr class="danger"><td>'.$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].')';
                                $texteErreur = $texteErreur.'</td><td>'.$erreur;
                                $texteErreur= $texteErreur. '</td><td><strong>Not OK</strong></td></tr>';
                                echo $texteErreur;
                                file_put_contents($nomFichier, '<p><u><b>'.$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].')</b></u><br>'.$erreur.'</p>', FILE_APPEND); 
                            } 
                            else {                            
                            
                                //Préparation et envoi de la requête à transmettre en fonction du protocole (TELNET, SSH, HTTP, HTTPS, SNMP ou AUTRE)
                                switch (strtoupper($tabCommandeChoisie["protocole"])) {
                                    case "TELNET":
                                        $requete=requeteTELNET($AP["adresseIPv4"], $tabCommandeChoisie["ligneCommande"],$AP["username"],$AP["password"]);
                                        fwrite($fp, $requete);
                                        $taille=128;
                                        while (!feof($fp)) {
                                            $reponse .= fgets($fp,$taille);
                                            $i++; 
                                        }        
                                        $debutRep=50;
                                        $finRep=200;
                                        fclose($fp);
                                        break;
                                        
                                    case "SSH":
                                        echo "requ&ecirc;te SSH";
                                        break;
                                    
                                    case "HTTP":
                                        $requete=requeteHTTP($AP["adresseIPv4"], $tabCommandeChoisie["ligneCommande"]);
                                        fwrite($fp, $requete);
                                        $reponse .= fgets($fp);                                         
                                        $nbTrames=500;
                                        $taille=1500;
                                        //Vérification du code de réponse
                                        if (!preg_match('/20/', substr($reponse,9,3))){$nbTrames=5;}             
                                        
                                        while(!feof($fp)){
                                            $reponse .= fgets($fp,$taille);                                         
                                            $nbTrames--;
                                            if ($nbTrames==0){break;}                                              
                                        }                                              
                                        $debutRep=0;
                                        $finRep=128; 
                                        fclose($fp);
                                        break;                                        
                                        
                                    case "HTTPS":
                                        echo "requ&ecirc;te HTTPS";;
                                        break;
                                    case "SNMP":
                                        try {      
                                            $timeout=1000000;
                                            $requete = snmprealwalk($AP["adresseIPv4"], $AP["snmpCommunity"],$tabCommandeChoisie["ligneCommande"],$timeout);
                                            $reponse = implode($requete);
                                        }
                                        catch(ErrorException $e)
                                        {        
                                            $reponse='';
                                            $info_erreur= $e->getMessage();
                                        }   
                                        
                                        $debutRep=0;
                                        $finRep=128;                                         
                                        break;      
                                    case "AUTRE":
                                        echo "requ&ecirc;te AUTRE";
                                        break;  
                                    default:
                                        $requete=$tabCommandeChoisie["ligneCommande"];
                                        break;
                                }                                                                                                           

                                $extraitReponse = substr($reponse,$debutRep,$finRep);  
                                $reponse = strip_tags($reponse,'<br>|<p>|<i>|</i>|<input>');
                                

                                if ($reponse != ''){
                                    echo '<tr class="success"><td>'.$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].')';
                                    echo '</td><td>'.$extraitReponse;                                          
                                    echo '</td><td><strong>OK</strong></td></tr>';
                                    file_put_contents($nomFichier, '<p><u><b>'.$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].')</b></u><br>'.$reponse.'</p>', FILE_APPEND);
                                    
                                }
                                else{
                                    echo '<tr class="danger"><td>'.$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].')';
                                    $erreur = 'Pas de r&eacute;ponse re&ccedil;ue';
                                    echo '</td><td>'.$erreur;                                       
                                    echo '</td><td><strong>Not OK</strong></td></tr>'; 
                                    file_put_contents($nomFichier, '<p><u><b>'.$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].')</b></u><br>'.$erreur.'</p>', FILE_APPEND);
                                }                           
                            }                                
                        }                             
                                                    
                        echo '</tbody></table>'; 
                        file_put_contents($nomFichier, '<input type="button" value="Imprimer..." onClick="window.print()"></body></html>', FILE_APPEND);
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
