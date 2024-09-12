<?php

namespace Asf\Api\Dto\Relatorio;

class MetricaEmprestimo {
    public string $data;
    public float $valor;

    public function __construct(string $data, float $valor) {
        $this->data = $data;
        $this->valor = $valor;
    }
}
