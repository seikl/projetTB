<?php $auth_realm = 'AP Tool'; require_once '../includes/authentification.php'; ?> <!DOCTYPE html>
<html>
    <head>
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
                    <?php 
                        //connexion a la BDD et récupération de la liste des modèles
                        include '../includes/connexionBDD.php';                    
                        include '../includes/fonctionsUtiles.php';
                        
                        //Récupération nombre d'AP à ajouter et des valeurs saisies
                        $tabValeursSaisies=null;
                        if (!isset($_POST['qtyAP'])){$qtyAP=0;} 
                        else {                                                        
                            $qtyAP=$_POST['qtyAP']; 
                            //vérification si ajout depuis le résultat d'une recherche
                            if (isset($_POST['infoAPTrouve'])){                                
                                $listeAPTrouves = unserialize(base64_decode($_POST['infoAPTrouve']));
                                $j=0;
                                $tempQtyAP=0;                                
                                for ($i=0;$i<$qtyAP;$i++)
                                {
                                    //si l'AP a été sélectionné pour être enregistré
                                    if (isset($_POST['chkAPSelectionne'.$i])){
                                       $tabValeursSaisies[$j]= array("nomAP" =>$listeAPTrouves[$i]["nomAP"], 
                                                            "noModeleAP"=>$listeAPTrouves[$i]["noModeleAP"], 
                                                            "IPgroupeA"=>$listeAPTrouves[$i]["IPgroupeA"],
                                                            "IPgroupeB"=>$listeAPTrouves[$i]["IPgroupeB"],
                                                            "IPgroupeC"=>$listeAPTrouves[$i]["IPgroupeC"],
                                                            "IPgroupeD"=>$listeAPTrouves[$i]["IPgroupeD"],
                                                            "snmpCommunity"=>$listeAPTrouves[$i]["snmpCommunity"],
                                                            "username"=>$listeAPTrouves[$i]["username"],
                                                            "password"=>$listeAPTrouves[$i]["password"]);
                                       $j++;
                                       $tempQtyAP++;
                                    }
                                }
                                $qtyAP=$tempQtyAP;
                            }                          
                            else {
                                for ($i=0;$i<$qtyAP;$i++)
                                {
                                    $tabValeursSaisies[$i]=array("nomAP" =>$_POST['nomAP'.$i], 
                                                            "noModeleAP"=>$_POST['noModeleAP'.$i], 
                                                            "IPgroupeA"=>$_POST['IPgroupeA'.$i],
                                                            "IPgroupeB"=>$_POST['IPgroupeB'.$i],
                                                            "IPgroupeC"=>$_POST['IPgroupeC'.$i],
                                                            "IPgroupeD"=>$_POST['IPgroupeD'.$i],
                                                            "snmpCommunity"=>$_POST['snmpCommunity'.$i],
                                                            "username"=>$_POST['username'.$i],
                                                            "password"=>$_POST['password'.$i]);
                                }                            
                                if ($qtyAP==0){$tabValeursSaisies[0]=array("nomAP" =>$_POST['nomAP0'],
                                                          "noModeleAP"=>$_POST['noModeleAP0'], 
                                                            "IPgroupeA"=>$_POST['IPgroupeA0'],
                                                            "IPgroupeB"=>$_POST['IPgroupeB0'],
                                                            "IPgroupeC"=>$_POST['IPgroupeC0'],
                                                            "IPgroupeD"=>$_POST['IPgroupeD0'],
                                                            "snmpCommunity"=>$_POST['snmpCommunity0'],
                                                            "username"=>$_POST['username0'],
                                                            "password"=>$_POST['password0']);}                                
                            }
                        }
                        
                        
                        echo "<b>INFOS: <br>";
                        print_r($tabValeursSaisies);
                        
                        echo "<br>infos chekcbox:<bR>";
                        print_r($listeAPTrouves[($i-1)]);
                        
                                                echo "<br>quantite AP: ".$qtyAP."<bR>";

                        
                        
                        echo "</br>";
?>                               
    </body>
</html>