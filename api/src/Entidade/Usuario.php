<?php

namespace Asf\Api\Entidade;

class Usuario {
    public const PERMISSAO_COMUM = 1;
    public const PERMISSAO_GERENTE = 2;

    public ?int $id = null;
    public string $nome;
    public string $email;
    public int $permissao;
    public string $senha;
}
