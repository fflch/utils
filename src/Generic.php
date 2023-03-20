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
        $em = explode('@', $email);
        $name = implode('@', array_slice($em, 0, count($em) - 1));
        $len = floor(strlen($name) / 2);
        return substr($name, 0, $len) . str_repeat('*', $len) . '@' . end($em);
    }

    /* Recebe YYYY-MM-DD e retorna DD/MM/YYYY */
    public static function data_ptbr($data)
    {
        return implode('/', array_reverse(explode('-', $data)));
    }

    /* Recebe DD/MM/YYYY e devolve YYYY-MM-DD */
    public static function data_en($data)
    {
        return implode('-', array_reverse(explode('/', $data)));
    }

    public static function crazyHash($n)
    {
        $hash = substr(dechex($n / 13), -1) . dechex($n + 2047483639) . substr(dechex($n / 13), -3);
        return strtoupper($hash);
    }

    public static function flatten($array, $prefix = '')
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = $result + self::flatten($value, $prefix . $key . '.');
            } else {
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
    public static function formata_periodo($inicio, $fim)
    {
        $data_inicial = Carbon::createFromFormat('d/m/Y', $inicio);
        $data_final = Carbon::createFromFormat('d/m/Y', $fim);

        # Vamos somar 1 dia para arredondamento ?
        #$diff = $data_inicial->diff($data_final->addDays(1));
        $diff = $data_inicial->diff($data_final);

        $text = '';

        # Tratamento dos anos
        if ($diff->format('%y') > 1) {
            $text .= $diff->format('%y anos');
        } elseif ($diff->format('%y') == 1) {
            $text .= $diff->format('%y ano');
        }

        # Divisor entre ano e mês: nada, virgula ou "e"
        if (!empty($text)) {
            if ($diff->format('%d') > 0 && $diff->format('%m') != 0) {
                $text .= ', ';
            } elseif ($diff->format('%d') == 0 && $diff->format('%m') != 0) {
                $text .= ' e ';
            }
        }

        # Tratamento dos meses
        if ($diff->format('%m') > 1) {
            $text .= $diff->format('%m meses');
        } elseif ($diff->format('%m') == 1) {
            $text .= $diff->format('%m mês');
        }

        # Divisor entre mês/ano e dia
        if (!empty($text) && $diff->format('%d') > 0) {
            $text .= ' e ';
        }

        # Tratamento dos dias
        if ($diff->format('%d') > 1) {
            $text .= $diff->format('%d dias');
        } elseif ($diff->format('%d') == 1) {
            $text .= $diff->format('%d dia');
        }

        return $text;
    }

    public static function array_to_csv($path, $array)
    {
        $fp = fopen($path, 'w');
        fputcsv($fp, array_keys($array[0]));
        foreach ($array as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    # http://technologyordie.com/php-mac-address-validation
    # cisco: 0012:2356:7890
    # - : 00-12-23-56-78-90
    # linux: 00:12:23:56:78:90
    # ? 00?12?23?56?78?90
    public static function format_mac($mac, $format = 'linux')
    {
        $mac = preg_replace('/[^a-fA-F0-9]/', '', $mac);

        $mac = str_split($mac, 2);
        if (!(count($mac) == 6)) {
            return false;
        }

        if ($format == 'linux' || $format == ':') {
            return $mac[0] . ':' . $mac[1] . ':' . $mac[2] . ':' . $mac[3] . ':' . $mac[4] . ':' . $mac[5];
        } elseif ($format == 'windows' || $format == '-') {
            return $mac[0] . '-' . $mac[1] . '-' . $mac[2] . '-' . $mac[3] . '-' . $mac[4] . '-' . $mac[5];
        } elseif ($format == 'cisco') {
            return $mac[0] . $mac[1] . ':' . $mac[2] . $mac[3] . ':' . $mac[4] . $mac[5];
        } else {
            return $mac[0] . "$format" . $mac[1] . "$format" . $mac[2] . "$format" . $mac[3] . "$format" . $mac[4] . "$format" . $mac[5];
        }
    }

    /**
     * Gera senha (string) contendo caracteres especiais, números, e letras maiúsculas e minúsculas
     *
     * O tamanho mínimo são 6 caracterese máximo 255. Default = 15.
     *
     * @param Int $len Tamanho da senha gerada
     * @return String
     * @author Masaki K Neto em 10/3/2022
     */
    public static function senhaAleatoria(int $len = 15)
    {
        $len = $len < 4 ? 6 : $len; // pelo menos tamanho 6
        $len = $len > 255 ? 255 : $len; // maxímo de 255
        $specials = '!@#$%&*_';
        $numbers = '0123456789';
        $lowers = 'abcdefghijklmnopqrstuvwxyz';
        $uppers = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return str_shuffle(substr(str_shuffle($specials), 0, 2) . substr(str_shuffle($numbers), 0, 2) . substr(str_shuffle($uppers), 0, intval(($len - 4) / 2)) . substr(str_shuffle($lowers), 0, $len - 4 - intval(($len - 4) / 2)));
    }

    /**
     * Formata um CPF
     *
     * Recebe um cpf somente números, completa com zeros à esquerda e acrescenta os pontos e traço.
     * Adaptado de https://gist.github.com/davidalves1/3c98ef866bad4aba3987e7671e404c1e
     *
     * @param Int|String $value CPF a ser formatado
     * @return String
     * @author Masaki K neto, em 20/3/2023
     */
    public static function formatarCpf($value)
    {
        $value = str_pad($value, 11, '0', STR_PAD_LEFT);
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', "\$1.\$2.\$3-\$4", $value);
    }
}
