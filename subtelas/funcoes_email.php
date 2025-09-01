<?php
    function gerarToken($length = 48) {
        $bytes = random_bytes($length);
        return rtrim(strtr(base64_encode($bytes), '+/', '-_'), '=');
    }

    function simularEnvioEmailReset($email, $resetLink) {
        $dir = __DIR__ . '/../tmp/emails';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $filePath = $dir . '/email_' . date('Ymd_His') . '_' . preg_replace('/[^a-z0-9_\-\.]/i', '_', $email) . '.txt';
        $content = "Para: {$email}\nAssunto: Redefinição de senha\n\nClique no link para redefinir sua senha:\n{$resetLink}\n\nSe você não solicitou, ignore este email.";
        file_put_contents($filePath, $content);
    }
?>

