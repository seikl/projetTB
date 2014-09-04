<?php 
/**************************************************************************************************** 
 * Auteur: Sébastien Kleber (sebastien.kleber@heig-vd.ch) 
 * 
 * Description:
 * page de formulair d'ajout d'un ou plusieurs periphériques réseaux. Tansmet les informations saisies
 * à "enregistrerAP.php"
 * 
 * Peut aussi recevoir en paramètres:
 *  un tableau contenant les informations des AP et la quntité d'AP
 *  qu'il va falloir enregistrer depuis "rechercherAPresultat.php":
 *  - nomAP: Le nom du périhpérique réseau
 * - IPgroupeA,B,C et D: les 4 champs composant l'adresse IPv4 du périphérique
 * * - noModeleAP: Le no de modèle correspondant
 * - snmpCommunity: la communauté SNMP du périphérique à enregistrer ("public" si champs vide)
 * - username: le nom d'utilisateur (champs vide possible)
 * - password: le mot de passe du périphérique à enregistrer
 * 
 * -qtyAP: le nombre d'AP à modifier pour générer les nombres de champs nécessaires pour l'édition des AP.
 * 
 *                                                                                            *
 * Modifié le: 01.09.2014                                                                           *
 ***************************************************************************************************/
$auth_realm = 'AP Tool'; require_once '../includes/authentification.php'; ?> 
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
      <p align="right"><br><a href="?action=logOut">LOGOUT</a>&nbsp;&nbsp;&nbsp;</p>
      <br>
    <div class="container-fluid">        

        <table border="0" width="90%" align="center">
           <tbody>
              <tr>
                <?php include '../includes/menus.php'; echo $menuPagesGestionBDD; ?>
              <tr>
                 <td width="30%" class="leftmenu">
                        <p><b>G&eacute;rer les enregistrments des AP</b></p>
                        <ul class="nav nav-pills nav-justified">                       
                           <li class="active"><a href="ajoutAP.php">Ajouter</a></li>
                           <li><a href="selectModifAP.php">Modifier</a></li>                       
                           <li><a href="selectSupprAP.php">Supprimer</a></li>
                        </ul>
                         <p><b>G&eacute;rer les mod&egrave;les enregistr&eacute;s</b></p>
                        <ul class="nav nav-pills nav-justified">                       
                           <li><a href="ajoutModele.php">Ajouter</a></li>
                           <li><a href="selectModifModele.php">Modifier</a></li>                       
                           <li><a href="selectSupprModele.php">Supprimer</a></li>
                        </ul>
                         <p><b>G&eacute;rer les lignes de commandes (CLI)</b></p>
                        <ul class="nav nav-pills nav-justified">                       
                           <li><a href="ajoutCLI.php">Ajouter</a></li>
                           <li><a href="selectModifCLI.php">Modifier</a></li>                       
                           <li><a href="selectSupprCLI.php">Supprimer</a></li>
                        </ul>                      
                 </td>                  
                  
                 <td class="informations">                     
                     <ol class="breadcrumb">
                        <li><a href="accueilGestionBDD.php">Accueil gestion de la BDD</a></li> 
                        <li>Ajouter un ou plusieurs AP</li>
                    </ol>
                   <ol>
                    <?php 
                        //connexion a la BDD et récupération de la liste des modèles
                        include '../includes/connexionBDD.php';                    
                        include '../includes/fonctionsUtiles.php';
                        
                        //Récupération nombre d'AP à ajouter et des valeurs saisies
                        $tabValeursSaisies=null;
                        if (!isset($_POST['qtyAP'])){$qtyAP=1;} 
                        else {                                                        
                            $qtyAP=$_POST['qtyAP']; 
                            //vérification si ajout depuis le résultat d'une recherche
                            if (isset($_POST['infoAPTrouve'])){                                
                                $listeAPTrouves = unserialize(base64_decode($_POST['infoAPTrouve']));
                                $j=0;
                                $tempQtyAP=0;                                
                                for ($i=0;$i<$qtyAP;$i++)
                                {
                                    //si l'AP a été sélectionné pour être enregistré
                                    if (isset($_POST['chkAPSelectionne'.$i])){
                                       $tabValeursSaisies[$j]= array("nomAP" =>$listeAPTrouves[$i]["nomAP"], 
                                                            "noModeleAP"=>$listeAPTrouves[$i]["noModeleAP"], 
                                                            "IPgroupeA"=>$listeAPTrouves[$i]["IPgroupeA"],
                                                            "IPgroupeB"=>$listeAPTrouves[$i]["IPgroupeB"],
                                                            "IPgroupeC"=>$listeAPTrouves[$i]["IPgroupeC"],
                                                            "IPgroupeD"=>$listeAPTrouves[$i]["IPgroupeD"],
                                                            "snmpCommunity"=>$listeAPTrouves[$i]["snmpCommunity"],
                                                            "username"=>$listeAPTrouves[$i]["username"],
                                                            "password"=>$listeAPTrouves[$i]["password"]);
                                       $j++;
                                       $tempQtyAP++;
                                    }
                                }
                                $qtyAP=$tempQtyAP;
                            }                          
                            else {
                                for ($i=0;$i<$qtyAP;$i++)
                                {
                                    $tabValeursSaisies[$i]=array("nomAP" =>$_POST['nomAP'.$i], 
                                                            "noModeleAP"=>$_POST['noModeleAP'.$i], 
                                                            "IPgroupeA"=>$_POST['IPgroupeA'.$i],
                                                            "IPgroupeB"=>$_POST['IPgroupeB'.$i],
                                                            "IPgroupeC"=>$_POST['IPgroupeC'.$i],
                                                            "IPgroupeD"=>$_POST['IPgroupeD'.$i],
                                                            "snmpCommunity"=>$_POST['snmpCommunity'.$i],
                                                            "username"=>$_POST['username'.$i],
                                                            "password"=>$_POST['password'.$i]);
                                }                            
                                if ($qtyAP==0){$tabValeursSaisies[0]=array("nomAP" =>$_POST['nomAP0'],
                                                          "noModeleAP"=>$_POST['noModeleAP0'], 
                                                            "IPgroupeA"=>$_POST['IPgroupeA0'],
                                                            "IPgroupeB"=>$_POST['IPgroupeB0'],
                                                            "IPgroupeC"=>$_POST['IPgroupeC0'],
                                                            "IPgroupeD"=>$_POST['IPgroupeD0'],
                                                            "snmpCommunity"=>$_POST['snmpCommunity0'],
                                                            "username"=>$_POST['username0'],
                                                            "password"=>$_POST['password0']);}                                
                            }
                        }                                                
                        
                        //enregistrement des modèles d'AP dans un tableau pour affichage dans une liste
                        try
                        {            
                            $i=0;
                            $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                            $resultatsAP=$connexion->query("SELECT * FROM modeles ORDER BY nomFabricant,nomModele, versionFirmware;");                                 
                            $resultatsAP->setFetchMode(PDO::FETCH_OBJ);                                 

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
                       
                       
                    <form id="ajoutAP" name="ajoutAP" class="form-inline" role="form" action="enregistrerAP.php" method="POST">
                        <div class="form-group">       

                            <table border="0" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nom de l'AP <bR>(d&eacute;faut: "AP-xx")</th>
                                    <th>Mod&egrave;le de l'AP<strong class="obligatoire">&nbsp;*</strong></th>
                                    <th>Adresse IPv4<strong class="obligatoire">&nbsp;*</strong></th>
                                    <th>SNMP <br>(d&eacute;faut: "public")</th>
                                    <th>Nom <br>d'utilisateur</th>
                                    <th>Mot de passe</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                //Affichage du formulaire
                                for($i=0;$i<$qtyAP;$i++){                                                                                                            
                                    echo '<tr>';
                                    
                                    $infosNomAP='placeholder="AP-'.$i.'"';  
                                    $infosNoModeleAP="";
                                    $infosIPgroupeA='placeholder="192"';
                                    $infosIPgroupeB='placeholder="168"';
                                    $infosIPgroupeC='placeholder="1"';
                                    $infosIPgroupeD='placeholder="0"';
                                    $infosSNMP='placeholder="public"';
                                    $infosUsername='placeholder="username"';
                                    $infosPassword='placeholder="password"';
                                    if(isset($tabValeursSaisies[$i])){
                                        
                                        if ($tabValeursSaisies[$i]["nomAP"]!=""){$infosNomAP='value="'.$tabValeursSaisies[$i]["nomAP"].'"';}
                                        if ($tabValeursSaisies[$i]["noModeleAP"]!=""){$infosNoModeleAP=$tabValeursSaisies[$i]["noModeleAP"];}
                                        if ($tabValeursSaisies[$i]["IPgroupeA"]!=""){$infosIPgroupeA='value="'.$tabValeursSaisies[$i]["IPgroupeA"].'"';}
                                        if ($tabValeursSaisies[$i]["IPgroupeB"]!=""){$infosIPgroupeB='value="'.$tabValeursSaisies[$i]["IPgroupeB"].'"';}
                                        if ($tabValeursSaisies[$i]["IPgroupeC"]!=""){$infosIPgroupeC='value="'.$tabValeursSaisies[$i]["IPgroupeC"].'"';}
                                        if ($tabValeursSaisies[$i]["IPgroupeD"]!=""){$infosIPgroupeD='value="'.$tabValeursSaisies[$i]["IPgroupeD"].'"';}
                                        if ($tabValeursSaisies[$i]["snmpCommunity"]!=""){$infosSNMP='value="'.$tabValeursSaisies[$i]["snmpCommunity"].'"';}
                                        if ($tabValeursSaisies[$i]["username"]!=""){$infosUsername='value="'.$tabValeursSaisies[$i]["username"].'"';}
                                        if ($tabValeursSaisies[$i]["password"]!=""){$infosPassword='value="'.$tabValeursSaisies[$i]["password"].'"';}
                                    }  
                                    
                                    echo '<td><input type="text" class="form-control" name="nomAP'.$i.'" id="nomAP'.$i.'" size="15" maxlength="25" '.$infosNomAP.'></td>';                                    
                                    echo '<td><select class="form-control" id="noModeleAP'.$i.'" name="noModeleAP'.$i.'">';
                                    echo '<option value="">Choix du mod&egrave;le</option>';
                                    foreach ($tabListeModeles as $modele){
                                        echo '<option value="'.$modele["noModeleAP"].'"';
                                        if ($modele["noModeleAP"]==$infosNoModeleAP){echo " selected";}    
                                        echo '>'.$modele["nomFabricant"].' '.$modele["nomModele"].' v.'.$modele["versionFirmware"].'&nbsp;&nbsp;&nbsp;</option>';                                                    
                                    }
                                    echo '</select></td>';                                    
                                    echo '<td><span class="nowrap">';
                                    echo '<input type="text" class="form-control" name="IPgroupeA'.$i.'" id="IPgroupeA'.$i.'" size="2" maxlength="3" '.$infosIPgroupeA.'><strong>.</strong>';
                                    echo '<input type="text" class="form-control" name="IPgroupeB'.$i.'" id="IPgroupeB'.$i.'" size="2" maxlength="3" '.$infosIPgroupeB.'><strong>.</strong>';
                                    echo '<input type="text" class="form-control" name="IPgroupeC'.$i.'" id="IPgroupeC'.$i.'" size="2" maxlength="3" '.$infosIPgroupeC.'><strong>.</strong>';
                                    echo '<input type="text" class="form-control" name="IPgroupeD'.$i.'" id="IPgroupeD'.$i.'" size="2" maxlength="3" '.$infosIPgroupeD.'>';
                                    echo '</span></td>'; 
                                    
                                    echo '<td><input type="text" class="form-control" name="snmpCommunity'.$i.'" id="snmpCommunity'.$i.'" size="10" maxlength="12" '.$infosSNMP.'></td>';
                                    echo '<td><input type="text" class="form-control" name="username'.$i.'" id="username'.$i.'" size="10" maxlength="20" '.$infosUsername.'></td>';
                                    echo '<td><input type="password" class="form-control" name="password'.$i.'" id="password'.$i.'" size="10" maxlength="20" '.$infosPassword.'></td>';
                                    echo '</tr>';
                                }
                                
                                echo '<td align="left">Nombre d\'AP &agrave; enregistrer: '.($qtyAP).'<input type="hidden" value="'.$qtyAP.'" name="qtyAP"/></td>';                                
                                ?>
                                <td colspan="5" align="right"><?php if($qtyAP>0){echo '<input type="submit" class="btn btn-primary" name="submit" id="submit" value="Enregistrer"/></td>';}?>
                            </tbody>
                            </table>                                    
                         </div>                             
                        </form>
                        <div>
                           <table align="center" width="80%"><tr><td align="left" width="40%">&nbsp;                                    
                           <?php
                                if ($qtyAP>0){                                       
                                    echo '<input type="button" class="btn btn-default" name="repliquerAP" id="repliquerAP" onclick="repliquerAP('.$qtyAP.')" value="Copier"/>&nbsp;R&eacute;pliquer la 1&egrave;re ligne';
                                    echo '</td><td align="right" width="40%">';
                                    echo '<form id="diminueQty" name="diminueQty" class="form-inline" role="form" action="ajoutAP.php" method="POST">';
                                    echo 'Retirer une ligne&nbsp;<input type="hidden" value="'.($qtyAP-1).'" name="qtyAP"/>';
                                    //pour mémoriser les saisies déjà effectuées
                                    for($i=0;$i<=$qtyAP;$i++){  
                                        echo '<input type="hidden" name="nomAP'.$i.'"/>';
                                        echo '<input type="hidden" name="noModeleAP'.$i.'"/>';
                                        echo '<input type="hidden" name="IPgroupeA'.$i.'"/>';
                                        echo '<input type="hidden" name="IPgroupeB'.$i.'"/>';
                                        echo '<input type="hidden" name="IPgroupeC'.$i.'"/>';
                                        echo '<input type="hidden" name="IPgroupeD'.$i.'"/>';
                                        echo '<input type="hidden" name="snmpCommunity'.$i.'"/>';
                                        echo '<input type="hidden" name="username'.$i.'"/>';
                                        echo '<input type="hidden" name="password'.$i.'"/>';
                                    }                                    
                                    echo '<input type="submit" class="btn btn-warning" name="retirerForm" onMouseOver="backupAP(this.form,'.$qtyAP.')" id="retirerForm" value="-"/></form>';
                                }
                                else {echo '</td><td align="right" width="40%">&nbsp;';}
                                    
                            ?>  
                            </td><td align="left" >                                
                                    <?php 
                                    echo '<form id="ajoutQty" name="ajoutQty" class="form-inline" role="form" action="ajoutAP.php" method="POST">';                                    
                                    echo '<input type="hidden" value="'.($qtyAP+1).'" name="qtyAP"/>';
                                    //pour mémoriser les saisies déjà effectuées
                                    for($i=0;$i<=$qtyAP;$i++){  
                                        echo '<input type="hidden" name="nomAP'.$i.'"/>';
                                        echo '<input type="hidden" name="noModeleAP'.$i.'"/>';
                                        echo '<input type="hidden" name="IPgroupeA'.$i.'"/>';
                                        echo '<input type="hidden" name="IPgroupeB'.$i.'"/>';
                                        echo '<input type="hidden" name="IPgroupeC'.$i.'"/>';
                                        echo '<input type="hidden" name="IPgroupeD'.$i.'"/>';
                                        echo '<input type="hidden" name="snmpCommunity'.$i.'"/>';
                                        echo '<input type="hidden" name="username'.$i.'"/>';
                                        echo '<input type="hidden" name="password'.$i.'"/>';
                                    }
                                    echo '<input type="submit" class="btn btn-success" name="ajouterForm" onMouseOver="backupAP(this.form,'.$qtyAP.')" id="ajouterForm" value="+"/>&nbsp;Ajouter une ligne';
                                    ?>
                                    
                                </form>
                            </td></tr></table>
                           
                           
                       </div>
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
    function backupAP(nomFormulaire, qtyAP)
     {        
         var formName = nomFormulaire.name;
        for(i=0;i<=qtyAP;i++){                
            document.getElementById(formName).elements['nomAP'+i].value=document.getElementById('ajoutAP').elements['nomAP'+i].value;
            document.getElementById(formName).elements['noModeleAP'+i].value=document.getElementById('ajoutAP').elements['noModeleAP'+i].value;
            document.getElementById(formName).elements['IPgroupeA'+i].value=document.getElementById('ajoutAP').elements['IPgroupeA'+i].value;
            document.getElementById(formName).elements['IPgroupeB'+i].value=document.getElementById('ajoutAP').elements['IPgroupeB'+i].value;
            document.getElementById(formName).elements['IPgroupeC'+i].value=document.getElementById('ajoutAP').elements['IPgroupeC'+i].value;
            document.getElementById(formName).elements['IPgroupeD'+i].value=document.getElementById('ajoutAP').elements['IPgroupeD'+i].value;
            document.getElementById(formName).elements['snmpCommunity'+i].value=document.getElementById('ajoutAP').elements['snmpCommunity'+i].value;
            document.getElementById(formName).elements['username'+i].value=document.getElementById('ajoutAP').elements['username'+i].value;
            document.getElementById(formName).elements['password'+i].value=document.getElementById('ajoutAP').elements['password'+i].value;
        }
     }
     
     function repliquerAP(qtyAP)
     {         
        var formName = 'ajoutAP';
         
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
            $("#ajoutAP").validate(
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