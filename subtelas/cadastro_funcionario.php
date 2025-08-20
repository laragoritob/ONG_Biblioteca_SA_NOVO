<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONG Biblioteca - Sala Arco-íris</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/cadastro_funcionario.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>
        <button class="btn-voltar" onclick="window.history.back()">← Voltar</button>
        <h1>CADASTRO DE FUNCIONÁRIOS</h1>
    </header>
        
    <div class="container">
            <form class="formulario" id="form_pessoal" action="#" method="post" onsubmit="return validaFormulario()">

                <h2>Informações Pessoais:</h2>

                <div class="linha-dupla">
                    <div class="input-group">
                        <p>Nome:</p>
                        <span class="icon">🖋️</span>
                        <input type="text" name="nome" required>
                    </div>

                    <div class="input-group">
                        <p>Data de Nascimento:</p>
                        <span class="icon">📆</span>
                        <input type="date" name="data_nascimento" required min="1925-01-01" max="" id="dataNascimento">
                    </div>
                </div>

                <div class="linha-dupla">
                    <div class="input-group">
                        <p>Sexo:</p>
                        <span class="icon">🧑🏾‍🤝‍👩🏻</span>
                            <select name="sexo" class="custom-select" required>
                                <option value="" disabled selected>Selecione</option>
                                <option value="Feminino">Feminino</option>
                                <option value="Masculino">Masculino</option>
                            </select>
                        </div>
                        

                    <div class="input-group">
                        <p>CPF:</p>
                        <span class="icon">📚</span>
                        <input type="text" name="cpf" maxlength="14" oninput="formatCPF(this)" required>

                    </div>
                </div>

                <div class="linha-dupla">
                    <div class="input-group">
                        <p>RG:</p>
                        <span class="icon">📄</span>
                        <input type="text" name="rg" maxlength="13" oninput="formatRG(this)" required>
                    </div>

                    <div class="input-group">
                        <p>Escolaridade:</p>
                        <span class="icon">🏫</span>
                        <select name="sexo" class="custom-select" required>
                            <option value="" disabled selected>Selecione</option>
                            <option value="EduInfantil">Educação Infantil</option>
                            <option value="EF1">Ensino Fundamental I</option>
                            <option value="EF2">Ensino Fundamental II</option>
                            <option value="EM">Ensino Médio</option>
                            <option value="ES">Ensino Superior</option>
                            <option value="PG">Pós-Graduação</option>
                            <option value="ME">Mestrado</option>
                            <option value="DO">Doutorado</option>
                        </select>
                    </div>
                </div>

                <div class="linha-dupla">
                    <div class="input-group">
                        <p>Estado Civil:</p>
                        <span class="icon">💍</span>
                        <select name="sexo" class="custom-select" required>
                            <option value="" disabled selected>Selecione</option>
                            <option value="Solteiro">Solteiro</option>
                            <option value="Casado">Casado</option>
                            <option value="Divorciado">Divorciado</option>
                            <option value="Viúvo">Viúvo</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <p>E-mail:</p>
                        <span class="icon">📩</span>
                        <input type="email" name="email" required>
                    </div>
                </div>
                <div class="linha-dupla">
                    <div class="input-group">
                        <p>Telefone:</p>
                        <span class="icon">📞</span>
                        <input type="text" name="rg" maxlength="15" oninput="formatTelefone(this)" required>
                    </div>

                    <div class="input-group">
                        <p> Foto do Funcionário: (PNG e JPEG) </p>
                        <span class="icon">📂</span>
                        <!-- Exibição do nome do arquivo selecionado -->
                        <input type="text" name="arquivo" id="arquivo" readonly placeholder="Nenhum arquivo selecionado">
                        <!-- Campo de seleção de arquivo -->
                        <input type="file" id="seletor_arquivo" accept=".png, .jpeg, .jpg" style="display: none;" multiple onchange="atualizarNomeArquivo()">
                    </div>
                    <!-- Botão para abrir o seletor de arquivos -->
                </div>
                <button type="button" class="botao-inline" onclick="document.getElementById('seletor_arquivo').click()">Selecionar Foto</button>

            <h3>Informações Gerais:</h3>
            
                <div class="linha-dupla">
                    <div class="input-group">
                        <p>Cargo:</p>
                        <span class="icon">🤵🏻</span>
                        <select name="cargo" class="custom-select" required>
                            <option value="" disabled selected>Selecione</option>
                            <option value="Gerente">Gerente</option>
                            <option value="Gestor">Gestor</option>
                            <option value="Bibliotecário">Bibliotecário</option>
                            <option value="Recreador">Recreador</option>
                            <option value="Repositor">Repositor</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <p>Data de Efetivação:</p>
                        <span class="icon">📆</span>
                        <input type="date" name="data_efetivacao" min="" max="" id="dataEfetivacao" required>
                    </div>
                </div>
        
            <h3>Endereço:</h3>
            
                <div class="linha-dupla">
                    <div class="input-group">
                            <p>CEP:</p>
                        <div class="cep-container">
                            <span class="icon">📦</span>
                            <input type="text" name="cep" id='cep' maxlength="9" oninput="formatCEP(this)" onblur="buscarCEP(this.value)" required>
                            <button type="button" class="btn-cep-inside" onclick="buscarCEP(document.getElementById('cep').value)">🔎</button>
                        </div>
                        </div>

                    <div class="input-group">
                        <p>Estado:</p>
                        <span class="icon">🗾</span>
                        <input type="text" name="estado" required>
                    </div>
                </div>

                <div class="linha-dupla">
                    <div class="input-group">
                        <p>Cidade:</p>
                        <span class="icon">🌆</span>
                        <input type="text" name="cidade" required>
                    </div>

                    <div class="input-group">
                        <p>Bairro:</p>
                        <span class="icon">🏘️</span>
                        <input type="text" name="bairro" required>
                    </div>
                </div>

                <div class="linha-dupla">
                    <div class="input-group">
                        <p>Rua:</p>
                        <span class="icon">🛣️</span>
                        <input type="text" name="rua" required>
                    </div>

                    <div class="input-group">
                        <p>N° Casa:</p>
                        <span class="icon">🏠</span>
                        <input type="text" name="numcasa" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                    </div>
                </div>

            <h3>Login:</h3>

                <div class="linha-dupla">
                    <div class="input-group">
                        <p>Usuário:</p>
                        <span class="icon">👤</span>
                        <input type="text" name="usuario" id='usuario' required>
                    </div>

                    <div class="input-group">
                        <p>Senha:</p>
                        <span class="icon">🔐</span>
                        <input type="password" name="senha" id="senhaInput" required>
                    </div>
                </div>

                <div class="checkbox-show-senha">
                    <input type="checkbox" id="mostrarSenha">
                    <label for="mostrarSenha">Mostrar senha</label>
                </div>

                <div class="linha-dupla">
                    <div class="links">
                        <button type="submit" class="btn" id="btnCadastrar"> Cadastrar </button>
                    </div>
        
                    <div class="links">
                        <button type="button" class="btn2" onclick="document.getElementById('form_pessoal').reset(); document.getElementById('arquivo').value = '';"> Cancelar </button>
                    </div>
                </div>
            </form>
    </div>
</body>
    <script src="subtelas_javascript/JS_cadastro_funcionario.js"></script>
</html>