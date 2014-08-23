<!DOCTYPE html>
<html lang="en">
  <head>
    <title>AP Tool</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <script type="text/javascript" src="../js/jquery-1.11.1.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/additional-methods.js"></script>
    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
  </head>

  <body>
      <br><br>
    <div class="container-fluid">        

        <table border="0" width="90%" align="center">
           <tbody>
              <tr>
                <?php include '../includes/menus.php'; echo $menuPagesGestionBDD; ?>                   
                 <td class="informations">                     
                    <ol class="breadcrumb">
                       <li><a href="accueilGestionBDD.php">Accueil gestion de la BDD</a></li> 
                       <li><a href="selectModifAP.php">Choix des AP &agrave; modifier</a></li> 
                       <li>Modifier des AP enregistr&eacute;s</li>
                   </ol>
                   <ol>
                    <?php 
                        //connexion a la BDD et récupération de la liste des modèles
                        include '../includes/connexionBDD.php';                    
                        include '../includes/fonctionsUtiles.php';
                        
                        //Récupération nombre des AP à modifier dans un tableau
                        $tabValeursRecues=null;
                        if (!isset($_POST['listeAP'])){$qtyAP=0;} 
                        else {
                            $tabValeursRecues = unserialize(base64_decode($_POST['listeAP']));
                            $qtyAP=count($tabValeursRecues);                                   
                        }                                                   
                        //enregistrement des modèles d'AP dans un tableau pour affichage dans une liste                        
                        try
                        {            
                                $i=0;
                                $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                $resultatsAP=$connexion->query("SELECT * FROM modeles;");                                 
                                $resultatsAP->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet                                
                                
                                while( $ligne = $resultatsAP->fetch() ) // on récupère la liste des membres
                                {     
                                    $noModeleAP =(string)$ligne->noModeleAP;
                                    $nomModele =(string)$ligne->nomModele;
                                    $versionFirmware=(string)$ligne->versionFirmware;
                                    $nomFabricant=(string)$ligne->nomFabricant;
                                    $adrMACFabricant=(string)$ligne->adrMACFabricant; 
                                    $tabListeModeles[$i]=array("noModeleAP" =>$noModeleAP, "nomModele"=>$nomModele, "versionFirmware"=>$versionFirmware,"nomFabricant"=>$nomFabricant, "adrMACFabricant"=>$adrMACFabricant);
                                    $i++;
                                }
                            $resultatsAP->closeCursor(); // on ferme le curseur des résultats
                                                
                        }                                                
                        catch(Exception $e)
                        {
                                echo '</select></form><li> Erreur lors du chargement</li></ol>';
                                echo 'Erreur : '.$e->getMessage().'<br />';
                                echo 'N° : '.$e->getCode();
                        } 
                    ?>                        
                       
                       
                    <form id="modifierAP" name="modifierAP" class="form-inline" role="form" action="enregistrerModifAP.php" method="POST">
                        <div class="form-group">       

                            <table border="0" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nom de l'AP (d&eacute;faut: "AP-xx")</th>
                                    <th>Mod&egrave;le de l'AP</th>
                                    <th>Adresse IPv4</th>
                                    <th>SNMP <br>(d&eacute;faut: "public")</th>
                                    <th>Nom d'utilisateur</th>
                                    <th>Mot de passe</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i=0;
                                //Affichage du formulaire
                                foreach ($tabValeursRecues as $AP){                                                                                                            
                                    echo '<tr>';
                                    
                                    $infosNoAP='value="'.$AP["noAP"].'"';
                                    $infosNomAP='value="'.$AP["nomAP"].'"';  
                                    $infosNoModeleAP=$AP["noModeleAP"];
                                    $adresseIPv4=$AP["adresseIPv4"];
                                    $infosSNMP='value="'.$AP["snmpCommunity"].'"';
                                    $infosUsername='value="'.$AP["username"].'"';
                                    $infosPassword='value="'.$AP["password"].'"';                                             
                                    
                                    $infosIPgroupeA='value="'.strstr($adresseIPv4, '.', true).'"';$adresseIPv4 =strstr($adresseIPv4, '.');$adresseIPv4 =  substr($adresseIPv4,1);
                                    $infosIPgroupeB='value="'.strstr($adresseIPv4, '.', true).'"';$adresseIPv4 =strstr($adresseIPv4, '.');$adresseIPv4 =  substr($adresseIPv4,1);
                                    $infosIPgroupeC='value="'.strstr($adresseIPv4, '.', true).'"';$adresseIPv4 =strstr($adresseIPv4, '.');$adresseIPv4 =  substr($adresseIPv4,1);
                                    $infosIPgroupeD='value="'.$adresseIPv4.'"';
                                        
                                    if ($AP["nomAP"]==""){$infosNomAP='placeholder="non indiqu&eacute;"';}
                                    if ($AP["snmpCommunity"]==""){$infosSNMP='placeholder="public"';}
                                    if ($AP["username"]==""){$infosUsername='placeholder="non indiqu&eacute;"';}
                                    if ($AP["password"]==""){$infosPassword='placeholder="non indiqu&eacute;"';}                                      
                                    
                                    echo '<td><input type="hidden" class="form-control" name="noAP'.$i.'" id="noAP'.$i.'"'.$infosNoAP.'>';
                                    echo '<input type="text" class="form-control" name="nomAP'.$i.'" id="nomAP'.$i.'" size="18" maxlength="25" '.$infosNomAP.'></td>';                                    
                                    echo '<td><select class="form-control" id="noModeleAP'.$i.'" name="noModeleAP'.$i.'">';
                                    echo '<option value="">Choix du mod&egrave;le</option>';
                                    foreach ($tabListeModeles as $modele){
                                        echo '<option value="'.$modele["noModeleAP"].'"';
                                        if ($modele["noModeleAP"]==$infosNoModeleAP){echo " selected";}    
                                        echo '>'.$modele["nomFabricant"].' '.$modele["nomModele"].' v.'.$modele["versionFirmware"].'</option>';                                                    
                                    }
                                    echo '</select></td>';                                    
                                    echo '<td><span class="nowrap">';
                                    echo '<input type="text" class="form-control" name="IPgroupeA'.$i.'" id="IPgroupeA'.$i.'" size="1" maxlength="3" '.$infosIPgroupeA.'><strong>.</strong>';
                                    echo '<input type="text" class="form-control" name="IPgroupeB'.$i.'" id="IPgroupeB'.$i.'" size="1" maxlength="3" '.$infosIPgroupeB.'><strong>.</strong>';
                                    echo '<input type="text" class="form-control" name="IPgroupeC'.$i.'" id="IPgroupeC'.$i.'" size="1" maxlength="3" '.$infosIPgroupeC.'><strong>.</strong>';
                                    echo '<input type="text" class="form-control" name="IPgroupeD'.$i.'" id="IPgroupeD'.$i.'" size="1" maxlength="3" '.$infosIPgroupeD.'>';
                                    echo '</span></td>'; 
                                    
                                    echo '<td><input type="text" class="form-control" name="snmpCommunity'.$i.'" id="snmpCommunity'.$i.'" size="10" maxlength="12" '.$infosSNMP.'></td>';
                                    echo '<td><input type="text" class="form-control" name="username'.$i.'" id="username'.$i.'" size="10" maxlength="20" '.$infosUsername.'></td>';
                                    echo '<td><input type="password" class="form-control" name="password'.$i.'" id="password'.$i.'" size="10" maxlength="20" '.$infosPassword.'></td>';
                                    echo '</tr>';
                                    $i++;
                                }
                                
                                echo '<td align="left">Nombre d\'AP &agrave; modfier: '.($qtyAP).'<input type="hidden" value="'.$qtyAP.'" name="qtyAP"/></td>';                                
                                ?>
                                <td colspan="5" align="right"><input type="submit" class="btn btn-primary" name="submit" id="submit" value="Enregistrer les modifications"/></td>
                            </tbody>
                            </table>                                    
                         </div>                             
                        </form>
                       <?php if ($qtyAP>1){ echo '<input type="button" class="btn btn-default" name="repliquerAP" id="repliquerAP" onclick="repliquerAP('.$qtyAP.')" value="Copier"/>&nbsp;R&eacute;pliquer la 1&egrave;re ligne';} ?>
                    </ol>
                 </td>
              </tr>
           </tbody>
        </table>      
      </div><!-- /container -->


    <!-- Bootstrap core JavaScrip ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <!-- Pour répliquer les données de la ere ligne -->
    <script type="text/javascript">
     function repliquerAP(qtyAP)
     {         
        var formName = 'modifierAP';
         
        for(i=1;i<=qtyAP;i++){                
            document.getElementById(formName).elements['nomAP'+i].value=document.getElementById(formName).elements['nomAP0'].value;
            document.getElementById(formName).elements['noModeleAP'+i].value=document.getElementById(formName).elements['noModeleAP0'].value;
            document.getElementById(formName).elements['IPgroupeA'+i].value=document.getElementById(formName).elements['IPgroupeA0'].value;
            document.getElementById(formName).elements['IPgroupeB'+i].value=document.getElementById(formName).elements['IPgroupeB0'].value;
            document.getElementById(formName).elements['IPgroupeC'+i].value=document.getElementById(formName).elements['IPgroupeC0'].value;
            document.getElementById(formName).elements['IPgroupeD'+i].value=document.getElementById(formName).elements['IPgroupeD0'].value;
            document.getElementById(formName).elements['snmpCommunity'+i].value=document.getElementById(formName).elements['snmpCommunity0'].value;
            document.getElementById(formName).elements['username'+i].value=document.getElementById(formName).elements['username0'].value;
            document.getElementById(formName).elements['password'+i].value=document.getElementById(formName).elements['password0'].value;
        }
     }      
    <!-- Pour la validation des champs du formulaire -->
  <?php
        echo'
        $(function()
        {
            $("#modifierAP").validate(
              {                
                rules: 
                {'; 
        
                for($i=0;$i<=$qtyAP;$i++){  
                    echo'
                  noModeleAP'.$i.': 
                  {
                    required: true                   
                  },
                  IPgroupeA'.$i.': 
                  {
                    required: true,
                    range:[10,255] 
                  },
                  IPgroupeB'.$i.':
                  {
                    required: true,
                    range:[0,255] 
                  },  
                  IPgroupeC'.$i.':
                  {
                    required: true,
                    range:[0,255] 
                  }, 
                  IPgroupeD'.$i.': 
                  {
                    required: true,
                    range:[0,255] 
                  }';
                  if ($i<=$qtyAP){echo ',';};                    
                }
        echo'
                },
                errorElement: "divBelow",
                errorPlacement: function(error, element) {
                    error.insertAfter(element);                    
                }           
              });
        });';
        ?>
    </script>      
  </body>
</html>