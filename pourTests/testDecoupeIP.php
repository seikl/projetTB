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
?> 