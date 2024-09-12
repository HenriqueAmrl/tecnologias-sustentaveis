<?php

namespace Asf\Api\Controladora;

use Asf\Api\Entidade\Cliente;
use Asf\Api\Repositorio\RepositorioCliente;
use Asf\Api\Visao\VisaoCliente;

class ControladoraCliente {
    private RepositorioCliente $repositorio;
    private VisaoCliente $visao;

    public function __construct(RepositorioCliente $repositorio, VisaoCliente $visao) {
        $this->repositorio = $repositorio;
        $this->visao = $visao;
    }

    public function obterTodos() {
        $filtros = $this->visao->obterFiltros();
        $clientes = $this->repositorio->obterTodos($filtros);
        $this->visao->listarClientes($clientes);
    }

    public function cadastrar($dados) :void {

        $erros = [];
        $validacoes = [
            'nome' => [
                'descricao' => 'nome',
                'min' => 5,
                'max' => 100
            ],
            'dataNascimento' => [
                'descricao' => 'data de nascimento',
                'min' => 10,
                'max' => 10
            ],
            'cpf' => [
                'descricao' => 'CPF',
                'min' => 11,
                'max' => 11
            ],
            'telefone' => [
                'descricao' => 'telefone',
                'min' => 10,
                'max' => 15
            ],
            'email' => [
                'descricao' => 'e-mail',
                'min' => 5,
                'max' => 100
            ],
            'endereco' => [
                'descricao' => 'endereço',
                'min' => 5,
                'max' => 100
            ]
        ];

        foreach ($validacoes as $campo => $validacao) {
            $dado = $dados[$campo];
            if (mb_strlen($dado) > $validacao['max']) {
                $erros[] = "O campo {$validacao['descricao']} deve ter no máximo {$validacao['max']} caracteres.";
            }
            if (mb_strlen($dado) < $validacao['min']) {
                $erros[] = "O campo {$validacao['descricao']} deve ter no mínimo {$validacao['min']} caracteres.";
            }
        }

        $dataNascimento = $dados['dataNascimento'];
        $dataAtual = date('Y-m-d');

        $clienteJaCadastrado = $this->repositorio->obterComCpf($dados['cpf']);
        if ($clienteJaCadastrado) {
            $erros[] = 'O CPF informado já está cadastrado para outro cliente.';
        }

        if ($dados['dataNascimento'] && $dataNascimento >= $dataAtual) {
            $erros[] = 'Informe uma data de nascimento válida.';
        }

        if ($erros) {
            $this->visao->exibirErro(implode(' ', $erros));
            return;
        }

        $cliente = new Cliente();
        $cliente->nome = $dados['nome'];
        $cliente->cpf = $dados['cpf'];
        $cliente->dataNascimento = $dataNascimento;
        $cliente->telefone = $dados['telefone'];
        $cliente->email = $dados['email'];
        $cliente->endereco = $dados['endereco'];

        $this->repositorio->salvar($cliente);
        $this->visao->exibirSucessoClienteCadastrado($cliente);
    }
}
