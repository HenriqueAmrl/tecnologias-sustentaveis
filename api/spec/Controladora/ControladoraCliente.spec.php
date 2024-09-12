<?php

namespace Asf\Api\Tests;

use Asf\Api\Controladora\ControladoraCliente;
use Asf\Api\Repositorio\RepositorioCliente;
use Asf\Api\Tests\Helper\BancoTeste;
use Asf\Api\Visao\VisaoCliente;
use DateTime;
use Kahlan\Plugin\Double;

describe('Cadastrar', function() {
    beforeEach(function() {
        $bancoTeste = new BancoTeste();
        $bancoTeste->recriarBanco();
        $this->pdo = $bancoTeste->obterPDO();
        $this->visao = Double::instance(['implements' => VisaoCliente::class]);
        $this->controladora = new ControladoraCliente(
            new RepositorioCliente($this->pdo),
            $this->visao
        );
    });

    it('Cadastro com sucesso', function() {
        expect($this->visao)->toReceive('exibirSucessoClienteCadastrado')->once();
        $this->controladora->cadastrar([
            'nome' => 'Fulano de Tal',
            'cpf' => '98765432100',
            'dataNascimento' => '1990-01-01',
            'telefone' => '12345678901',
            'email' => 'teste@teste.com',
            'endereco' => 'Rua Teste, 123'
        ]);
    });

    it('Cadastro com CPF já existente', function() {
        expect($this->visao)->toReceive('exibirErro')->with('O CPF informado já está cadastrado para outro cliente.')->once();
        $this->controladora->cadastrar([
            'nome' => 'Fulano de Tal',
            'cpf' => '95201479774',
            'dataNascimento' => '1990-01-01',
            'telefone' => '12345678901',
            'email' => 'teste@teste.com',
            'endereco' => 'Rua Teste, 123'
        ]);
    });

    it('Cadastro com data de nascimento maior que a data atual', function() {
        expect($this->visao)->toReceive('exibirErro')->with('Informe uma data de nascimento válida.')->once();
        $this->controladora->cadastrar([
            'nome' => 'Fulano de Tal',
            'cpf' => '98765432100',
            'dataNascimento' => (new DateTime('tomorrow'))->format('Y-m-d'),
            'telefone' => '12345678901',
            'email' => 'teste@teste.com',
            'endereco' => 'Rua Teste, 123'
        ]);
    });

    it('Cadastro com nome menor que 5 caracteres', function() {
        expect($this->visao)->toReceive('exibirErro')->with('O campo nome deve ter no mínimo 5 caracteres.')->once();
        $this->controladora->cadastrar([
            'nome' => 'Nome',
            'cpf' => '98765432100',
            'dataNascimento' => '1990-01-01',
            'telefone' => '12345678901',
            'email' => 'teste@teste.com',
            'endereco' => 'Rua Teste, 123'
        ]);
    });

    it('Cadastro com nome maior que 100 caracteres', function() {
        expect($this->visao)->toReceive('exibirErro')->with('O campo nome deve ter no máximo 100 caracteres.')->once();
        $this->controladora->cadastrar([
            'nome' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum a dapibus elit. Nullam sed dolor.',
            'cpf' => '98765432100',
            'dataNascimento' => '1990-01-01',
            'telefone' => '12345678901',
            'email' => 'teste@teste.com',
            'endereco' => 'Rua Teste, 123'
        ]);
    });

    it('Cadastro com mais de um erro ao mesmo tempo', function() {
        expect($this->visao)->toReceive('exibirErro')
            ->with('O campo nome deve ter no máximo 100 caracteres. O campo CPF deve ter no máximo 11 caracteres.')
            ->once();
        $this->controladora->cadastrar([
            'nome' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum a dapibus elit. Nullam sed dolor.',
            'cpf' => '98765432100123456789',
            'dataNascimento' => '1990-01-01',
            'telefone' => '12345678901',
            'email' => 'teste@teste.com',
            'endereco' => 'Rua Teste, 123'
        ]);
    });
});
