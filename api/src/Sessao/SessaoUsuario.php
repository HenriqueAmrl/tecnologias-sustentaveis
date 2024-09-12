<?php

namespace Asf\Api\Sessao;

use Asf\Api\Dto\UsuarioParaSessao;

interface SessaoUsuario {
    public function registrarUsuario(UsuarioParaSessao $usuario): void;

    public function removerUsuario(): void;

    public function obterUsuario(): ?UsuarioParaSessao;
}
