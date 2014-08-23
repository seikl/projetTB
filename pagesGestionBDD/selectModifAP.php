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
                        <li>Choix des AP &agrave; modifier</li>
                    </ol>
                     <ol>
                        <table width="auto">
                        <tr><td width="auto">                          
                        <form id="selectModifAP" class="form-inline" role="form" action="selectModifAP.php" method="POST">
                            <div class="form-group">                                                           
                            <label for="name">Veuillez s&eacute;lectionner les AP &agrave; modifier:</label><br>
                            <select class="form-control" id="noModele" name="noModele" onChange="this.form.submit()">
                     
                            <?php                                          
                           //connexion a la BDD et récupération de la liste des modèles
                           include '../includes/connexionBDD.php';                    
                           include '../includes/fonctionsUtiles.php';                          

                           if (!isset($_POST['noModele'])){                            
                               $noModele='0';
                               echo "<option value='0' selected>Tous les mod&egrave;les...&nbsp;&nbsp;&nbsp;</option>";
                           }
                           else {
                               $noModele = $_POST['noModele'];                            
                               echo "<option value='0'>Tous les mod&egrave;les...&nbsp;&nbsp;&nbsp;</option>";                               
                           }                                                                                 

                           //Récupération de la liste des modèles
                           try
                           {                            
                                   $i =0;                                
                                   $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                   $resultatsModelesAP=$connexion->query("SELECT * FROM modeles;");                                 
                                   $resultatsModelesAP->setFetchMode(PDO::FETCH_OBJ);                                 

                                   while( $ligne = $resultatsModelesAP->fetch() ) // on récupère la liste des membres
                                   {     
                                       $noModeleAP=(string)$ligne->noModeleAP;
                                       $nomModele=(string)$ligne->nomModele;
                                       $versionFirmware=(string)$ligne->versionFirmware;
                                       $nomFabricant=(string)$ligne->nomFabricant;
                                       $adrMACFabricant=(string)$ligne->adrMACFabricant; 
                                       if ($noModeleAP==$noModele){
                                           echo '<option value="'.$noModeleAP.'" selected>'.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.'&nbsp;&nbsp;&nbsp;</option>';
                                       }
                                       else {
                                           echo '<option value="'.$noModeleAP.'">'.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.'&nbsp;&nbsp;&nbsp;</option>';  
                                       }
                                   }
                               $resultatsModelesAP->closeCursor(); // on ferme le curseur des résultats                                                                            
                           }

                           catch(Exception $e)
                           {
                                   echo '</select></div></form></td></tr></table><li>Erreur lors du chargement</li></ol>';
                                   echo 'Erreur : '.$e->getMessage().'<br />';
                                   echo 'N° : '.$e->getCode();
                           }                        

                           echo '</select></form><br><br></td></tr>';                                      
                           echo '<tr><td width="auto">';
                           echo '<form id="selectionAP" name="selectionAP" class="form-inline" role="form" action="modifierAP.php" method="POST">';
                           echo '<label for="name">Choix des AP &agrave; modifier:</label><br>
                               <select multiple size="10" class="form-control" onchange="verifier(this.form)" id="listeAP" name="listeAP[]">';                    

                           //Pour afifcher la liste des AP à sélectionner
                           try
                           {
                                $i=0;
                                $tabListeAP=null;
                                $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                if ($noModele=='0'){
                                    $resultatsListeAP=$connexion->query("SELECT * FROM accessPoints a, modeles m WHERE a.noModeleAP=m.noModeleAP;");                                 
                                }
                                else{
                                    $resultatsListeAP=$connexion->query("SELECT * FROM accessPoints a, modeles m WHERE a.noModeleAP=m.noModeleAP AND a.noModeleAP =".$noModele.";");
                                }                                    

                                $resultatsListeAP->setFetchMode(PDO::FETCH_OBJ);                                 

                                while($ligne = $resultatsListeAP->fetch() ) // on récupère la liste des membres
                                {     
                                    $noAP=(string)$ligne->noAP;
                                    $nomAP=(string)$ligne->nomAP;
                                    $ip=(string)$ligne->adresseIPv4;
                                    $username=(string)$ligne->username;
                                    $password=(string)$ligne->password;
                                    $snmpCommunity=(string)$ligne->snmpCommunity;
                                    $nomFabricant=(string)$ligne->nomFabricant;
                                    $noModeleAP=(string)$ligne->noModeleAP;
                                    $nomModele=(string)$ligne->nomModele;
                                    $versionFirmware=(string)$ligne->versionFirmware;   
                                    $adrMACFabricant =(string)$ligne->adrMACFabricant;
                                    //$noModeleAP =(string)$ligne->noModeleAP;
                                    $tabListeAP[$i]=array("noAP" =>$noAP, "nomAP"=>$nomAP, "adresseIPv4"=>$ip,"snmpCommunity"=>$snmpCommunity, "username"=>$username, "password"=>$password);       
                                    echo '<option value="'.$tabListeAP[$i].'">'.$noAP.' - '.$nomAP.' ('.$nomFabricant.' '.$nomModele.' v.'.$versionFirmware.', IP: '.$ip.')&nbsp;&nbsp;&nbsp;</option>';                                       
                                    $i++;
                                }
                                $resultatsListeAP->closeCursor(); // on ferme le curseur des résultats                                                                            
                            }
                            catch(Exception $e)
                            {
                                    echo '</select>Erreur lors du chargement<br>';
                                    echo 'Erreur : '.$e->getMessage().'<br>';
                                    echo 'N° : '.$e->getCode();
                            }     
                            echo '</select><br></td></tr>'; 
                            $actionOnClick="$('#selectionAP').submit();";                            
                            $actionReset="location='selectModifAP.php'";
                            echo '<tr><td valign="bottom">'; 
                            echo '<input type="text" id="verification" name="verification">';
                            echo '<input type="button" class="btn btn-primary" onclick='.$actionOnClick.' value="Modifier les AP s&eacute;lectionn&eacute;s"/>';
                            echo '<input type="button" class="btn  btn-default" onclick='.$actionReset.' value="R&eacute;initialiser"/>';
                            echo '</form></div></td></tr></table>';
?>     
                     </ol> 
                 </td>
              </tr>
           </tbody>
        </table>
        
        

      </div><!-- /container -->


    <!-- Bootstrap core JavaScrip ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->       
   <script language="JavaScript">
        function verifier(nomFormulaire)
         {        
            var formName = nomFormulaire.name;               
            document.getElementById(formName).elements['verification'].value=document.getElementById('listeAP').value;
         };
         
        //Pour la validation de la sélection d'un modele
        $(function()
        {
            $("#selectionAP").validate(
              {                
                rules: 
                {            
                  verification:
                  {
                    required: true,
                    range:[8,32]
                  }    
                },
                errorElement: "divBelow",
                errorPlacement: function(error, element) {
                    error.insertAfter(element);                    
                }                
              });
        });   
    </script>         
  </body>
</html>