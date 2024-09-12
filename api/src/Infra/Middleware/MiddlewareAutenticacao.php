<?php

namespace Asf\Api\Infra\Middleware;

use Asf\Api\Sessao\SessaoUsuario;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class MiddlewareAutenticacao implements MiddlewareInterface {
    private SessaoUsuario $sessao;
    private ?int $permissaoNecessaria;

    public function __construct(SessaoUsuario $sessao, ?int $permissaoNecessaria = null) {
        $this->sessao = $sessao;
        $this->permissaoNecessaria = $permissaoNecessaria;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $usuario = $this->sessao->obterUsuario();
        if ($usuario === null) {
            return new Response(401);
        }

        if ($this->permissaoNecessaria && $usuario->permissao < $this->permissaoNecessaria) {
            return new Response(403);
        }

        return $handler->handle($request);
    }
}
