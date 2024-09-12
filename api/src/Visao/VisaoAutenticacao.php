<?php

namespace Asf\Api\Visao;

use Asf\Api\Dto\Credenciais;
use Asf\Api\Dto\UsuarioParaSessao;

interface VisaoAutenticacao {
    public function obterCredenciais(): ?Credenciais;

    public function exibirLoginComSucesso(UsuarioParaSessao $usuario): void;

    public function exibirLogoutComSucesso(): void;

    public function exibirErro(string $mensagem): void;
}
