<?php
$adressIP  = '172.16.1.20';
$domain = strstr($adressIP, '.');
echo $domain; // prints @example.com
echo "<br><br>";
$user = strstr($adressIP, '.', true); // As of PHP 5.3.0
echo $user; // prints name

echo "position 1er . : ".  strpos($adressIP, '.').'<br><br>';

echo "1er champ IP: ". strstr($adressIP, '.', true)."<bR>";       
$adressIP =strstr($adressIP, '.');$adressIP =  substr($adressIP,1);
echo "2e champ IP: ". strstr($adressIP, '.', true)."<bR>"; 
$adressIP =strstr($adressIP, '.');$adressIP =  substr($adressIP,1);
echo "3e champ IP: ". strstr($adressIP, '.', true)."<bR>"; 
$adressIP =strstr($adressIP, '.');$adressIP =  substr($adressIP,1);
echo "3e champ IP: ".$adressIP."<bR>";



$requete='GET /index.html HTTP/1.1
Host: 172.16.6.63
User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Cookie: AutoRefresh=off
Connection: keep-alive
';
$tabRequete= explode("\n", $requete);

echo "<br><br>DeCOUPE HTTP HEADER:<bR>";

$ligne=$tabRequete[0];

               $url= strstr($ligne,'/');
               
               $url='http://172.16.1.29'.strstr($url,' HTTP',true);

echo $url;

?> 