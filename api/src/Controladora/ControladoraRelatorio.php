<?php

namespace Asf\Api\Controladora;

use Asf\Api\Repositorio\RepositorioEmprestimo;
use Asf\Api\Visao\VisaoRelatorio;

class ControladoraRelatorio {
    private RepositorioEmprestimo $repositorio;
    private VisaoRelatorio $visao;

    public function __construct(RepositorioEmprestimo $repositorio, VisaoRelatorio $visao) {
        $this->repositorio = $repositorio;
        $this->visao = $visao;
    }

    public function emprestimosPorPeriodo(): void {
        $inicio = $this->visao->obterDataInicial();
        $fim = $this->visao->obterDataFinal();

        if ($inicio === null || $fim === null) {
            $this->visao->exibirErro('Data inicial e data final são obrigatórias');
            return;
        }
        if ($inicio > $fim) {
            $this->visao->exibirErro('Data inicial não pode ser maior que data final');
            return;
        }

        $dados = $this->repositorio->relatorioPorPeriodo($inicio, $fim);
        $this->visao->exibirRelatorioEmprestimosPorPeriodo($dados);
    }
}
