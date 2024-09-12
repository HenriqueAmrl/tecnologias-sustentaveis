<?php

namespace Asf\Api\Dto;

class ParcelaListagem {
    public int $id;
    public int $status;
    public float $valor;
    public string $dataVencimento;
    public ?string $dataPagamento;
    public ?string $nomePagador;
}
