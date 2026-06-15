<?php
//require_once 'verifica_sessao.php'; comentado teste
?>
<!DOCTYPE html>
<html lang="pt-BR">

<!--
  ╔═══════════════════════════════════════════════════════╗
  ║  FitControl — SGPAV                                   ║
  ║  Sistema de Gestão de Perecíveis e Alertas de Validade║
  ║  Trabalho 2 — Programação Web — IFES                  ║
  ║  Equipe: Hugo, Isadora, William, Bruno e Lucas        ║
  ╚═══════════════════════════════════════════════════════╝
-->

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="FitControl SGPAV — Sistema de Gestão de Perecíveis e Alertas de Validade." />
  <title>FitControl — <?= $usuario_nome ?? 'Equipe FitControl' ?></title>

  <!-- Fontes externas no head, antes do CSS -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

  <!-- Font Awesome para ícones -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- CSS unificado (base + layout + components + pages) -->
  <link rel="stylesheet" href="style.css" />
</head>

<body>

<!-- ════════════════════════════════════════════════════════
     TELA DE LOGIN
════════════════════════════════════════════════════════ -->
<div id="login-page" class="login-page">

  <!-- Lado esquerdo — apresentação do segmento de negócio -->
  <section class="login-left" aria-label="Apresentação do sistema FitControl">

    <div class="login-brand">
      <div class="login-brand-icon" role="img" aria-label="Logotipo FitControl">
        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" width="40" height="40" aria-hidden="true">
          <path d="M24 8C24 8 12 17 12 26C12 32.627 17.373 38 24 38C30.627 38 36 32.627 36 26C36 17 24 8 24 8Z"
                fill="white" fill-opacity="0.2" stroke="white" stroke-width="1.5"/>
          <path d="M15 26H20L22 21L24 34L26 18L28 26H33"
                stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <div>
        <div class="login-brand-name">Fit<em>Control</em></div>
        <div class="login-brand-sub">Gestão de Bem-Estar · SGPAV</div>
      </div>
    </div>

    <div class="login-headline">
      <h2>Controle inteligente de suplementos e perecíveis</h2>
      <p>
        O FitControl é especializado em lojas de suplementos alimentares e nutrição esportiva.
        Monitore validades, evite perdas e converta produtos próximos ao vencimento em vendas
        com cupons automáticos.
      </p>
      <ul class="login-features" aria-label="Funcionalidades do sistema">
        <li class="login-feature-item">
          <div class="login-feature-icon" aria-hidden="true">📦</div>
          <span><strong>Controle de Lotes</strong> — rastreabilidade do recebimento à venda</span>
        </li>
        <li class="login-feature-item">
          <div class="login-feature-icon" aria-hidden="true">⚠️</div>
          <span><strong>Motor de Alertas</strong> — notificações automáticas de vencimento</span>
        </li>
        <li class="login-feature-item">
          <div class="login-feature-icon" aria-hidden="true">🎟️</div>
          <span><strong>Cupons Anti-Desperdício</strong> — converte perda em venda com desconto</span>
        </li>
        <li class="login-feature-item">
          <div class="login-feature-icon" aria-hidden="true">📊</div>
          <span><strong>Dashboard Financeiro</strong> — visão em tempo real de ativos e perdas</span>
        </li>
        <li class="login-feature-item">
          <div class="login-feature-icon" aria-hidden="true">👥</div>
          <span><strong>Cadastro de Clientes</strong> — histórico de compras e campanhas</span>
        </li>
      </ul>
    </div>

  </section><!-- /login-left -->

  <!-- Lado direito — formulário de autenticação -->
  <section class="login-right" aria-label="Formulário de acesso ao sistema">

    <header class="login-form-header">
      <h1 class="login-form-title">Bem-vindo de volta 👋</h1>
      <p class="login-form-sub">Faça login para acessar o painel de controle</p>
    </header>

    <div class="login-demo-tip" role="note">
      <i class="fa fa-circle-info" aria-hidden="true"></i>
      <span><strong>Demo:</strong> admin@fitcontrol.com &nbsp;/&nbsp; 123456</span>
    </div>

    <div style="display:flex;flex-direction:column;gap:16px;">

      <div class="form-group">
        <label class="form-label" for="login-email">
          <i class="fa fa-envelope" aria-hidden="true"></i> E-mail
        </label>
        <input class="form-input" type="email" id="login-email" name="email"
               placeholder="admin@fitcontrol.com" value="admin@fitcontrol.com"
               autocomplete="email" required />
      </div>

      <div class="form-group">
        <label class="form-label" for="login-senha">
          <i class="fa fa-lock" aria-hidden="true"></i> Senha
        </label>
        <div class="input-password-wrap">
          <input class="form-input" type="password" id="login-senha" name="senha"
                 placeholder="••••••••" value="123456"
                 autocomplete="current-password" required />
          <button class="btn-eye" type="button" onclick="togglePassword()" aria-label="Mostrar ou ocultar senha">
            <i class="fa fa-eye"></i>
          </button>
        </div>
      </div>

      <div class="flex justify-between items-center" style="flex-wrap:wrap;gap:8px;">
        <label class="checkbox-label">
          <input type="checkbox" id="lembrar" name="lembrar" checked />
          <span>Lembrar acesso</span>
        </label>
        <a href="#" class="text-blue text-sm font-600">Esqueci a senha</a>
      </div>

      <div id="login-err" class="form-error hidden" role="alert" aria-live="assertive"></div>

      <button class="btn-login-main" type="button" onclick="doLogin()">
        <i class="fa fa-arrow-right-to-bracket" aria-hidden="true"></i>
        Entrar no sistema
      </button>

    </div>

    <p class="login-footer-text">FitControl SGPAV &copy; 2025 — IFES — Programação Web</p>

  </section><!-- /login-right -->

</div><!-- /login-page -->


<!-- ════════════════════════════════════════════════════════
     APLICAÇÃO PRINCIPAL
════════════════════════════════════════════════════════ -->
<div id="app" class="app hidden">

  <!-- Overlay escuro no mobile quando sidebar está aberta -->
  <div id="sidebar-overlay" class="sidebar-overlay" aria-hidden="true"></div>

  <!-- ══════════ SIDEBAR ══════════ -->
  <aside id="sidebar" class="sidebar" aria-label="Menu de navegação principal">

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

    <nav class="sidebar-nav" aria-label="Seções do sistema">

      <div class="nav-group-label">Visão Geral</div>

      <a class="nav-item active" data-nav="dashboard" href="#dashboard"
         onclick="navigate('dashboard'); return false;" aria-current="page">
        <span class="nav-icon" aria-hidden="true">📊</span>
        <span class="nav-label">Dashboard</span>
      </a>

      <a class="nav-item" data-nav="sobre" href="#sobre"
         onclick="navigate('sobre'); return false;">
        <span class="nav-icon" aria-hidden="true">ℹ️</span>
        <span class="nav-label">Sobre o Sistema</span>
      </a>

      <div class="nav-group-label">Estoque</div>

      <a class="nav-item" data-nav="produtos" href="#produtos"
         onclick="navigate('produtos'); return false;">
        <span class="nav-icon" aria-hidden="true">🥛</span>
        <span class="nav-label">Produtos</span>
      </a>

      <a class="nav-item" data-nav="lotes" href="#lotes"
         onclick="navigate('lotes'); return false;">
        <span class="nav-icon" aria-hidden="true">📦</span>
        <span class="nav-label">Lotes</span>
      </a>

      <a class="nav-item" data-nav="alertas" href="#alertas"
         onclick="navigate('alertas'); return false;">
        <span class="nav-icon" aria-hidden="true">⚠️</span>
        <span class="nav-label">Alertas</span>
        <span class="nav-badge" id="badge-alertas" aria-label="alertas ativos">4</span>
      </a>

      <div class="nav-group-label">Comercial</div>

      <a class="nav-item" data-nav="clientes" href="#clientes"
         onclick="navigate('clientes'); return false;">
        <span class="nav-icon" aria-hidden="true">👥</span>
        <span class="nav-label">Cadastro de Clientes</span>
      </a>

      <a class="nav-item" data-nav="vendas" href="#vendas"
         onclick="navigate('vendas'); return false;">
        <span class="nav-icon" aria-hidden="true">🛒</span>
        <span class="nav-label">Vendas</span>
      </a>

      <a class="nav-item" data-nav="cupons" href="#cupons"
         onclick="navigate('cupons'); return false;">
        <span class="nav-icon" aria-hidden="true">🎟️</span>
        <span class="nav-label">Cupons</span>
      </a>

      <a class="nav-item" data-nav="relatorios" href="#relatorios"
         onclick="navigate('relatorios'); return false;">
        <span class="nav-icon" aria-hidden="true">📈</span>
        <span class="nav-label">Relatórios</span>
      </a>

    </nav>

<footer class="sidebar-footer">
  <div class="sidebar-user">
    <div class="avatar avatar-md" aria-hidden="true">
      <?= $usuario_iniciais ?? 'EF' ?>
    </div>
    <div class="sidebar-user-info">
      <div class="sidebar-user-name">
        <?= $usuario_nome ?? 'Equipe FitControl' ?>
      </div>
      <div class="sidebar-user-role">Administrador</div>
    </div>
  </div>

  <a class="btn-logout" href="logout.php">
    <i class="fa fa-right-from-bracket" aria-hidden="true"></i> Sair
  </a>
</footer>
  </aside><!-- /sidebar -->


  <!-- ══════════ CONTEÚDO PRINCIPAL ══════════ -->
  <div class="main-wrapper">

    <!-- TOPBAR -->
    <header class="topbar" role="banner">

      <button class="topbar-hamburger" id="hamburger" type="button"
              aria-label="Abrir ou fechar menu" aria-expanded="false" aria-controls="sidebar">
        <i class="fa fa-bars" aria-hidden="true"></i>
      </button>

      <div class="topbar-breadcrumb" id="topbar-title" aria-live="polite">Dashboard</div>

      <div class="topbar-actions">

        <button class="topbar-icon-btn" id="btn-tema" type="button"
                onclick="toggleTheme()" title="Alternar tema" aria-label="Alternar tema claro ou escuro">
          🌙
        </button>

        <button class="topbar-icon-btn" type="button"
                onclick="navigate('alertas')" title="Alertas" aria-label="Ver alertas de validade">
          <i class="fa fa-bell" aria-hidden="true"></i>
          <span class="notif-badge" id="notif-dot" aria-hidden="true"></span>
        </button>

    <div class="topbar-user-pill">
  <div class="avatar avatar-sm" aria-hidden="true">
    <?= $usuario_iniciais ?? 'EF' ?>
  </div>

  <div class="topbar-user-info">
    <div class="topbar-user-name">
      <?= $usuario_nome ?? 'Equipe FitControl' ?>
    </div>
    <div class="topbar-user-role">Sistema Corporativo</div>
  </div>
</div>

      </div>
    </header><!-- /topbar -->


    <!-- ÁREA DE CONTEÚDO -->
    <main class="content-area" id="main-content">


      <!-- ══════════════════════════════════════
           DASHBOARD
      ══════════════════════════════════════ -->
      <section class="page-section active" id="sec-dashboard" aria-label="Dashboard">

        <header class="section-header">
          <h2 class="section-title">Visão em tempo real de ativos e perdas potenciais</h2>
          <p class="section-desc">Motor de alertas automático integrado ao controle de estoque.</p>
        </header>

        <div class="stat-grid" role="region" aria-label="Indicadores principais">

          <article class="stat-card blue-accent">
            <div class="stat-card-row">
              <div class="stat-icon-wrap blue" aria-hidden="true">📦</div>
              <div class="stat-trend info">Catálogo</div>
            </div>
            <div class="stat-value" id="stat-produtos">—</div>
            <div class="stat-label">Produtos cadastrados</div>
          </article>

          <article class="stat-card green-accent">
            <div class="stat-card-row">
              <div class="stat-icon-wrap green" aria-hidden="true">🗂️</div>
              <div class="stat-trend up">Ativo</div>
            </div>
            <div class="stat-value" id="stat-lotes">—</div>
            <div class="stat-label">Lotes monitorados</div>
          </article>

          <article class="stat-card amber-accent">
            <div class="stat-card-row">
              <div class="stat-icon-wrap amber" aria-hidden="true">⚠️</div>
              <div class="stat-trend warn">Atenção</div>
            </div>
            <div class="stat-value" id="stat-risco">—</div>
            <div class="stat-label">Lotes em risco</div>
          </article>

          <article class="stat-card red-accent">
            <div class="stat-card-row">
              <div class="stat-icon-wrap red" aria-hidden="true">💸</div>
              <div class="stat-trend down">Financeiro</div>
            </div>
            <div class="stat-value" id="stat-valor">—</div>
            <div class="stat-label">Valor em risco</div>
          </article>

        </div>

        <div class="dash-grid">

          <article class="card">
            <header class="card-header">
              <div>
                <div class="card-title">Alertas Inteligentes</div>
                <div class="card-subtitle">Lotes com vencimento iminente que requerem ação imediata</div>
              </div>
            </header>
            <div class="table-wrap">
              <table aria-label="Alertas de vencimento">
                <thead>
                  <tr>
                    <th scope="col">Produto</th>
                    <th scope="col">Lote</th>
                    <th scope="col">Status</th>
                    <th scope="col">Prazo</th>
                    <th scope="col">Valor em Risco</th>
                    <th scope="col">Ação</th>
                  </tr>
                </thead>
                <tbody id="dash-alertas-tbody">
                  <tr><td colspan="6" style="padding:24px;text-align:center;color:var(--gray-400)">Carregando…</td></tr>
                </tbody>
              </table>
            </div>
          </article>

          <article class="card">
            <header class="card-header">
              <div>
                <div class="card-title">Estoque por Categoria</div>
                <div class="card-subtitle">Valor financeiro em estoque (R$) — gráfico de barras</div>
              </div>
            </header>
            <!-- Gráfico de barras CSS (substituindo pizza — feedback prof.) -->
            <div class="bar-chart" id="bar-chart" role="img" aria-label="Gráfico de barras de estoque por categoria"></div>
            <!-- Legenda com rótulos claros (feedback prof.) -->
            <div class="status-legend" aria-label="Legenda de status dos lotes">
              <div class="status-legend-item">
                <div class="status-legend-label">
                  <span class="status-dot green" aria-hidden="true"></span>OK — mais de 30 dias
                </div>
                <strong class="status-legend-count" id="leg-ok">—</strong>
              </div>
              <div class="status-legend-item">
                <div class="status-legend-label">
                  <span class="status-dot amber" aria-hidden="true"></span>Em Risco — 8 a 30 dias
                </div>
                <strong class="status-legend-count" id="leg-risco">—</strong>
              </div>
              <div class="status-legend-item">
                <div class="status-legend-label">
                  <span class="status-dot red" aria-hidden="true"></span>Crítico — menos de 7 dias
                </div>
                <strong class="status-legend-count" id="leg-critico">—</strong>
              </div>
              <div class="status-legend-item">
                <div class="status-legend-label">
                  <span class="status-dot gray" aria-hidden="true"></span>Vencido
                </div>
                <strong class="status-legend-count" id="leg-vencido">—</strong>
              </div>
            </div>
          </article>

        </div>

      </section><!-- /dashboard -->


      <!-- ══════════════════════════════════════
           SOBRE O SISTEMA
      ══════════════════════════════════════ -->
      <section class="page-section" id="sec-sobre" aria-label="Sobre o Sistema">

        <header class="section-header">
          <h2 class="section-title">Sobre o FitControl</h2>
          <p class="section-desc">Segmento de negócio, objetivo do sistema e funcionalidades desenvolvidas.</p>
        </header>

        <div class="grid-2">

          <article class="card">
            <header class="card-header">
              <div class="card-title">🎯 Objetivo do Sistema</div>
            </header>
            <p class="sobre-text">
              O <strong>FitControl (SGPAV)</strong> é um Sistema de Gestão de Perecíveis e Alertas de Validade
              desenvolvido para <strong>lojas de suplementos alimentares e nutrição esportiva</strong>.
            </p>
            <p class="sobre-text">
              O mercado brasileiro de suplementação movimenta mais de <strong>R$ 6 bilhões/ano</strong> (ABIAD, 2024).
              Produtos como whey protein, aminoácidos e vitaminas têm prazo de validade definido e, sem controle,
              geram <strong>perdas financeiras significativas</strong>.
            </p>
            <p class="sobre-text">
              O FitControl resolve esse problema com alertas proativos, geração automática de cupons e um CRM
              integrado que converte potencial perda em venda antes do vencimento.
            </p>
          </article>

          <article class="card">
            <header class="card-header">
              <div class="card-title">🏋️ Segmento de Negócio</div>
            </header>
            <ul class="sobre-feature-list">
              <li class="sobre-feature-item">
                <div class="sobre-feature-icon" aria-hidden="true">🥛</div>
                <div class="sobre-feature-body">
                  <strong>Proteínas</strong>
                  <p>Whey Protein, Caseína, Albumina — alto ticket, validade crítica.</p>
                </div>
              </li>
              <li class="sobre-feature-item">
                <div class="sobre-feature-icon" aria-hidden="true">💊</div>
                <div class="sobre-feature-body">
                  <strong>Aminoácidos e Vitaminas</strong>
                  <p>BCAA, Glutamina, Vitamina C, Ômega 3 e micronutrientes.</p>
                </div>
              </li>
              <li class="sobre-feature-item">
                <div class="sobre-feature-icon" aria-hidden="true">⚡</div>
                <div class="sobre-feature-body">
                  <strong>Pré-treinos e Creatinas</strong>
                  <p>Suplementos de performance com rastreabilidade total de lotes.</p>
                </div>
              </li>
              <li class="sobre-feature-item">
                <div class="sobre-feature-icon" aria-hidden="true">🏪</div>
                <div class="sobre-feature-body">
                  <strong>Gestão Completa</strong>
                  <p>Do recebimento do lote à venda, com CRM e dashboard financeiro.</p>
                </div>
              </li>
            </ul>
          </article>

        </div>

        <article class="card mt-20">
          <header class="card-header">
            <div class="card-title">⚙️ Funcionalidades do Sistema</div>
          </header>
          <div class="features-grid">
            <div class="feature-card">
              <div class="feature-card-icon">📊</div>
              <div class="feature-card-title">Dashboard em Tempo Real</div>
              <p class="feature-card-desc">Visão de ativos, perdas potenciais e alertas inteligentes.</p>
            </div>
            <div class="feature-card">
              <div class="feature-card-icon">📦</div>
              <div class="feature-card-title">Gestão de Lotes</div>
              <p class="feature-card-desc">Rastreabilidade com fabricação, validade, custo e estoque.</p>
            </div>
            <div class="feature-card">
              <div class="feature-card-icon">⚠️</div>
              <div class="feature-card-title">Motor de Alertas</div>
              <p class="feature-card-desc">Crítico (&lt;7d), Em Risco (8–30d) e Atenção (&gt;30d).</p>
            </div>
            <div class="feature-card">
              <div class="feature-card-icon">🎟️</div>
              <div class="feature-card-title">Cupons Automáticos</div>
              <p class="feature-card-desc">Cupons vinculados a lotes para converter perda em venda.</p>
            </div>
            <div class="feature-card">
              <div class="feature-card-icon">👥</div>
              <div class="feature-card-title">Cadastro de Clientes</div>
              <p class="feature-card-desc">Histórico de compras, CRM e campanhas direcionadas.</p>
            </div>
            <div class="feature-card">
              <div class="feature-card-icon">📈</div>
              <div class="feature-card-title">Relatórios Gerenciais</div>
              <p class="feature-card-desc">Vendas, perdas reais, perdas evitadas e uso de cupons.</p>
            </div>
          </div>
        </article>

      </section><!-- /sobre -->


      <!-- ══════════════════════════════════════
           PRODUTOS
      ══════════════════════════════════════ -->
      <section class="page-section" id="sec-produtos" aria-label="Produtos">

        <header class="section-header-flex">
          <div>
            <h2 class="section-title">Produtos</h2>
            <p class="section-desc">Cadastro e gerenciamento do catálogo de suplementos.</p>
          </div>
          <button class="btn btn-primary" type="button" onclick="openModalProduto()">
            <i class="fa fa-plus" aria-hidden="true"></i> Novo Produto
          </button>
        </header>

        <div class="search-bar">
          <input class="form-input" type="search" id="busca-produto"
                 placeholder="Buscar por nome, marca ou categoria…"
                 oninput="filtrarProdutos()" aria-label="Buscar produtos" />
        </div>

        <article class="card">
          <div class="table-wrap">
            <table aria-label="Tabela de produtos">
              <thead>
                <tr>
                  <th scope="col">Ícone</th>
                  <th scope="col">Produto</th>
                  <th scope="col">Marca</th>
                  <th scope="col">Categoria</th>
                  <th scope="col">Unidade</th>
                  <th scope="col">Preço</th>
                  <th scope="col">Estoque</th>
                  <th scope="col">Ações</th>
                </tr>
              </thead>
              <tbody id="produtos-tbody">
                <tr><td colspan="8" style="padding:24px;text-align:center;color:var(--gray-400)">Carregando…</td></tr>
              </tbody>
            </table>
          </div>
          <!-- Paginação (feedback prof.) -->
          <div class="pagination">
            <button class="btn btn-sm btn-outline" id="btn-pag-prev" type="button" onclick="mudarPagina('prev')">
              <i class="fa fa-chevron-left" aria-hidden="true"></i> Anterior
            </button>
            <span class="pagination-info" id="pag-info-prod" aria-live="polite"></span>
            <button class="btn btn-sm btn-outline" id="btn-pag-next" type="button" onclick="mudarPagina('next')">
              Próximo <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </button>
          </div>
        </article>

      </section><!-- /produtos -->


      <!-- ══════════════════════════════════════
           LOTES
      ══════════════════════════════════════ -->
      <section class="page-section" id="sec-lotes" aria-label="Gestão de Lotes">

        <header class="section-header-flex">
          <div>
            <h2 class="section-title">Gestão de Lotes</h2>
            <p class="section-desc">Rastreabilidade completa com motor de alertas de vencimento.</p>
          </div>
          <button class="btn btn-primary" type="button" onclick="openModalLote()">
            <i class="fa fa-plus" aria-hidden="true"></i> Novo Lote
          </button>
        </header>

        <!-- Filtros com legenda clara (feedback prof.) -->
        <div class="filter-bar" role="group" aria-label="Filtrar lotes por status">
          <button class="filter-btn active" type="button" data-filtro="todos"   onclick="setFiltroLote('todos')">Todos</button>
          <button class="filter-btn"        type="button" data-filtro="ok"      onclick="setFiltroLote('ok')">🟢 OK (+ de 30 dias)</button>
          <button class="filter-btn"        type="button" data-filtro="risco"   onclick="setFiltroLote('risco')">🟡 Em Risco (8–30 dias)</button>
          <button class="filter-btn"        type="button" data-filtro="critico" onclick="setFiltroLote('critico')">🔴 Crítico (– de 7 dias)</button>
          <button class="filter-btn"        type="button" data-filtro="vencido" onclick="setFiltroLote('vencido')">⚫ Vencido</button>
        </div>

        <article class="card">
          <div class="table-wrap">
            <table aria-label="Tabela de lotes">
              <thead>
                <tr>
                  <th scope="col">Lote</th>
                  <th scope="col">Produto</th>
                  <th scope="col">Fabricação</th>
                  <th scope="col">Validade</th>
                  <th scope="col">Estoque Atual / Inicial</th>
                  <th scope="col">Status</th>
                  <th scope="col">Valor Exposto</th>
                  <th scope="col">Ações</th>
                </tr>
              </thead>
              <tbody id="lotes-tbody">
                <tr><td colspan="8" style="padding:24px;text-align:center;color:var(--gray-400)">Carregando…</td></tr>
              </tbody>
            </table>
          </div>
        </article>

      </section><!-- /lotes -->


      <!-- ══════════════════════════════════════
           ALERTAS
      ══════════════════════════════════════ -->
      <section class="page-section" id="sec-alertas" aria-label="Alertas de Validade">

        <header class="section-header">
          <h2 class="section-title">Motor Inteligente de Validade</h2>
          <p class="section-desc">Alertas automáticos por urgência com ação direta de geração de cupom.</p>
        </header>

        <div id="alertas-container" role="list" aria-live="polite">
          <p class="text-muted">Carregando alertas…</p>
        </div>

      </section><!-- /alertas -->


      <!-- ══════════════════════════════════════
           CLIENTES (renomeado de CRM — feedback prof.)
      ══════════════════════════════════════ -->
      <section class="page-section" id="sec-clientes" aria-label="Cadastro de Clientes">

        <header class="section-header-flex">
          <div>
            <h2 class="section-title">Cadastro de Clientes</h2>
            <p class="section-desc">Histórico de compras e relacionamento para campanhas direcionadas.</p>
          </div>
          <button class="btn btn-primary" type="button" onclick="openModalCliente()">
            <i class="fa fa-user-plus" aria-hidden="true"></i> Novo Cliente
          </button>
        </header>

        <article class="card">
          <div class="table-wrap">
            <table aria-label="Tabela de clientes">
              <thead>
                <tr>
                  <th scope="col">Cliente</th>
                  <th scope="col">E-mail</th>
                  <th scope="col">Telefone</th>
                  <th scope="col">CPF</th>
                  <th scope="col">Compras</th>
                  <th scope="col">Total Gasto</th>
                  <th scope="col">Ações</th>
                </tr>
              </thead>
              <tbody id="clientes-tbody">
                <tr><td colspan="7" style="padding:24px;text-align:center;color:var(--gray-400)">Carregando…</td></tr>
              </tbody>
            </table>
          </div>
        </article>

      </section><!-- /clientes -->


      <!-- ══════════════════════════════════════
           VENDAS
      ══════════════════════════════════════ -->
      <section class="page-section" id="sec-vendas" aria-label="Vendas">

        <header class="section-header-flex">
          <div>
            <h2 class="section-title">Vendas</h2>
            <p class="section-desc">Registro de transações com rastreamento de cupons utilizados.</p>
          </div>
          <button class="btn btn-primary" type="button" onclick="openModalVenda()">
            <i class="fa fa-cart-plus" aria-hidden="true"></i> Nova Venda
          </button>
        </header>

        <article class="card">
          <div class="table-wrap">
            <table aria-label="Tabela de vendas">
              <thead>
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Data</th>
                  <th scope="col">Cliente</th>
                  <th scope="col">Itens</th>
                  <th scope="col">Cupom Usado</th>
                  <th scope="col">Total</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody id="vendas-tbody">
                <tr><td colspan="7" style="padding:24px;text-align:center;color:var(--gray-400)">Carregando…</td></tr>
              </tbody>
            </table>
          </div>
        </article>

      </section><!-- /vendas -->


      <!-- ══════════════════════════════════════
           CUPONS
      ══════════════════════════════════════ -->
      <section class="page-section" id="sec-cupons" aria-label="Cupons de Desconto">

        <header class="section-header">
  <div>
    <h2 class="section-title">Cupons Inteligentes Anti-Desperdício</h2>
    <p class="section-desc">Cupons vinculados a lotes próximos do vencimento para converter perda em venda.</p>
  </div>
  <button class="btn btn-primary" onclick="openModalNovoCupom()">
    <i class="fa fa-plus" aria-hidden="true"></i> Novo Cupom
  </button>
</header>

        <article class="card">
          <div class="table-wrap">
            <table aria-label="Tabela de cupons">
              <thead>
                <tr>
                  <th scope="col">Código</th>
                  <th scope="col">Produto / Lote</th>
                  <th scope="col">Desconto</th>
                  <th scope="col">Expiração</th>
                  <th scope="col">Status</th>
                  <th scope="col">Detalhes</th>
                </tr>
              </thead>
              <tbody id="cupons-tbody">
                <tr><td colspan="6" style="padding:24px;text-align:center;color:var(--gray-400)">Carregando…</td></tr>
              </tbody>
            </table>
          </div>
        </article>

      </section><!-- /cupons -->


      <!-- ══════════════════════════════════════
           RELATÓRIOS
      ══════════════════════════════════════ -->
      <section class="page-section" id="sec-relatorios" aria-label="Relatórios Gerenciais">

        <header class="section-header">
          <h2 class="section-title">Relatórios Gerenciais</h2>
          <p class="section-desc">Indicadores financeiros, perdas e efetividade dos cupons anti-desperdício.</p>
        </header>

        <div class="relatorio-kpi-grid" role="region" aria-label="KPIs financeiros">

          <article class="stat-card blue-accent">
            <div class="stat-card-row">
              <div class="stat-icon-wrap blue" aria-hidden="true">💰</div>
              <div class="stat-trend up">Receita</div>
            </div>
            <div class="stat-value" id="rel-vendas">—</div>
            <div class="stat-label">Total de vendas</div>
          </article>

          <article class="stat-card red-accent">
            <div class="stat-card-row">
              <div class="stat-icon-wrap red" aria-hidden="true">📉</div>
              <div class="stat-trend down">Perda</div>
            </div>
            <div class="stat-value" id="rel-perdas">—</div>
            <div class="stat-label">Perdas reais (vencidos)</div>
          </article>

          <article class="stat-card green-accent">
            <div class="stat-card-row">
              <div class="stat-icon-wrap green" aria-hidden="true">✅</div>
              <div class="stat-trend up">Recuperado</div>
            </div>
            <div class="stat-value" id="rel-evitado">—</div>
            <div class="stat-label">Perdas evitadas pelos cupons</div>
          </article>

          <article class="stat-card amber-accent">
            <div class="stat-card-row">
              <div class="stat-icon-wrap amber" aria-hidden="true">🎟️</div>
              <div class="stat-trend info">Cupons</div>
            </div>
            <div class="stat-value" id="rel-cupons">—</div>
            <div class="stat-label">Cupons utilizados</div>
          </article>

        </div>

        <article class="card">
          <header class="card-header">
            <div>
              <div class="card-title">Vendas por Cliente</div>
              <div class="card-subtitle">Histórico de compras e uso de cupons</div>
            </div>
          </header>
          <div class="table-wrap">
            <table aria-label="Vendas por cliente">
              <thead>
                <tr>
                  <th scope="col">Cliente</th>
                  <th scope="col">Nº Compras</th>
                  <th scope="col">Total Gasto</th>
                  <th scope="col">Cupons Usados</th>
                </tr>
              </thead>
              <tbody id="rel-tbody">
                <tr><td colspan="4" style="padding:24px;text-align:center;color:var(--gray-400)">Carregando…</td></tr>
              </tbody>
            </table>
          </div>
        </article>

      </section><!-- /relatorios -->


    </main><!-- /content-area -->

    <footer class="app-footer">
      <p>FitControl SGPAV &copy; 2025 &mdash; Trabalho de Programação Web &mdash; IFES &mdash; Equipe: Hugo, Isadora, William, Bruno e Lucas</p>
    </footer>

  </div><!-- /main-wrapper -->

</div><!-- /app -->


<!-- ════════════════════════════════════════════════════════
     MODAIS
════════════════════════════════════════════════════════ -->

<!-- Modal: Gerar Cupom -->
<div class="modal-overlay" id="modal-cupom" role="dialog" aria-modal="true" aria-labelledby="t-cupom">
  <div class="modal">
    <header class="modal-header">
      <h2 class="modal-title" id="t-cupom">🎟️ Gerar Cupom de Desconto</h2>
      <button class="modal-close" type="button" onclick="closeModalCupom()" aria-label="Fechar">✕</button>
    </header>
    <div id="modal-cupom-body" class="modal-body"></div>
    <input type="hidden" id="cup-lote-id" />
    <footer class="modal-footer">
      <button class="btn btn-outline" type="button" onclick="closeModalCupom()">Cancelar</button>
      <button class="btn btn-amber"   type="button" onclick="confirmarCupom()">
        <i class="fa fa-ticket" aria-hidden="true"></i> Gerar Cupom
      </button>
    </footer>
  </div>
</div>

<!-- Modal: Novo Produto -->
<div class="modal-overlay" id="modal-produto" role="dialog" aria-modal="true" aria-labelledby="t-produto">
  <div class="modal">
    <header class="modal-header">
      <h2 class="modal-title" id="t-produto">🥛 Cadastrar Produto</h2>
      <button class="modal-close" type="button" onclick="closeModalProduto()" aria-label="Fechar">✕</button>
    </header>
    <form id="form-produto" novalidate>
      <div class="form-grid-2">

        <div class="form-group form-col-full">
          <label class="form-label" for="pf-nome">Nome do Produto <span class="required">*</span></label>
          <input class="form-input" type="text" id="pf-nome" placeholder="Ex: Whey Protein 900g" required />
        </div>

        <div class="form-group">
          <label class="form-label" for="pf-marca">Marca <span class="required">*</span></label>
          <input class="form-input" type="text" id="pf-marca" placeholder="Ex: Max Titanium" required />
        </div>

        <div class="form-group">
          <label class="form-label" for="pf-categoria">Categoria <span class="required">*</span></label>
          <select class="form-input" id="pf-categoria" required>
            <option value="">Selecione…</option>
            <option value="Proteínas">Proteínas</option>
            <option value="Aminoácidos">Aminoácidos</option>
            <option value="Vitaminas">Vitaminas</option>
            <option value="Creatinas">Creatinas</option>
            <option value="Pré-Treinos">Pré-Treinos</option>
            <option value="Outros">Outros</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label" for="pf-preco">Preço de Venda (R$) <span class="required">*</span></label>
          <input class="form-input" type="number" id="pf-preco" min="0.01" step="0.01" placeholder="0,00" required />
        </div>

        <div class="form-group">
          <label class="form-label" for="pf-unidade">Unidade de Medida</label>
          <select class="form-input" id="pf-unidade">
            <option value="UN">UN — Unidade</option>
            <option value="KG">KG — Quilograma</option>
            <option value="G">G — Gramas</option>
            <option value="ML">ML — Mililitro</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label" for="pf-icone">Ícone</label>
          <select class="form-input" id="pf-icone">
            <option value="🥛">🥛 Proteína / Whey</option>
            <option value="💊">💊 Cápsula / Vitamina</option>
            <option value="🍊">🍊 Vitamina C</option>
            <option value="⚡">⚡ Performance</option>
            <option value="🐟">🐟 Ômega / Colágeno</option>
            <option value="🔥">🔥 Termogênico</option>
            <option value="🌿">🌿 Natural / Vegano</option>
            <option value="📦">📦 Outros</option>
          </select>
        </div>

      </div>
    </form>
    <footer class="modal-footer">
      <button class="btn btn-outline" type="button" onclick="closeModalProduto()">Cancelar</button>
      <button class="btn btn-primary" type="button" onclick="salvarProduto()">
        <i class="fa fa-floppy-disk" aria-hidden="true"></i> Salvar Produto
      </button>
    </footer>
  </div>
</div>

<!-- Modal: Editar Produto -->
<div class="modal-overlay" id="modal-editar-produto" role="dialog" aria-modal="true" aria-labelledby="t-edit-prod">
  <div class="modal">
    <header class="modal-header">
      <h2 class="modal-title" id="t-edit-prod">✏️ Editar Produto</h2>
      <button class="modal-close" type="button" onclick="closeModalEditarProduto()" aria-label="Fechar">✕</button>
    </header>
    <div id="modal-editar-prod-body"></div>
    <input type="hidden" id="ep-id" />
    <footer class="modal-footer">
      <button class="btn btn-outline" type="button" onclick="closeModalEditarProduto()">Cancelar</button>
      <button class="btn btn-primary" type="button" onclick="salvarEdicaoProduto()">
        <i class="fa fa-floppy-disk" aria-hidden="true"></i> Salvar Alterações
      </button>
    </footer>
  </div>
</div>

<!-- Modal: Novo Lote -->
<div class="modal-overlay" id="modal-lote" role="dialog" aria-modal="true" aria-labelledby="t-lote">
  <div class="modal">
    <header class="modal-header">
      <h2 class="modal-title" id="t-lote">📦 Cadastrar Lote</h2>
      <button class="modal-close" type="button" onclick="closeModalLote()" aria-label="Fechar">✕</button>
    </header>
    <form id="form-lote" novalidate>
      <div class="form-grid-2">

        <!-- Autocomplete em vez de dropdown (feedback prof.) -->
        <div class="form-group form-col-full autocomplete-wrap">
          <label class="form-label" for="lf-busca-produto">Produto <span class="required">*</span></label>
          <input class="form-input" type="text" id="lf-busca-produto"
                 placeholder="Digite para buscar o produto…"
                 oninput="autocompleteProduto()" autocomplete="off" />
          <input type="hidden" id="lf-produto-id" />
          <div class="autocomplete-list" id="autocomplete-lista" role="listbox"></div>
        </div>

        <div class="form-group">
          <label class="form-label" for="lf-numero">Número do Lote <span class="required">*</span></label>
          <input class="form-input" type="text" id="lf-numero" placeholder="Ex: #L-0001" required />
        </div>

        <div class="form-group">
          <label class="form-label" for="lf-qtd">Quantidade Inicial <span class="required">*</span></label>
          <input class="form-input" type="number" id="lf-qtd" min="1" placeholder="0" required />
        </div>

        <div class="form-group">
          <label class="form-label" for="lf-fabricacao">Data de Fabricação <span class="required">*</span></label>
          <input class="form-input" type="date" id="lf-fabricacao" required />
        </div>

        <div class="form-group">
          <label class="form-label" for="lf-validade">Data de Validade <span class="required">*</span></label>
          <input class="form-input" type="date" id="lf-validade" required />
        </div>

        <div class="form-group form-col-full">
          <label class="form-label" for="lf-custo">Custo Unitário (R$) <span class="required">*</span></label>
          <input class="form-input" type="number" id="lf-custo" min="0.01" step="0.01" placeholder="0,00" required />
        </div>

      </div>
    </form>
    <footer class="modal-footer">
      <button class="btn btn-outline" type="button" onclick="closeModalLote()">Cancelar</button>
      <button class="btn btn-primary" type="button" onclick="salvarLote()">
        <i class="fa fa-floppy-disk" aria-hidden="true"></i> Salvar Lote
      </button>
    </footer>
  </div>
</div>

<!-- Modal: Novo Cliente -->
<div class="modal-overlay" id="modal-cliente" role="dialog" aria-modal="true" aria-labelledby="t-cliente">
  <div class="modal">
    <header class="modal-header">
      <h2 class="modal-title" id="t-cliente">👤 Cadastrar Cliente</h2>
      <button class="modal-close" type="button" onclick="closeModalCliente()" aria-label="Fechar">✕</button>
    </header>
    <form id="form-cliente" novalidate>
      <div class="form-grid-2">

        <div class="form-group form-col-full">
          <label class="form-label" for="cf-nome">Nome Completo <span class="required">*</span></label>
          <input class="form-input" type="text" id="cf-nome" placeholder="Nome do cliente" required />
        </div>

        <div class="form-group">
          <label class="form-label" for="cf-email">E-mail <span class="required">*</span></label>
          <input class="form-input" type="email" id="cf-email" placeholder="email@exemplo.com" required />
        </div>

        <div class="form-group">
          <label class="form-label" for="cf-telefone">Telefone <span class="required">*</span></label>
          <input class="form-input" type="tel" id="cf-telefone" placeholder="(00) 00000-0000" required />
        </div>

        <div class="form-group">
          <label class="form-label" for="cf-cpf">CPF <span class="required">*</span></label>
          <input class="form-input" type="text" id="cf-cpf" placeholder="000.000.000-00" maxlength="14" required />
        </div>

        <div class="form-group">
          <label class="form-label" for="cf-nascimento">Data de Nascimento</label>
          <input class="form-input" type="date" id="cf-nascimento" />
        </div>

        <div class="form-group form-col-full">
          <label class="form-label" for="cf-obs">Observações</label>
          <textarea class="form-input form-textarea" id="cf-obs" placeholder="Preferências, alergias…"></textarea>
        </div>

      </div>
    </form>
    <footer class="modal-footer">
      <button class="btn btn-outline" type="button" onclick="closeModalCliente()">Cancelar</button>
      <button class="btn btn-primary" type="button" onclick="salvarCliente()">
        <i class="fa fa-floppy-disk" aria-hidden="true"></i> Salvar Cliente
      </button>
    </footer>
  </div>
</div>

<!-- Modal: Editar Cliente -->
<div class="modal-overlay" id="modal-editar-cliente" role="dialog" aria-modal="true" aria-labelledby="t-edit-cli">
  <div class="modal">
    <header class="modal-header">
      <h2 class="modal-title" id="t-edit-cli">✏️ Editar Cliente</h2>
      <button class="modal-close" type="button" onclick="closeModalEditarCliente()" aria-label="Fechar">✕</button>
    </header>
    <div id="modal-editar-cli-body"></div>
    <input type="hidden" id="ec-id" />
    <footer class="modal-footer">
      <button class="btn btn-outline" type="button" onclick="closeModalEditarCliente()">Cancelar</button>
      <button class="btn btn-primary" type="button" onclick="salvarEdicaoCliente()">
        <i class="fa fa-floppy-disk" aria-hidden="true"></i> Salvar Alterações
      </button>
    </footer>
  </div>
</div>

<!-- Modal: Nova Venda -->
<div class="modal-overlay" id="modal-venda" role="dialog" aria-modal="true" aria-labelledby="t-venda">
  <div class="modal modal-wide">
    <header class="modal-header">
      <h2 class="modal-title" id="t-venda">🛒 Registrar Venda</h2>
      <button class="modal-close" type="button" onclick="closeModalVenda()" aria-label="Fechar">✕</button>
    </header>
    <form id="form-venda" novalidate>
      <div class="form-grid-2">

        <div class="form-group">
          <label class="form-label" for="vf-cliente">Cliente</label>
          <select class="form-input" id="vf-cliente"></select>
        </div>

        <div class="form-group">
          <label class="form-label" for="vf-data">Data da Venda</label>
          <input class="form-input" type="date" id="vf-data" />
        </div>

        <div class="form-group form-col-full">
          <label class="form-label" for="vf-lote">Produto / Lote <span class="required">*</span></label>
          <select class="form-input" id="vf-lote" onchange="calcularTotalVenda()" required></select>
        </div>

        <div class="form-group">
          <label class="form-label" for="vf-qtd">Quantidade <span class="required">*</span></label>
          <input class="form-input" type="number" id="vf-qtd" min="1" value="1" oninput="calcularTotalVenda()" required />
        </div>

        <div class="form-group">
          <label class="form-label" for="vf-cupom">Cupom de Desconto</label>
          <select class="form-input" id="vf-cupom" onchange="calcularTotalVenda()"></select>
        </div>

        <div class="form-group form-col-full">
          <label class="form-label" for="vf-total">Total da Venda</label>
          <input class="form-input" type="text" id="vf-total" readonly
                 style="font-weight:700;font-size:16px;" aria-readonly="true" />
        </div>

      </div>
    </form>
    <footer class="modal-footer">
      <button class="btn btn-outline" type="button" onclick="closeModalVenda()">Cancelar</button>
      <button class="btn btn-primary" type="button" onclick="salvarVenda()">
        <i class="fa fa-check" aria-hidden="true"></i> Confirmar Venda
      </button>
    </footer>
  </div>
</div>


<!-- ════════════════════════════════════════════════════════
     TOAST DE NOTIFICAÇÃO
════════════════════════════════════════════════════════ -->
<div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true"></div>


<!-- JavaScript antes do fechamento do body (boa prática HTML) -->
<script src="app.js"></script>

<!-- Modal: Novo Cupom Manual -->
<div class="modal-overlay" id="modal-novo-cupom" role="dialog" aria-modal="true">
  <div class="modal">
    <header class="modal-header">
      <h2 class="modal-title">🎟️ Novo Cupom</h2>
      <button class="modal-close" type="button" onclick="closeModal('modal-novo-cupom')" aria-label="Fechar">✕</button>
    </header>
    <div class="form-grid-2" style="padding:0 0 8px;">
      <div class="form-group">
        <label class="form-label" for="nc-codigo">Código <span class="required">*</span></label>
        <input class="form-input" type="text" id="nc-codigo" placeholder="Ex: PROMO20" style="text-transform:uppercase;" />
      </div>
      <div class="form-group">
        <label class="form-label" for="nc-desconto">Desconto (%) <span class="required">*</span></label>
        <input class="form-input" type="number" id="nc-desconto" min="1" max="80" placeholder="15" />
      </div>
      <div class="form-group">
        <label class="form-label" for="nc-expira">Expiração <span class="required">*</span></label>
        <input class="form-input" type="date" id="nc-expira" />
      </div>
      <div class="form-group">
        <label class="form-label" for="nc-produto">Produto (opcional)</label>
        <select class="form-input" id="nc-produto">
          <option value="">— Nenhum —</option>
        </select>
      </div>
    </div>
    <footer class="modal-footer">
      <button class="btn btn-outline" type="button" onclick="closeModal('modal-novo-cupom')">Cancelar</button>
      <button class="btn btn-primary" type="button" onclick="salvarNovoCupom()">
        <i class="fa fa-floppy-disk"></i> Salvar Cupom
      </button>
    </footer>
  </div>
</div>

<!-- Modal: Editar Cupom -->
<div class="modal-overlay" id="modal-editar-cupom" role="dialog" aria-modal="true">
  <div class="modal">
    <header class="modal-header">
      <h2 class="modal-title">✏️ Editar Cupom</h2>
      <button class="modal-close" type="button" onclick="closeModal('modal-editar-cupom')" aria-label="Fechar">✕</button>
    </header>
    <input type="hidden" id="ecp-id" />
    <div class="form-grid-2" style="padding:0 0 8px;">
      <div class="form-group">
        <label class="form-label" for="ecp-codigo">Código <span class="required">*</span></label>
        <input class="form-input" type="text" id="ecp-codigo" style="text-transform:uppercase;" />
      </div>
      <div class="form-group">
        <label class="form-label" for="ecp-desconto">Desconto (%) <span class="required">*</span></label>
        <input class="form-input" type="number" id="ecp-desconto" min="1" max="80" />
      </div>
      <div class="form-group">
        <label class="form-label" for="ecp-expira">Expiração <span class="required">*</span></label>
        <input class="form-input" type="date" id="ecp-expira" />
      </div>
      <div class="form-group">
        <label class="form-label" for="ecp-status">Status</label>
        <select class="form-input" id="ecp-status">
          <option value="Ativo">Ativo</option>
          <option value="Utilizado">Utilizado</option>
          <option value="Expirado">Expirado</option>
        </select>
      </div>
    </div>
    <footer class="modal-footer">
      <button class="btn btn-outline" type="button" onclick="closeModal('modal-editar-cupom')">Cancelar</button>
      <button class="btn btn-primary" type="button" onclick="salvarEdicaoCupom()">
        <i class="fa fa-floppy-disk"></i> Salvar Alterações
      </button>
    </footer>
  </div>
</div>
<div id="modal-detalhes-venda" class="modal-overlay">
  <div class="modal modal-md">
    <header class="modal-header">
      <h3>🛒 Detalhes da Venda</h3>
      <button class="modal-close" onclick="fecharDetalhesVenda()">×</button>
    </header>

    <div class="modal-body" id="detalhes-venda-body"></div>

    <footer class="modal-footer">
      <button class="btn btn-primary" onclick="fecharDetalhesVenda()">Fechar</button>
    </footer>
  </div>
</div>

<div class="modal-overlay" id="modal-editar-lote">
  <div class="modal">
    <header class="modal-header">
      <h2 class="modal-title">✏️ Editar Lote</h2>
      <button class="modal-close" type="button" onclick="closeModalEditarLote()">✕</button>
    </header>

    <input type="hidden" id="el-id">

    <div class="form-grid-2">
      <div class="form-group">
        <label class="form-label">Número do lote</label>
        <input class="form-input" id="el-numero">
      </div>

      <div class="form-group">
        <label class="form-label">Fabricação</label>
        <input class="form-input" type="date" id="el-fabricacao">
      </div>

      <div class="form-group">
        <label class="form-label">Validade</label>
        <input class="form-input" type="date" id="el-validade">
      </div>

      <div class="form-group">
        <label class="form-label">Qtd. atual</label>
        <input class="form-input" type="number" id="el-qtd-atual">
      </div>

      <div class="form-group">
        <label class="form-label">Qtd. inicial</label>
        <input class="form-input" type="number" id="el-qtd-inicial">
      </div>

      <div class="form-group">
        <label class="form-label">Custo unitário</label>
        <input class="form-input" type="number" step="0.01" id="el-custo">
      </div>
    </div>

    <footer class="modal-footer">
      <button class="btn btn-outline" type="button" onclick="closeModalEditarLote()">Cancelar</button>
      <button class="btn btn-primary" type="button" onclick="salvarEdicaoLote()">Salvar Alterações</button>
    </footer>
  </div>
</div>
<div class="modal-overlay" id="modal-editar-lote">
  <div class="modal">
    <header class="modal-header">
      <h2 class="modal-title">✏️ Editar Lote</h2>
      <button class="modal-close" type="button" onclick="closeModalEditarLote()">✕</button>
    </header>

    <input type="hidden" id="el-id">

    <div class="form-grid-2">
      <div class="form-group">
        <label class="form-label">Número do lote</label>
        <input class="form-input" id="el-numero">
      </div>

      <div class="form-group">
        <label class="form-label">Fabricação</label>
        <input class="form-input" type="date" id="el-fabricacao">
      </div>

      <div class="form-group">
        <label class="form-label">Validade</label>
        <input class="form-input" type="date" id="el-validade">
      </div>

      <div class="form-group">
        <label class="form-label">Qtd. atual</label>
        <input class="form-input" type="number" id="el-qtd-atual">
      </div>

      <div class="form-group">
        <label class="form-label">Qtd. inicial</label>
        <input class="form-input" type="number" id="el-qtd-inicial">
      </div>

      <div class="form-group">
        <label class="form-label">Custo unitário</label>
        <input class="form-input" type="number" step="0.01" id="el-custo">
      </div>
    </div>

    <footer class="modal-footer">
      <button class="btn btn-outline" type="button" onclick="closeModalEditarLote()">Cancelar</button>
      <button class="btn btn-primary" type="button" onclick="salvarEdicaoLote()">Salvar Alterações</button>
    </footer>
  </div>
</div>
</body>
</html>