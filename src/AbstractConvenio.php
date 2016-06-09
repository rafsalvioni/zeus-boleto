<?php

namespace Zeus\Boleto;

/**
 * Description of AbstractConvenio
 *
 * @author rafaelsalvioni
 */
abstract class AbstractConvenio
{
    /**
     *
     * @var Banco
     */
    protected $banco;
    protected $agencia;
    protected $conta;
    
    public function __construct(Banco $banco, $agencia, $conta)
    {
        $agencia = \str_pad($agencia, 4, '0', \STR_PAD_LEFT);
        if (!\preg_match('/^\d{4}$/', $agencia)) {
            throw new ConvenioException('A agência deve conter até 4 dígitos!');
        }
        
        $this->banco   = $banco;
        $this->agencia = $agencia;
        $this->conta   = Utils::normalizaLinha($conta);
    }

    /**
     * 
     * @return Banco
     */
    public function getBanco()
    {
        return $this->banco;
    }

    public function getAgencia()
    {
        return $this->agencia;
    }

    public function getConta()
    {
        return $this->conta;
    }
    
    public function getAgenciaConta()
    {
        return $this->agencia . ' / ' . $this->conta;
    }
    
    abstract public function getNossoNumero(Boleto $boleto);
    
    abstract public function getCampoLivre(Boleto $boleto);
}

class ConvenioException extends Exception {}