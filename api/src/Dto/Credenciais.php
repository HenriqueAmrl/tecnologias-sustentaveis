<?php

namespace Asf\Api\Dto;

class Credenciais {
    public string $login;
    public string $senha;

    public function __construct(string $login, string $senha) {
        $this->login = $login;
        $this->senha = $senha;
    }
}
