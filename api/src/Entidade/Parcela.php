<?php

namespace Asf\Api\Entidade;

class Parcela {
    const STATUS_EM_ABERTO = 1;
    const STATUS_EM_ATRASO = 2;
    const STATUS_PAGA = 3;

    public ?int $id = null;
    public int $numero;
    public string $dataVencimento;
    public float $valor;
    public ?Emprestimo $emprestimo = null;
}
