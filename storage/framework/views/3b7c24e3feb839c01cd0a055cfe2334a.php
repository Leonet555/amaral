<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#c41e3a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="manifest" href="/manifest.json">
    <title>Vitorum | Plataforma Profissional</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --bg: #f8fafc;
            --text: #0f172a;
            --muted: #475569;
            --line: #e2e8f0;
            --primary: #c41e3a;
            --primary-soft: #fef2f2;
            --black: #0f172a;
            --card: #ffffff;
            --shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
            --ease: all 0.25s ease;
        }
        [data-theme="dark"] {
            --bg: #0f172a;
            --text: #f1f5f9;
            --muted: #94a3b8;
            --line: #334155;
            --primary: #e11d48;
            --primary-soft: #451a2a;
            --black: #f1f5f9;
            --card: #1e293b;
            --shadow: 0 4px 14px rgba(0, 0, 0, 0.3);
        }

        * { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; }

        body {
            font-family: "Inter", Arial, sans-serif;
            color: var(--text);
            background: var(--bg);
            -webkit-font-smoothing: antialiased;
        }

        .container { width: min(1160px, 92vw); margin: 0 auto; }

        .nav-wrap {
            position: sticky;
            top: 0;
            z-index: 40;
            background: var(--card);
            border-bottom: 1px solid var(--line);
            box-shadow: 0 1px 0 rgba(0,0,0,.04);
        }
        .theme-toggle {
            width: 40px; height: 40px;
            border: none;
            background: var(--line);
            color: var(--text);
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: background .2s, color .2s;
        }
        .theme-toggle:hover { background: var(--primary-soft); color: var(--primary); }

        .navbar {
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--black);
            text-decoration: none;
            letter-spacing: -0.02em;
        }
        .brand .brand-logo { height: 52px; width: auto; display: block; }
        .brand .brand-fallback { display: none; }
        .brand .brand-fallback.show { display: inline; }
        .brand span { color: var(--primary); }

        .nav-links { display: flex; gap: 16px; align-items: center; }

        .nav-links a {
            color: var(--black);
            text-decoration: none;
            font-weight: 600;
            font-size: .94rem;
            transition: var(--ease);
        }
        .nav-links a:hover { color: var(--primary); }

        .btn {
            text-decoration: none;
            border: 0;
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
            font-size: .94rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--ease);
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 14px rgba(196, 30, 58, 0.35);
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(196, 30, 58, 0.4); }

        .btn-outline {
            color: var(--black);
            border: 1px solid var(--line);
            background: var(--card);
        }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); transform: translateY(-1px); }

        .inline { display: flex; gap: 8px; flex-wrap: wrap; }

        .hero {
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 48px;
            align-items: center;
            padding: 48px 0 40px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            background: var(--primary-soft);
            color: var(--primary);
            border-radius: 999px;
            padding: 6px 14px;
            font-size: .82rem;
            font-weight: 700;
        }

        .hero h1 {
            margin: 16px 0 12px;
            font-size: clamp(1.9rem, 3.5vw, 3rem);
            line-height: 1.1;
            letter-spacing: -0.03em;
            color: var(--black);
            font-weight: 800;
            opacity: 0;
            animation: fadeUp .5s ease forwards .1s;
        }

        .hero p {
            margin: 0 0 24px;
            color: var(--muted);
            font-size: 1.05rem;
            max-width: 520px;
            opacity: 0;
            animation: fadeUp .5s ease forwards .2s;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            opacity: 0;
            animation: fadeUp .5s ease forwards .3s;
        }

        .hero-visual {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--shadow);
            animation: floating 5s ease-in-out infinite;
        }

        .bracket { display: grid; gap: 10px; }
        .round-label { color: var(--muted); font-size: .78rem; font-weight: 700; margin-bottom: 8px; }
        .match {
            background: var(--bg);
            border-radius: 10px;
            padding: 12px 14px;
            border: 1px solid var(--line);
            transition: var(--ease);
        }
        .match:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
        .ath { display: flex; justify-content: space-between; font-size: .9rem; color: var(--black); padding: 2px 0; }
        .status-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--primary);
            display: inline-block;
            margin-right: 6px;
        }

        .section { padding: 36px 0 28px; }
        .section h2 {
            margin: 0 0 12px;
            font-size: clamp(1.4rem, 2.2vw, 1.75rem);
            font-weight: 800;
            color: var(--black);
            letter-spacing: -0.02em;
        }
        .section p.lead { margin: 0 0 16px; color: var(--muted); font-size: 1rem; max-width: 560px; line-height: 1.55; }
        .section ul { margin: 12px 0 0; padding-left: 1.4rem; color: var(--muted); font-size: 1rem; line-height: 1.7; }
        .section ul li { margin-bottom: 8px; }
        .section .sub { margin-top: 28px; }
        .section .sub h3 { font-size: 1.1rem; font-weight: 700; color: var(--black); margin: 0 0 8px; }
        .section .sub p { margin: 0; color: var(--muted); font-size: .98rem; line-height: 1.5; }
        .mission-block { background: var(--primary-soft); border-radius: 12px; padding: 20px 24px; margin-top: 24px; border-left: 4px solid var(--primary); }
        .mission-block p { margin: 0; color: var(--text); font-size: 1rem; line-height: 1.55; }

        .events {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .event-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--ease);
            border: 1px solid var(--line);
        }
        .event-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(15, 23, 42, 0.12); border-color: transparent; }

        .event-banner {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
            background: #e2e8f0;
        }

        .event-content { padding: 16px; }
        .event-content .name { font-weight: 700; font-size: 1rem; margin-bottom: 6px; color: var(--black); }
        .event-content .meta { color: var(--muted); font-size: .88rem; margin-bottom: 10px; }

        .badge {
            display: inline-flex;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: .76rem;
            font-weight: 700;
        }
        .badge-open { color: var(--primary); background: var(--primary-soft); }
        .badge-soon { color: #b45309; background: #ffedd5; }
        .badge-live { color: #065f46; background: #d1fae5; }

        .btn-xs {
            font-size: .85rem;
            padding: 8px 14px;
            border-radius: 8px;
            text-align: center;
            background: var(--black);
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            transition: var(--ease);
        }
        .btn-xs:hover { background: var(--primary); transform: translateY(-1px); }

        .skeleton {
            border-radius: 14px;
            height: 260px;
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: shimmer 1.2s infinite;
        }

        .cta {
            margin-top: 40px;
            background: var(--black);
            border-radius: 16px;
            padding: 32px 36px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            box-shadow: var(--shadow);
        }
        .cta h3 { margin: 0 0 6px; font-size: 1.35rem; font-weight: 800; color: #fff; letter-spacing: -0.02em; }
        .cta p { margin: 0; color: #94a3b8; font-size: .98rem; }
        .cta .btn-outline { border-color: #475569; color: #fff; }
        .cta .btn-outline:hover { border-color: var(--primary); background: transparent; color: #fca5a5; }

        .footer {
            margin-top: 32px;
            padding: 24px 0 32px;
            border-top: 1px solid var(--line);
            color: var(--muted);
            font-size: .9rem;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
        @keyframes fadeUp {
            0% { opacity: 0; transform: translateY(12px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes shimmer {
            100% { background-position: 200% 0; }
        }

        @media (max-width: 980px) {
            .hero { grid-template-columns: 1fr; padding: 32px 0 28px; }
            .events { grid-template-columns: 1fr 1fr; }
            .cta { flex-direction: column; align-items: flex-start; }
        }
        @media (max-width: 560px) {
            .events { grid-template-columns: 1fr; }
            .hero-actions { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="nav-wrap">
        <div class="container">
            <nav class="navbar">
                <a href="/" class="brand"><img src="/logo" alt="Vitorum" class="brand-logo" onerror="this.style.display='none';var s=this.nextElementSibling;if(s)s.classList.add('show');"><span class="brand-fallback">Vitorum</span></a>
                <div class="nav-links">
                    <a href="/login">Login</a>
                    <a class="btn btn-primary" href="/dashboard">Criar campeonato</a>
                </div>
            </nav>
        </div>
    </div>

    <main class="container">
        <section class="hero">
            <article>
                <span class="eyebrow">Sua Arena de Competição</span>
                <h1>Do Organizadores aos Campeões.</h1>
                <p>Um espaço feito para criadores de torneios, equipes e atletas viverem a competição de forma organizada, dinâmica e profissional. Aqui você pode criar torneios, montar chaves, acompanhar resultados e dar estrutura aos eventos que conectam jogadores e comunidades.</p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="/dashboard">Começar agora</a>
                    <a class="btn btn-outline" href="/login">Entrar na plataforma</a>
                </div>
            </article>

            <aside class="hero-visual" aria-label="Mockup de chaveamento">
                <div class="round-label"><span class="status-dot"></span>Chaveamento automático ativo</div>
                <div class="bracket">
                    <div class="match">
                        <div class="ath"><span>Felipe Santos</span><strong>2</strong></div>
                        <div class="ath"><span>Lucas Almeida</span><strong>0</strong></div>
                    </div>
                    <div class="match">
                        <div class="ath"><span>Rafael Costa</span><strong>1</strong></div>
                        <div class="ath"><span>Igor Mendes</span><strong>2</strong></div>
                    </div>
                    <div class="match">
                        <div class="ath"><span>Final</span><span class="badge badge-live">AO VIVO</span></div>
                        <div class="ath"><span>Felipe Santos x Igor Mendes</span><strong>-</strong></div>
                    </div>
                </div>
            </aside>
        </section>

        <section class="section" data-aos="fade-up">
            <h2>Conecte, Estruture e Faça Competir</h2>
            <p class="lead">Na nossa plataforma, organizadores têm ferramentas para planejar, gerenciar e publicar torneios com facilidade — desde campeonatos amadores até competições de alto nível.</p>
            <p class="lead">Atletas e equipes podem</p>
            <ul>
                <li>✔️ encontrar eventos relevantes</li>
                <li>✔️ acompanhar calendários e resultados</li>
                <li>✔️ conectar com outras equipes</li>
                <li>✔️ evoluir na competição</li>
            </ul>
            <p class="lead" style="margin-top:16px;">Tudo pensado para tornar cada torneio uma experiência completa.</p>
        </section>

        <section class="section" data-aos="fade-up">
            <h2>Transforme sua Visão em Torneios Reais</h2>
            <p class="lead">Quer seja um campeonato local, um evento online ou uma liga profissional, esta plataforma dá suporte ao ciclo completo:</p>
            <ul>
                <li>Criar e configurar torneios</li>
                <li>Gerenciar inscrições e chaves</li>
                <li>Acompanhar classificações e resultados</li>
                <li>Unir comunidades apaixonadas pela competição</li>
            </ul>
        </section>

        <section class="section" data-aos="fade-up">
            <h2>Para Todos os Públicos da Competição</h2>
            <div class="sub">
                <h3>Criadores de Torneios</h3>
                <p>Ferramentas robustas para cada etapa da organização.</p>
            </div>
            <div class="sub">
                <h3>Equipes</h3>
                <p>Espaço para competir, crescer e se destacar.</p>
            </div>
            <div class="sub">
                <h3>Atletas</h3>
                <p>Encontre eventos, acompanhe sua trajetória e eleve seu jogo.</p>
            </div>
            <div class="mission-block" data-aos="fade-up">
                <strong>🏆 Missão</strong>
                <p style="margin-top:8px;">Ser o ponto de encontro onde competição, comunidade e esporte se encontram — conectando quem cria, participa e celebra grandes jogos.</p>
            </div>
        </section>

        <section class="section">
            <h2 data-aos="fade-up">Eventos em destaque</h2>
            <p class="lead" data-aos="fade-up" data-aos-delay="80">Lista premium com status em tempo real e acesso rápido às páginas de cada torneio.</p>
            <div class="events" id="events-container">
                <div class="skeleton"></div>
                <div class="skeleton"></div>
                <div class="skeleton"></div>
            </div>
        </section>

        <section class="cta" data-aos="fade-up">
            <div>
                <h3>Pronto para profissionalizar seus campeonatos?</h3>
                <p>Comece com um evento piloto e escale com segurança operacional.</p>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <a class="btn btn-outline" href="/login">Entrar</a>
                <a class="btn btn-primary" href="/dashboard">Criar campeonato</a>
            </div>
        </section>

        <footer class="footer">
            © <?php echo e(date('Y')); ?> TorneiosCombat • Conectando quem cria, participa e celebra grandes jogos.
        </footer>
    </main>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 520,
            once: true,
            easing: "ease-out-cubic"
        });

        const eventsContainer = document.getElementById("events-container");
        function imageSrc(url, folder) {
            if (!url) return "";
            if (/^https?:\/\//i.test(url)) return url;
            var base = window.location.origin;
            if (url.indexOf("/") === -1) return base + "/uploads/" + (folder || "banners") + "/" + url;
            return base + (url.startsWith("/") ? url : "/" + url);
        }
        function isValidBannerUrl(url) {
            if (!url || typeof url !== "string") return false;
            const t = url.trim();
            return t.startsWith("http://") || t.startsWith("https://") || t.startsWith("data:image");
        }
        function escapeAttr(s) {
            if (!s) return "";
            return String(s)
                .replace(/&/g, "&amp;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#39;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;");
        }
        var defaultBannerPng = (typeof window !== "undefined" && window.location ? window.location.origin : "") + "/images/torneio-generico.png";
        var fallbackBannerSvg = "data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22640%22 height=%22240%22%3E%3Crect fill=%22%231e293b%22 width=%22640%22 height=%22240%22/%3E%3Ctext x=%2250%25%22 y=%2245%25%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22 fill=%22%23f8fafc%22 font-size=%2232%22 font-weight=%22bold%22%3ETORNEIO%3C/text%3E%3Ctext x=%2250%25%22 y=%2258%25%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22 fill=%22%23c41e3a%22 font-size=%2218%22%3ECombat%3C/text%3E%3C/svg%3E";
        var defaultBanner = defaultBannerPng;
        var fallbackBanner = fallbackBannerSvg;

        function badgeClass(status) {
            if (status === "STARTED") return "badge-live";
            if (status === "CLOSED") return "badge-soon";
            return "badge-open";
        }

        async function buildEvents() {
            try {
                const response = await fetch("/api/highlights", {
                    headers: { "Accept": "application/json" }
                });
                const events = await response.json();

                if (!response.ok || !Array.isArray(events)) {
                    throw new Error("Falha ao carregar destaque.");
                }

                if (!events.length) {
                    eventsContainer.innerHTML = "<article class='event-card'><div class='event-content'><div class='name'>Nenhum torneio em destaque no momento.</div><div class='meta'>Assim que organizadores publicarem eventos com banner e status OPEN/STARTED, eles aparecerao aqui.</div><a href='/login' class='btn-xs'>Criar torneio</a></div></article>";
                    return;
                }

                eventsContainer.innerHTML = events.map((event) => {
                    var bannerSrc = event.banner_url ? imageSrc(event.banner_url, "banners") : defaultBannerPng;
                    if (!bannerSrc || (!/^https?:\/\//i.test(bannerSrc) && !/^\/\//.test(bannerSrc) && bannerSrc.indexOf("/") !== 0)) bannerSrc = fallbackBanner;
                    var safeSrc = escapeAttr(bannerSrc);
                    var safeName = escapeAttr(event.name || "");
                    return `
                    <article class="event-card">
                        <img class="event-banner" src="${safeSrc}" alt="${safeName}" loading="lazy" data-fallback="${escapeAttr(fallbackBanner)}" onerror="var f=this.getAttribute('data-fallback');if(f){this.onerror=null;this.src=f;}">
                        <div class="event-content">
                            <div class="name">${safeName}</div>
                            <div class="meta">${new Date(event.date).toLocaleDateString('pt-BR')} • ${escapeAttr(event.location || "")}</div>
                            <div class="inline" style="justify-content:space-between;align-items:center;">
                                <span class="badge ${badgeClass(event.status)}">${event.status}</span>
                                <a href="/login" class="btn-xs">Ver</a>
                            </div>
                        </div>
                    </article>
                `;
                }).join("");
            } catch (error) {
                eventsContainer.innerHTML = "<article class='event-card'><div class='event-content'><div class='name'>Nao foi possivel carregar os destaques.</div><div class='meta'>Verifique se a API esta online e se existe o endpoint /api/highlights.</div></div></article>";
            }
        }

        setTimeout(buildEvents, 450);
    </script>
    <script>
    (function() {
      var KEY = "vitorum-theme";
      function getTheme() { return localStorage.getItem(KEY) || "light"; }
      function setTheme(v) { localStorage.setItem(KEY, v); document.documentElement.setAttribute("data-theme", v === "dark" ? "dark" : ""); updateThemeIcons(); }
      function updateThemeIcons() {
        var isDark = getTheme() === "dark";
        document.querySelectorAll(".theme-toggle").forEach(function(btn) { btn.textContent = isDark ? "☀️" : "🌙"; });
      }
      setTheme(getTheme());
      document.addEventListener("click", function(e) {
        if (e.target.closest && e.target.closest(".theme-toggle")) { setTheme(getTheme() === "dark" ? "light" : "dark"); }
      });
    })();
    </script>
    <script>
    if ("serviceWorker" in navigator) {
      window.addEventListener("load", function() { navigator.serviceWorker.register("/sw.js").catch(function() {}); });
    }
    var deferredInstallPrompt = null;
    window.addEventListener("beforeinstallprompt", function(e) {
      e.preventDefault();
      deferredInstallPrompt = e;
      var banner = document.getElementById("pwa-install-banner");
      if (banner) banner.style.display = "block";
    });
    window.addEventListener("appinstalled", function() {
      deferredInstallPrompt = null;
      var banner = document.getElementById("pwa-install-banner");
      if (banner) banner.style.display = "none";
    });
    function pwaInstall() {
      if (!deferredInstallPrompt) return;
      deferredInstallPrompt.prompt();
      deferredInstallPrompt.userChoice.then(function(choice) {
        if (choice.outcome === "accepted") { deferredInstallPrompt = null; var b = document.getElementById("pwa-install-banner"); if (b) b.style.display = "none"; }
      });
    }
    </script>
    <div id="pwa-install-banner" style="display:none; position: fixed; bottom: 0; left: 0; right: 0; background: #1e293b; color: #fff; padding: 14px 16px; padding-bottom: calc(14px + env(safe-area-inset-bottom)); box-shadow: 0 -4px 20px rgba(0,0,0,.2); z-index: 999; font-size: .9rem;">
      <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; max-width: 400px; margin: 0 auto;">
        <span>Instale o Vitorum para usar como app.</span>
        <button type="button" onclick="pwaInstall()" style="background: #c41e3a; color: #fff; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; white-space: nowrap;">Instalar</button>
      </div>
    </div>
</body>
</html>
<?php /**PATH /home/u494944867/domains/vitorum.com.br/resources/views/home.blade.php ENDPATH**/ ?>