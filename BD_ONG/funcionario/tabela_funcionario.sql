create table Funcionario (
    Cod_Funcionario int not null,
    Nome varchar(50) not null,
    CPF varchar(15) not null,
    Sexo char(2) not null,
    Escolaridade varchar(40) not null,
    Telefone varchar(20) not null,
    RG varchar(15) not null,
    Data_Nascimento date not null,
    Data_Efetivacao date not null,
    CEP varchar(20) not null,
    UF char(2) not null,
    Cidade varchar(30) not null,
    Bairro varchar(30) not null,
    Rua varchar(40) not null,
    Num_Residencia int not null,
    Usuario varchar(20) not null,
    Senha varchar(20) not null,
    Cargo varchar(20) not null,
    Estado_Civil varchar(20) not null,
    Email varchar(50) not null,
	Foto_Funcionario longblob,
    
    constraint PK_Funcionario primary key (Cod_Funcionario)
);
    