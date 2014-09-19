<?php
/**************************************************************************************************** 
 * Auteur: Sébastien Kleber (sebastien.kleber@heig-vd.ch) 
 * 
 * Description:
 * script PHP contenant des fonctions à but utilitaire:
 *  - Lever une exception en cas d'alerte
 *  - calculer une adresse réseau depuis une ip et son masque
 *  - calculer un broadcast IP depuis l'adresse du réseua et son maque
 *                                                                                                  *
 * Modifié le: 31.08.2014                                                                           *
 ***************************************************************************************************/

    //pour lever une exception en cas de warning() (source: http://stackoverflow.com/questions/1241728/can-i-try-catch-a-warning, le 06.08.2014)
    function handleError($errno, $errstr, $errfile, $errline, array $errcontext)
    {
        if (0 === error_reporting()) {
            return false;
        }
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    set_error_handler('handleError');
    //--------------------------------------------    
    
    
    //obtenir l'adresse du réseau (source: http://forum.codecall.net/topic/58903-php-get-network-address-from-ip/, le 07.08.2014)  
    function netmask($ip, $cidr) {
         $bitmask = $cidr == 0 ? 0 : 0xffffffff << (32 - $cidr);
         return long2ip(ip2long($ip) & $bitmask);
     }
    //--------------------------------------------
      
    //get the broadcast from CIDR (source: https://mebsd.com/coding-snipits/broadcast-from-network-cidr-equation-examples.html, le 05.08.2014)       
    function cidr2broadcast($network, $cidr)
    {
        $broadcast = long2ip(ip2long($network) + pow(2, (32 - $cidr)) - 1);
      return $broadcast;
    }     
    //--------------------------------------------
     
?>
