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