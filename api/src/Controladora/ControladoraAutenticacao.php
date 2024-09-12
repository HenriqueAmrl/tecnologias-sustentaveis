<?php

namespace Asf\Api\Controladora;

use Asf\Api\Servico\ServicoAutenticacao;
use Asf\Api\Visao\VisaoAutenticacao;

class ControladoraAutenticacao {
    private VisaoAutenticacao $visao;
    private ServicoAutenticacao $servico;

    public function __construct(VisaoAutenticacao $visao, ServicoAutenticacao $servico) {
        $this->visao = $visao;
        $this->servico = $servico;
    }

    public function efetuarLogin(): void {
        $dados = $this->visao->obterCredenciais();
        if (!$dados) {
            $this->visao->exibirErro('Dados de login inválidos');
            return;
        }

        $usuario = $this->servico->realizarLogin($dados);
        if (!$usuario) {
            $this->visao->exibirErro('Usuário ou senha inválidos');
            return;
        }

        $this->visao->exibirLoginComSucesso($usuario);
    }

    public function efetuarLogout(): void {
        $this->servico->realizarLogout();
        $this->visao->exibirLogoutComSucesso();
    }
}
