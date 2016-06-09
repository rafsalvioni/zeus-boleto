<?php

namespace Zeus\Boleto;

/**
 * Description of Banco
 *
 * @author rafaelsalvioni
 */
class Banco
{
    protected $codigo;
    protected $nome;
    protected $dv;

    public function __construct($codigo, $nome)
    {
        $codigo = \str_pad($codigo, 3, '0', \STR_PAD_LEFT);
        if (!\preg_match('/^\d{3}$/', $codigo)) {
            throw new BancoException('O código do banco deve ter 3 dígitos!');
        }
        
        $this->codigo = $codigo;
        $this->nome   = Utils::normalizaLinha($nome);
        $this->dv     = self::modulo10($codigo);
    }
    
    public function getCodigo($comDv = false)
    {
        $codigo = $this->codigo;
        if ($comDv) {
            $codigo .= "-{$this->dv}";
        }
        return $codigo;
    }

    public function getNome()
    {
        return $this->nome;
    }
    
    public function getDv()
    {
        return $this->dv;
    }
    
    /**
     * Cálculo módulo 10.
     * 
     * @param string $data
     * @return int
     */
    protected static function modulo10($data)
    {
        $data   = \str_split($data);
        $sum    = 0;
        $weight = 2;
        
        while (!empty($data)) {
            $prod   = (string)($weight * (int)\array_pop($data));
            $sum   += $prod{0} + (isset($prod{1}) ? $prod{1} : 0);
            $weight = $weight == 2 ? 1 : 2;
        }
        
        $mod = ($sum % 10);
        return $mod == 0 ? 0 : 10 - $mod;
    }
}

class BancoException extends Exception {}
