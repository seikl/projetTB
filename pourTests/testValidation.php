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
     
<form id="myform" action="#">
  
 <p>
   <label><input type='radio' name='subscribe' id='chksubscribe' />Subscribe to our Newsletter</label> 
 </p>
  <p class='container'>
   <label for='email'> Email: </label>
   <input type='text' name='email' id='email' class='skip'/>
 </p>
 
 
  <p>
   <label><input type='radio' name='subscribe' id='chkCommande' />une Commande</label> 
 </p>
   <p class='container'>typeCommande: </label>
   <input type='text' name='typeCommande' id='typeCommande' class="skip"/>
 </p>
<input type="submit" name="submit" value="Submit">
</form>
        
   <script language="JavaScript">
$(function()
  {
    var validator = $('#myform').validate(
      {
        ignore:'.skip',
        rules:
        { 
          'email':{ required:true, email:true },
        typeCommande: 
        {
          required: true                   
        } 
        }      
    });
    
    $('#chksubscribe').change(function() 
    {
      if($(this).is(":checked")) 
      {
        $('#email').removeClass('skip');
        $('#typeCommande').addClass('skip');
      }
      else
      {
          alert('');
        $('#typeCommande').removeClass('skip');
        $('#email').addClass('skip');
      }
      validator.resetForm();
    });
    
    $('#chkCommande').change(function() 
    {
      if($(this).is(":checked")) 
      {
          alert('');
        $('#typeCommande').removeClass('skip');
        $('#email').addClass('skip');          

      }
      else
      {
        $('#email').removeClass('skip');
        $('#typeCommande').addClass('skip');
      }
      validator.resetForm();
    });
    
    
  });   
    </script>       
    </body>
</html>
