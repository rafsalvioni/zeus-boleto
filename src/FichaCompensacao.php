<?php

namespace Zeus\Boleto;

use Zeus\Barcode\Renderer\FpdfRenderer;

class FichaCompensacao
{
    public static function gerar(Boleto $boleto, \FPDF $pdf = null, $comCanhoto = false)
    {
        if (!$pdf) {
            $pdf = new \FPDF();
            $pdf->AddPage();
        }
        
        $pdf->SetFont('Arial');
        $pdf->SetDrawColor(0, 0, 0);
        
        self::desenharTemplate($pdf);
        self::desenharBarcode($boleto, $pdf);
        
        return $pdf;
    }
    
    private static function desenharTemplate(\FPDF $pdf)
    {
        $y = $pdf->GetY();
        $x = $pdf->GetX();
        
        $pdf->SetFontSize(7);
        
        $pdf->Rect($x, $y + 7, 190, 115);
        $pdf->Rect(60, $y, 0, 7);
        $pdf->Rect(80, $y, 0, 7);
        
        $pdf->Text($x + 2, $y + 10, 'Local de Pagamento');
        $pdf->Text($x + 142, $y + 10, 'Vencimento');
        $pdf->Rect($x, $y + 17, 190, 0);
        
        $pdf->Text($x + 2, $y + 20, 'Cedente');
        $pdf->Text($x + 142, $y + 20, \utf8_decode('Agência / Cod. Cedente'));
        $pdf->Rect($x, $y + 27, 190, 0);
        
        $pdf->Text($x + 2, $y + 30, 'Data do Documento');
        $pdf->Text($x + 142, $y + 30, \utf8_decode('Nosso número'));
        $pdf->Rect($x, $y + 37, 190, 0);
        
        $pdf->Text($x + 2, $y + 40, 'Carteira');
        $pdf->Text($x + 142, $y + 40, '(=) Valor do documento');
        $pdf->Rect($x, $y + 47, 190, 0);
        
        $pdf->Rect($x, $y + 97, 190, 0);
        $pdf->Text($x + 2, $y + 50, \utf8_decode('Instruções (texto de responsabilidade do cedente)'));
        
        $pdf->Rect($x + 140, $y + 7, 0, 90);
        $pdf->Text($x + 2, $y + 100, 'Sacado');
        $pdf->Text($x + 142, $y + 90, '(=) Valor cobrado');
        
        $pdf->Rect($x + 140, $y + 57, 50, 0);
        $pdf->Text($x + 142, $y + 50, '(-) Desconto');
        
        $pdf->Rect($x + 140, $y + 67, 50, 0);
        $pdf->Text($x + 142, $y + 60, '(-) Abatimento');
        
        $pdf->Rect($x + 140, $y + 77, 50, 0);
        $pdf->Text($x + 142, $y + 70, '(+) Mora / Multa');
        
        $pdf->Rect($x + 140, $y + 87, 50, 0);
        $pdf->Text($x + 142, $y + 80, \utf8_decode('(+) Outros acréscimos'));
        
        $y += 122;
        $pdf->SetY($y);
        
        $pdf->Text($x + 132, $y + 4, \utf8_decode('------------------- Autenticação Mecânica -------------------'));
        $pdf->SetFontSize(9);
        $pdf->Text($x + 155, $y + 15, \utf8_decode('Ficha de Compensação'));
    }
    
    private static function desenharBarcode(Boleto $boleto, \FPDF $pdf)
    {
        $bc     = $boleto->getCodigoBarras();
        $bc->quietZone = 10;
        $render = new FpdfRenderer();
        $x      = $pdf->GetX();
        $y      = $pdf->GetY();
        $render->offsetTop  = (int)$render->convertValueTo($y + 2, 'px');
        $render->offsetLeft = (int)$render->convertValueTo($x, 'px');
        $render->setResource($pdf);
        $bc->draw($render);
    }
}
