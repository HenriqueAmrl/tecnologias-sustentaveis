export class Formatador {
    static formatarCpf(cpf: string): string {
        return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    }
    static formatarData(data: Date): string {
        return data.toLocaleDateString('pt-BR', { timeZone: 'UTC' });
    }
    static formatarDataHora(data: Date): string {
        return data.toLocaleDateString('pt-BR') + ' ' + data.toLocaleTimeString('pt-BR');
    }
    static formatarDinheiro(valor: number): string {
        return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }
}