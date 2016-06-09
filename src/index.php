<?php

require 'vendor/autoload.php';

class Convenio extends Zeus\Boleto\AbstractConvenio
{
    public function getCampoLivre(\Zeus\Boleto\Boleto $boleto) {
        return \str_repeat('0', 25);
    }

    public function getNossoNumero(\Zeus\Boleto\Boleto $boleto) {
        
    }

}
$banco   = new Zeus\Boleto\Banco('001', 'Banco do Brasil');
$cedente = new Zeus\Boleto\Cedente('Rafael', '123456', true);
$sacado  = new Zeus\Boleto\Sacado('Rafael', '945736', false, 'Rua Sei La', 'São Paulo', 'SP');
$convenio = new Convenio($banco, '3571', '16899-8');
$boleto  = new Zeus\Boleto\Boleto(1, $convenio, $cedente, $sacado, new \DateTime(), 14523.6352);

Zeus\Boleto\FichaCompensacao::gerar($boleto)->Output();