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
                        <li><a href="accueilGestionAP.php">Accueil gestion des AP</a></li>    
                        <li  class="active"><a href="afficherListeAP.php">Afficher la liste  de tous les AP inscrits</a></li>

                    <?php                       
                        $infosRecues= unserialize(base64_decode($_GET['infosAP']));                                                                       
                        
                        //pour effectuer les connexons SNMP                 
                        include '../includes/fonctionsUtiles.php';
                        
                        //récupération des infos
                        $noAP = $infosRecues["noAP"]; 
                        $nomAP = $infosRecues["nomAP"];
                        $ip=$infosRecues["adresseIPv4"];
                        $nomFabricant= $infosRecues["nomFabricant"];
                        $nomModele=$infosRecues["nomModele"];
                        $versionFirmware=$infosRecues["versionFirmware"];
                        $adrMACFabricant =$infosRecues["adrMACFabricant"];   
                        $snmpCommunity=$infosRecues["snmpCommunity"]; 
                        
                        echo '<li> Informations sur: <strong>'.$nomAP.'</strong> ('.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.', adresse IP: '.$ip.')</li>
                            </ol>';
                        
                        try {
                                $timeout=1000000;
                                //récupération infos SNMP (communauté par défaut: public)
                                $sysname[0] = snmpget($ip, $snmpCommunity, ".1.3.6.1.2.1.1.5.0",$timeout);
                                $sysname[1] = preg_replace("/STRING:/i","",$sysname[0]); 

                                $sysdesc[0] = snmpget($ip, $snmpCommunity, ".1.3.6.1.2.1.1.1.0",$timeout);
                                $sysdesc[1] = preg_replace("/STRING:/i","",$sysdesc[0]);
                                
                                $adrMACreq = snmpwalkoid($ip, $snmpCommunity, ".1.3.6.1.2.1.2.2.1.6",$timeout);
                                $adrMAC = implode($adrMACreq);
                                $adrMAC = preg_replace("/STRING:/i","",$adrMAC);                                 

                                $sysloc[0] = snmpget($ip, $snmpCommunity, ".1.3.6.1.2.1.1.6.0",$timeout);
                                $sysloc[1] = preg_replace("/STRING:/i","",$sysloc[0]);                                     

                                $sysuptime[0] = snmpget($ip, $snmpCommunity, ".1.3.6.1.2.1.1.3.0",$timeout);
                                $sysuptime[1] = preg_replace("/Timeticks:/i","",$sysuptime[0]);                                                              
                        }
                        catch(ErrorException $e)
                        {                            
                                $sysname[1] = $e->getMessage();
                                $sysdesc[1] = $e->getMessage();
                                $adrMAC = $e->getMessage();
                                $sysloc[1] = $e->getMessage();
                                $sysuptime[1] = $e->getMessage();
                        }     
                        
                        echo '<table class="table" align="center" width="75%">                            
                            <thead>
                               <tr>
                                  <th>&nbsp;</th>
                                  <th>Informations SNMP ( community = '.$snmpCommunity.')</th>
                                  <th>Informations de la BDD</th>
                               </tr>
                            </thead>
                            <tbody>
                               <tr>
                                  <td align="right"><strong>Nom du syst&egrave;me:</strong></td>
                                  <td>'.$sysname[1].'</td>
                                  <td>'.$nomAP.'</td>
                               </tr>
                               <tr>
                                  <td align="right"><strong>Description du syst&egrave;me:</strong></td>
                                  <td>'.$sysdesc[1].'</td>
                                  <td>'.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.'</td>
                               </tr>
                                <tr>
                                  <td align="right"><strong>Adresse MAC du fabricant:</strong></td>
                                  <td>'.$adrMAC.'</td>
                                  <td>'.$adrMACFabricant.'</td>
                               </tr>
                               <tr>
                                  <td align="right"><strong>Emplacement du syst&egrave;me:</strong></td>
                                  <td>'.$sysloc[1].'</td>
                                  <td>N/A</td>
                               </tr>
                               <tr>
                                  <td align="right"><strong>Uptime:</strong></td>
                                  <td>'.$sysuptime[1].'</td>
                                  <td>N/A</td>
                               </tr>                               

                            </tbody>
                         </table>';
                        
                        
                        
                                                    
                    ?>
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