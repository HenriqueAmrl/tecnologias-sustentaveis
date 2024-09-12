<?php

namespace Asf\Api\Controladora;

use Asf\Api\Entidade\Emprestimo;
use Asf\Api\Repositorio\RepositorioCliente;
use Asf\Api\Repositorio\RepositorioEmprestimo;
use Asf\Api\Repositorio\RepositorioFormaPagamento;
use Asf\Api\Servico\ServicoEmprestimo;
use Asf\Api\Servico\ServicoFormaPagamento;
use Asf\Api\Visao\VisaoEmprestimo;
use DateTime;

class ControladoraEmprestimo {
    private RepositorioEmprestimo $repositorio;
    private RepositorioCliente $repositorioCliente;
    private RepositorioFormaPagamento $repositorioFormaPagamento;
    private VisaoEmprestimo $visao;
    private ServicoEmprestimo $servico;

    const VALOR_MINIMO_EMPRESTIMO = 500;
    const VALOR_MAXIMO_EMPRESTIMO = 50000;

    public function __construct(
        RepositorioEmprestimo $repositorio,
        RepositorioCliente $repositorioCliente,
        RepositorioFormaPagamento $repositorioFormaPagamento,
        VisaoEmprestimo $visao,
        ServicoEmprestimo $servico
    ) {
        $this->repositorio = $repositorio;
        $this->repositorioCliente = $repositorioCliente;
        $this->repositorioFormaPagamento = $repositorioFormaPagamento;
        $this->visao = $visao;
        $this->servico = $servico;
    }

    public function obterTodos() {
        $inicio = $this->visao->obterDataInicial();
        $fim = $this->visao->obterDataFinal();
        
        $emprestimos = $this->repositorio->obterTodos($inicio, $fim);
        $this->visao->listarEmprestimos($emprestimos);
    }

    public function cadastrar(): void {
        $dados = [
            'cliente' => ['id' => $this->visao->obterCliente()],
            'formaPagamento' => ['id' => $this->visao->obterFormaPagamento()],
            'valor' => $this->visao->obterValor()
        ];

        $formaPagamento = $this->repositorioFormaPagamento->obterComId($dados['formaPagamento']['id']);
        if (!$formaPagamento) {
            $this->visao->exibirErro('Forma de pagamento não encontrada');
            return;
        }
        $cliente = $this->repositorioCliente->obterComId($dados['cliente']['id']);
        if (!$cliente) {
            $this->visao->exibirErro('Cliente não encontrado');
            return;
        }
        if ($dados['valor'] < self::VALOR_MINIMO_EMPRESTIMO || $dados['valor'] > self::VALOR_MAXIMO_EMPRESTIMO) {
            $this->visao->exibirErro('Valor do empréstimo incorreto');
            return;
        }
        $servicoFormaPagamento = new ServicoFormaPagamento();
        $parcelas = $servicoFormaPagamento->calcularParcelas($formaPagamento, $dados);
        $valores = array_column($parcelas, 'valor');
        $valorTotal = array_sum($valores);

        $emprestimo = new Emprestimo();
        $emprestimo->formaPagamento = $formaPagamento;
        $emprestimo->cliente = $cliente;
        $emprestimo->dataCriacao = date('Y-m-d H:i:s');
        $emprestimo->valor = $dados['valor'];
        $emprestimo->valorTotal = $valorTotal;

        $this->repositorio->salvar($emprestimo);

        foreach ($parcelas as $parcela) {
            $parcela->emprestimo = $emprestimo;
        }
        $this->repositorio->salvarParcelas($parcelas);
        $this->visao->exibirSucessoEmprestimoCadastrado($emprestimo);
    }

    public function obterComId($id) {
        $emprestimo = $this->repositorio->obterComId($id);
        $this->visao->listarEmprestimos($emprestimo);
    }

    public function obterParcelasDoEmprestimo($id) {
        $parcelas = $this->repositorio->obterParcelasEmprestimo($id);
        $this->visao->listarParcelasEmprestimo($parcelas);
    }

    public function pagarParcela($idEmprestimo, $numeroParcela): void {
        $erro = $this->servico->pagarParcela($idEmprestimo, $numeroParcela);
        if ($erro) {
            $this->visao->exibirErro($erro);
            return;
        }

        $this->visao->pagoComSucesso();
    }
}
