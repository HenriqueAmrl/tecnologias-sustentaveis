<?php

namespace Asf\Api\Dto\Relatorio;

class EmprestimosPorPeriodo {
    /** @var MetricaEmprestimo[] */
    public array $metricas;
    public float $valorTotal;
    public float $valorMedio;

    public function __construct(array $metricas, float $valorTotal, float $valorMedio) {
        $this->metricas = $metricas;
        $this->valorTotal = $valorTotal;
        $this->valorMedio = $valorMedio;
    }
}
