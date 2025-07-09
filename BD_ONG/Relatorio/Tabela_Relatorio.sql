create table Relatorio (
    Cod_Relatorio int not null,
    Cod_Funcionario int not null,
    Nome varchar(100) not null,
    Data_Relatorio date not null,
    Tipo_Arquivo varchar(6) not null,
    Arquivo_Selecionado mediumblob,
    
    constraint PK_Relatorio primary key (Cod_Relatorio),
    constraint FK_Relatorio_Funcionario foreign key (Cod_Funcionario) references Funcionario(Cod_Funcionario)
);