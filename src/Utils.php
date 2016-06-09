<?php

namespace Zeus\Boleto;

/**
 * Description of Utils
 *
 * @author rafaelsalvioni
 */
class Utils
{
    public static function normalizaLinha($string)
    {
        $string = \preg_replace('/[\t\r\n]/', ' ', $string);
        $string = \preg_replace('/\s{2,}/', ' ', $string);
        $string = \trim($string);
        return $string;
    }
    
    public static function normalizaValorMoeda($valor)
    {
        $valor *= 100;
        $valor  = (int)$valor;
        $valor /= 100;
        return $valor;
    }
}
