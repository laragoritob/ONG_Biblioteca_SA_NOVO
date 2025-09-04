<?php

// Função para gerar um código numérico de 5 dígitos para recuperação de senha
function gerarCodigoRecuperacao($tamanho = 5) {
    // Gera um número aleatório entre 10000 e 99999 (sempre terá 5 dígitos)
    return strval(rand(10000, 99999));
}

// Função para simular o envio de e-mail com o código de recuperação
function simularEnvioEmail($destinatario, $codigo) {
    // Monta a mensagem do email com o código de recuperação
    $mensagem = "Olá! Seu código de recuperação é: $codigo\n";
    // Formata o registro completo do email
    $registro = "Para: $destinatario\n$mensagem\n----------------------\n";

    // Salva o "e-mail enviado" em um arquivo de texto para testes e simulação
    file_put_contents("emails_simulados.txt", $registro, FILE_APPEND);
}
?>
