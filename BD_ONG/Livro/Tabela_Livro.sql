create table Livro (
    Cod_Livro int not null,
    Titulo varchar(50) not null,
    Genero varchar(50) not null,
    Nome_Autor varchar(50) not null,
    Data_Lancamento varchar(12),
    Data_Registro varchar(12),
    Quantidade int not null,
    Num_Prateleira char(2),
    Foto_Livro longblob,
    
    constraint PK_Livro primary key (Cod_Livro)
);
    