<?php

namespace Asf\Api\Visao;

use Asf\Api\Base\VisaoEmApi;
use Asf\Api\Entidade\Emprestimo;
use DateTime;
use DateTimeInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class VisaoEmprestimoEmApi extends VisaoEmApi implements VisaoEmprestimo {
    public Request $req;
    public Response $res;

    public function __construct(Request $req, Response $res) {
        $this->req = $req;
        $this->res = $res;
    }

    public function listarEmprestimos(array $emprestimos): void {
        $this->res = $this->sucesso($this->res, $emprestimos);
    }

    public function exibirErro(string $mensagem): void {
        $this->res = $this->erro($this->res, $mensagem);
    }

    public function exibirSucessoEmprestimoCadastrado(Emprestimo $emprestimo): void {
        $this->res = $this->sucesso($this->res, ['id' => $emprestimo->id], 201);
    }

    public function obterCliente(): int {
        $dados = $this->req->getParsedBody();
        return $dados['cliente']['id'];
    }

    public function obterFormaPagamento(): int {
        $dados = $this->req->getParsedBody();
        return $dados['formaPagamento']['id'];
    }

    public function obterValor(): float {
        $dados = $this->req->getParsedBody();
        return $dados['valor'];
    }

    public function listarParcelasEmprestimo(array $parcelas): void {
        $this->res = $this->sucesso($this->res, $parcelas);
    }

    public function pagoComSucesso(): void {
        $this->res = $this->res->withStatus(204);
    }

    public function obterDataInicial(): ?DateTimeInterface {
        $data = $this->req->getQueryParams()['dataInicial'] ?? null;
        $dateTime = DateTime::createFromFormat('Y-m-d', $data);
        if ($dateTime === false) {
            return null;
        }

        return $dateTime->setTime(0, 0, 0);
    }

    public function obterDataFinal(): ?DateTimeInterface {
        $data = $this->req->getQueryParams()['dataFinal'] ?? null;
        $dateTime = DateTime::createFromFormat('Y-m-d', $data);
        if ($dateTime === false) {
            return null;
        }

        return $dateTime->setTime(23, 59, 59);
    }
}
