<?php

namespace Asf\Api\Visao;

use Asf\Api\Base\VisaoEmApi;
use Asf\Api\Dto\Credenciais;
use Asf\Api\Dto\UsuarioParaSessao;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class VisaoAutenticacaoEmApi extends VisaoEmApi implements VisaoAutenticacao {
    public Request $req;
    public Response $res;

    public function __construct(Request $req, Response $res) {
        $this->req = $req;
        $this->res = $res;
    }

    public function obterCredenciais(): ?Credenciais {
        $dados = $this->req->getParsedBody();
        if (!$dados['login'] || !$dados['senha']) {
            return null;
        }
        
        return new Credenciais($dados['login'], $dados['senha']);
    }

    public function exibirLoginComSucesso(UsuarioParaSessao $usuario): void {
        $this->res = $this->sucesso($this->res, $usuario);
    }

    public function exibirErro(string $mensagem): void {
        $this->res = $this->erro($this->res, $mensagem);
    }

    public function exibirLogoutComSucesso(): void {
        $this->res = $this->res->withStatus(204);
    }
}
