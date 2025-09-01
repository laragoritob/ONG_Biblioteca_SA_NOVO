<?php

    // Função para gerar um código numérico de 5 dígitos
    function gerarCodigoRecuperacao($tamanho = 5) {
        // Gera um número aleatório entre 10000 e 99999 (sempre terá 5 dígitos)
        return strval(rand(10000, 99999));
    }

    // Função para simular o envio de e-mail com o código
    function simularEnvioEmail($destinatario, $codigo) {
        $mensagem = "Olá! Seu código de recuperação é: $codigo\n";
        $registro = "Para: $destinatario\n$mensagem\n----------------------\n";

        // Salva o "e-mail enviado" em um arquivo de texto para testes
        file_put_contents("../emails_simulados.txt", $registro, FILE_APPEND);
    }
?>
