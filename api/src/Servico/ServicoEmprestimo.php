<?php

namespace Asf\Api\Servico;

use Asf\Api\Entidade\Parcela;
use Asf\Api\Repositorio\RepositorioEmprestimo;
use Asf\Api\Sessao\SessaoUsuario;

class ServicoEmprestimo {
    private RepositorioEmprestimo $repositorio;
    private SessaoUsuario $sessaoUsuario;

    public function __construct(RepositorioEmprestimo $repositorio, SessaoUsuario $sessaoUsuario) {
        $this->repositorio = $repositorio;
        $this->sessaoUsuario = $sessaoUsuario;
    }

    public function pagarParcela(int $idEmprestimo, int $idParcela) : ?string {
        $parcelas = $this->repositorio->obterParcelasEmprestimo($idEmprestimo);
        if (!$parcelas) {
            return 'Empréstimo não encontrado';
        }
        $parcelaSelecionada = null;
        $existeParcelaAnteriorEmAberto = false;
        foreach ($parcelas as $parcela) {
            if ($parcela->id == $idParcela) {
                $parcelaSelecionada = $parcela;
                break;
            }
            if ($parcela->status == Parcela::STATUS_EM_ABERTO) {
                $existeParcelaAnteriorEmAberto = true;
            }
        }
        if (!$parcelaSelecionada) {
            return 'Parcela não encontrada';
        }
        if ($parcelaSelecionada->status == Parcela::STATUS_PAGA) {
            return 'Parcela já paga';
        }
        if ($existeParcelaAnteriorEmAberto) {
            return 'Existe parcela anterior em aberto';
        }

        $usuario = $this->sessaoUsuario->obterUsuario();
        $sucesso = $this->repositorio->registrarPagamento($idEmprestimo, $idParcela, $usuario->id);
        if (!$sucesso) {
            return 'Erro ao registrar pagamento';
        }

        return null;
    }
}