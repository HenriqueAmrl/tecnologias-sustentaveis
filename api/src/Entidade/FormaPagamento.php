<?php

namespace Asf\Api\Entidade;

class FormaPagamento {
    public ?int $id = null;
    public string $descricao;
    public int $numeroParcelas;
    public int $juros;
}
