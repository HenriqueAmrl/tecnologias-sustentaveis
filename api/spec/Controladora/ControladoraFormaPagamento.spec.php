<?php

namespace Asf\Api\Tests;

use Asf\Api\Controladora\ControladoraFormaPagamento;
use Asf\Api\Repositorio\RepositorioFormaPagamento;
use Asf\Api\Servico\ServicoFormaPagamento;
use Asf\Api\Tests\Helper\BancoTeste;
use Asf\Api\Visao\VisaoFormaPagamento;
use Kahlan\Arg;
use Kahlan\Plugin\Double;

describe('Obter todos', function() {
    beforeEach(function() {
        $bancoTeste = new BancoTeste();
        $bancoTeste->recriarBanco();
        $this->pdo = $bancoTeste->obterPDO();
        $this->visaoDouble = Double::instance(['implements' => VisaoFormaPagamento::class]);
        $this->controladora = new ControladoraFormaPagamento(
            new RepositorioFormaPagamento($this->pdo),
            $this->visaoDouble,
            new ServicoFormaPagamento()
        );
    });

    it('Verifica se o retorno está vazio', function() {
        expect($this->visaoDouble)->toReceive('listarFormasPagamento')->once()->with(Arg::toHaveLength(4));
        $this->controladora->obterTodos();
    });
});

describe('Simular parcelas', function() {
    beforeEach(function() {
        $bancoTeste = new BancoTeste();
        $bancoTeste->recriarBanco();
        $this->pdo = $bancoTeste->obterPDO();
        $this->visaoDouble = Double::instance(['implements' => VisaoFormaPagamento::class]);
        $this->controladora = new ControladoraFormaPagamento(
            new RepositorioFormaPagamento($this->pdo),
            $this->visaoDouble,
            new ServicoFormaPagamento()
        );
    });

    it('Simular com dados válidos', function() {
        $idFormaPagamento = 3;
        $dadosSimulacao = ['valor' => 1000];

        expect($this->visaoDouble)->toReceive('exibirParcelas')->once()->with(Arg::toHaveLength(6));

        $this->controladora->simularParcelas($idFormaPagamento, $dadosSimulacao);
    });
});
