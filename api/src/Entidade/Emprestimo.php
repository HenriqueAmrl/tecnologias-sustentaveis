<?php

namespace Asf\Api\Entidade;

class Emprestimo {
    public ?int $id = null;
    public string $dataCriacao;
    public Cliente $cliente;
    public FormaPagamento $formaPagamento;
    public float $valor;
    public float $valorTotal;
}
