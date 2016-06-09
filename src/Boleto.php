<?php

namespace Zeus\Boleto;

use Zeus\Barcode\Febraban\Bloqueto;

/**
 * Description of Boleto
 *
 * @author rafaelsalvioni
 */
class Boleto
{
    protected $numero;
    protected $convenio;
    protected $cedente;
    protected $sacado;
    protected $documento;
    protected $dataVencimento;
    protected $dataDocumento;
    protected $dataProcessamento;
    protected $valorDocumento;
    protected $descontos;
    protected $deducoes;
    protected $moraMulta;
    protected $acrescimos;
    protected $localPagamento;
    protected $instrucoes;
    protected $especie;
    protected $aceite;
    
    public function __construct(
        $numero,
        AbstractConvenio $convenio,
        Cedente $cedente,
        Sacado $sacado,
        \DateTime $dataVencimento,
        $valorDocumento = 0.01
    ) {
        $this->numero         = $numero;
        $this->convenio       = $convenio;
        $this->cedente        = $cedente;
        $this->sacado         = $sacado;
        $this->dataVencimento = $dataVencimento;
        $this->dataDocumento  = $this->dataProcessamento = new \DateTime();
        
        $this->setValorDocumento($valorDocumento)
             ->setDescontos(0)
             ->setDeducoes(0)
             ->setMoraMulta(0)
             ->setAcrescimos(0)
             ->setLocalPagamento('Pagável em qualquer agência bancária até o vencimento')
             ->setAceite('N');
    }

    public function setDocumento($documento)
    {
        $this->documento = Utils::normalizaLinha($documento);
        return $this;
    }

    public function setDataVencimento(\DateTime $dataVencimento)
    {
        $this->dataVencimento = $dataVencimento;
        return $this;
    }

    public function setDataDocumento(\DateTime $dataDocumento)
    {
        $this->dataDocumento = $dataDocumento;
        return $this;
    }

    public function setDataProcessamento(\DateTime $dataProcessamento)
    {
        $this->dataProcessamento = $dataProcessamento;
        return $this;
    }

    public function setValorDocumento($valorDocumento)
    {
        $valorDocumento = Utils::normalizaValorMoeda($valorDocumento);
        if ($valorDocumento <= 0) {
            throw new BoletoException('O valor do documento deve ser maior que zero!');
        }
        $this->valorDocumento = $valorDocumento;
        return $this;
    }

    public function setDescontos($descontos)
    {
        $descontos = Utils::normalizaValorMoeda($descontos);
        if ($descontos < 0) {
            throw new BoletoException('O valor do desconto não pode ser menor que zero');
        }
        $this->descontos = $descontos;
        return $this;
    }

    public function setDeducoes($deducoes)
    {
        $deducoes = Utils::normalizaValorMoeda($deducoes);
        if ($deducoes < 0) {
            throw new BoletoException('O valor de deduções não pode ser menor que zero');
        }
        $this->deducoes = $deducoes;
        return $this;
    }

    public function setMoraMulta($moraMulta)
    {
        $moraMulta = Utils::normalizaValorMoeda($moraMulta);
        if ($moraMulta < 0) {
            throw new BoletoException('O valor de mora/multa não pode ser menor que zero');
        }
        $this->moraMulta = Utils::normalizaValorMoeda($moraMulta);
        return $this;
    }

    public function setAcrescimos($acrescimos)
    {
        $acrescimos = Utils::normalizaValorMoeda($acrescimos);
        if ($acrescimos < 0) {
            throw new BoletoException('O valor dos acréscimos não pode ser menor que zero');
        }
        $this->acrescimos = Utils::normalizaValorMoeda($acrescimos);
        return $this;
    }

    public function setLocalPagamento($localPagamento)
    {
        $this->localPagamento = Utils::normalizaLinha($localPagamento);
        return $this;
    }

    public function setInstrucoes($instrucoes)
    {
        $this->instrucoes = $instrucoes;
        return $this;
    }

    public function setEspecie($especie)
    {
        $this->especie = Utils::normalizaLinha($especie);
        return $this;
    }

    public function setAceite($aceite)
    {
        $this->aceite = Utils::normalizaLinha($aceite);
        return $this;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function getConvenio()
    {
        return $this->convenio;
    }

    public function getCedente()
    {
        return $this->cedente;
    }

    public function getSacado()
    {
        return $this->sacado;
    }

    public function getDocumento()
    {
        return $this->documento;
    }

    public function getDataVencimento()
    {
        return $this->dataVencimento;
    }

    public function getDataDocumento()
    {
        return $this->dataDocumento;
    }

    public function getDataProcessamento()
    {
        return $this->dataProcessamento;
    }

    public function getValorDocumento()
    {
        return $this->valorDocumento;
    }

    public function getDescontos()
    {
        return $this->descontos;
    }

    public function getDeducoes()
    {
        return $this->deducoes;
    }

    public function getMoraMulta()
    {
        return $this->moraMulta;
    }

    public function getAcrescimos()
    {
        return $this->acrescimos;
    }
    
    public function getValorCobrado()
    {
        return $this->valorDocumento +
               $this->acrescimos +
               $this->moraMulta -
               $this->descontos -
               $this->deducoes;
    }

    public function getLocalPagamento()
    {
        return $this->localPagamento;
    }

    public function getInstrucoes()
    {
        return $this->instrucoes;
    }

    public function getEspecie()
    {
        return $this->especie;
    }

    public function getAceite()
    {
        return $this->aceite;
    }
    
    public function getCodigoBarras()
    {
        $valorCobrado = $this->getValorCobrado();
        if ($valorCobrado > 99999999.99) {
            throw new BoletoException('O valor cobrado excede o máximo permitido');
        }
        
        $campoLivre = $this->convenio->getCampoLivre($this);
        if (!\preg_match('/^\d{1,25}$/', $campoLivre)) {
            throw new BoletoException('O campo livre deve ter entre 1 e 25 dígitos!');
        }
        
        return Bloqueto::builder(
            $this->convenio->getBanco()->getCodigo(),
            $this->dataVencimento,
            $valorCobrado,
            $campoLivre
        );
    }
}

class BoletoException extends Exception {}