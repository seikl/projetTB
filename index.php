<?php 
/**************************************************************************************************** 
 * Auteur: Sébastien Kleber (sebastien.kleber@heig-vd.ch) 
 * 
 * Description:
 * page d'accueil. Bienvenue!
 * 
 * Modifié le: 19.09.2014
 ***************************************************************************************************/
$auth_realm = 'AP Tool'; require_once './includes/authentification.php'; ?> 
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>AP Tool</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <script type="text/javascript" src="./js/jquery-1.11.1.js"></script>                
    <!-- Bootstrap core CSS -->
    <link href="./bootstrap/css/bootstrap.css" rel="stylesheet">
  </head>

  <body onunload="$('#loading2').hide();">
      <p align="right"><br><a href="?action=logOut">LOGOUT</a>&nbsp;&nbsp;&nbsp;</p>
      <br>
    <div class="container-fluid">        

        <table border="0" width="90%" align="center">
           <tbody>
              <tr>
                <td width="30%">                       
                      &nbsp;
                 </td>                
                 <td>                           
                    <ul class="nav nav-tabs nav-justified">
                    <li ><a href="./pagesGestionAP/accueilGestionAP.php">Gestion des AP</a></li>
                     <li><a href="./pagesGestionBDD/accueilGestionBDD.php">Gestion des enregistrements de la BDD</a></li>
                     <li><a href="./pagesGestionSysteme/accueilGestionSysteme.php">Configuration syst&egrave;me</a></li>
                    </ul>
                    <br>           
                 </td>
              </tr>
              <tr>   
                <td width="30%">                       
                      &nbsp;
                 </td>                   
                 <td class="informations"> 
                     <ol>
                         
                         <h3>Bienvenue!</h3><br>                         
                         
                        <input type="button" class="btn btn-default" onclick="window.location='pagesGestionAP/accueilGestionAP.php'" value="Page d'accueil de gestion des p&eacute;riph&eacute;riques"/><br><br>
                        <input type="button" class="btn btn-default" onclick="window.location='pagesGestionAP/accueilGestionAP.php'" value="Page d'accueil de gestion des enregistrements de la BDD"/><br><br>
                        <input type="button" class="btn btn-default" onclick="window.location='pagesGestionAP/accueilGestionAP.php'" value="Page d'accueil de gestion du syst&egrave;me"/><br><br>                     
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



