<?php

namespace FFLCH;

class Utils
{
    /* https://scripts.guru/partially-hide-email-address-using-php/ */
    public static function partially_email($email)
    {
        $em   = explode("@",$email);
        $name = implode(array_slice($em, 0, count($em)-1), '@');
        $len  = floor(strlen($name)/2);
        return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
    }

}
