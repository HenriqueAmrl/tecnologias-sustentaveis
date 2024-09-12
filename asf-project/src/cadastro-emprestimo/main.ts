import './main.css';

import { ControladoraCadastroEmprestimo } from './controladora-cadastro-emprestimo';

const c = new ControladoraCadastroEmprestimo();
c.configurarBuscaCliente();
c.exibirFormasPagamento();
c.configurarExibicaoParcelas();
c.configurarLimpezaFormulario();
c.configurarCadastroEmprestimo();