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
                        <li>R&eacute;sultat:</li>
                    </ol>  
                     <ol>
                    <?php           
                        include '../includes/preperationRequete.php'; 
                        $delaiTimeout = 5;
                        
                        //Récupération des informations
                        if ($_POST) {                            
                            $tabCommandeChoisie= unserialize(base64_decode($_POST['commandeChoisie']));
                            $tabListeAP = unserialize(base64_decode($_POST['listeAP']));
                        }
                        else {echo " Probl&egrave;me &agrave; la r&eacute;ception de la commande.";}
                        
                        
                        echo '<table class="table table-responsive" align="center">
                            <caption> R&eacute;ponses eçues des requ&ecirc;tes transmises:</caption>
                            <thead>                            
                               <tr>';
                        echo "<th>No et IP de l'AP</th>
                            <th>R&eacute;ponse</th>
                            <th>Connexion OK?</th>
                            </tr>
                            </thead>
                            <tbody>";                        
                        //parcours des AP
                        foreach ($tabListeAP as $AP){
                             
                            try{
                                //Ouverture d'un socket sur le port concerné
                                $fp = fsockopen($AP["adresseIPv4"], $tabCommandeChoisie["portProtocole"], $errno, $errstr, $delaiTimeout);                                
                                $erreur = $errno.' - '.$errstr;
                            }
                            catch (ErrorException $e){
                                $erreur=$e->getMessage();                             
                            }
                            if (!$fp) {
                                $texteErreur ='<tr class="danger"><td>'.$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].')';
                                $texteErreur = $texteErreur.'</td><td>Pas de reacute;ponse re&ccedil;ue ('.$erreur.')';
                                $texteErreur= $texteErreur. '</td><td><strong>Not OK</strong></td></tr>';
                                
                                echo $texteErreur;
                            } 
                            else { 
                                //Préparation de la requête à transmettre en fonction du protocole (TELNET, SSH, HTTP, HTTPS, SNMP ou AUTRE)
                                switch (strtoupper($tabCommandeChoisie["protocole"])) {
                                    case "TELNET":
                                        $requete=requeteTELNET($AP["adresseIPv4"], $tabCommandeChoisie["ligneCommande"],$AP["username"],$AP["password"]);
                                        break;
                                    case "SSH":
                                        echo "requ&ecirc;te SSH";
                                        break;
                                    case "HTTP":
                                        $requete= requeteHTTP($AP["adresseIPv4"], $tabCommandeChoisie["ligneCommande"]);                                        
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

                                fwrite($fp, $requete);                                          

                                ///$reponse = fgets($fp);
                                
                                $reponse = '';
                                while (!feof($fp)) {
                                    $reponse .= fgets($fp, 128);
                                }


                                fclose($fp);
                                echo substr($reponse,0,500);  

                                if ($reponse != ''){
                                    echo '<tr class="success"><td>'.$AP["noAP"].'-'.$AP["nomAP"].' (IP: '.$AP["adresseIPv4"].')';
                                    echo '</td><td>'.$reponse;
                                    echo '</td><td><strong>OK</strong></td></tr>';
                                }
                                else{
                                    //echo $texteErreur;
                                }
                                
                            }
                        }
                                                    
                        echo '</tbody></table>';                        
                        
                        echo "<br>-------------------------------------------------<br> commande choisie: ";//.htmlspecialchars(print_r($tabCommandeChoisie, true));
                        //echo "<br><br> listeAP: ".htmlspecialchars(print_r($tabListeAP, true));                         

                        echo '<br><br>'.stripcslashes(ereg_replace("(\r\n|\n|\r)", "[CR][LF]", $tabCommandeChoisie["ligneCommande"]));                                          
                       ?>                        

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