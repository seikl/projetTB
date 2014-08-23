<!DOCTYPE html>
<html lang="en">
  <head>
    <title>AP Tool</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <script type="text/javascript" src="../js/jquery-1.11.1.js"></script>
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
                    </ol>
                   <ol>
                        <div class="form-group">                              
                    <?php                                               
                        echo "
                            <table class='table table-striped' width='auto' align='left'>                            
                            <thead>
                               <tr>
                                  <th>Nombre d'enregistrements contenus dans la BDD</th>
                               </tr>
                            </thead>
                            <tbody>";                   
                    
                        //connexion a la BDD
                        include '../includes/connexionBDD.php';
                        
                        $actionOnClick="alert();";
                        try
                        {                            
                                $i =0;
                                $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

                                $resultatsAP=$connexion->query("SELECT COUNT(a.noAP) as nombreAP FROM accessPoints a;"); 
                                $resultatsAP->setFetchMode(PDO::FETCH_OBJ);                                                                 
                                while( $ligne = $resultatsAP->fetch() ) 
                                {                                                                                  
                                        $textCellule= (string)$ligne->nombreAP.' access points enregistr&eacute;(s)';
                                        echo '<tr><td onclick="window.location.href = \'selectModifAP.php\'">';
                                        echo $textCellule;
                                        echo '</td></tr>';
                                }
                                $resultatsAP->closeCursor();
                                
                                $resultatsModeles=$connexion->query("SELECT COUNT(m.noModeleAP) as nombreModelesAP FROM modeles m;"); 
                                $resultatsModeles->setFetchMode(PDO::FETCH_OBJ);                                 
                                while( $ligne = $resultatsModeles->fetch() ) 
                                {                                                                                  
                                        $textCellule= (string)$ligne->nombreModelesAP.' mod&egrave;les enregistr&eacute;(s)';
                                        echo '<tr><td onclick="window.location.href = \'selectModifModele.php\'">';
                                        echo $textCellule;
                                        echo '</td></tr>';
                                }
                                $resultatsModeles->closeCursor();
                                
                                
                                $resultatsCLI=$connexion->query("SELECT COUNT(l.noCLI) as nombreCLI FROM lignesCommande l;"); 
                                $resultatsCLI->setFetchMode(PDO::FETCH_OBJ);                                 
                                while( $ligne = $resultatsCLI->fetch() ) 
                                {                                                                                  
                                        $textCellule= (string)$ligne->nombreCLI.' lignes de commande enregistr&eacute;e(s)';
                                        echo '<tr><td onclick="window.location.href = \'selectModifCLI.php\'">';
                                        echo $textCellule;
                                        echo '</td></tr>';
                                }
                                $resultatsCLI->closeCursor();                                
                        }

                        catch(Exception $e)
                        {
                                echo '<tr><td>Erreur : '.$e->getMessage().'<br />';
                                echo 'N° : '.$e->getCode().'</td></tr>';
                        }


                        
                        echo '</tbody>
                         </table>  
                        ';                            
                    ?>
                        </div>
                    </form>
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