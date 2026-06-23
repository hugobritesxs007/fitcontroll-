<?php
// conexao.php
$host = 'sql303.infinityfree.com';
$dbname = 'if0_42247780_fitcontrol';
$user = 'if0_42247780';
$pass = 'Og3CaW8OjpJ4kZ';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>