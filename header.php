<?php
// ============================================================
// header.php — FitControl SGPAV
// Cabeçalho reutilizável: sidebar + topbar
//
// USO NAS PÁGINAS:
//   <?php
//     require_once 'verifica_sessao.php'; // SEMPRE antes do header
//     include_once 'header.php';
//   ?>
//
// Variáveis disponíveis após incluir verifica_sessao.php:
//   $usuario_nome     → Nome completo do usuário logado
//   $usuario_iniciais → Iniciais para o avatar (ex.: "AF")
//
// Como destacar o item ativo no menu lateral:
//   Defina $pagina_ativa ANTES de incluir este header. Ex.:
//   $pagina_ativa = 'produtos';
//   Se não definida, nenhum item fica ativo.
// ============================================================

// Garante que a variável existe mesmo que não tenha sido definida na página
$pagina_ativa = $pagina_ativa ?? '';

/**
 * nav_item() — Gera um item de navegação do menu lateral.
 * Adiciona a classe 'active' e o atributo aria-current caso
 * o item corresponda à página atual.
 *
 * @param string $id    Identificador único da seção (ex: 'dashboard')
 * @param string $emoji Emoji usado como ícone visual
 * @param string $label Texto do item de menu
 * @param string $badge (Opcional) Conteúdo do badge de notificação
 */
function nav_item(string $id, string $emoji, string $label, string $badge = ''): void {
    global $pagina_ativa;
    $ativo  = ($pagina_ativa === $id) ? ' active' : '';
    $aria   = ($pagina_ativa === $id) ? ' aria-current="page"' : '';
    $badgeHtml = $badge
        ? '<span class="nav-badge" id="badge-' . $id . '" aria-label="' . $badge . ' itens">' . $badge . '</span>'
        : '';
    echo <<<HTML
    <a class="nav-item{$ativo}" href="{$id}.php"{$aria}>
        <span class="nav-icon" aria-hidden="true">{$emoji}</span>
        <span class="nav-label">{$label}</span>
        {$badgeHtml}
    </a>
    HTML;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!--
        Título dinâmico: exibe o nome do usuário logado na barra do navegador.
        REQUISITO: "Mostre o nome do usuário logado na barra de título da página."
    -->
    <title>FitControl — <?= $usuario_nome ?></title>

    <!-- Fontes externas -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- CSS unificado do projeto -->
    <link rel="stylesheet" href="style.css" />
</head>
<body>

<!-- ══════════════════════════════════════════
     APLICAÇÃO PRINCIPAL
     ══════════════════════════════════════════ -->
<div id="app" class="app">

    <!-- Overlay escuro no mobile quando a sidebar está aberta -->
    <div id="sidebar-overlay" class="sidebar-overlay" aria-hidden="true"></div>

    <!-- ══════════ SIDEBAR / MENU LATERAL ══════════ -->
    <aside id="sidebar" class="sidebar" aria-label="Menu de navegação principal">

        <!-- Logo / marca do sistema -->
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon" aria-hidden="true">
                <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" width="22" height="22">
                    <path d="M24 8C24 8 12 17 12 26C12 32.627 17.373 38 24 38C30.627 38 36 32.627 36 26C36 17 24 8 24 8Z"
                          fill="white" fill-opacity="0.25" stroke="white" stroke-width="1.5"/>
                    <path d="M15 26H20L22 21L24 34L26 18L28 26H33"
                          stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <div class="sidebar-brand-name">Fit<em>Control</em></div>
                <div class="sidebar-brand-sub">SGPAV System</div>
            </div>
        </div>

        <!-- Navegação principal -->
        <nav class="sidebar-nav" aria-label="Seções do sistema">

            <div class="nav-group-label">Visão Geral</div>
            <?php nav_item('dashboard', '📊', 'Dashboard') ?>
            <?php nav_item('sobre',     'ℹ️',  'Sobre o Sistema') ?>

            <div class="nav-group-label">Estoque</div>
            <?php nav_item('produtos', '🥛', 'Produtos') ?>
            <?php nav_item('lotes',    '📦', 'Lotes') ?>
            <?php nav_item('alertas',  '⚠️',  'Alertas', '4') ?>

            <div class="nav-group-label">Comercial</div>
            <?php nav_item('clientes',  '👥', 'Cadastro de Clientes') ?>
            <?php nav_item('vendas',    '🛒', 'Vendas') ?>
            <?php nav_item('cupons',    '🎟️', 'Cupons') ?>
            <?php nav_item('relatorios','📈', 'Relatórios') ?>

        </nav>

        <!-- Rodapé da sidebar: info do usuário + botão de logout -->
        <footer class="sidebar-footer">
            <div class="sidebar-user">
                <!-- Avatar com as iniciais do usuário logado -->
                <div class="avatar avatar-md" aria-hidden="true"><?= $usuario_iniciais ?></div>
                <div class="sidebar-user-info">
                    <!-- Nome buscado do banco via verifica_sessao.php -->
                    <div class="sidebar-user-name"><?= $usuario_nome ?></div>
                    <div class="sidebar-user-role">Administrador</div>
                </div>
            </div>
            <!-- Botão de logout: aponta para logout.php que destrói a sessão -->
            <a href="logout.php" class="btn-logout" role="button"
               onclick="return confirm('Deseja realmente sair do sistema?')">
                <i class="fa fa-right-from-bracket" aria-hidden="true"></i> Sair
            </a>
        </footer>

    </aside><!-- /sidebar -->

    <!-- ══════════ CONTEÚDO PRINCIPAL ══════════ -->
    <div class="main-wrapper">

        <!-- TOPBAR (barra de topo) -->
        <header class="topbar" role="banner">
            <!-- Hambúrguer mobile -->
            <button class="topbar-hamburger" id="hamburger" type="button"
                    aria-label="Abrir ou fechar menu" aria-expanded="false"
                    aria-controls="sidebar">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </button>

            <!-- Breadcrumb / título da página atual -->
            <div class="topbar-breadcrumb" id="topbar-title" aria-live="polite">
                <?= ucfirst($pagina_ativa ?: 'Dashboard') ?>
            </div>

            <div class="topbar-actions">
                <!-- Botão de alternância de tema claro/escuro -->
                <button class="topbar-icon-btn" id="btn-tema" type="button"
                        onclick="toggleTheme()" title="Alternar tema"
                        aria-label="Alternar tema claro ou escuro">🌙</button>

                <!-- Botão de alertas de vencimento -->
                <a href="alertas.php" class="topbar-icon-btn" title="Alertas" aria-label="Ver alertas de validade">
                    <i class="fa fa-bell" aria-hidden="true"></i>
                    <span class="notif-badge" id="notif-dot" aria-hidden="true"></span>
                </a>

                <!-- Pill com nome e avatar do usuário logado -->
                <div class="topbar-user-pill">
                    <div class="avatar avatar-sm" aria-hidden="true"><?= $usuario_iniciais ?></div>
                    <div>
                        <!-- Nome consultado do banco de dados (não da sessão) -->
                        <div class="topbar-user-name"><?= $usuario_nome ?></div>
                        <div class="topbar-user-role">Administrador</div>
                    </div>
                </div>
            </div>
        </header><!-- /topbar -->

        <!-- O conteúdo específico de cada página começa aqui -->
        <main class="main-content" id="main-content">
