<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>AP Manager - Accueil</title>
    </head>
    <body>
        <?php

        
// Initialize session and set URL.
// must set $url first. Duh...
        
        
        

        
        
        
$curl = curl_init();

//active directToInternet
/*
$valeurs="option=5&cookie0=-1&cookie1=-1&cookie2=-1&cookie3=-1&cookie4=-1&cookie5=-1&cookie6=-1&cookie7=-1&cli_cmd=unset+policy+94+disable&page_url=%2F5E50B325A764893E79E78DD6D62EE6693AE0BA1%2Fpolicy_list_cnt.html%3Fpage_number%3D1%26num_per_page%3D50%26src_zone_name%3DAll%2520zones%26dst_zone_name%3DAll%2520zones%26params%3D5%2C-1%2C-1%2C-1%2C-1%2C-1%2C-1%2C-1&error_url=%2F5E50B325A764893E79E78DD6D62EE6693AE0BA1%2Fpolicy_list_cnt.html%3Fpage_number%3D1%26num_per_page%3D50%26src_zone_name%3DAll%2520zones%26dst_zone_name%3DAll%2520zones%26params%3D5%2C-1%2C-1%2C-1%2C-1%2C-1%2C-1%2C-1&vsys_id=0&num_per_page=50&page_num=1&page_option=0&src_zone_name=All+zones&dst_zone_name=All+zones&src_address_name=&dst_address_name=&qstr=&cmd=&url=&delay_return=";    
$referer="http://firewall/5E50B325A764893E79E78DD6D62EE6693AE0BA1/policy_list_cnt.html?params=5%2c-1%2c-1%2c-1%2c-1%2c-1%2c-1%2c-1&page_number=1&num_per_page=50";
$url='http://172.16.0.27/5E50B325A764893E79E78DD6D62EE6693AE0BA1/list_hidden_form.html';
*/

/*
//désactive directToInternet/
$valeurs="option=5&cookie0=-1&cookie1=-1&cookie2=-1&cookie3=-1&cookie4=-1&cookie5=-1&cookie6=-1&cookie7=-1&cli_cmd=set+policy+id+94+disable&page_url=%2FB22CA16978AD7FC4E093954E805C59FE8EA3B4B%2Fpolicy_list_cnt.html%3Fpage_number%3D1%26num_per_page%3D50%26src_zone_name%3DAll%2520zones%26dst_zone_name%3DAll%2520zones%26params%3D5%2C-1%2C-1%2C-1%2C-1%2C-1%2C-1%2C-1&error_url=%2FB22CA16978AD7FC4E093954E805C59FE8EA3B4B%2Fpolicy_list_cnt.html%3Fpage_number%3D1%26num_per_page%3D50%26src_zone_name%3DAll%2520zones%26dst_zone_name%3DAll%2520zones%26params%3D5%2C-1%2C-1%2C-1%2C-1%2C-1%2C-1%2C-1&vsys_id=0&num_per_page=50&page_num=1&page_option=0&src_zone_name=All+zones&dst_zone_name=All+zones&src_address_name=&dst_address_name=&qstr=&cmd=&url=&delay_return=";    
$referer="http://firewall/B22CA16978AD7FC4E093954E805C59FE8EA3B4B/policy_list_cnt.html?page_number=1&num_per_page=50&src_zone_name=All%20zones&dst_zone_name=All%20zones&params=5,-1,-1,-1,-1,-1,-1,-1";
$url='http://firewall/B22CA16978AD7FC4E093954E805C59FE8EA3B4B/list_hidden_form.html';     
 */

//CHange le nom d'un Avaya AP-6
$valeurs="EmWeb_ns%3Asnmp%3A233=APADSL01&EmWeb_ns%3Asnmp%3A234.0*s=pas+de+lieu&EmWeb_ns%3Asnmp%3A235=&EmWeb_ns%3Asnmp%3A236=sinfi%40lerepuis.ch&EmWeb_ns%3Asnmp%3A237=";    
$referer="http://172.16.1.29/";
$url='http://172.16.1.29/';     
     


curl_setopt_array($curl, array(    
    CURLOPT_FRESH_CONNECT=>true,
    CURLOPT_RETURNTRANSFER => false,    
    CURLOPT_UNRESTRICTED_AUTH=>true,    
    CURLOPT_FOLLOWLOCATION=>true,
    CURLOPT_HEADER=>true,
    CURLOPT_USERPWD=>":repuis",
    CURLOPT_CONNECTTIMEOUT=>10,
    CURLOPT_URL => $url,
    CURLOPT_POST => false,
            CURLOPT_POST => $boolReqPOST,
            CURLOPT_POSTFIELDS => $valeurs   
));
;

if(!$result = curl_exec($curl)){
    die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
}

echo "infos sur la requete: ";
 $info = curl_getinfo($curl);
 echo $info['request_header']."<br>";

echo "reponse: <br>";
print_r($result);

curl_close($curl);

        ?>
    </body>
</html>
