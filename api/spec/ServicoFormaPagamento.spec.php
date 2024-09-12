<?php

namespace Asf\Api\Tests;

use Asf\Api\Entidade\FormaPagamento;
use Asf\Api\Entidade\Parcela;
use Asf\Api\Servico\ServicoFormaPagamento;

describe( 'Calcular Parcelas', function() {

    it( 'Verifica se o retorno da função está vazio', function() {
        $servicoFormaPagamento = new ServicoFormaPagamento();
        $formaPagamento = new FormaPagamento();
        $formaPagamento->numeroParcelas = 3;
        $formaPagamento->juros = 5;
        $dados['valor'] = 3000;
        $resultado = $servicoFormaPagamento->calcularParcelas( $formaPagamento, $dados );
        expect( $resultado )->not->toBeEmpty();
    } );

    it( 'Verifica se o resultado na primeira posição da função é uma instância de parcela', function() {
        $servicoFormaPagamento = new ServicoFormaPagamento();
        $formaPagamento = new FormaPagamento();
        $formaPagamento->numeroParcelas = 3;
        $formaPagamento->juros = 5;
        $dados['valor'] = 3000;
        $resultado = $servicoFormaPagamento->calcularParcelas( $formaPagamento, $dados );
        expect( $resultado[0] )->toBeAnInstanceOf(Parcela::class);
    } );

    it( 'Verifica se o resto da divisão está sendo somado corretamente nas parcelas do empréstimo', function() {
        $servicoFormaPagamento = new ServicoFormaPagamento();
        $formaPagamento = new FormaPagamento();
        $formaPagamento->numeroParcelas = 3;
        $formaPagamento->juros = 10;
        $dados['valor'] = 1000;
        $parcelas = $servicoFormaPagamento->calcularParcelas( $formaPagamento, $dados );

        expect(count($parcelas))->toBe($formaPagamento->numeroParcelas);

        $primeiraParcelaDataEsperada = date('Y-m-d', strtotime('+30 days'));
        $segundaParcelaDataEsperada = date('Y-m-d', strtotime('+60 days'));
        $terceiraParcelaDataEsperada = date('Y-m-d', strtotime('+90 days'));

        expect($parcelas[0]->numero)->toBe(1);
        expect($parcelas[0]->dataVencimento)->toBe($primeiraParcelaDataEsperada);
        expect($parcelas[0]->valor)->toBe(366.67);

        expect($parcelas[1]->numero)->toBe(2);
        expect($parcelas[1]->dataVencimento)->toBe($segundaParcelaDataEsperada);
        expect($parcelas[1]->valor)->toBe(366.67);

        expect($parcelas[2]->numero)->toBe(3);
        expect($parcelas[2]->dataVencimento)->toBe($terceiraParcelaDataEsperada);
        expect($parcelas[2]->valor)->toBe(366.66);
    } );

    it( 'Verifica se o cálculo de empréstimo a vista está sendo aplicado corretamente', function() {
        $servicoFormaPagamento = new ServicoFormaPagamento();
        $formaPagamento = new FormaPagamento();
        $formaPagamento->numeroParcelas = 1;
        $formaPagamento->juros = 0;
        $dados['valor'] = 5000;
        $parcelas = $servicoFormaPagamento->calcularParcelas( $formaPagamento, $dados );

        expect(count($parcelas))->toBe($formaPagamento->numeroParcelas);

        $primeiraParcelaDataEsperada = date('Y-m-d', strtotime('+30 days'));

        expect($parcelas[0]->numero)->toBe(1);
        expect($parcelas[0]->dataVencimento)->toBe($primeiraParcelaDataEsperada);
        expect($parcelas[0]->valor)->toBe((float) 5000);
    } );

} );
?>