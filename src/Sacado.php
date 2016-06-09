<?php

namespace Zeus\Boleto;

/**
 * Description of Sacado
 *
 * @author rafaelsalvioni
 */
class Sacado extends AbstractPessoa
{
    protected $endereco;
    protected $cidade;
    protected $uf;

    public function __construct($nome, $documento, $pf, $endereco, $cidade, $uf)
    {
        parent::__construct($nome, $documento, $pf);
        $this->endereco = Utils::normalizaLinha($endereco);
        $this->cidade   = Utils::normalizaLinha($cidade);
        $this->uf       = Utils::normalizaLinha($uf);
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function getCidade()
    {
        return $this->cidade;
    }

    public function getUf()
    {
        return $this->uf;
    }
}
