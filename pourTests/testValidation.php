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
     
        <form method="post" name="myform" id="myform">
            <table>
                <tr><td>
               		<label for='items'>Number of Items: (min: 1)</label><br>
		<input type="text" name="items" ><bR>
                    </td>
<td>
		<label for='amount'>Amount: (min 10 max 100)</label><br>
		<input type="text" name="amount" >
                </td><td>
		<label for='factor'>Factor: (Between 0.08 and 0.09)</label><br>
		<input type="text" name="factor" >
                </td></tr>
	   <tr><td>
	<input type="submit" name='submit' value="Submit"> 
               </td></tr> 
            </table>

</form>
        
    <script type="text/javascript">
        $(function()
        {
            
            
            $("#myform").validate(
              {
                rules: 
                {
                  items: 
                  {
                    required: true,
                    min:1
                  },
                  amount: 
                  {
                    range:[10,100]
                  },
                  factor:
                  {
                    required: true,
                    range:[0.08,0.09]  
                  },
                  dullness:
                  {
                    required: true,
                    range:[-9.5,11.1]
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
