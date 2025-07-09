create table Cliente (
    Cod_Cliente int not null,
    Tipo_Cliente varchar(15) not null,
    Nome varchar(50) not null,
    CPF varchar(15) not null,
    Sexo char(2) not null,
    Nome_Responsavel varchar(50),
    Escolaridade varchar(40),
    Telefone varchar(20) not null,
    RG varchar(15) not null,
    Data_Nascimento date not null,
    CEP varchar(20) not null,
    UF char(2) not null,
    Cidade varchar(30) not null,
    Bairro varchar(30) not null,
    Rua varchar(40) not null,
    Num_Residencia  int not null,
    Estado_Civil varchar(20) not null,
    Email varchar(50) not null,
    Foto_Cliente longblob,
    
    constraint PK_Cliente primary key (Cod_Cliente)
);
    