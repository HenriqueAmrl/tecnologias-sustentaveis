import { describe, expect, it } from 'vitest';
import { Formatador } from '../../src/helper/formatador';

describe('Formatar CPF', () => {
    it('Formatar CPF somente números', () => {
        const cpf = '12345678901';
        const cpfFormatado = Formatador.formatarCpf(cpf);
        expect(cpfFormatado).toBe('123.456.789-01');
    });
    it('Formatar CPF já formatado', () => {
        const cpf = '123.456.789-01';
        const cpfFormatado = Formatador.formatarCpf(cpf);
        expect(cpfFormatado).toBe('123.456.789-01');
    });
});

describe('Formatar Data', () => {
    it('Formatar data', () => {
        const data = new Date('2021-12-31');
        const dataFormatada = Formatador.formatarData(data);
        expect(dataFormatada).toBe('31/12/2021');
    });
});

describe('Formatar Data e Hora', () => {
    it('Formatar data e hora', () => {
        const data = new Date('2021-12-31T23:59:59');
        const dataFormatada = Formatador.formatarDataHora(data);
        expect(dataFormatada).toBe('31/12/2021 23:59:59');
    });
});

describe('Formatar Dinheiro', () => {
    it('Formatar dinheiro com valor positivo', () => {
        const valor = 1234.56;
        const valorFormatado = Formatador.formatarDinheiro(valor);
        expect(valorFormatado).toBe('R$ 1.234,56');
    });
    it('Formatar dinheiro com valor negativo', () => {
        const valor = -1234.56;
        const valorFormatado = Formatador.formatarDinheiro(valor);
        expect(valorFormatado).toBe('-R$ 1.234,56');
    });
    it('Formatar dinheiro com valor zero', () => {
        const valor = 0;
        const valorFormatado = Formatador.formatarDinheiro(valor);
        expect(valorFormatado).toBe('R$ 0,00');
    });
    it('Formatar dinheiro com valor inteiro', () => {
        const valor = 1234;
        const valorFormatado = Formatador.formatarDinheiro(valor);
        expect(valorFormatado).toBe('R$ 1.234,00');
    });
    it('Formatar dinheiro com vários decimais', () => {
        const valor = 10.44444444;
        const valorFormatado = Formatador.formatarDinheiro(valor);
        expect(valorFormatado).toBe('R$ 10,44');
    });
    it('Formatar dinheiro somente centavos', () => {
        const valor = 0.01;
        const valorFormatado = Formatador.formatarDinheiro(valor);
        expect(valorFormatado).toBe('R$ 0,01');
    });
    it('Formatar dinheiro com centavos zerados', () => {
        const valor = 1234.00;
        const valorFormatado = Formatador.formatarDinheiro(valor);
        expect(valorFormatado).toBe('R$ 1.234,00');
    });
});