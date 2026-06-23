/* ═══════════════════════════════════════════════════════════
app.js — FitControl SGPAV — VERSÃO LIMPA E COMPLETA
═══════════════════════════════════════════════════════════ */

// Estado global (vem do MySQL)
let appData = {
    produtos: [],
    lotes: [],
    clientes: [],
    vendas: [],
    cupons: [],
    alertas: []
};

var state = { pagProduto: 1, itensPagina: 5, filtroLote: 'todos' };

// Carregar dados do MySQL
async function carregarDadosDoBanco() {
    try {
        const response = await fetch('api.php?action=get_all');
        if (!response.ok) throw new Error('Falha ao carregar dados');
        
        appData = await response.json();
        
        const secaoAtiva = document.querySelector('.page-section.active');
        if (secaoAtiva) {
            const secaoId = secaoAtiva.id.replace('sec-', '');
            renderizarSecao(secaoId);
        }
    } catch (error) {
        console.error('Erro:', error);
        toast('Erro ao carregar dados do banco', 'erro');
    }
}

function renderizarSecao(secao) {
    const renderMap = {
        dashboard:  renderDashboard,
        produtos:   renderProdutos,
        lotes:      renderLotes,
        alertas:    renderAlertas,
        clientes:   renderClientes,
        vendas:     renderVendas,
        cupons:     renderCupons,
        relatorios: renderRelatorios,
    };
    if (renderMap[secao]) renderMap[secao]();
}

/* ──────────────────────────────────────
HELPERS
────────────────────────────────────── */
function byId(arr, id) {
    return arr.find(function(x) { return x.id == id; }) || null;
}

function diasParaVencer(dataStr) {
    var hoje = new Date();
    hoje.setHours(0, 0, 0, 0);
    var d = new Date(dataStr + 'T00:00:00');
    return Math.ceil((d - hoje) / 86400000);
}

function calcStatus(dataValidade) {
    var d = diasParaVencer(dataValidade);
    if (d <= 0)  return { chave: 'vencido',  label: 'VENCIDO',           badge: 'badge-dark',  icon: '⚫', cor: 'gray' };
    if (d <= 7)  return { chave: 'critico',  label: 'CRÍTICO (<7d)',     badge: 'badge-red',   icon: '🔴', cor: 'red'  };
    if (d <= 30) return { chave: 'risco',    label: 'EM RISCO (8–30d)',  badge: 'badge-amber', icon: '', cor: 'amber'};
    return              { chave: 'ok',       label: 'OK (>30d)',         badge: 'badge-green', icon: '🟢', cor: 'green'};
}

function moeda(v) {
    var num = parseFloat(v) || 0;
    return 'R$\u00a0' + num.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function fmtData(s) {
    if (!s) return '—';
    var p = s.split('-');
    return p[2] + '/' + p[1] + '/' + p[0];
}

function today() {
    return new Date().toISOString().split('T')[0];
}

function initials(nome) {
    var parts = (nome || '').split(' ');
    return (parts[0] ? parts[0][0] : '') + (parts[1] ? parts[1][0] : '');
}

function toast(msg, tipo) {
    var el = document.getElementById('toast');
    if (!el) return;
    var icons = { sucesso: '✅', erro: '❌', info: 'ℹ️' };
    el.innerHTML = '<span class="toast-icon">' + (icons[tipo] || '💬') + '</span><span>' + msg + '</span>';
    el.className = 'toast toast-' + (tipo || 'sucesso');
    el.classList.add('show');
    clearTimeout(el._timer);
    el._timer = setTimeout(function() { el.classList.remove('show'); }, 3800);
}

/* ──────────────────────────────────────
AUTENTICAÇÃO
────────────────────────────────────── */
function doLogin() {
    var email = document.getElementById('login-email').value.trim();
    var senha = document.getElementById('login-senha').value.trim();
    
    if (email === 'admin@fitcontrol.com' && senha === '123456') {
        document.getElementById('login-page').classList.add('hidden');
        document.getElementById('app').classList.remove('hidden');
        
        carregarDadosDoBanco().then(() => {
            navigate('dashboard');
        });
    } else {
        toast('E-mail ou senha incorretos', 'erro');
    }
}

function doLogout() {
    document.getElementById('app').classList.add('hidden');
    document.getElementById('login-page').classList.remove('hidden');
    document.getElementById('login-senha').value = '';
}

function togglePassword() {
    var inp = document.getElementById('login-senha');
    var btn = document.querySelector('.btn-eye i');
    if (inp.type === 'password') {
        inp.type = 'text';
        if (btn) { btn.className = 'fa fa-eye-slash'; }
    } else {
        inp.type = 'password';
        if (btn) { btn.className = 'fa fa-eye'; }
    }
}

function handleLoginKey(e) {
    if (e.key === 'Enter') doLogin();
}

/* ──────────────────────────────────────
SIDEBAR MOBILE
────────────────────────────────────── */
function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebar-overlay').classList.add('visible');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('visible');
    document.body.style.overflow = '';
}

function toggleSidebar() {
    var sb = document.getElementById('sidebar');
    if (sb.classList.contains('open')) {
        closeSidebar();
    } else {
        openSidebar();
    }
}

/* ──────────────────────────────────────
TEMA ESCURO
────────────────────────────────────── */
function toggleTheme() {
    document.body.classList.toggle('dark-mode');
    var btn = document.getElementById('btn-tema');
    var isDark = document.body.classList.contains('dark-mode');
    if (btn) btn.textContent = isDark ? '☀️' : '🌙';
    toast(isDark ? '🌙 Tema escuro ativado' : '☀️ Tema claro ativado', 'info');
}

/* ──────────────────────────────────────
NAVEGAÇÃO
────────────────────────────────────── */
var titulos = {
    dashboard:  'Dashboard',
    sobre:      'Sobre o Sistema',
    produtos:   'Produtos',
    lotes:      'Gestão de Lotes',
    alertas:    'Alertas de Validade',
    clientes:   'Cadastro de Clientes',
    vendas:     'Vendas',
    cupons:     'Cupons Inteligentes',
    relatorios: 'Relatórios',
};

function navigate(secao) {
    var sections = document.querySelectorAll('.page-section');
    for (var i = 0; i < sections.length; i++) {
        sections[i].classList.remove('active');
    }
    
    var navItems = document.querySelectorAll('.nav-item');
    for (var j = 0; j < navItems.length; j++) {
        navItems[j].classList.remove('active');
    }
    
    var sec = document.getElementById('sec-' + secao);
    if (sec) sec.classList.add('active');
    
    var navEl = document.querySelector('[data-nav="' + secao + '"]');
    if (navEl) navEl.classList.add('active');
    
    var tEl = document.getElementById('topbar-title');
    if (tEl) tEl.textContent = titulos[secao] || secao;
    
    if (window.innerWidth <= 1024) closeSidebar();
    
    var renderMap = {
        dashboard:  renderDashboard,
        produtos:   renderProdutos,
        lotes:      renderLotes,
        alertas:    renderAlertas,
        clientes:   renderClientes,
        vendas:     renderVendas,
        cupons:     renderCupons,
        relatorios: renderRelatorios,
    };
    if (renderMap[secao]) renderMap[secao]();
}

/* ──────────────────────────────────────
DASHBOARD
────────────────────────────────────── */
function renderDashboard() {
    var lotesRisco = appData.lotes.filter(function(l) {
        var d = diasParaVencer(l.dataValidade);
        return d > 0 && d <= 30;
    });
    
    var valRisco = lotesRisco.reduce(function(acc, l) {
        return acc + (parseFloat(l.qtdAtual) * parseFloat(l.custo));
    }, 0);
    
    setText('stat-produtos', appData.produtos.length);
    setText('stat-lotes',    appData.lotes.length);
    setText('stat-risco',    lotesRisco.length);
    setText('stat-valor',    moeda(valRisco));
    
    var badge = document.getElementById('badge-alertas');
    if (badge) badge.textContent = appData.alertas.length;
    
    var dot = document.getElementById('notif-dot');
    if (dot) dot.style.display = appData.alertas.length > 0 ? 'block' : 'none';
    
    var tbody = document.getElementById('dash-alertas-tbody');
    if (tbody) {
        var rows = '';
        appData.alertas.slice(0, 6).forEach(function(a) {
            var lote = byId(appData.lotes, a.idLote);
            var prod = lote ? byId(appData.produtos, lote.idProduto) : null;
            var dias = lote ? diasParaVencer(lote.dataValidade) : 0;
            var st   = lote ? calcStatus(lote.dataValidade) : { badge: 'badge-gray', icon: '—', label: '—' };
            var val  = lote ? moeda(parseFloat(lote.qtdAtual) * parseFloat(lote.custo)) : '—';
            
            rows += '<tr>' +
                '<td class="td-name">' + (prod ? (prod.icone || '📦') + ' ' + prod.nome : '—') + '</td>' +
                '<td class="td-mono">' + (lote ? lote.numero : '—') + '</td>' +
                '<td><span class="badge ' + st.badge + '">' + st.icon + ' ' + st.label + '</span></td>' +
                '<td>' + (dias > 0 ? dias + ' dias' : '<span class="text-danger font-600">Vencido</span>') + '</td>' +
                '<td class="text-danger font-700">' + val + '</td>' +
                '<td><button class="btn btn-sm btn-amber" onclick="openModalCupom(' + (lote ? lote.id : 0) + ')">🎟️ Gerar Cupom</button></td>' +
            '</tr>';
        });
        tbody.innerHTML = rows || '<tr><td colspan="6" style="padding:24px;text-align:center;color:var(--gray-400)">Nenhum alerta ativo</td></tr>';
    }
    
    var statusCounts = { ok: 0, risco: 0, critico: 0, vencido: 0 };
    appData.lotes.forEach(function(l) {
        var ch = calcStatus(l.dataValidade).chave;
        if (statusCounts[ch] !== undefined) statusCounts[ch]++;
    });
    
    setText('leg-ok',      statusCounts.ok);
    setText('leg-risco',   statusCounts.risco);
    setText('leg-critico', statusCounts.critico);
    setText('leg-vencido', statusCounts.vencido);
    
    renderBarChart();
}

function renderBarChart() {
    var categorias = ['Proteínas', 'Aminoácidos', 'Vitaminas', 'Creatinas', 'Pré-Treinos'];
    var cores = ['#2563eb', '#d97706', '#22c55e', '#6366f1', '#ef4444'];
    
    var vals = categorias.map(function(cat) {
        var ps = appData.produtos.filter(function(p) { return p.categoria === cat; });
        var ls = appData.lotes.filter(function(l) {
            return ps.some(function(p) { return p.id == l.idProduto; });
        });
        return ls.reduce(function(acc, l) { return acc + (parseFloat(l.qtdAtual) * parseFloat(l.custo)); }, 0);
    });
    
    var max = Math.max.apply(null, vals) || 1;
    var html = '';
    
    categorias.forEach(function(cat, i) {
        var pct = Math.max((vals[i] / max * 100), vals[i] > 0 ? 6 : 0).toFixed(1);
        html += '<div class="bar-group">' +
            '<div class="bar-val">' + moeda(vals[i]) + '</div>' +
            '<div class="bar-fill" style="height:' + pct + '%;background:' + cores[i] + '" title="' + cat + ': ' + moeda(vals[i]) + '"></div>' +
            '<div class="bar-lbl">' + cat + '</div>' +
        '</div>';
    });
    
    var el = document.getElementById('bar-chart');
    if (el) el.innerHTML = html;
}

/* ──────────────────────────────────────
PRODUTOS
────────────────────────────────────── */
function renderProdutos() {
    var busca = (getVal('busca-produto') || '').toLowerCase();
    var filtrados = appData.produtos.filter(function(p) {
        return !busca ||
            p.nome.toLowerCase().indexOf(busca) > -1 ||
            (p.marca && p.marca.toLowerCase().indexOf(busca) > -1) ||
            p.categoria.toLowerCase().indexOf(busca) > -1;
    });
    
    var total = filtrados.length;
    var totalPags = Math.max(Math.ceil(total / state.itensPagina), 1);
    if (state.pagProduto > totalPags) state.pagProduto = 1;
    
    var ini = (state.pagProduto - 1) * state.itensPagina;
    var slice = filtrados.slice(ini, ini + state.itensPagina);
    
    var tbody = document.getElementById('produtos-tbody');
    if (!tbody) return;
    
    var rows = '';
    slice.forEach(function(p) {
        var ls = appData.lotes.filter(function(l) { return l.idProduto == p.id; });
        var estoque = ls.reduce(function(a, l) { return a + parseInt(l.qtdAtual); }, 0);
        
        rows += '<tr>' +
            '<td style="font-size:22px;text-align:center">' + (p.icone || '📦') + '</td>' +
            '<td class="td-name">' + p.nome + '</td>' +
            '<td>' + (p.marca || '—') + '</td>' +
            '<td><span class="badge badge-blue">' + p.categoria + '</span></td>' +
            '<td>' + (p.unidade || 'UN') + '</td>' +
            '<td class="font-600">' + moeda(p.preco) + '</td>' +
            '<td>' + estoque + ' un.</td>' +
            '<td><div class="action-group">' +
                '<button class="btn btn-sm btn-outline" onclick="openModalEditarProduto(' + p.id + ')" title="Editar produto">✏️ Editar</button>' +
                '<button class="btn btn-sm btn-danger" onclick="deleteProduto(' + p.id + ')" title="Excluir produto">🗑️</button>' +
            '</div></td>' +
        '</tr>';
    });
    
    tbody.innerHTML = rows || '<tr><td colspan="8"><div class="empty-state"><div class="empty-state-icon">📦</div><div class="empty-state-text">Nenhum produto encontrado</div></div></td></tr>';
    
    setText('pag-info-prod', 'Pág. ' + state.pagProduto + ' / ' + totalPags + ' (' + total + ' produto' + (total !== 1 ? 's' : '') + ')');
    setDisabled('btn-pag-prev', state.pagProduto <= 1);
    setDisabled('btn-pag-next', state.pagProduto >= totalPags);
}

function filtrarProdutos() {
    state.pagProduto = 1;
    renderProdutos();
}

function mudarPagina(dir) {
    if (dir === 'prev' && state.pagProduto > 1) state.pagProduto--;
    else if (dir === 'next') state.pagProduto++;
    renderProdutos();
}

async function deleteProduto(id) {
    var p = byId(appData.produtos, id);
    if (!p) return;
    
    if (!confirm('Excluir "' + p.nome + '"?\n\nLotes associados também serão removidos.')) return;
    
    try {
        const response = await fetch('api.php?action=delete_produto', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        });
        
        const result = await response.json();
        if (result.success) {
            toast('Produto "' + p.nome + '" excluído.', 'info');
            carregarDadosDoBanco();
        }
    } catch (error) {
        toast('Erro ao excluir produto.', 'erro');
    }
}

/* ──────────────────────────────────────
LOTES
────────────────────────────────────── */
function setFiltroLote(chave) {
    state.filtroLote = chave;
    var btns = document.querySelectorAll('[data-filtro]');
    for (var i = 0; i < btns.length; i++) {
        btns[i].classList.toggle('active', btns[i].getAttribute('data-filtro') === chave);
    }
    renderLotes();
}

function renderLotes() {
    var filtrados = appData.lotes.filter(function(l) {
        return state.filtroLote === 'todos' || calcStatus(l.dataValidade).chave === state.filtroLote;
    });
    
    var tbody = document.getElementById('lotes-tbody');
    if (!tbody) return;
    
    var rows = '';
    filtrados.forEach(function(l) {
        var prod = byId(appData.produtos, l.idProduto);
        var dias = diasParaVencer(l.dataValidade);
        var st   = calcStatus(l.dataValidade);
        var val  = moeda(parseFloat(l.qtdAtual) * parseFloat(l.custo));
        var pct  = l.qtdInicial > 0 ? Math.round((l.qtdAtual / l.qtdInicial) * 100) : 0;
        var cls  = st.chave === 'critico' || st.chave === 'vencido' ? 'red' : st.chave === 'risco' ? 'amber' : 'green';
        
        rows += '<tr>' +
            '<td class="td-mono">' + l.numero + '</td>' +
            '<td class="td-name">' + (prod ? (prod.icone || '📦') + ' ' + prod.nome : '—') + '</td>' +
            '<td>' + fmtData(l.dataFabricacao) + '</td>' +
            '<td>' + fmtData(l.dataValidade) + '</td>' +
            '<td><div class="flex items-center gap-8">' +
                '<div class="progress-wrap"><div class="progress-fill ' + cls + '" style="width:' + pct + '%"></div></div>' +
                '<span class="text-sm text-muted" style="white-space:nowrap">' + l.qtdAtual + '/' + l.qtdInicial + '</span>' +
            '</div></td>' +
            '<td><span class="badge ' + st.badge + '">' + st.icon + ' ' + st.label + (dias > 0 ? ' (' + dias + 'd)' : '') + '</span></td>' +
            '<td class="font-600">' + val + '</td>' +
            '<td><div class="action-group">' +
                '<button class="btn btn-sm btn-outline" onclick="openModalEditarLote(' + l.id + ')" title="Editar">✏️</button>' +
                '<button class="btn btn-sm btn-danger" onclick="deleteLote(' + l.id + ')" title="Excluir">🗑️</button>' +
            '</div></td>' +
        '</tr>';
    });
    
    tbody.innerHTML = rows || '<tr><td colspan="8"><div class="empty-state"><div class="empty-state-icon"></div><div class="empty-state-text">Nenhum lote para este filtro</div></div></td></tr>';
}

async function deleteLote(id) {
    var l = byId(appData.lotes, id);
    if (!l || !confirm('Excluir o lote ' + l.numero + '?')) return;
    
    try {
        const response = await fetch('api.php?action=delete_lote', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        });
        
        const result = await response.json();
        if (result.success) {
            toast('Lote ' + l.numero + ' excluído.', 'info');
            carregarDadosDoBanco();
        }
    } catch (error) {
        toast('Erro ao excluir lote.', 'erro');
    }
}

/* ──────────────────────────────────────
ALERTAS
────────────────────────────────────── */
function renderAlertas() {
    var container = document.getElementById('alertas-container');
    if (!container) return;
    
    if (appData.alertas.length === 0) {
        container.innerHTML = '<div class="empty-state"><div class="empty-state-icon">🎉</div><div class="empty-state-text">Nenhum alerta ativo no momento.</div></div>';
        return;
    }
    
    var html = '';
    appData.alertas.forEach(function(a) {
        var lote = byId(appData.lotes, a.idLote);
        var prod = lote ? byId(appData.produtos, lote.idProduto) : null;
        var dias = lote ? diasParaVencer(lote.dataValidade) : 0;
        var val  = lote ? moeda(parseFloat(lote.qtdAtual) * parseFloat(lote.custo)) : '—';
        var tipo = a.tipo.toLowerCase();
        
        var compradores = [];
        if (prod) {
            appData.vendas.forEach(function(v) {
                if (v.itens) {
                    var found = v.itens.some(function(item) {
                        var lt = byId(appData.lotes, item.idLote);
                        return lt && lt.idProduto == prod.id;
                    });
                    if (found) {
                        var cli = byId(appData.clientes, v.idCliente);
                        if (cli && compradores.indexOf(cli.nome) === -1) compradores.push(cli.nome);
                    }
                }
            });
        }
        
        var icons = { critico: '🔴', risco: '🟡', atencao: '', vencido: '⚫' };
        var badgeClass = { critico: 'badge-red', risco: 'badge-amber', atencao: 'badge-blue', vencido: 'badge-dark' };
        
        html += '<div class="alert-card-full ' + tipo + '">' +
            '<div class="alert-card-icon">' + (icons[tipo] || '⚠️') + '</div>' +
            '<div class="alert-card-content">' +
                '<div class="alert-card-top">' +
                    '<div class="alert-card-title">' + (prod ? (prod.icone || '📦') + ' ' + prod.nome : '—') + ' — ' + (lote ? lote.numero : '') + '</div>' +
                    '<span class="badge ' + (badgeClass[tipo] || 'badge-gray') + '">' + a.tipo + '</span>' +
                '</div>' +
                '<div class="alert-card-meta">' +
                    a.msg + ' · Vence em: <strong>' + fmtData(lote ? lote.dataValidade : '') + '</strong> · ' +
                    (dias <= 0 ? 'Produto vencido · ' : dias + ' dias restantes · ') +
                    'Valor exposto: <strong class="text-danger">' + val + '</strong>' +
                '</div>' +
                (compradores.length ? '<div class="alert-card-compradores">👥 <strong>' + compradores.length + ' cliente(s) comprou este produto:</strong> ' + compradores.join(', ') + '</div>' : '') +
                '<div class="alert-actions">' +
                    '<button class="btn btn-sm btn-amber" onclick="openModalCupom(' + (lote ? lote.id : 0) + ')">🎟️ Gerar Cupom de Desconto</button>' +
                '</div>' +
            '</div>' +
        '</div>';
    });
    
    container.innerHTML = html;
}

/* ─────────────────────────────────────
CLIENTES
────────────────────────────────────── */
function renderClientes() {
    var tbody = document.getElementById('clientes-tbody');
    if (!tbody) return;
    
    var rows = '';
    appData.clientes.forEach(function(c) {
        var vc = appData.vendas.filter(function(v) { return v.idCliente == c.id; });
        var totalGasto = vc.reduce(function(acc, v) { 
            return acc + (parseFloat(v.total) || 0); 
        }, 0);
        
        rows += '<tr>' +
            '<td><div class="flex items-center gap-8">' +
                '<div class="avatar avatar-sm">' + initials(c.nome) + '</div>' +
                '<span class="td-name">' + c.nome + '</span>' +
            '</div></td>' +
            '<td>' + c.email + '</td>' +
            '<td>' + c.telefone + '</td>' +
            '<td class="text-sm">' + c.cpf + '</td>' +
            '<td>' + vc.length + ' compra' + (vc.length !== 1 ? 's' : '') + '</td>' +
            '<td class="font-700">' + moeda(totalGasto) + '</td>' +
            '<td><div class="action-group">' +
                '<button class="btn btn-sm btn-outline" onclick="openModalEditarCliente(' + c.id + ')" title="Editar cliente">✏️ Editar</button>' +
                '<button class="btn btn-sm btn-danger" onclick="deleteCliente(' + c.id + ')" title="Excluir cliente">🗑️</button>' +
            '</div></td>' +
        '</tr>';
    });
    
    tbody.innerHTML = rows || '<tr><td colspan="7"><div class="empty-state"><div class="empty-state-icon">👥</div><div class="empty-state-text">Nenhum cliente cadastrado</div></div></td></tr>';
}

async function deleteCliente(id) {
    var c = byId(appData.clientes, id);
    if (!c || !confirm('Excluir "' + c.nome + '"?')) return;
    
    try {
        const response = await fetch('api.php?action=delete_cliente', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        });
        
        const result = await response.json();
        if (result.success) {
            toast('Cliente "' + c.nome + '" excluído.', 'info');
            carregarDadosDoBanco();
        }
    } catch (error) {
        toast('Erro ao excluir cliente.', 'erro');
    }
}

/* ──────────────────────────────────────
VENDAS
────────────────────────────────────── */
function renderVendas() {
    var tbody = document.getElementById('vendas-tbody');
    if (!tbody) return;
    
    var rows = '';
    appData.vendas.forEach(function(v) {
        var cli = byId(appData.clientes, v.idCliente);
        var cup = v.idCupom ? byId(appData.cupons, v.idCupom) : null;
        
        rows += '<tr>' +
            '<td class="td-mono">#' + String(v.id).padStart(4, '0') + '</td>' +
            '<td>' + fmtData(v.data) + '</td>' +
            '<td><div class="flex items-center gap-8">' +
                (cli ? '<div class="avatar avatar-sm">' + initials(cli.nome) + '</div>' : '') +
                '<span>' + (cli ? cli.nome : '<em class="text-muted">Não identificado</em>') + '</span>' +
            '</div></td>' +
            '<td>' + (v.itens ? v.itens.length : 0) + ' item' + ((v.itens ? v.itens.length : 0) !== 1 ? 's' : '') + '</td>' +
            '<td>' + (cup ? '<span class="badge badge-green">🎟️ ' + cup.codigo + '</span>' : '<span class="text-muted">—</span>') + '</td>' +
            '<td class="font-700">' + moeda(v.total) + '</td>' +
            '<td><span class="badge badge-green">✅ Concluída</span></td>' +
            '<td><button class="btn btn-sm btn-outline" onclick="verDetalhesVenda(' + v.id + ')">👁️ Ver itens</button> ' +
            '<button class="btn btn-sm btn-danger" onclick="excluirVenda(' + v.id + ')">🗑️</button></td>' +
        '</tr>';
    });
    
    tbody.innerHTML = rows || '<tr><td colspan="8"><div class="empty-state"><div class="empty-state-icon">🛒</div><div class="empty-state-text">Nenhuma venda registrada</div></div></td></tr>';
}

/* ──────────────────────────────────────
CUPONS
────────────────────────────────────── */
function renderCupons() {
    var tbody = document.getElementById('cupons-tbody');
    if (!tbody) return;
    
    var rows = '';
    appData.cupons.forEach(function(c) {
        var al = appData.alertas.find(function(a) { return a.id == c.idAlerta; });
        var lt = al ? byId(appData.lotes, al.idLote) : null;
        var pr = lt ? byId(appData.produtos, lt.idProduto) : null;
        var nomeProduto = pr ? (pr.icone || '📦') + ' ' + pr.nome : '—';
        
        var scl = c.status === 'Ativo' ? 'badge-green' : c.status === 'Utilizado' ? 'badge-gray' : 'badge-red';
        
        rows += '<tr>' +
            '<td class="td-mono" style="letter-spacing:1.5px">' + c.codigo + '</td>' +
            '<td>' + nomeProduto + '</td>' +
            '<td><span class="font-700 text-blue">' + c.desconto + '%</span> off</td>' +
            '<td>' + fmtData(c.expiracao) + '</td>' +
            '<td><span class="badge ' + scl + '">' + c.status + '</span></td>' +
            '<td><div class="action-group">' +
                '<button class="btn btn-sm btn-outline" onclick="openModalEditarCupom(' + c.id + ')">✏️ Editar</button>' +
                '<button class="btn btn-sm btn-danger" onclick="deleteCupom(' + c.id + ')">🗑️</button>' +
            '</div></td>' +
        '</tr>';
    });
    
    tbody.innerHTML = rows || '<tr><td colspan="6"><div class="empty-state"><div class="empty-state-icon">🎟️</div><div class="empty-state-text">Nenhum cupom gerado</div></div></td></tr>';
}

function openModalNovoCupom() {
    var sel = document.getElementById('nc-produto');
    if (sel) {
        sel.innerHTML = '<option value="">— Nenhum —</option>';
        appData.produtos.forEach(function(p) {
            var opt = document.createElement('option');
            opt.value = p.id;
            opt.textContent = (p.icone || '📦') + ' ' + p.nome;
            sel.appendChild(opt);
        });
    }
    
    var dtEl = document.getElementById('nc-expira');
    if (dtEl) {
        var dt = new Date();
        dt.setDate(dt.getDate() + 30);
        dtEl.value = dt.toISOString().split('T')[0];
    }
    
    setVal('nc-codigo', '');
    setVal('nc-desconto', '15');
    openModal('modal-novo-cupom');
}

async function salvarNovoCupom() {
    var codigo   = (getVal('nc-codigo') || '').trim().toUpperCase();
    var desconto = parseInt(getVal('nc-desconto'), 10);
    var expira   = getVal('nc-expira');
    var idProd   = parseInt(getVal('nc-produto'), 10) || null;
    
    if (!codigo) { toast('Informe o código do cupom.', 'erro'); return; }
    if (!desconto || desconto < 1 || desconto > 80) { toast('Desconto deve ser entre 1% e 80%.', 'erro'); return; }
    if (!expira) { toast('Informe a data de expiração.', 'erro'); return; }
    
    try {
        const response = await fetch('api.php?action=save_cupom', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                idProduto: idProd,
                codigo: codigo,
                desconto: desconto,
                expiracao: expira,
                status: 'Ativo'
            })
        });
        
        const result = await response.json();
        if (result.success) {
            closeModal('modal-novo-cupom');
            toast('Cupom "' + codigo + '" criado!', 'sucesso');
            carregarDadosDoBanco();
        }
    } catch (error) {
        toast('Erro ao salvar cupom.', 'erro');
    }
}

function openModalEditarCupom(id) {
    var c = byId(appData.cupons, id);
    if (!c) return;
    
    setVal('ecp-id',       c.id);
    setVal('ecp-codigo',   c.codigo);
    setVal('ecp-desconto', c.desconto);
    setVal('ecp-expira',   c.expiracao);
    setVal('ecp-status',   c.status);
    openModal('modal-editar-cupom');
}

async function salvarEdicaoCupom() {
    var id       = parseInt(getVal('ecp-id'), 10);
    var codigo   = (getVal('ecp-codigo') || '').trim().toUpperCase();
    var desconto = parseInt(getVal('ecp-desconto'), 10);
    var expira   = getVal('ecp-expira');
    var status   = getVal('ecp-status');
    
    if (!codigo) { toast('Informe o código.', 'erro'); return; }
    if (!desconto || desconto < 1 || desconto > 80) { toast('Desconto deve ser entre 1% e 80%.', 'erro'); return; }
    if (!expira) { toast('Informe a data de expiração.', 'erro'); return; }
    
    try {
        const response = await fetch('api.php?action=update_cupom', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: id,
                codigo: codigo,
                desconto: desconto,
                expiracao: expira,
                status: status
            })
        });
        
        const result = await response.json();
        if (result.success) {
            closeModal('modal-editar-cupom');
            toast('Cupom "' + codigo + '" atualizado!', 'sucesso');
            carregarDadosDoBanco();
        }
    } catch (error) {
        toast('Erro ao atualizar cupom.', 'erro');
    }
}

async function deleteCupom(id) {
    var c = byId(appData.cupons, id);
    if (!c) return;
    if (!confirm('Excluir o cupom "' + c.codigo + '"?')) return;
    
    try {
        const response = await fetch('api.php?action=delete_cupom', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        });
        
        const result = await response.json();
        if (result.success) {
            toast('Cupom "' + c.codigo + '" excluído.', 'info');
            carregarDadosDoBanco();
        }
    } catch (error) {
        toast('Erro ao excluir cupom.', 'erro');
    }
}

/* ──────────────────────────────────────
RELATÓRIOS
────────────────────────────────────── */
function renderRelatorios() {
    var totalVendas = appData.vendas.reduce(function(acc, v) { 
        return acc + (parseFloat(v.total) || 0); 
    }, 0);
    
    var vencidos = appData.lotes.filter(function(l) { 
        return diasParaVencer(l.dataValidade) <= 0; 
    });
    
    var perdasReais = vencidos.reduce(function(acc, l) { 
        return acc + (parseFloat(l.qtdAtual) * parseFloat(l.custo)); 
    }, 0);
    
    var cupsUsados = appData.cupons.filter(function(c) { 
        return c.status === 'Utilizado'; 
    }).length;
    
    // ✅ CÁLCULO DINÂMICO DE PERDAS EVITADAS
    var perdasEvit = 0;
    
    // Para cada venda que usou cupom, calcula quanto seria perdido
    appData.vendas.forEach(function(v) {
        if (v.idCupom !== null) {
            // Pega o cupom usado
            var cupom = appData.cupons.find(function(c) { return c.id == v.idCupom; });
            if (cupom) {
                // Calcula o valor original da venda (sem desconto)
                var valorOriginal = parseFloat(v.total) / (1 - (cupom.desconto / 100));
                // A perda evitada é a diferença entre o valor original e o valor com desconto
                // Mais uma estimativa de que sem o cupom, essa venda não aconteceria
                perdasEvit += (valorOriginal - parseFloat(v.total));
            }
        }
    });
}

/* ──────────────────────────────────────
MODAIS — HELPERS
────────────────────────────────────── */
function openModal(id) {
    var el = document.getElementById(id);
    if (el) el.classList.add('open');
}

function closeModal(id) {
    var el = document.getElementById(id);
    if (el) el.classList.remove('open');
}

/* ──────────────────────────────────────
MODAL CUPOM
────────────────────────────────────── */
function openModalCupom(idLote) {
    var lote = byId(appData.lotes, idLote);
    var prod = lote ? byId(appData.produtos, lote.idProduto) : null;
    var dias = lote ? diasParaVencer(lote.dataValidade) : 0;
    var val  = lote ? moeda(parseFloat(lote.qtdAtual) * parseFloat(lote.custo)) : '—';
    
    var compradores = [];
    if (prod) {
        appData.vendas.forEach(function(v) {
            if (v.itens) {
                var ok = v.itens.some(function(item) {
                    var lt = byId(appData.lotes, item.idLote);
                    return lt && lt.idProduto == prod.id;
                });
                if (ok) {
                    var c = byId(appData.clientes, v.idCliente);
                    if (c && compradores.indexOf(c.nome) === -1) compradores.push(c.nome);
                }
            }
        });
    }
    
    var body = document.getElementById('modal-cupom-body');
    if (body) {
        body.innerHTML = '<div class="cupom-preview">' +
            '<div class="cupom-preview-title">🎟️ ' + (prod ? (prod.icone || '📦') + ' ' + prod.nome : '—') + '</div>' +
            '<div class="cupom-preview-info">Lote: ' + (lote ? lote.numero : '—') + ' · Vence em: ' + fmtData(lote ? lote.dataValidade : '') + '</div>' +
            '<div class="cupom-preview-value">' + (dias > 0 ? dias + ' dias' : 'VENCIDO') + '</div>' +
            '<div class="cupom-preview-info">Valor em risco: ' + val + '</div>' +
        '</div>' +
        '<div class="form-grid-2">' +
            '<div class="form-group">' +
                '<label class="form-label" for="cup-desconto">Desconto (%) <span class="required">*</span></label>' +
                '<input class="form-input" type="number" id="cup-desconto" value="15" min="1" max="80">' +
            '</div>' +
            '<div class="form-group">' +
                '<label class="form-label" for="cup-expira">Expiração <span class="required">*</span></label>' +
                '<input class="form-input" type="date" id="cup-expira">' +
            '</div>' +
        '</div>' +
        (compradores.length ? '<div class="alert-card blue mt-12"><div class="alert-body"><div class="alert-title">👥 Clientes aptos a receber este cupom</div><div class="alert-desc">' + compradores.join(', ') + '</div></div></div>' : '<p class="text-muted text-sm mt-8">Nenhum comprador histórico encontrado para este produto.</p>');
    }
    
    var expEl = document.getElementById('cup-expira');
    if (expEl) {
        var dt = new Date();
        dt.setDate(dt.getDate() + 14);
        expEl.value = dt.toISOString().split('T')[0];
    }
    
    var hidEl = document.getElementById('cup-lote-id');
    if (hidEl) hidEl.value = idLote;
    
    openModal('modal-cupom');
}

function closeModalCupom() { closeModal('modal-cupom'); }

async function confirmarCupom() {
    var desc   = parseInt(getVal('cup-desconto'), 10);
    var expira = getVal('cup-expira');
    var idLote = parseInt(getVal('cup-lote-id'), 10);
    
    if (!desc || desc < 1 || desc > 80) { toast('Desconto deve ser entre 1% e 80%.', 'erro'); return; }
    if (!expira) { toast('Informe a data de expiração.', 'erro'); return; }
    
    var lote = byId(appData.lotes, idLote);
    var prod = lote ? byId(appData.produtos, lote.idProduto) : null;
    var pref = prod ? prod.nome.split(' ')[0].toUpperCase().substring(0, 5) : 'FIT';
    var codigo = pref + desc + String(Math.floor(Math.random() * 900) + 100);
    
    try {
        const response = await fetch('api.php?action=save_cupom', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                idProduto: prod ? prod.id : null,
                codigo: codigo,
                desconto: desc,
                expiracao: expira,
                status: 'Ativo'
            })
        });
        
        const result = await response.json();
        if (result.success) {
            closeModalCupom();
            toast('Cupom "' + codigo + '" gerado com ' + desc + '% off!', 'sucesso');
            carregarDadosDoBanco();
        }
    } catch (error) {
        toast('Erro ao gerar cupom.', 'erro');
    }
}

/* ──────────────────────────────────────
MODAL PRODUTO — CRIAR
────────────────────────────────────── */
function openModalProduto() {
    var form = document.getElementById('form-produto');
    if (form) form.reset();
    openModal('modal-produto');
}

function closeModalProduto() { closeModal('modal-produto'); }

async function salvarProduto() {
    var nome  = (getVal('pf-nome') || '').trim();
    var marca = (getVal('pf-marca') || '').trim();
    var cat   = getVal('pf-categoria');
    var preco = parseFloat(getVal('pf-preco'));
    var un    = getVal('pf-unidade') || 'UN';
    var icone = getVal('pf-icone') || '';
    
    if (!nome || !marca || !cat || !preco || preco <= 0) {
        toast('Preencha todos os campos obrigatórios.', 'erro');
        return;
    }
    
    try {
        const response = await fetch('api.php?action=save_produto', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                nome: nome,
                marca: marca,
                categoria: cat,
                unidade: un,
                preco: preco,
                icone: icone,
                estoque: 0
            })
        });
        
        const result = await response.json();
        if (result.success) {
            closeModalProduto();
            toast('Produto "' + nome + '" cadastrado!', 'sucesso');
            carregarDadosDoBanco();
        } else {
            toast('Erro ao salvar: ' + (result.error || 'Falha'), 'erro');
        }
    } catch (error) {
        toast('Erro de conexão ao salvar produto.', 'erro');
    }
}

/* ──────────────────────────────────────
MODAL PRODUTO — EDITAR
────────────────────────────────────── */
function openModalEditarProduto(id) {
    var p = byId(appData.produtos, id);
    if (!p) return;
    
    var corpo = document.getElementById('modal-editar-prod-body');
    if (corpo) {
        corpo.innerHTML = '<div class="form-grid-2">' +
            '<div class="form-group form-col-full">' +
                '<label class="form-label" for="ep-nome">Nome <span class="required">*</span></label>' +
                '<input class="form-input" type="text" id="ep-nome" value="' + esc(p.nome) + '">' +
            '</div>' +
            '<div class="form-group">' +
                '<label class="form-label" for="ep-marca">Marca <span class="required">*</span></label>' +
                '<input class="form-input" type="text" id="ep-marca" value="' + esc(p.marca || '') + '">' +
            '</div>' +
            '<div class="form-group">' +
                '<label class="form-label" for="ep-preco">Preço (R$) <span class="required">*</span></label>' +
                '<input class="form-input" type="number" id="ep-preco" value="' + p.preco + '" min="0" step="0.01">' +
            '</div>' +
        '</div>';
    }
    
    var hidEl = document.getElementById('ep-id');
    if (hidEl) hidEl.value = id;
    
    openModal('modal-editar-produto');
}

function closeModalEditarProduto() { closeModal('modal-editar-produto'); }

async function salvarEdicaoProduto() {
    var id    = parseInt(getVal('ep-id'), 10);
    var nome  = (getVal('ep-nome') || '').trim();
    var marca = (getVal('ep-marca') || '').trim();
    var preco = parseFloat(getVal('ep-preco'));
    
    if (!nome || !marca || !preco || preco <= 0) { 
        toast('Preencha todos os campos.', 'erro'); 
        return; 
    }
    
    try {
        const response = await fetch('api.php?action=update_produto', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: id,
                nome: nome,
                marca: marca,
                preco: preco
            })
        });
        
        const result = await response.json();
        if (result.success) {
            closeModalEditarProduto();
            toast('Produto "' + nome + '" atualizado!', 'sucesso');
            carregarDadosDoBanco();
        }
    } catch (error) {
        toast('Erro ao atualizar produto.', 'erro');
    }
}

/* ──────────────────────────────────────
MODAL LOTE
────────────────────────────────────── */
function openModalLote() {
    var form = document.getElementById('form-lote');
    if (form) form.reset();
    setVal('lf-produto-id', '');
    setVal('lf-busca-produto', '');
    
    var lista = document.getElementById('autocomplete-lista');
    if (lista) { lista.innerHTML = ''; lista.classList.remove('open'); }
    
    openModal('modal-lote');
}

function closeModalLote() { closeModal('modal-lote'); }

function autocompleteProduto() {
    var q = (getVal('lf-busca-produto') || '').toLowerCase().trim();
    var lista = document.getElementById('autocomplete-lista');
    if (!lista) return;
    
    if (q.length < 1) { 
        lista.innerHTML = ''; 
        lista.classList.remove('open'); 
        return; 
    }
    
    var sug = appData.produtos.filter(function(p) {
        return p.nome.toLowerCase().indexOf(q) > -1 || (p.marca && p.marca.toLowerCase().indexOf(q) > -1);
    }).slice(0, 7);
    
    if (sug.length === 0) {
        lista.innerHTML = '<div class="autocomplete-empty">Nenhum produto encontrado</div>';
    } else {
        lista.innerHTML = sug.map(function(p) {
            return '<div class="autocomplete-item" onclick="selecionarProduto(' + p.id + ', \'' + esc(p.nome) + '\')">' +
                (p.icone || '📦') + ' <strong>' + p.nome + '</strong> — <em>' + (p.marca || '') + '</em>' +
            '</div>';
        }).join('');
    }
    
    lista.classList.add('open');
}

function selecionarProduto(id, nome) {
    setVal('lf-busca-produto', nome);
    setVal('lf-produto-id', id);
    
    var lista = document.getElementById('autocomplete-lista');
    if (lista) { lista.innerHTML = ''; lista.classList.remove('open'); }
}

async function salvarLote() {
    var idProd = parseInt(getVal('lf-produto-id'), 10);
    var num    = (getVal('lf-numero') || '').trim();
    var qtd    = parseInt(getVal('lf-qtd'), 10);
    var fab    = getVal('lf-fabricacao');
    var val    = getVal('lf-validade');
    var custo  = parseFloat(getVal('lf-custo'));
    
    if (!idProd || !num || !qtd || !fab || !val || !custo || custo <= 0) {
        toast('Preencha todos os campos obrigatórios.', 'erro'); 
        return;
    }
    if (val <= fab) { 
        toast('Validade deve ser posterior à fabricação.', 'erro'); 
        return; 
    }
    if (qtd < 1) { 
        toast('Quantidade deve ser maior que zero.', 'erro'); 
        return; 
    }
    
    try {
        const response = await fetch('api.php?action=save_lote', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                produto_id: idProd,
                codigo_lote: num,
                dataFabricacao: fab,
                validade: val,
                qtd_inicial: qtd,
                custo: custo
            })
        });
        
        const result = await response.json();
        if (result.success) {
            closeModalLote();
            toast('Lote "' + num + '" cadastrado!', 'sucesso');
            carregarDadosDoBanco();
        } else {
            toast('Erro ao salvar lote: ' + (result.error || 'Falha'), 'erro');
        }
    } catch (error) {
        toast('Erro de conexão ao salvar lote.', 'erro');
    }
}

/* ──────────────────────────────────────
MODAL CLIENTE — CRIAR
────────────────────────────────────── */
function openModalCliente() {
    var form = document.getElementById('form-cliente');
    if (form) form.reset();
    openModal('modal-cliente');
}

function closeModalCliente() { closeModal('modal-cliente'); }

async function salvarCliente() {
    var nome  = (getVal('cf-nome') || '').trim();
    var email = (getVal('cf-email') || '').trim();
    var tel   = (getVal('cf-telefone') || '').trim();
    var cpf   = (getVal('cf-cpf') || '').trim();
    var nasc  = getVal('cf-nascimento');
    var obs   = (getVal('cf-obs') || '').trim();
    
    if (!nome || !email || !tel || !cpf) {
        toast('Preencha nome, e-mail, telefone e CPF.', 'erro'); 
        return;
    }
    if (!/\S+@\S+\.\S+/.test(email)) { 
        toast('E-mail inválido.', 'erro'); 
        return; 
    }
    
    try {
        const response = await fetch('api.php?action=save_cliente', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                nome: nome,
                email: email,
                telefone: tel,
                cpf: cpf,
                nascimento: nasc,
                observacoes: obs
            })
        });
        
        const result = await response.json();
        if (result.success) {
            closeModalCliente();
            toast('Cliente "' + nome + '" cadastrado!', 'sucesso');
            carregarDadosDoBanco();
        } else {
            toast('Erro ao salvar cliente.', 'erro');
        }
    } catch (error) {
        toast('Erro de conexão ao salvar cliente.', 'erro');
    }
}

/* ──────────────────────────────────────
MODAL CLIENTE — EDITAR
────────────────────────────────────── */
function openModalEditarCliente(id) {
    var c = byId(appData.clientes, id);
    if (!c) return;
    
    var corpo = document.getElementById('modal-editar-cli-body');
    if (corpo) {
        corpo.innerHTML = '<div class="form-grid-2">' +
            '<div class="form-group form-col-full">' +
                '<label class="form-label" for="ec-nome">Nome Completo <span class="required">*</span></label>' +
                '<input class="form-input" type="text" id="ec-nome" value="' + esc(c.nome) + '">' +
            '</div>' +
            '<div class="form-group">' +
                '<label class="form-label" for="ec-email">E-mail <span class="required">*</span></label>' +
                '<input class="form-input" type="email" id="ec-email" value="' + esc(c.email) + '">' +
            '</div>' +
            '<div class="form-group">' +
                '<label class="form-label" for="ec-telefone">Telefone <span class="required">*</span></label>' +
                '<input class="form-input" type="tel" id="ec-telefone" value="' + esc(c.telefone) + '">' +
            '</div>' +
            '<div class="form-group">' +
                '<label class="form-label" for="ec-cpf">CPF</label>' +
                '<input class="form-input" type="text" id="ec-cpf" value="' + esc(c.cpf) + '" maxlength="14">' +
            '</div>' +
            '<div class="form-group form-col-full">' +
                '<label class="form-label" for="ec-obs">Observações</label>' +
                '<textarea class="form-textarea" id="ec-obs">' + esc(c.obs || '') + '</textarea>' +
            '</div>' +
        '</div>';
    }
    
    var hidEl = document.getElementById('ec-id');
    if (hidEl) hidEl.value = id;
    
    openModal('modal-editar-cliente');
}

function closeModalEditarCliente() { closeModal('modal-editar-cliente'); }

async function salvarEdicaoCliente() {
    var id    = parseInt(getVal('ec-id'), 10);
    var nome  = (getVal('ec-nome') || '').trim();
    var email = (getVal('ec-email') || '').trim();
    var tel   = (getVal('ec-telefone') || '').trim();
    var cpf   = (getVal('ec-cpf') || '').trim();
    var obs   = (getVal('ec-obs') || '').trim();
    
    if (!nome || !email || !tel) { 
        toast('Nome, e-mail e telefone são obrigatórios.', 'erro'); 
        return; 
    }
    if (!/\S+@\S+\.\S+/.test(email)) { 
        toast('E-mail inválido.', 'erro'); 
        return; 
    }
    
    try {
        const response = await fetch('api.php?action=update_cliente', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: id,
                nome: nome,
                email: email,
                telefone: tel,
                cpf: cpf,
                observacoes: obs
            })
        });
        
        const result = await response.json();
        if (result.success) {
            closeModalEditarCliente();
            toast('Cliente "' + nome + '" atualizado!', 'sucesso');
            carregarDadosDoBanco();
        }
    } catch (error) {
        toast('Erro ao atualizar cliente.', 'erro');
    }
}

/* ──────────────────────────────────────
MODAL VENDA
────────────────────────────────────── */
function openModalVenda() {
    var selCli = document.getElementById('vf-cliente');
    if (selCli) {
        selCli.innerHTML = '<option value="">— Consumidor não identificado —</option>';
        appData.clientes.forEach(function(c) {
            var opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.nome;
            selCli.appendChild(opt);
        });
    }
    
    var selLote = document.getElementById('vf-lote');
    if (selLote) {
        selLote.innerHTML = '<option value="">— Selecione um lote —</option>';
        appData.lotes.forEach(function(l) {
            var p = byId(appData.produtos, l.idProduto);
            var dis = l.qtdAtual < 1;
            var opt = document.createElement('option');
            opt.value = l.id;
            opt.textContent = (p ? (p.icone || '📦') + ' ' + p.nome : '—') + ' — ' + l.numero + ' (' + l.qtdAtual + ' un.) Val: ' + fmtData(l.dataValidade);
            if (dis) opt.disabled = true;
            selLote.appendChild(opt);
        });
    }
    
    var selCup = document.getElementById('vf-cupom');
    if (selCup) {
        selCup.innerHTML = '<option value="">— Sem cupom —</option>';
        appData.cupons.filter(function(c) { return c.status === 'Ativo'; }).forEach(function(c) {
            var opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.codigo + ' (' + c.desconto + '% off)';
            selCup.appendChild(opt);
        });
    }
    
    var dtEl = document.getElementById('vf-data');
    if (dtEl) dtEl.value = today();
    
    calcularTotalVenda();
    openModal('modal-venda');
}

function closeModalVenda() { closeModal('modal-venda'); }

function calcularTotalVenda() {
    var idLote = parseInt(getVal('vf-lote'), 10);
    var qtd    = parseInt(getVal('vf-qtd'), 10) || 1;
    var idCup  = parseInt(getVal('vf-cupom'), 10);
    
    var lote = byId(appData.lotes, idLote);
    var prod = lote ? byId(appData.produtos, lote.idProduto) : null;
    var sub  = prod ? parseFloat(prod.preco) * qtd : 0;
    var desc = 0;
    
    if (idCup) {
        var cup = byId(appData.cupons, idCup);
        if (cup) desc = sub * (cup.desconto / 100);
    }
    
    var el = document.getElementById('vf-total');
    if (el) el.value = moeda(sub - desc);
}

async function salvarVenda() {
    var idCliente = parseInt(getVal('vf-cliente'), 10) || null;
    var idLote    = parseInt(getVal('vf-lote'), 10);
    var qtd       = parseInt(getVal('vf-qtd'), 10);
    var idCupom   = parseInt(getVal('vf-cupom'), 10) || null;
    var data      = getVal('vf-data');
    
    if (!idLote) { toast('Selecione um lote.', 'erro'); return; }
    if (!qtd || qtd < 1) { toast('Informe uma quantidade válida.', 'erro'); return; }
    
    var lote = byId(appData.lotes, idLote);
    if (!lote) { toast('Lote não encontrado.', 'erro'); return; }
    if (qtd > lote.qtdAtual) {
        toast('Estoque insuficiente. Disponível: ' + lote.qtdAtual + ' un.', 'erro'); 
        return;
    }
    
    var prod = byId(appData.produtos, lote.idProduto);
    var sub  = prod ? parseFloat(prod.preco) * qtd : 0;
    var desc = 0;
    
    if (idCupom) {
        var cup = byId(appData.cupons, idCupom);
        if (cup) desc = sub * (cup.desconto / 100);
    }
    
    try {
        const response = await fetch('api.php?action=save_venda', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                cliente_id: idCliente,
                cupom_id: idCupom,
                data: data || today(),
                total: sub - desc,
                itens: [{ idLote: idLote, qtd: qtd, preco: prod ? prod.preco : 0 }]
            })
        });
        
        const result = await response.json();
        if (result.success) {
            closeModalVenda();
            toast('Venda registrada! Total: ' + moeda(sub - desc), 'sucesso');
            carregarDadosDoBanco();
        } else {
            toast('Erro ao registrar venda.', 'erro');
        }
    } catch (error) {
        toast('Erro de conexão ao salvar venda.', 'erro');
    }
}

/* ──────────────────────────────────────
MICRO-UTILITÁRIOS DOM
────────────────────────────────────── */
function setText(id, val) {
    var el = document.getElementById(id);
    if (el) el.textContent = val;
}

function getVal(id) {
    var el = document.getElementById(id);
    return el ? el.value : '';
}

function setVal(id, val) {
    var el = document.getElementById(id);
    if (el) el.value = val;
}

function setDisabled(id, val) {
    var el = document.getElementById(id);
    if (el) el.disabled = val;
}

function esc(s) {
    return String(s || '').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

/* ──────────────────────────────────────
INICIALIZAÇÃO
────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    var loginSenha = document.getElementById('login-senha');
    if (loginSenha) {
        loginSenha.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') doLogin();
        });
    }
    
    var loginEmail = document.getElementById('login-email');
    if (loginEmail) {
        loginEmail.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') doLogin();
        });
    }
    
    var hamburger = document.getElementById('hamburger');
    if (hamburger) {
        hamburger.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });
    }
    
    var overlay = document.getElementById('sidebar-overlay');
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }
    
    var modals = document.querySelectorAll('.modal-overlay');
    for (var i = 0; i < modals.length; i++) {
        (function(m) {
            m.addEventListener('click', function(e) {
                if (e.target === m) m.classList.remove('open');
            });
        })(modals[i]);
    }
    
    document.addEventListener('click', function(e) {
        var lista = document.getElementById('autocomplete-lista');
        var input = document.getElementById('lf-busca-produto');
        if (!lista || !input) return;
        if (!input.contains(e.target) && !lista.contains(e.target)) {
            lista.innerHTML = '';
            lista.classList.remove('open');
        }
    });
    
    var temaSalvo = localStorage.getItem('fitcontrol_tema');
    if (temaSalvo === 'dark') {
        document.body.classList.add('dark-mode');
        var btnTema = document.getElementById('btn-tema');
        if (btnTema) btnTema.textContent = '☀️';
    } else if (!temaSalvo && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.body.classList.add('dark-mode');
        var btnTema2 = document.getElementById('btn-tema');
        if (btnTema2) btnTema2.textContent = '☀️';
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            var opened = document.querySelector('.modal-overlay.open');
            if (opened) opened.classList.remove('open');
        }
    });
    
    // Mostra tela de login inicialmente
    document.getElementById('login-page').classList.remove('hidden');
    document.getElementById('app').classList.add('hidden');
});

/* ──────────────────────────────────────
DETALHES DA VENDA
────────────────────────────────────── */
function verDetalhesVenda(id) {
    var venda = appData.vendas.find(function(v) { return v.id == id; });
    if (!venda) return;
    
    var cli   = byId(appData.clientes, venda.idCliente);
    var cup   = venda.idCupom ? byId(appData.cupons, venda.idCupom) : null;
    
    var html = '<div style="margin-bottom:16px;">' +
        '<p><strong>Data:</strong> ' + fmtData(venda.data) + '</p>' +
        '<p><strong>Cliente:</strong> ' + (cli ? cli.nome : 'Não identificado') + '</p>' +
        (cup ? '<p><strong>Cupom:</strong> ' + cup.codigo + ' (' + cup.desconto + '% off)</p>' : '') +
    '</div>' +
    '<table style="width:100%;border-collapse:collapse;">' +
        '<thead><tr style="border-bottom:2px solid var(--gray-200)">' +
            '<th style="text-align:left;padding:8px 4px;">Produto</th>' +
            '<th style="text-align:center;padding:8px 4px;">Qtd</th>' +
            '<th style="text-align:right;padding:8px 4px;">Preço unit.</th>' +
            '<th style="text-align:right;padding:8px 4px;">Subtotal</th>' +
        '</tr></thead><tbody>';
    
    if (venda.itens) {
        venda.itens.forEach(function(item) {
            var lote    = byId(appData.lotes, item.idLote);
            var produto = lote ? byId(appData.produtos, lote.idProduto) : null;
            html += '<tr style="border-bottom:1px solid var(--gray-100)">' +
                '<td style="padding:10px 4px;">' + (produto ? (produto.icone || '📦') + ' ' + produto.nome : '—') + '</td>' +
                '<td style="text-align:center;padding:10px 4px;">' + item.qtd + '</td>' +
                '<td style="text-align:right;padding:10px 4px;">' + moeda(item.preco) + '</td>' +
                '<td style="text-align:right;padding:10px 4px;font-weight:700;">' + moeda(item.preco * item.qtd) + '</td>' +
            '</tr>';
        });
    }
    
    html += '</tbody><tfoot><tr>' +
        '<td colspan="3" style="text-align:right;padding:12px 4px;font-weight:700;">Total:</td>' +
        '<td style="text-align:right;padding:12px 4px;font-weight:800;font-size:16px;color:var(--blue-600)">' + moeda(venda.total) + '</td>' +
    '</tr></tfoot></table>';
    
    document.getElementById('detalhes-venda-body').innerHTML = html;
    document.getElementById('modal-detalhes-venda').classList.add('open');
}

function fecharDetalhesVenda() {
    document.getElementById('modal-detalhes-venda').classList.remove('open');
}

async function excluirVenda(id) {
    if (!confirm('Deseja excluir esta venda?')) return;
    
    try {
        const response = await fetch('api.php?action=delete_venda', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        });
        
        const result = await response.json();
        if (result.success) {
            toast('Venda excluída com sucesso!', 'sucesso');
            carregarDadosDoBanco();
        }
    } catch (error) {
        toast('Erro ao excluir venda.', 'erro');
    }
}

async function salvarEdicaoLote() {
    var id = Number(document.getElementById('el-id').value);
    var numero = document.getElementById('el-numero').value;
    var fabricacao = document.getElementById('el-fabricacao').value;
    var validade = document.getElementById('el-validade').value;
    var qtdAtual = Number(document.getElementById('el-qtd-atual').value);
    var qtdInicial = Number(document.getElementById('el-qtd-inicial').value);
    var custo = Number(document.getElementById('el-custo').value);
    
    try {
        const response = await fetch('api.php?action=update_lote', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: id,
                numero: numero,
                dataFabricacao: fabricacao,
                dataValidade: validade,
                qtdAtual: qtdAtual,
                qtdInicial: qtdInicial,
                custo: custo
            })
        });
        
        const result = await response.json();
        if (result.success) {
            closeModalEditarLote();
            toast('Lote editado com sucesso!', 'sucesso');
            carregarDadosDoBanco();
        }
    } catch (error) {
        toast('Erro ao editar lote.', 'erro');
    }
}

function openModalEditarLote(id) {
    var lote = byId(appData.lotes, id);
    if (!lote) return;
    
    document.getElementById('el-id').value = lote.id;
    document.getElementById('el-numero').value = lote.numero;
    document.getElementById('el-fabricacao').value = lote.dataFabricacao;
    document.getElementById('el-validade').value = lote.dataValidade;
    document.getElementById('el-qtd-atual').value = lote.qtdAtual;
    document.getElementById('el-qtd-inicial').value = lote.qtdInicial;
    document.getElementById('el-custo').value = lote.custo;
    
    openModal('modal-editar-lote');
}

function closeModalEditarLote() {
    closeModal('modal-editar-lote');
}