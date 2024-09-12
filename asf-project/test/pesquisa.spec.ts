import { describe, it, expect, vi, beforeAll, afterAll } from 'vitest';
import { VisaoCadastroEmprestimo } from '../src/cadastro-emprestimo/visao-cadastro-emprestimo';

const visaoCadastro = new VisaoCadastroEmprestimo();

// Configuração para usar data fixa
beforeAll(() => {
    const diaAtualFake = new Date('2021-06-01');
    vi.useFakeTimers();
    vi.setSystemTime(diaAtualFake);
});
afterAll(() => {
    vi.useRealTimers();
});

describe('Calcular idade', () => {
    it('Cliente fez aniversário ontem', () => {
        const dataNascimento = '1990-05-31';
        const idade = visaoCadastro.calcularIdade(dataNascimento);
        expect(idade).toBe(31);
    });
    it('Cliente faz aniversário hoje', () => {
        const dataNascimento = '1990-06-01';
        const idade = visaoCadastro.calcularIdade(dataNascimento);
        expect(idade).toBe(31);
    });
    it('Cliente faz aniversário amanhã', () => {
        const dataNascimento = '1990-06-02';
        const idade = visaoCadastro.calcularIdade(dataNascimento);
        expect(idade).toBe(30);
    });
});