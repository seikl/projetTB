<?php 
/**************************************************************************************************** 
 * Auteur: Sébastien Kleber (sebastien.kleber@heig-vd.ch) 
 * 
 * Description:
 * page de sauvgerde dans la BDD (pour l'instant n'enregistre que dans un fichier commun)
 * 
 * Modifié le: 19.09.2014
 ***************************************************************************************************/
$auth_realm = 'AP Tool'; require_once '../includes/authentification.php'; ?> 
<!DOCTYPE html>
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
                           <li class="active"><a href="#">Sauvegarder la BDD</a></li>    
                           <li><a href="#">Recharger la BDD</a></li>  
                        </ul> 
                 </td> 
                 
                 <td class="informations">
                    <ol class="breadcrumb">
                        <li><a href="accueilGestionSysteme.php">Accueil gestion du syst&egrave;mes</a></li>                            
                    </ol>  
                     <ol>

                        <?php                                          
                      
                            echo 'Base de donn&eacute;es sauvegard&eacute;e.<br><br>';
                                   
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