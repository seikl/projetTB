<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>AP Manager - recherche materiel</title>
    </head>
    <body>
<!-- POUR TEST IMAGE CHARGEMENT 

<div id="loading-image">
	<img src="<?//php bloginfo('template_url'); ?>/images/ajax-loader.gif" alt="Loading..." />
</div>
-->
        Chargement....
        <?php
            /*
            This if primary for MS Windows (may work at other system, depending on 3rd side programs' output)
            3rd side programs:
            - ping
            - arp
            - ngrep (requires WinPCap for Windows or LibPCap for Unixs)
            */

            ///SETTINGS/////////////////////////////////////
            $ngrep = "ngrep"; //NGREP binary
            $ping = "ping -c1"; //PING with arguments
            $arp = "arp"; //ARP with arguments to show all ARP records

            ///FUNCTIONS////////////////////////////////////

            //Get HW (MAC) address from IP address
            function get_mac($ip) {
              $ip = trim($ip);
              shell_exec("ping -c1"." ".$ip);
              $arp = shell_exec("arp");
              $arp = explode("\n", $arp);
              
              foreach($arp as $line) {
                if(ereg(": $ip ---", $line)) { return("This is your adapter, to find MAC try \"ipconfig /all\""); }
                if(ereg("  $ip ", $line)) {
                  //echo($line."\n <br>"); //Debug
                  $line = explode($ip, $line);
                  $line = trim($line[1]);
                  $line = explode("dynamique", $line);
                  $line = trim($line[0]);
                  //echo($line."\n <br>"); //Debug
                  $macAVAYA="00-20-A6";
                if (strcasecmp($line,$macAVAYA) == 0)
                    $line = $line." <b>Avaya AP spotted! </b><br>";
                /* DEBUG else
                    $resultatMAC = $resultatMAC. "not an Avaya AP. <br>"; */
                
                return($line);
                }
              }
              return("Not found. Couldn't broadcast to IP.");
            }

            //Passive scan for active computers (IPs) in network (it's 100% stealth),
            //but you can use "nmap" (for example) for scanning more more quickly and efectively...
            //This is waiting in infinite loop...
            function sniff_ips($device = 1, $subnet = "") {
              $device = trim($device);
              $subnet = trim($subnet);
              $ngrep = ($GLOBALS["ngrep"]." -d ".$device);
              $fp = popen($ngrep, "r");

              $ips[0] = "";
              $i = 0;
              while($fp && !feof($fp)) {
                $line = fgets($fp);
                if(ereg("$subnet.*:.* -> .*:.*", $line)) {
                  $line = explode(" ", $line);
                  $line = explode(":", $line[1]);
                  $ip = trim($line[0]);

                  if(!in_array($ip, $ips)) {
                    $ips[$i] = $ip;
                    $i++;

                    //You hav $ip, you can do anything, that you want:
                    echo($ip." = ".get_mac($ip)."\n <br>"); //Get it's MAC and print it

                  }
                }
              }
            }

            //Quick active scan for MACs and IPS
            function quick_ipmac_scan($subnet) {
              for($i=1;$i<256;$i++) {
                //Mega threaded ( This will open 255 processes ;))
                $ipAPinger=$subnet.".".$i;
                $fp[$i] = popen("ping -n1 -w1"." ".$ipAPinger, "r");
								//echo("IP: $ip\nMAC: ".get_mac($ip)."\n");
              }
              for($i=1;$i<256;$i++) {
                while( $fp[$i] && !feof($fp[$i]) ) { fgets($fp[$i]); }
              } 

              
              //$arp = shell_exec("arp -vn"); //pour Linux
              $arp = shell_exec("arp -a"); //pour Windows
              $tableARP= explode("\n", $arp);
              
              echo "<h1> Table ARP:</H1>";
              //print_r($tableARP); //DEBUG
              
              foreach($tableARP as $line) {  
         
                if(preg_match("/00:20:a6/i", $line)) {
                  $line = $line."<b><== Borne AVAYA!</b>";
                }
                echo($line."\n <br>");    
              } 
							            
              
            }

            ///Examples of usage://///////////////////////////////////////////////////////
            //You have to modify this script, to get that output format, that you want...


            //Sniff for IPs:
            /*echo("Sniffing for IP/MAC addresses\nC-c for stop\n\n");*/
            //This will sniff on 3rd device ("ngrep -L" for device listing)
            //And only IPs that starts with "192.168" will be accepted
            /*sniff_ips(3, "172.16"); //ngrep -d 3 | grep 192.168.*:.* -> .*:.*  */

            /*
            Example output:
            Sniffing for IP/MAC addresses
            C-c for stop

            192.168.15.82 = This is your adapter, to find MAC try "ipconfig /all"
            192.168.15.65 = 00-00-24-c1-e7-e8
            192.168.15.84 = 00-04-e2-cb-bc-6a
            192.168.15.77 = Not found. Couldn't broadcast to IP.
            192.168.15.80 = Not found. Couldn't broadcast to IP.
            */

            //--------------------------------------------------------------------------


            //Quick active scan for MACs/IPs:
            print("Scanning for IP/MAC addresses\nC-c for stop\n <br>");
            quick_ipmac_scan("172.16.1");
						$ip="172.16.1.29";
						//echo("IP: $ip\nMAC: ".get_mac($ip)."\n");
            /*
            Example output:
            Scanning for IP/MAC addresses
            C-c for stop

            Rozhrani: 192.168.15.82 --- 0x40003
              internetova  adresa    fyzicka  adresa        typ
              192.168.15.65         00-00-24-c1-e7-e8     dynamicka 
              192.168.15.80         00-16-ce-0a-0e-a1     dynamicka 
            */

            //--------------------------------------------------------------------------

            //*Get MAC:
            //$ip = "192.168.15.82"; //This is your adapter, to find MAC try "ipconfig /all"
            //$ip = "404.168.15.82"; //Not found. Couldn't broadcast to IP.
            //$ip = "192.168.15.65";
            //echo("IP: $ip\nMAC: ".get_mac($ip)."\n");*/

            /*
            Example output:
            IP: 192.168.15.65
            MAC: 00-00-24-c1-e7-e8
            */

        ?>
    </body>
</html>