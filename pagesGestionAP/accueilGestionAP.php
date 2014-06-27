<!DOCTYPE html>
<html lang="en">
  <head>
    <title>AP Manager</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <script type="text/javascript" src="../js/jquery-1.11.1.js"></script>
    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
  </head>

  <body>
      <br><br>
    <div class="container-fluid">        

        <table border="0" width="80%" align="center">
           <tbody>
              <tr>
                 <td width="30%">                       
                      &nbsp;
                 </td>
                 <td width="2px">                       
                      &nbsp;
                 </td>                 
                 <td>                           
                    <ul class="nav nav-tabs nav-justified">
                     <li class="active"><a href="../pagesGestionAP/accueilGestionAP.php">Gestion des AP</a></li>
                     <li><a href="../pagesGestionBDD/accueilGestionBDD.php">Gestion des enregistrements de la BDD</a></li>
                     <li><a href="#">Configuration syst&egrave;me</a></li>
                    </ul>
                    <br>           
                 </td>
              </tr>
              <tr>
                 <td width="30%" class="leftmenu">
                        <p><b>Informations sur les AP</b></p>
                        <ul class="nav nav-pills nav-stacked">                       
                           <li><a href="#">Afficher la liste  de tous les AP inscrits</a></li>
                           <li><a href="#">Interroger un AP</a></li>                       
                        </ul>
                        <p><b>Configurer les AP</b></p>
                        <ul class="nav nav-pills nav-stacked">                       
                           <li><a href="#">Appliquer une commande &agrave; un ou plusieurs AP</a></li>    
                        </ul> 
                 </td>  
                 <td width="2px">                       
                      &nbsp;
                 </td>                 
                 <td class="informations">
                     
                     <ol class="breadcrumb">
                        <li><a href="#">Gestion des AP</a></li>
                        <li class="active">Accueil</li>
                    </ol>
                    <?php   
                        include '../includes/connexionBDD.php';
                        echo '
                            <table class="table">
                            <caption>Striped Table Layout</caption>
                            <thead>
                               <tr>
                                  <th>Name</th>
                                  <th>City</th>
                                  <th>Pincode</th>
                               </tr>
                            </thead>
                            <tbody>
                               <tr>
                                  <td>Tanmay</td>
                                  <td>Bangalore</td>
                                  <td>560001</td>
                               </tr>
                               <tr>
                                  <td>Sachin</td>
                                  <td>Mumbai</td>
                                  <td>400003</td>
                               </tr>
                               <tr>
                                  <td>Uma</td>
                                  <td>Pune</td>
                                  <td>411027</td>
                               </tr>
                            </tbody>
                         </table>  
                        ';
                            
                    ?>
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