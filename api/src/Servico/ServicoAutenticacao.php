<?php

namespace Asf\Api\Servico;

use Asf\Api\Dto\Credenciais;
use Asf\Api\Dto\UsuarioParaSessao;
use Asf\Api\Repositorio\RepositorioUsuario;
use Asf\Api\Sessao\SessaoUsuario;

class ServicoAutenticacao {
    private const SAL_SENHA = 'AzFZ#lTU(+sZkFt:UkGXmO=b3J4A.f#9eUXHJmJD';
    private const PIMENTA_SENHA = 'bUbG5?PCwhYBT_zKK=OD';

    private RepositorioUsuario $repositorioUsuario;
    private SessaoUsuario $sessaoUsuario;

    public function __construct(RepositorioUsuario $repositorioUsuario, SessaoUsuario $sessaoUsuario) {
        $this->repositorioUsuario = $repositorioUsuario;
        $this->sessaoUsuario = $sessaoUsuario;
    }

    public function realizarLogin(Credenciais $dados): ?UsuarioParaSessao {
        $usuario = $this->repositorioUsuario->obterPorLogin($dados->login);
        if (!$usuario) {
            return null;
        }

        if (!$this->compararHash($dados->senha, $usuario->senha)) {
            return null;
        }

        $usuarioParaSessao = new UsuarioParaSessao();
        $usuarioParaSessao->id = $usuario->id;
        $usuarioParaSessao->permissao = $usuario->permissao;
        $this->sessaoUsuario->registrarUsuario($usuarioParaSessao);

        return $usuarioParaSessao;
    }

    private function gerarHash(string $senha): string {
        $senha .= self::SAL_SENHA;
        $hash = hash('sha256', $senha);
        
        return $hash . self::PIMENTA_SENHA;
    }

    private function compararHash(string $senha, string $hash): bool {
        return hash_equals($hash, $this->gerarHash($senha));
    }

    public function realizarLogout(): void {
        $this->sessaoUsuario->removerUsuario();
    }
}
