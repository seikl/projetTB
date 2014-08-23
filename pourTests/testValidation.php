<!DOCTYPE html>
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
     
        <form method="post" name="selectionAP" id="selectionAP">
            <table>
                <tr><td>
               		<label for='items'>Number of Items: (min: 1)</label><br>
		<input type="text" name="items" ><bR>
                    </td>
<td>
		<label for='verification'>Amount: (min 10 max 100)</label><br>
		<input type="text" name="verification" >
                </td><td>
		<label for='factor'>Factor: (Between 0.08 and 0.09)</label><br>
		<input type="text" name="factor" >
                </td></tr>
	   <tr><td>
	<input type="submit" name='submit' value="Submit"> 
               </td></tr> 
            </table>

</form>
   <script language="JavaScript">
        //Pour la validation de la sélection d'un modele
        $(function()
        {
            $("#selectionAP").validate(
              {                
                rules: 
                {            
                  verification:
                  {
                    required: true,
                    range:[8,32]
                  }    
                },
                errorElement: "divBelow",
                errorPlacement: function(error, element) {
                    error.insertAfter(element);                    
                }                
              });
        });   
    </script>       
    </body>
</html>
