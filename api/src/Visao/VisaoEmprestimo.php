<?php

namespace Asf\Api\Visao;

use Asf\Api\Entidade\Emprestimo;
use DateTimeInterface;

interface VisaoEmprestimo {
    public function listarEmprestimos(array $emprestimos): void;

    public function exibirErro(string $mensagem): void;

    public function exibirSucessoEmprestimoCadastrado(Emprestimo $emprestimo): void;

    public function obterCliente(): int;

    public function obterFormaPagamento(): int;

    public function obterValor(): float;

    public function listarParcelasEmprestimo(array $emprestimos): void;

    public function pagoComSucesso(): void;

    public function obterDataInicial(): ?DateTimeInterface;

    public function obterDataFinal(): ?DateTimeInterface;
}
