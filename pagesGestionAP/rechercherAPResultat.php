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
                <?php include '../includes/menus.php'; echo $menuPagesGestionAP; ?>  
            
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
                            $tabinfosRecues = unserialize(base64_decode($_POST['vendorMAC']));
                            $vendorMAC = $tabinfosRecues["adrMACFabricant"];
                            $noModeleAP = $tabinfosRecues["noModeleAP"];
                            
                            $adrReseau = netmask($ip, $masque);
                            $adrBroadcast =cidr2broadcast($adrReseau, $masque);  
                            
                            echo "<br>Adresse r&eacute;seau: ".$adrReseau." --- Masque: ".$masque."    --- Broadcast: ".$adrBroadcast;
  
                            $adresseDebut = long2ip(ip2long($adrReseau)+1);
                            $adresseFin = long2ip(ip2long($adrBroadcast)-1);
                            
                            echo "<br>Adresse de d&eacute;but: ".$adresseDebut."    --- Adresse de fin: ".$adresseFin;
                            
                            $nbiter = ip2long($adresseFin) - ip2long($adresseDebut) +1;
                            echo "<br>Nombre d'iterrations: ".$nbiter."--- adresse MAC du vendeur recherch&eacute;: ".$vendorMAC;
                            
                            $tabARP = quick_ipmac_scan(ip2long($adresseDebut),ip2long($adresseFin)); 
                            
                            $vendorMACLinux = preg_replace("/-/", ":", $vendorMAC); //pour Linux 
                            $vendorMACWindows = preg_replace("/:/", "-", $vendorMAC); //pour Windows
                                                        
                            echo "<br>Nombres d'entr&eacute;es ARP dans la table: ".count($tabARP);  
                            echo "<br>Temps d'ex&eacute;cution: ";
                            $end = microtime(true);
                            $time = number_format(($end - $start), 2);
                            echo $time, ' secondes<br><br>';                            
                            $nombreAPTrouves=0;
                            $listeAPTrouves = null;
                            $tableARPComplete=null;
                            $tableARP='';
                            //enregistrement des valeurs recues dans un tableau (listeARP)
                            $i=0;
                            foreach($tabARP as $ligneTabARP) {
                                $hostname=strstr($ligneTabARP, ' ', true);
                                $adresseIP = strstr($ligneTabARP,'(');$adresseIP = substr($adresseIP,1);$adresseIP=strstr($adresseIP, ')', true);
                                $adresseMAC = strstr($ligneTabARP,'at ');
                                $tableARPComplete[$i]=array("hostname"=>$hostname,"adresseIP"=>$adresseIP,"adresseMAC"=>$adresseMAC);
                                $i++;
                            }                               
                            //parcours du tableau                          
                            foreach ($tableARPComplete as $host){
                                //pour se débarraser des entrées inutiles (sans adresse MAC) de la table ARP 
                                if ($host["adresseMAC"]!=")"){$tableARP .='>> '.$host["hostname"].' '.$host["adresseIP"].' '.$host["adresseMAC"].'<br>';}
                                if(preg_match("/".$vendorMACLinux."/i", $host["adresseMAC"])) {
                                    $listeAPTrouves[$nombreAPTrouves] = array("adresseIP"=>$host["adresseIP"],"hostname"=>$host["hostname"],"adresseMAC"=>$host["adresseMAC"]);                                    
                                    $nombreAPTrouves++;                                    
                                }
                            }
                            //création d'un tableau pour afficher si AP trouvés ou non  
                            echo '<table class="table table-condensed table-striped" align="left">';                                
                            if ($nombreAPTrouves==0){
                                echo '<thead><tr><th>';
                                echo 'Aucun AP correspondant &agrave; ce mod&egrave;le n\'a &eacute;t&eacute; trouv&eacute.';
                                echo '<br> Cliquez sur le bouton ci-dessous pour plus d\'informations.';
                                echo '</th></tr></thead></table>';                                    
                            }
                            else{
                                echo '<form id="ajoutAP" name="ajoutAP" role="form" action="enregistrerAP.php" method="POST">';
                                echo '<caption>Nombre d\'AP trouv&eacutes: '.$nombreAPTrouves.'</caption>';
                                echo '<thead><tr><th>'; 
                                echo 'Informations sur l\'AP';
                                echo '</th><th>';
                                echo 'Enregistrer?';
                                echo '</th></tr></thead>';
                                // création d'un formulaire pour proposer l'enregistrement à la volée des AP trouvés
                                echo '<tbody>';                                                                
                                for ($i=0;$i<count($listeAPTrouves);$i++){
                                    $adresseIPv4 = $listeAPTrouves[$i]["adresseIP"];
                                    $infosIPgroupeA=strstr($adresseIPv4, '.', true);$adresseIPv4 =strstr($adresseIPv4, '.');$adresseIPv4 =  substr($adresseIPv4,1);
                                    $infosIPgroupeB=strstr($adresseIPv4, '.', true);$adresseIPv4 =strstr($adresseIPv4, '.');$adresseIPv4 =  substr($adresseIPv4,1);
                                    $infosIPgroupeC=strstr($adresseIPv4, '.', true);$adresseIPv4 =strstr($adresseIPv4, '.');$adresseIPv4 =  substr($adresseIPv4,1);
                                    $infosIPgroupeD=$adresseIPv4;
                                    echo '<tr><td>';
                                    echo $listeAPTrouves[$i]["hostname"].' '.$listeAPTrouves[$i]["adresseIP"];
                                    echo '<input type="hidden" name="nomAP'.$i.'" id="nomAP'.$i.'" value="'.$listeAPTrouves[$i]["hostname"].'">';    
                                    echo '<input type="hidden" name="noModeleAP'.$i.'" id="noModeleAP'.$i.'" value="'.$noModeleAP.'">';
                                    echo '<input type="hidden" name="infosIPgroupeA'.$i.'" id="infosIPgroupeA'.$i.'" value="'.$infosIPgroupeA.'">';
                                    echo '<input type="hidden" name="infosIPgroupeB'.$i.'" id="infosIPgroupeB'.$i.'" value="'.$infosIPgroupeB.'">';
                                    echo '<input type="hidden" name="infosIPgroupeC'.$i.'" id="infosIPgroupeC'.$i.'" value="'.$infosIPgroupeC.'">';
                                    echo '<input type="hidden" name="infosIPgroupeD'.$i.'" id="infosIPgroupeD'.$i.'" value="'.$infosIPgroupeD.'">';
                                    echo '<input type="hidden" name="snmpCommunity'.$i.'" id="snmpCommunity'.$i.'" value="">';
                                    echo '<input type="hidden" name="username'.$i.'" id="username'.$i.'" value="">';
                                    echo '<input type="hidden" name="password'.$i.'" id="password'.$i.'" value="">';
                                    echo '</td><td><input type="checkbox" name="APSelectionne'.$i.'"></td></tr>';
                                }
                                echo '<input type="hidden" name="qtyAP" id="qtyAP" value="'.count($listeAPTrouves).'"></form></tbody>';
                                
                            }
                            echo '</table>';
                            $boutonTableARP= '<br><br><button id="afficherTableARP" class="btn btn-info" onclick="$(';
                            $boutonTableARP.= "'#loading2'";
                            $boutonTableARP.= ').show();">Afficher la table ARP compl&egrave;te</button>';                            

                            echo $boutonTableARP;                                
                            echo '<div id="loading2" style="display:none;" >'.$tableARP.'</div>';
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