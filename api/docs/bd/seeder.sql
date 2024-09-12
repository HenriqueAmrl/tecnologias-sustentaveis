USE asf;

INSERT INTO cliente(id, nome, cpf, dataNascimento) VALUES
(1, 'Maria Eduarda Hottz', '95201479774', '2001-08-12'),
(2, 'Carlos Henrique do Amaral Reis', '68397724621', '2001-04-09'),
(3, 'Ana Luiza da Silva', '12345678901', '1995-12-25'),
(4, 'João Oliveira Santos', '98765432109', '1988-07-17'),
(5, 'Fernanda Souza Lima', '45612378965', '1990-03-08'),
(6, 'Rafael Pereira Costa', '78945612378', '1982-11-02'),
(7, 'Amanda Santos Pereira', '32165498732', '1999-09-15');

INSERT INTO formaPagamento(id, descricao, numeroParcelas, juros) VALUES
(1, 'À vista', 1, 0),
(2, 'Parcelado 3x', 3, 5),
(3, 'Parcelado 6x', 6, 10),
(4, 'Parcelado 10x', 10, 15);

INSERT INTO emprestimo(id, idCliente, idFormaPagamento, dataCriacao, valor, valorTotal) VALUES
(1, 1, 1, '2021-08-12 10:00:00', 1000.00, 1000.00);

INSERT INTO parcela(id, idEmprestimo, numero, dataVencimento, valor) VALUES
(1, 1, 1, '2021-09-12', 1000.00);

INSERT INTO usuario(id, nome, email, permissao, senha) VALUES
(1, 'Thiago', 'thiago@asf.com', 2, '6eecf7626014481c585f8a28d9319784ec98d53946b4734e9aaafe7b28fc37f4bUbG5?PCwhYBT_zKK=OD'),
(2, 'Carlos', 'carlos@asf.com', 1, '6eecf7626014481c585f8a28d9319784ec98d53946b4734e9aaafe7b28fc37f4bUbG5?PCwhYBT_zKK=OD'),
(3, 'Duda', 'duda@asf.com', 1, '6eecf7626014481c585f8a28d9319784ec98d53946b4734e9aaafe7b28fc37f4bUbG5?PCwhYBT_zKK=OD');