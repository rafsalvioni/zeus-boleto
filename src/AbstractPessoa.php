<?php

namespace Zeus\Boleto;

/**
 * Description of AbstractPessoa
 *
 * @author rafaelsalvioni
 */
abstract class AbstractPessoa
{
    protected $nome;
    protected $documento;
    protected $pf;
    
    public function __construct($nome, $documento, $pf)
    {
        $this->nome      = Utils::normalizaLinha($nome);
        $this->documento = Utils::normalizaLinha($documento);
        $this->pf        = (bool)$pf;
    }
    
    public function getNome()
    {
        return $this->nome;
    }

    public function getDocumento()
    {
        return $this->documento;
    }

    public function isPf()
    {
        return $this->pf;
    }
}
