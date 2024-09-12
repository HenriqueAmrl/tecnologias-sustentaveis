<?php

namespace Asf\Api\Entidade;
use Asf\Api\Dto\LimiteCredito;

class Cliente {
    public ?int $id = null;
    public string $nome;
    public string $cpf;
    public string $dataNascimento;

    public ?string $telefone;

    public ?string $email;

    public ?string $endereco;

    public ?LimiteCredito $limite;

}
