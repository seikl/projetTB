<?php $auth_realm = 'AP Tool'; require_once '../includes/authentification.php'; ?> <!DOCTYPE html>
<html lang="en">
  <head>
    <title>AP Tool</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <script type="text/javascript" src="../js/jquery-1.11.1.js"></script>                
    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
  </head>

  <body onunload="$('#loading2').hide();">
      <p align="right"><br><a href="?action=logOut">LOGOUT</a>&nbsp;&nbsp;&nbsp;</p>
      <br>
    <div class="container-fluid">        

        <table border="0" width="90%" align="center">
           <tbody>
              <tr>
                <?php include '../includes/menus.php'; echo $menuPagesGestionSysteme; ?> 
              <tr>
                 <td width="30%" class="leftmenu">
                     <p><b>Gestion de l'acc&egrave;s</b></p>
                        <ul class="nav nav-pills nav-stacked">                       
                            <li><a href="modifPassword.php">Modifier le mot de passe</a></li>                  
                        </ul>
                        <p><b>Gestion de la BDD</b></p>
                        <ul class="nav nav-pills nav-stacked">                       
                           <li><a href="#">Sauvegarder la BDD</a></li>    
                           <li><a href="#">Recharger la BDD</a></li>  
                        </ul> 
                 </td> 
                 
                 <td class="informations">
                    <ol class="breadcrumb">
                        <li><a href="accueilGestionSysteme.php">Accueil gestion du syst&egrave;mes</a></li>                            
                    </ol>  
                     <ol>
                        <?php               
                            $boutonRetour = '<button class="btn btn-primary" onclick="history.back()">Revenir sur le formulaire</button>';
                            
                            $initialisation=true;
                           //pour vérifier si valeurs déjà existantes dans le formulaire
                           if ($_POST) {                            
                               $ancienmdp= $_POST['ancienmdp'];
                               $nouveaumdp= $_POST['nouveaumdp1'];
                               $initialisation=false;                               
                           }
                           
                            if ($initialisation){
                                echo ' 
                                <form id="modifPassword" name="modifPassword" class="form-inline" role="form" action="modifPassword.php" method="POST">
                                    <div class="form-group">     
                                        <label for="ancienmdp">Ancien mot de passe (admin par d&eacute;faut:)</label><br>
                                        <input type="password" class="form-control" name="ancienmdp" id="ancienmdp" size="15" maxlength="20" value="admin"><br>
                                        -------------------------------------------------<bR>
                                        <label for="nouveaumdp1">Nouveau mot de passe:</label><br>
                                        <input type="password" class="form-control" name="nouveaumdp1" id="nouveaumdp1" size="15" maxlength="20"><br>
                                        <label for="nouveaumdp2">V&eacute;rification du nouveau mot de passe:</label><br>
                                        <input type="password" class="form-control" name="nouveaumdp2" id="nouveaumdp2" size="15" maxlength="20"><br><bR>
                                        <input type="submit" id="modifmdp" class="btn btn-primary" value="Modifer le mot de passe"/>
                                    </div>
                                </form>';                            
                            } 
                            else {
                                
                                $ini_array = parse_ini_file("../includes/loginInfo.ini");

                                $ancienpassword = $ini_array["mdp"];                                
                                if ($ancienmdp != $ancienpassword){
                                    echo "<strong> ancien mot de passe erron&eacute;.<br>veuillez effectuer la correction n&eacutecessaire.</strong><br><bR>";
                                    echo $boutonRetour;
                                }
                                else {
                                    $nouveaumdp=preg_replace("/'/i", "\'", $nouveaumdp);
                                    $modification='sed -i -e \'s/mdp = "'.$ancienpassword.'"/mdp = "'.$nouveaumdp.'" /g\' loginInfo.ini';
                                    shell_exec($modification);                                   
                                    
                                    echo "<strong> Mot de passe modifi&eacute; avec succ&egrave;s.</strong>";
                                    echo '<p align="left"><br><a href="?action=logOut">Se d&eacute;connecter</a>&nbsp;&nbsp;&nbsp;</p>';
                                    //safefilerewrite($file, implode("\r\n", $res));
                                }
                                
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