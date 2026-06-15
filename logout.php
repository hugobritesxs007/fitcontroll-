<?php
// ============================================================
// logout.php — FitControl SGPAV
// Encerra a sessão do usuário e redireciona para o login
// ============================================================

// Inicia a sessão (obrigatório antes de qualquer operação com sessão)
session_start();

// 1. Apaga todas as variáveis de sessão armazenadas no array $_SESSION
//    Isso remove: usuario_id, usuario_email e qualquer outro dado de sessão
$_SESSION = [];

// 2. Apaga o cookie de sessão do navegador do usuário
//    Sem isso, o cookie ainda ficaria no browser mesmo com a sessão destruída
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),   // Nome do cookie (ex: PHPSESSID)
        '',               // Valor vazio
        time() - 42000,   // Data no passado — força expiração imediata
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// 3. Destroi definitivamente a sessão no servidor
session_destroy();

// 4. Redireciona para a tela de login com mensagem de confirmação via query string
header('Location: login.php?saiu=1');
exit; // Sempre usar exit após header() para parar a execução do script
