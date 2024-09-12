import 'milligram/dist/milligram.min.css';
import './main.css';
import { ControladoraAutenticacao } from './src/autenticacao/controladora-autenticacao';

const c = new ControladoraAutenticacao();
c.validarLogin();
c.configurarLogout();
c.validarPermissao();
