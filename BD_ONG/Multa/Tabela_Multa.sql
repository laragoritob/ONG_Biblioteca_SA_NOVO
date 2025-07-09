create table Multa (
    Cod_Multa int not null,
    Cod_Cliente int not null,
    Data_Multa date not null,
    Valor_Multa decimal(10,2) not null,
    
    constraint PK_Multa primary key(Cod_Multa),
    constraint FK_Cliente_Multa foreign key (Cod_Cliente) references Cliente(Cod_Cliente)
);