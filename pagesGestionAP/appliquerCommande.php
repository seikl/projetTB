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
                        
                        
                        //parcours des AP
                        foreach ($tabListeAP as $AP){
                             
                            try{
                                //Ouverture d'un socket sur le port concerné
                                $fp = fsockopen($AP["adresseIPv4"], $tabCommandeChoisie["portProtocole"], $errno, $errstr, $delaiTimeout);                                
                            }
                            catch (ErrorException $e){
                                $erreur=$e->getMessage();                             
                            }
                            if (!$fp) {
                                echo "AP non atteignable! (".$AP["adresseIPv4"]."<br/>";
                            } 
                            else { 
                                //Préparation de la requête à transmettre en fonction du protocole (TELNET, SSH, HTTP, HTTPS, SNMP ou AUTRE)
                                switch (strtoupper($tabCommandeChoisie["protocole"])) {
                                    case "TELNET":
                                        $requete=requeteTELNET($AP["adresseIPv4"], $tabCommandeChoisie["ligneCommande"],$AP["username"],$AP["password"]);
                                        break;
                                    case "SSH":
                                        echo "i égal 1";
                                        break;
                                    case "HTTP":
                                        $requete= requeteHTTP($AP["adresseIPv4"], $tabCommandeChoisie["ligneCommande"]);                                        
                                        break;
                                    case "HTTPS":
                                        echo "i égal 2";
                                        break;
                                    case "SNMP":
                                        echo "i égal 2";
                                        break;      
                                    case "AUTRE":
                                        echo "i égal 2";
                                        break;  
                                    default:
                                        $requete=$tabCommandeChoisie["ligneCommande"];
                                        break;
                                }                                

                                //fwrite($fp, $out);          

                                $reponse = 'test';

                                //$reponse .= fgets($fp);


                                fclose($fp);
                                echo substr($reponse,0,500);  

                                if ($reponse != '')
                                    echo utf8_decode("<br>Requête envoyée avec succès.<br> Requête transmise: ".$requete);
                                else
                                    echo utf8_decode("<br>Erreur dans la commmande.");                                 
                                
                            }
                        }
                            
                            
                            
                        
                        
                         echo "<br>-------------------------------------------------<br> commande choisir: ".htmlspecialchars(print_r($tabCommandeChoisie, true));
                         echo "<br><br> listeAP: ".htmlspecialchars(print_r($tabListeAP, true));
                         
                         
                         echo '<br><br>'.stripcslashes(ereg_replace("(\r\n|\n|\r)", "[CR][LF]", $tabCommandeChoisie["ligneCommande"]));

                         echo'';                        
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