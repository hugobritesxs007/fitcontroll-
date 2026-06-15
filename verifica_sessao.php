<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?acesso=negado');
    exit;
}

require_once 'conexao.php';

$stmt = $pdo->prepare('SELECT nome FROM usuarios WHERE id = :id LIMIT 1');
$stmt->execute([':id' => $_SESSION['usuario_id']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$usuario_nome = htmlspecialchars($row['nome']);
$partes_nome = explode(' ', $usuario_nome);
$usuario_iniciais = strtoupper(
    substr($partes_nome[0], 0, 1) .
    (isset($partes_nome[1]) ? substr($partes_nome[1], 0, 1) : '')
);