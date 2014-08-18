<!DOCTYPE html>
<html lang="en">
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
                           <li class="active"><a href="rechercherAP.php">Rechercher des AP sur le r&eacute;seau</a></li>                       
                        </ul>
                        <p><b>Configurer les AP</b></p>
                        <ul class="nav nav-pills nav-stacked">                       
                           <li><a href="choisirCommande.php">Appliquer une commande &agrave; un ou plusieurs AP</a></li>    
                        </ul> 
                 </td>  
            
                 <td class="informations">
                    <ol class="breadcrumb">
                        <li><a href="accueilGestionAP.php">Accueil gestion des AP</a></li>  
                        <li><a href="rechercherAP.php">Rechercher des AP sur le r&eacute;seau</a></li>
                        <li>R&eacute;sutlat de la recherche</li>
                    </ol>  
                     
                     <ol>
                        <?php
                            include '../includes/fonctionsUtiles.php';   
                            include '../includes/scanIP.php';                                
                            
                            $start = microtime(true);//pour calcul temps d'exécution
                            
                            //pour laisser la recherche s'effectuer sur 5 minutes
                            set_time_limit(300);
                            
                            $ip = $_POST['groupeA'].".".$_POST['groupeB'].".".$_POST['groupeC'].".".$_POST['groupeD'];                            
                            $masque = (int)$_POST['masque'];
                            $vendorMAC = $_POST['vendorMAC'];
                            
                            $adrReseau = netmask($ip, $masque);
                            $adrBroadcast =cidr2broadcast($adrReseau, $masque);  
                            
                            echo "<br>Adresse r&eacute;seau: ".$adrReseau."    --- Masque: ".$masque."    --- Broadcast: ".$adrBroadcast;
  
                            $adresseDebut = long2ip(ip2long($adrReseau)+1);
                            $adresseFin = long2ip(ip2long($adrBroadcast)-1);
                            
                            echo "<br>Adresse de d&eacute;but: ".$adresseDebut."    --- Adresse de fin: ".$adresseFin;
                            
                            $nbiter = ip2long($adresseFin) - ip2long($adresseDebut) +1;
                            echo "<br>Nombre d'iterrations: ".$nbiter."--- adresse MAC du vendeur recherch&eacute;: ".$vendorMAC;
                            
                            $tabARP = quick_ipmac_scan(ip2long($adresseDebut),ip2long($adresseFin)); 
                            
                            $vendorMACLinux = preg_replace("/-/", ":", $vendorMAC); //pour Linux 
                            $vendorMACWindows = preg_replace("/:/", "-", $vendorMAC); //pour Windows
                                                        
                            echo "<br>Nombres d'entr&eacute;es ARP dans la table: ".count($tabARP);  
                            echo "<br> Temps d'ex&eacute;cution: ";
                            $end = microtime(true);
                            $time = number_format(($end - $start), 2);
                            echo $time, ' secondes<br>';                            
                            $nombreAPTrouves=0;
                            $listeAPTrouves = null;
                            $listeARP=null;
                            
                            foreach($tabARP as $ligneTabARP) {  
                                if(preg_match("/".$vendorMACLinux."/i", $ligneTabARP)) {
                                    $listeAPTrouves .= $ligneTabARP."<br>";                                    
                                    $nombreAPTrouves++;                                    
                                }   
                                $listeARP .= $ligneTabARP."<br>";
                            }
                            
                            if ($nombreAPTrouves==0){echo 'Mod&egrave;le non trouv&eacute.';}
                            else{echo '<br> Nombre d\'AP trouv&eacutes: '.$nombreAPTrouves.'<br>'.$listeAPTrouves;}
                            
                            $boutonTableARP= '<br><br><button id="afficherTableARP" class="btn btn-info" onclick="$(';
                            $boutonTableARP.= "'#loading2'";
                            $boutonTableARP.= ').show();">Afficher la table ARP compl&egrave;te</button>';                            

                            echo $boutonTableARP;                                
                            echo '<div id="loading2" style="display:none;" >'.$listeARP.'&nbsp;Envoi des requ&ecirc;tes en cours...</div>';
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
    <script type="text/javascript">
         (function (d) {
           d.getElementById('validation').onsubmit = function () {
             d.getElementById('afficherTableARP').style.display = 'none';
             d.getElementById('loading2').style.display = 'show';
           };
         }(document));
     </script>    
  </body>
</html>