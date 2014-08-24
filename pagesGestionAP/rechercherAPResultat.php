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
                            $tabinfosRecues = unserialize(base64_decode($_POST['infosModele']));
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
                            
                            $vendorMACLinux = $vendorMAC; //pour Linux 
                            //$vendorMACWindows = preg_replace("/:/", "-", $vendorMAC); //pour Windows
                                                         
                            echo "<br>Temps d'ex&eacute;cution: ";
                            $end = microtime(true);
                            $time = number_format(($end - $start), 2);
                            echo $time, ' secondes<br><br>';                            
                            $nombreAPTrouves=0;
                            $listeAPTrouves = null;
                            $tableARPComplete=null;
                            $modeleTrouve=false;
                            $tableARP='';
                            //enregistrement des valeurs recues dans un tableau (listeARP)
                            $i=0;
                            foreach($tabARP as $ligneTabARP) {
                                $hostname=strstr($ligneTabARP, ' ', true);
                                $adresseIP = strstr($ligneTabARP,'(');$adresseIP = substr($adresseIP,1);$adresseIP=strstr($adresseIP, ')', true);
                                $adresseMAC = strstr($ligneTabARP,'at ');$adresseMAC = strstr($adresseMAC,' ');
                                $tableARPComplete[$i]=array("hostname"=>$hostname,"adresseIP"=>$adresseIP,"adresseMAC"=>$adresseMAC);
                                $i++;
                            }                               
                            //parcours du tableau pour vérifier si des MAC correspondent au modèle recherché                         
                            foreach ($tableARPComplete as $host){
                                
                                //pour se débarraser des entrées inutiles (sans adresse MAC) de la table ARP 
                                if (preg_match("/ether/i", $host["adresseMAC"])){$tableARP .='>> '.$host["hostname"].' '.$host["adresseIP"].' '.$host["adresseMAC"].'<br>';}
                                if(preg_match("/".$vendorMAC."/i", strstr($host["adresseMAC"],'on',true))) {
                                    $listeAPTrouves[$nombreAPTrouves] = array("adresseIP"=>$host["adresseIP"],"hostname"=>$host["hostname"],"adresseMAC"=>$host["adresseMAC"]);                                    
                                    $nombreAPTrouves++;                                    
                                }                                 
                            }
                                                 
                            echo '<table class="table table-hover" align="left" width="75%">';                                 
                            echo '<caption>Nombre d\'AP trouv&eacutes: '.$nombreAPTrouves.'</caption>';  
                            //création d'un tableau pour afficher si AP trouvés ou non                                                          
                            if ($nombreAPTrouves==0){
                                echo '<thead><tr><th>Aucun AP correspondant &agrave; ce mod&egrave;le n\'a &eacute;t&eacute; trouv&eacute'; 
                                echo '('.$vendorMACLinux.')</th></tr></thead>';                                 
                            }
                            else{
                                echo '<form id="ajoutAPRecherche" name="ajoutAPRecherche" class="form-inline" role="form" action="../pagesGestionBDD/ajoutAP.php" method="POST">';
                                echo '<div class="form-group">';                                   
                                echo '<thead><tr><th>';
                                echo 'Informations sur l\'AP';
                                echo '</th><th>';
                                echo 'Enregistrer?';
                                echo '</th></tr></thead>';
                                // création des champs pour effectuer l'enregistrement à la volée des AP trouvés                                                                                                
                                for ($i=0;$i<$nombreAPTrouves;$i++){
                                    $adresseIPv4 = $listeAPTrouves[$i]["adresseIP"];
                                    $infosIPgroupeA=strstr($adresseIPv4, '.', true);$adresseIPv4 =strstr($adresseIPv4, '.');$adresseIPv4 =  substr($adresseIPv4,1);
                                    $infosIPgroupeB=strstr($adresseIPv4, '.', true);$adresseIPv4 =strstr($adresseIPv4, '.');$adresseIPv4 =  substr($adresseIPv4,1);
                                    $infosIPgroupeC=strstr($adresseIPv4, '.', true);$adresseIPv4 =strstr($adresseIPv4, '.');$adresseIPv4 =  substr($adresseIPv4,1);
                                    $infosIPgroupeD=$adresseIPv4;
                                    echo '<tr><td>';
                                    echo $listeAPTrouves[$i]["hostname"].' '.$listeAPTrouves[$i]["adresseIP"];
                                    $infoAPTrouve[$i] = array("nomAP"=>$listeAPTrouves[$i]["hostname"],
                                                                "noModeleAP"=>$noModeleAP,
                                                                "IPgroupeA"=>$infosIPgroupeA,
                                                                "IPgroupeB"=>$infosIPgroupeB,
                                                                "IPgroupeC"=>$infosIPgroupeC,
                                                                "IPgroupeD"=>$infosIPgroupeD,
                                                                "snmpCommunity"=>"",
                                                                "username"=>"",
                                                                "password"=>"");                                    
                                    echo '</td><td><input type="checkbox" name="chkAPSelectionne'.$i.'"/></td></tr>';
                                }
                                $modeleTrouve=true;
                            }    
                            echo '<tr><td align="right">';                            
                            $boutonTableARP= '<br><br><input type="button" id="afficherTableARP" class="btn btn-info" onclick="$(';
                            $boutonTableARP.= "'#loading2'";
                            $boutonTableARP.= ').show();" value="Afficher la table ARP"/>';                            
                            echo $boutonTableARP; 

                            echo '</td><td align="left" valign="top">';
                            if($modeleTrouve){                                    
                                $infoAPTrouve = base64_encode(serialize($infoAPTrouve));                                     
                                echo '<input type="hidden" name="infoAPTrouve" id="infoAPTrouve" value="'.$infoAPTrouve.'"/>';
                                echo '<input type="hidden" name="qtyAP" id="qtyAP" value="'.$nombreAPTrouves.'"/>';
                                echo '<input type="submit" class="btn btn-primary" value="Enregistrer les AP s&eacute;lectionn&eacute;s"/></td>';
                                echo '</div></form>';   
                            }
                            else{
                                echo '<input type="button" class="btn btn-default" value="Revenir &agrave; la saisie de recherche" onClick="history.back()"/></td>';
                            }
                                    

                            echo '<tr><td colspan="2" align="left"><div id="loading2" style="display:none;">'.$tableARP.'</div></td></tr>';
                            echo '</table>';
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