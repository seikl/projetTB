<?php $auth_realm = 'AP Tool'; require_once '../includes/authentification.php'; ?> <!DOCTYPE html>
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
                           <li><a href="ajoutAP.php">Ajouter</a></li>
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
                           <li class="active"><a href="selectModifCLI.php">Modifier</a></li>                       
                           <li><a href="selectSupprCLI.php">Supprimer</a></li>
                        </ul>                      
                 </td>
                 
                 <td class="informations">                     
                     <ol class="breadcrumb">
                        <li><a href="accueilGestionBDD.php">Accueil gestion de la BDD</a></li> 
                        <li>Modifier une lgine de commande</li>
                    </ol>
                     <ol>
                         
                     
                            <?php                                          
                               //connexion a la BDD et récupération de la liste des modèles
                               //include '../includes/connexionBDD.php';                    
                               include '../includes/fonctionsUtiles.php';      
                               
                                $boutonRetour = '<button class="btn btn-primary" onclick="history.back()">Retour</button>';
                               echo "<strong>En construction...</strong><br><br>";
                               echo $boutonRetour;
                               
                               
                            ?>    
                     </ol> 
                 </td>
              </tr>
           </tbody>
        </table>
        
        

      </div><!-- /container -->


    <!-- Bootstrap core JavaScrip ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <script type="text/javascript">
        $(function()
        {
            $("#modifModele").validate(
              {                
                rules: 
                {            
                  nomModele: 
                  {
                    required: true                   
                  },
                  versionFirmware: 
                  {
                    required: true
                  },
                  adrMACFabricant1: 
                  {
                    required: true
                  },  
                  adrMACFabricant2: 
                  {
                    required: true
                  }, 
                  adrMACFabricant3: 
                  {
                    required: true
                  }                   
                },
                errorElement: "divRight",
                errorPlacement: function(error, element) {
                    error.insertAfter(element);                    
                }                
              });
        });
    </script>      
  </body>
</html>