<?php

namespace Asf\Api\Sessao;

use Asf\Api\Dto\UsuarioParaSessao;

class SessaoUsuarioEmArquivo implements SessaoUsuario {
    public function registrarUsuario(UsuarioParaSessao $usuario): void {
        $this->iniciarSessao(true);
        $_SESSION['usuario'] = $usuario;
    }

    public function removerUsuario(): void {
        $this->iniciarSessao();
        unset($_SESSION['usuario']);
    }

    public function obterUsuario(): ?UsuarioParaSessao {
        $this->iniciarSessao();
        return $_SESSION['usuario'] ?? null;
    }

    private function iniciarSessao(bool $rotacionarId = false): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_name('sid');
            session_set_cookie_params(3600, '/', null, false, true);
            session_start();
        }

        if ($rotacionarId) {
            session_regenerate_id(true);
        }
    }
}
