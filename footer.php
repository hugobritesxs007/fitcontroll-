        </main><!-- /main-content -->
        <!--
            RODAPÉ DA PÁGINA PRINCIPAL
            Este arquivo fecha as tags abertas pelo header.php:
            <main>, <div class="main-wrapper"> e <div id="app">
        -->
        <footer class="app-footer" role="contentinfo">
            <p>
                FitControl SGPAV &copy; <?= date('Y') ?> —
                IFES — Programação Web —
                Desenvolvido por Hugo, Isadora, William, Bruno e Lucas
            </p>
        </footer>

    </div><!-- /main-wrapper -->

</div><!-- /app -->

<!-- ══════════════════════════════════════════
     SCRIPTS GLOBAIS DO PROJETO
     ══════════════════════════════════════════ -->
<script>
    // ── Hambúrguer: abre/fecha sidebar no mobile ──────────────────────────────
    const hamburger = document.getElementById('hamburger');
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebar-overlay');

    if (hamburger && sidebar) {
        hamburger.addEventListener('click', () => {
            const aberto = sidebar.classList.toggle('open');
            hamburger.setAttribute('aria-expanded', String(aberto));
            overlay.setAttribute('aria-hidden', String(!aberto));
        });
        // Clique no overlay fecha a sidebar
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            hamburger.setAttribute('aria-expanded', 'false');
            overlay.setAttribute('aria-hidden', 'true');
        });
    }

    // ── Alternância de tema claro/escuro ─────────────────────────────────────
    function toggleTheme() {
        const escuro = document.body.classList.toggle('dark-mode');
        // Persiste a preferência do usuário no localStorage
        localStorage.setItem('fitcontrol_tema', escuro ? 'dark' : 'light');
        document.getElementById('btn-tema').textContent = escuro ? '☀️' : '🌙';
    }

    // Restaura o tema salvo ao carregar a página
    (function() {
        const temaSalvo = localStorage.getItem('fitcontrol_tema');
        if (temaSalvo === 'dark') {
            document.body.classList.add('dark-mode');
            const btnTema = document.getElementById('btn-tema');
            if (btnTema) btnTema.textContent = '☀️';
        }
    })();
</script>

<script src="app.js"></script>

</body>
</html>
