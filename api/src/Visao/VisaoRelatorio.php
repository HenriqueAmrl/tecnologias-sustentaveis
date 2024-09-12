<?php

namespace Asf\Api\Visao;

use Asf\Api\Dto\Relatorio\EmprestimosPorPeriodo;
use DateTimeInterface;

interface VisaoRelatorio {
    public function obterDataInicial(): ?DateTimeInterface;

    public function obterDataFinal(): ?DateTimeInterface;

    public function exibirRelatorioEmprestimosPorPeriodo(EmprestimosPorPeriodo $dados): void;

    public function exibirErro(string $mensagem): void;
}
