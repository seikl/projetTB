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
                           <li class="active"><a href="sauvegarderBDD.php">Sauvegarder la BDD</a></li>    
                           <li><a href="rechargerBDD.php">Recharger la BDD</a></li>  
                        </ul> 
                 </td> 
                 
                 <td class="informations">
                    <ol class="breadcrumb">
                        <li><a href="accueilGestionSysteme.php">Accueil gestion du syst&egrave;mes</a></li>                            
                    </ol>  
                     <ol>

                          <?php     
                        
                            include '../includes/connexionBDD.php'; 
                            $nomFichier='../fichiers/backup_apmanagerdb.sql'; 
                            $requeteBackup='mysqldump -u '.$PARAM_utilisateur.' -p'.$PARAM_mot_passe.' '.$PARAM_nom_bd.' > '.$nomFichier;                    
                            $requeteInfos='ls -oh ../fichiers/*.sql'; 
                            $initialisation=true;
                            
                            //pour vérifier si validation de restauration de la BDD
                            if (isset($_POST['validationSauvegarde'])){
                                $initialisation=false; 
                            }
                          
                            if ($initialisation){                                   
                                echo ' 
                                <form id="sauvegarderBDD" name="sauvegarderBDD" class="form-inline" role="form" action="sauvegarderBDD.php" method="POST">
                                    <div class="form-group">     
                                        <label for="validationSauvegarde">Etes-vous s&ucirc;r de vouloir sauvegarder la base de donn&eacute;es?</label><br>
                                        <u>Information sur le fichier de sauvegarde existant:</u><br>';
                                        $infosFichier= shell_exec($requeteInfos);
                                echo '<div class="well well-sm">'.$infosFichier.'</div>
                                        <input type="hidden" class="form-control" name="validationSauvegarde" id="validationSauvegarde" value="true"><br><bR>
                                        <input type="submit" id="backupBDD" class="btn btn-warning" value="Sauvegarder la base de donn&eacute;s"/>
                                    </div>
                                </form>';                            
                            }                                                                                              
                           else {
                                if (file_exists($nomFichier)){unlink ($nomFichier);} 
                                shell_exec($requeteBackup);
                                echo '<strong>Base de donn&eacute;es sauvegard&eacute;e.</strong><br><br>';   
                                
                                $infosFichier= shell_exec($requeteInfos);
                                echo '<u>Informations sur le fichier de sauvegarde:</u> <br><br><div class="well well-sm">'.$infosFichier.'</div><br><br>'; 
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