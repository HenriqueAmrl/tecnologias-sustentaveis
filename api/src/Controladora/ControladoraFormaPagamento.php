<?php

namespace Asf\Api\Controladora;

use Asf\Api\Entidade\FormaPagamento;
use Asf\Api\Entidade\Parcela;
use Asf\Api\Repositorio\RepositorioFormaPagamento;
use Asf\Api\Servico\ServicoFormaPagamento;
use Asf\Api\Visao\VisaoFormaPagamento;

class ControladoraFormaPagamento {
    private RepositorioFormaPagamento $repositorio;
    private VisaoFormaPagamento $visao;
    private ServicoFormaPagamento $servico;

    public function __construct(RepositorioFormaPagamento $repositorio, VisaoFormaPagamento $visao, ServicoFormaPagamento $servico) {
        $this->repositorio = $repositorio;
        $this->visao = $visao;
        $this->servico = $servico;
    }

    public function obterTodos() {
        $formaPagamento = $this->repositorio->obterTodos();
        $this->visao->listarFormasPagamento($formaPagamento);
    }

    public function simularParcelas($id, $corpoRequisicao) {
        $formaPagamento = $this->repositorio->obterComId($id);
        $parcelas = $this->servico->calcularParcelas($formaPagamento, $corpoRequisicao);
        $this->visao->exibirParcelas($parcelas);
    }
}
