<?php

namespace Uspdev;

/**
 * Utils Class
 */
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

    /* Recebe YYYY-MM-DD e retorna DD/MM/YYYY */
    public static function data_ptbr($data)
    {
        return implode('/',array_reverse(explode('-',$data)));
    }

    /* Recebe DD/MM/YYYY e devolve YYYY-MM-DD */
    public static function data_en($data)
    {
       return implode('-',array_reverse(explode('/',$data)));
    }
    
    public static function crazyHash($n) {
        $hash = substr( dechex( $n / 13 ), - 1 ) . dechex( $n + 2047483639 ) . substr( dechex( $n / 13 ), - 3 );
        return strtoupper($hash);
    }
    
    public static function flatten($array, $prefix = '') {
      $result = array();
      foreach($array as $key=>$value) {
          if(is_array($value)) {
              $result = $result + self::flatten($value, $prefix . $key . '.');
          }
          else {
              $result[$prefix . $key] = $value;
          }
      }
      return $result;
    }

}
