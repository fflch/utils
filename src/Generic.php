<?php

namespace Uspdev\Utils;

use Carbon\Carbon;

/**
 * Utils Class
 */
class Generic
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

    /**
     * Calcula a diferença entre duas datas no formato d/m/Y.
     * Exemplo de retorno:
     * 2 ano(s), 1 mês(es) e 10 dia(s)
     * 1 ano, 2 meses e 1 dia
     * 1 ano e 10 meses
     * 10 meses e 1 dia 
     * 1 ano e 2 dias
     */
    public static function formata_periodo($inicio, $fim) {

        $data_inicial = Carbon::createFromFormat("d/m/Y", $inicio);
        $data_final = Carbon::createFromFormat("d/m/Y", $fim);

        # Vamos somar 1 dia para arredondamento ?
        #$diff = $data_inicial->diff($data_final->addDays(1));
        $diff = $data_inicial->diff($data_final);

        $text = '';

        # Tratamento dos anos
        if($diff->format('%y') > 1){
            $text .= $diff->format('%y anos');

        } else if($diff->format('%y') == 1){
            $text .= $diff->format('%y ano');
        }

        # Divisor entre ano e mês: nada, virgula ou "e"
        if(!empty($text)){
            if($diff->format('%d') > 0 && $diff->format('%m') !=0 ){
                $text .= ', ';
            } else if($diff->format('%d') == 0 && $diff->format('%m') !=0){
                $text .= ' e ';
            }
        }

        # Tratamento dos meses
        if($diff->format('%m') > 1){
            $text .= $diff->format('%m meses');

        } else if($diff->format('%m') == 1){
            $text .= $diff->format('%m mês');
        }

        # Divisor entre mês/ano e dia
        if(!empty($text) && $diff->format('%d') > 0){
            $text .= ' e ';
        }

        # Tratamento dos dias
        if($diff->format('%d') > 1){
            $text .= $diff->format('%d dias');

        } else if($diff->format('%d') == 1){
            $text .= $diff->format('%d dia');
        }

        return $text;
    }

}
