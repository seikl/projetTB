<script type="text/javascript">
		document.write('<div id="loading"><br><center>Testing VPN Connections...<img src="./images/progress_bar.gif"/><center></div>');
			//Ajax Function
				function getHTTPObject() 
					{ 
						var xmlhttp; 
						if (window.ActiveXObject) 
					{
						try 
					{
						xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
					}
						catch (e)
					{
						try
					{
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
						catch (E) 
					{
						xmlhttp = false;
					}
					}
					} 
						else
					{
						xmlhttp = false;
					}
						if (window.XMLHttpRequest) 
					{
						try
					{
						xmlhttp = new XMLHttpRequest();
					}	 
						catch (e) 
					{
						xmlhttp = false;
					}
					} 
						return xmlhttp; 
					}
//HTTP Objects.. 
		var http = getHTTPObject();

//Function which we are calling...
		function AjaxFunction()
			{
				url='../pagesGestionAP/rechercherAPResultat.php';
				http.open("GET",url, true);
				http.onreadystatechange = function()
			{
		if (http.readyState == 4) 
			{
//Change the text when result comes.....
		document.getElementById("loading").innerHTML=http. responseText;	
			}
			}
		http.send(null);	
			}
</script>
</head>
<body onload="AjaxFunction()">
    
    HELLO WORLD!
</body>