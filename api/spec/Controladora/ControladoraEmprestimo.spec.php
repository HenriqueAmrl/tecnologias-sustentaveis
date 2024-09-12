<?php

namespace Asf\Api\Tests;

use Asf\Api\Controladora\ControladoraEmprestimo;
use Asf\Api\Repositorio\RepositorioCliente;
use Asf\Api\Repositorio\RepositorioEmprestimo;
use Asf\Api\Repositorio\RepositorioFormaPagamento;
use Asf\Api\Servico\ServicoEmprestimo;
use Asf\Api\Sessao\SessaoUsuario;
use Asf\Api\Tests\Helper\BancoTeste;
use Asf\Api\Visao\VisaoEmprestimo;
use Kahlan\Arg;
use Kahlan\Plugin\Double;

describe('Obter todos', function() {
    beforeEach(function() {
        $bancoTeste = new BancoTeste();
        $bancoTeste->recriarBanco();
        $this->pdo = $bancoTeste->obterPDO();
        $this->visaoDouble = Double::instance([
            'implements' => VisaoEmprestimo::class,
            'fakeMethods' => [
                'obterDataInicial' => function () { return null; },
                'obterDataFinal' => function () { return null; }
            ]
        ]);
        $sessaoUsuario = Double::instance(['implements' => SessaoUsuario::class]);
        $repositorioEmprestimo = new RepositorioEmprestimo($this->pdo);
        $this->controladora = new ControladoraEmprestimo(
            $repositorioEmprestimo,
            new RepositorioCliente($this->pdo),
            new RepositorioFormaPagamento($this->pdo),
            $this->visaoDouble,
            new ServicoEmprestimo($repositorioEmprestimo, $sessaoUsuario)
        );
    });

    it('Verifica se o retorno está vazio', function() {
        expect($this->visaoDouble)->toReceive('listarEmprestimos')->once()->with(Arg::toHaveLength(1));
        $this->controladora->obterTodos();
    });
});

describe('Cadastrar', function() {
    beforeEach(function() {
        $bancoTeste = new BancoTeste();
        $bancoTeste->recriarBanco();
        $this->pdo = $bancoTeste->obterPDO();
        
        $this->visaoDouble = Double::instance(['implements' => VisaoEmprestimo::class]);
        $sessaoUsuario = Double::instance(['implements' => SessaoUsuario::class]);
        $repositorioEmprestimo = new RepositorioEmprestimo($this->pdo);
        $this->controladora = new ControladoraEmprestimo(
            $repositorioEmprestimo,
            new RepositorioCliente($this->pdo),
            new RepositorioFormaPagamento($this->pdo),
            $this->visaoDouble,
            new ServicoEmprestimo($repositorioEmprestimo, $sessaoUsuario)
        );
    });

    it('Cadastrar com forma de pagamento inválida', function() {
        allow($this->visaoDouble)->toReceive('obterFormaPagamento')->andReturn(999);
        allow($this->visaoDouble)->toReceive('obterCliente')->andReturn(1);
        allow($this->visaoDouble)->toReceive('obterValor')->andReturn(1000);
        expect($this->visaoDouble)->toReceive('exibirErro')->once()->with('Forma de pagamento não encontrada');

        $this->controladora->cadastrar();
    });

    it('Cadastrar com cliente inválido', function() {
        allow($this->visaoDouble)->toReceive('obterFormaPagamento')->andReturn(1);
        allow($this->visaoDouble)->toReceive('obterCliente')->andReturn(999);
        allow($this->visaoDouble)->toReceive('obterValor')->andReturn(1000);
        expect($this->visaoDouble)->toReceive('exibirErro')->once()->with('Cliente não encontrado');

        $this->controladora->cadastrar();
    });

    it('Cadastrar com valor abaixo do mínimo', function() {
        allow($this->visaoDouble)->toReceive('obterFormaPagamento')->andReturn(1);
        allow($this->visaoDouble)->toReceive('obterCliente')->andReturn(1);
        allow($this->visaoDouble)->toReceive('obterValor')->andReturn(400);
        expect($this->visaoDouble)->toReceive('exibirErro')->once()->with('Valor do empréstimo incorreto');

        $this->controladora->cadastrar();
    });

    it('Cadastrar com valor acima do máximo', function() {
        allow($this->visaoDouble)->toReceive('obterFormaPagamento')->andReturn(1);
        allow($this->visaoDouble)->toReceive('obterCliente')->andReturn(1);
        allow($this->visaoDouble)->toReceive('obterValor')->andReturn(60000);
        expect($this->visaoDouble)->toReceive('exibirErro')->once()->with('Valor do empréstimo incorreto');

        $this->controladora->cadastrar();
    });

    it('Cadastrar com dados válidos', function() {
        allow($this->visaoDouble)->toReceive('obterFormaPagamento')->andReturn(1);
        allow($this->visaoDouble)->toReceive('obterCliente')->andReturn(1);
        allow($this->visaoDouble)->toReceive('obterValor')->andReturn(1000);
        expect($this->visaoDouble)->toReceive('exibirSucessoEmprestimoCadastrado')->once();

        $this->controladora->cadastrar();
    });
});
