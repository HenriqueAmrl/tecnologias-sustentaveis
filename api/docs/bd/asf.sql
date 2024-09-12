CREATE DATABASE asf;

USE asf;

CREATE TABLE cliente (
    id int auto_increment,
    nome varchar(100) not null,
    cpf varchar(11) not null,
    dataNascimento date not null,
    PRIMARY KEY(id)
);

CREATE TABLE formaPagamento (
    id int auto_increment,
    descricao varchar(100) not null,
    numeroParcelas int not null,
    juros int not null,
    PRIMARY KEY(id)
);

CREATE TABLE emprestimo (
    id int auto_increment,
    idCliente int not null,
    idFormaPagamento int not null,
    dataCriacao datetime not null,
    valor decimal(10,2) not null,
    valorTotal decimal(10,2) not null,
    PRIMARY KEY(id),
    FOREIGN KEY(idCliente) REFERENCES cliente(id),
    FOREIGN KEY(idFormaPagamento) REFERENCES formaPagamento(id)
);

CREATE TABLE parcela (
    id int auto_increment,
    idEmprestimo int not null,
    numero int not null,
    dataVencimento date not null,
    valor decimal(10,2) not null,
    PRIMARY KEY(id),
    FOREIGN KEY(idEmprestimo) REFERENCES emprestimo(id)
);

CREATE TABLE usuario (
    id int auto_increment,
    nome varchar(100) not null,
    email varchar(100) not null,
    permissao tinyint unsigned not null,
    senha varchar(100) not null,
    PRIMARY KEY(id)
);

ALTER TABLE cliente ADD telefone VARCHAR(12) AFTER dataNascimento;
ALTER TABLE cliente ADD email VARCHAR(150) AFTER telefone;
ALTER TABLE cliente ADD endereco VARCHAR(200) AFTER email;

ALTER TABLE parcela 
ADD COLUMN dataPagamento datetime null, 
ADD COLUMN idUsuarioPagamento int null,
ADD CONSTRAINT FOREIGN KEY (idUsuarioPagamento) REFERENCES usuario(id);
