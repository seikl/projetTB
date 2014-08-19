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
                 <td width="30%">                       
                      &nbsp;
                 </td>               
                 <td>                           
                    <ul class="nav nav-tabs nav-justified">
                     <li><a href="../pagesGestionAP/accueilGestionAP.php">Gestion des AP</a></li>
                     <li class="active"><a href="../pagesGestionBDD/accueilGestionBDD.php">Gestion des enregistrements de la BDD</a></li>
                     <li><a href="#">Configuration syst&egrave;me</a></li>
                    </ul>
                    <br>           
                 </td>
              </tr>
              <tr>
                 <td width="30%" class="leftmenu">
                        <p><b>G&eacute;rer les enregistrments des AP</b></p>
                        <ul class="nav nav-pills nav-justified">                       
                           <li><a href="ajouterAP.php">Ajouter</a></li>
                           <li><a href="modifierAP.php">Modifier</a></li>                       
                           <li><a href="supprimerAP.php">Supprimer</a></li>
                        </ul>
                         <p><b>G&eacute;rer les mod&egrave;les enregistr&eacute;s</b></p>
                        <ul class="nav nav-pills nav-justified">                       
                           <li><a href="ajouterModele.php">Ajouter</a></li>
                           <li><a href="modifierModele.php">Modifier</a></li>                       
                           <li><a href="supprimerModele.php">Supprimer</a></li>
                        </ul>
                         <p><b>G&eacute;rer les lignes de commandes (CLI)</b></p>
                        <ul class="nav nav-pills nav-justified">                       
                           <li><a href="ajouterCLI.php">Ajouter</a></li>
                           <li><a href="modifierCLI.php">Modifier</a></li>                       
                           <li><a href="supprimerCLI.php">Supprimer</a></li>
                        </ul>                      
                 </td>                 
                 <td class="informations">
                     
                     <ol class="breadcrumb">
                        <li><a href="accueilGestionBDD.php">Accueil gestion de la BDD</a></li> 
                        <li>Ajouter un mod&egrave;le d'AP</li>
                    </ol>
                   <ol>
                       
                    <form id="ajoutModele" name="ajoutModele" class="form-inline" role="form" action="enregistrerModeleAP.php" method="POST">
                        <div class="form-group">       

                            <table border="0" class="table">
                                <tr><td align="right">
                                    <input type="text" class="form-control" name="nomModele" id="nomModele" size="25" maxlength="25" placeholder="AP-7">
                                </td><td>
                                    <strong class="obligatoire">*&nbsp;</strong><label for='nomModele'>Nom du mod&egrave;le (par ex. AP-6 ou RT66CU)</label><br>
                                </td></tr>
                                <tr><td align="right">
                                    <input type="text" class="form-control" name="versionFirmware" id="versionFirmware" size="8" maxlength="8" placeholder="1.24.10">
                                </td><td>
                                    <strong class="obligatoire">*&nbsp;</strong><label for='versionFirmware'>Version du firmware (par ex. 2.4.11)</label><br>
                                </td></tr>
                                <tr><td align="right">
                                    <input type="text" class="form-control" name="nomFabricant" id="nomFabricant" size="20" maxlength="20" placeholder="Avaya">
                                </td><td>
                                    &nbsp;&nbsp;&nbsp;<label for='nomFabricant'>Nom du fabricant (par ex. Avaya)</label><br>
                                </td></tr> 
                                <tr><td align="right">
                                    <input type="text" class="form-control" name="adrMACFabricant" id="adrMACFabricant" size="8" maxlength="8" placeholder="00:a6:50">
                                </td><td>
                                    <strong class="obligatoire">*&nbsp;</strong><label for='adrMACFabricant'>Adresse MAC du Fabricant (par ex. 00:11:22)</label><br>
                                </td></tr>                                 
                                <tr><td  align="right">
                                    <input type="submit" id="submit" class="btn btn-primary" value="Enregistrer"/>                           
                                </td><td>
                                        Tous les champs marqu&eacute;s d'une <strong class="obligatoire">*&nbsp;</strong>sont obligatoires.
                                </td></tr>
                            </table>                                    
                         </div>                             
                        </form>
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
            $("#ajoutModele").validate(
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
                  adrMACFabricant: 
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