<!DOCTYPE html>
<html lang="en">
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
                           <li class="active"><a href="rechercherAP.php">Rechercher des AP sur le r&eacute;seau</a></li>                       
                        </ul>
                        <p><b>Configurer les AP</b></p>
                        <ul class="nav nav-pills nav-stacked">                       
                           <li><a href="#">Appliquer une commande &agrave; un ou plusieurs AP</a></li>    
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
                            
                            $ip = $_POST['groupeA'].".".$_POST['groupeB'].".".$_POST['groupeC'].".".$_POST['groupeD'];                            
                            $masque = (int)$_POST['masque'];
                            $vendorMAC = $_POST['vendorMAC'];
                            
                            $adrReseau = netmask($ip, $masque);
                            $adrBroadcast =cidr2broadcast($adrReseau, $masque);  
                            
                            echo "<br><br>=>>>> adresse reseau: ".$adrReseau."    --- Masque: ".$masque."    --- Broadcast: ".$adrBroadcast;
  
                            $adresseDebut = long2ip(ip2long($adrReseau)+1);
                            $adresseFin = long2ip(ip2long($adrBroadcast)-1);
                            
                            echo "<br><br>=>>>> début: ".$adresseDebut."    --- Fin: ".$adresseFin;
                            
                            $nbiter = ip2long($adresseFin) - ip2long($adresseDebut) +1;
                            echo "<br><br>=>>>> nb d'iterrations: ".$nbiter."--- adr MAC du vendeur: ".$vendorMAC;
                            
                            $tabARP = quick_ipmac_scan(ip2long($adresseDebut),ip2long($adresseFin));

                            $vendorMAC = preg_replace("/:/", "-", $vendorMAC); //pour Windows
                            //$vendorMAC = preg_replace("/-/", ":", $vendorMAC); //pour Linux 
                            
                            foreach($tabARP as $ligne) {  
                                if(preg_match("/".$vendorMAC."/i", $ligne)) {
                                    $ligne = $ligne."<strong><== Gefundet!</strong>";
                                  }        
                                echo $ligne."<br>";
                            } 

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