import './main.css';
import { ControladoraRelatorioEmprestimo } from './controladora-relatorio-emprestimo';

const controladora = new ControladoraRelatorioEmprestimo();
controladora.configurarRelatorio();
controladora.configurarMostrarDados();