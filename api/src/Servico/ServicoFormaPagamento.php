<?php

namespace Asf\Api\Servico;

use Asf\Api\Entidade\FormaPagamento;
use Asf\Api\Entidade\Parcela;

class ServicoFormaPagamento {
    /**
     * @return Parcela[]
     */
    public function calcularParcelas(FormaPagamento $formaPagamento, $dados) : array {
        $numeroParcelas = $formaPagamento->numeroParcelas;
        $juros = ($formaPagamento->juros) / 100;
        $valorEmprestimo = (float)$dados['valor'];
        $valorComJuros = $valorEmprestimo + $valorEmprestimo * $juros;

        $dataAtual = date('Y-m-d');

        $parcelas = [];

        $valorParcela = bcdiv((string) ($valorComJuros/$numeroParcelas), (string) 1, 2);
        $somaParcela = $valorParcela * $numeroParcelas;
        $resto = round($valorComJuros - $somaParcela, 2) * 100;

        for ( $i = 1; $i <= $numeroParcelas; $i++ ) {
            $dataVencimento = date( 'Y-m-d', strtotime("+" . $i*30 . " days", strtotime($dataAtual)) );

            $valorAdicional = 0;
            if( $resto ) {
                $valorAdicional = 0.01;
                $resto --;
            }

            $parcela = new Parcela();
            $parcela->numero =  $i;
            $parcela->dataVencimento =  $dataVencimento;
            $parcela->valor =  $valorParcela + $valorAdicional;

            $parcelas[] = $parcela;
        }

        return $parcelas;
    }
}