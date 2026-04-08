<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#c41e3a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="manifest" href="/manifest.json">
    <title>Painel | Vitorum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --bg: #f8fafc;
            --card: #ffffff;
            --line: #e2e8f0;
            --text: #1e293b;
            --muted: #64748b;
            --primary: #c41e3a;
            --primary-soft: #fef2f2;
            --danger: #c41e3a;
            --success-bg: #ecfdf5;
            --success-text: #065f46;
            --error-bg: #fff1f2;
            --error-text: #be123c;
            --radius: 14px;
            --radius-sm: 10px;
            --shadow: 0 1px 3px rgba(0,0,0,.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,.06);
            --shadow-lg: 0 12px 28px rgba(0,0,0,.08);
            --surface: #f1f5f9;
        }
        [data-theme="dark"] {
            --bg: #0f172a;
            --card: #1e293b;
            --line: #334155;
            --text: #f1f5f9;
            --muted: #94a3b8;
            --primary: #e11d48;
            --primary-soft: #451a2a;
            --danger: #e11d48;
            --success-bg: #14532d;
            --success-text: #86efac;
            --error-bg: #451a2a;
            --error-text: #fda4af;
            --shadow: 0 1px 3px rgba(0,0,0,.3);
            --shadow-md: 0 4px 12px rgba(0,0,0,.4);
            --shadow-lg: 0 12px 28px rgba(0,0,0,.5);
            --surface: #334155;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Inter, system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }
        .container { width: min(1240px, 94vw); margin: 24px auto 40px; }
        .topbar {
            background: var(--card);
            border-radius: var(--radius);
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            box-shadow: var(--shadow);
        }
        .brand { font-size: 1.05rem; font-weight: 800; color: var(--text); text-decoration: none; letter-spacing: -0.02em; display: flex; align-items: center; }
        .brand .brand-logo { height: 48px; width: auto; display: block; }
        .brand .brand-fallback { display: none; }
        .brand .brand-fallback.show { display: inline; }
        .brand span { color: var(--primary); }
        .top-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .theme-toggle {
            width: 40px; height: 40px;
            border: none;
            background: #f1f5f9;
            color: #1e293b;
            border-radius: var(--radius-sm);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: background .2s, color .2s;
        }
        .theme-toggle:hover { background: var(--primary-soft); color: var(--primary); }
        [data-theme="dark"] .theme-toggle { background: var(--line); color: var(--text); }
        .link-btn, .nav-btn {
            border: none;
            background: #f1f5f9;
            color: var(--text);
            border-radius: var(--radius-sm);
            padding: 9px 14px;
            font-size: .9rem;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, color .2s, transform .2s;
        }
        .link-btn:hover, .nav-btn:hover { background: #e2e8f0; color: var(--primary); transform: translateY(-1px); }
        .role-chip { background: var(--primary-soft); color: var(--primary); }
        .danger { color: var(--primary); background: var(--card); }
        .danger:hover { background: var(--primary-soft); color: var(--danger); }
        .hero {
            margin-top: 16px;
            border-radius: var(--radius);
            background: var(--card);
            padding: 24px 28px;
            box-shadow: var(--shadow);
        }
        .hero h1 { margin: 0 0 6px; font-size: 1.55rem; letter-spacing: -0.03em; font-weight: 700; color: var(--text); }
        .hero p { margin: 0; color: var(--muted); font-size: .98rem; }
        .layout { margin-top: 18px; display: grid; grid-template-columns: 340px 1fr; gap: 18px; }
        .panel {
            border-radius: var(--radius);
            background: var(--card);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .panel-head {
            padding: 16px 20px;
            font-weight: 700;
            font-size: .95rem;
            color: var(--text);
            background: var(--surface);
            border-bottom: 1px solid var(--line);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }
        .panel-body { padding: 18px 20px; }
        .org-dashboard { margin-bottom: 24px; }
        .org-kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .org-kpi-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
            border: 1px solid var(--line);
            transition: box-shadow .2s;
        }
        .org-kpi-card:hover { box-shadow: var(--shadow-md); }
        .org-kpi-card .value { font-size: 1.75rem; font-weight: 800; color: var(--text); line-height: 1.2; }
        .org-kpi-card .label { font-size: .82rem; color: var(--muted); margin-top: 4px; font-weight: 500; }
        .org-kpi-card.primary .value { color: var(--primary); }
        .org-kpi-card.success .value { color: var(--success-text); }
        .org-kpi-card.warning .value { color: #b45309; }
        .org-charts-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px; }
        @media (max-width: 768px) { .org-charts-row { grid-template-columns: 1fr; } }
        .org-chart-wrap { background: var(--card); border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); border: 1px solid var(--line); }
        .org-chart-wrap h3 { margin: 0 0 16px; font-size: 1rem; font-weight: 700; color: var(--text); }
        .org-chart-canvas-wrap { height: 220px; position: relative; width: 100%; }
        .org-events-sidebar .card { display: flex; flex-direction: column; gap: 8px; }
        .org-events-sidebar .card .reg-badge { font-size: .75rem; font-weight: 700; color: var(--primary); background: var(--primary-soft); padding: 2px 8px; border-radius: 999px; }
        .card {
            border-radius: var(--radius-sm);
            padding: 16px;
            background: var(--card);
            margin-bottom: 12px;
            box-shadow: var(--shadow);
            transition: box-shadow .2s, transform .2s;
        }
        .card:hover { box-shadow: var(--shadow-md); transform: translateY(-1px); }
        .card h4 { margin: 0 0 6px; font-size: 1rem; font-weight: 600; color: var(--text); }
        .meta { color: var(--muted); font-size: .85rem; margin-bottom: 8px; }
        .muted { color: var(--muted); font-size: .9rem; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        label { display: block; margin: 8px 0 6px; font-weight: 600; font-size: .86rem; color: var(--text); }
        input, textarea, select {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-sm);
            padding: 10px 12px;
            font-size: .92rem;
            background: var(--card);
            transition: border-color .2s, box-shadow .2s;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(196,30,58,.12);
        }
        textarea { min-height: 80px; resize: vertical; }
        button {
            border: none;
            background: var(--primary);
            color: #fff;
            border-radius: var(--radius-sm);
            padding: 10px 16px;
            font-weight: 600;
            font-size: .9rem;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s, background .2s;
        }
        button:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(196,30,58,.3); background: #a01830; }
        .btn-soft { background: #f1f5f9; color: var(--text); }
        .btn-soft:hover { background: #e2e8f0; color: var(--primary); box-shadow: none; }
        .btn-dark { background: #334155; color: #fff; }
        .btn-dark:hover { background: #1e293b; box-shadow: 0 6px 16px rgba(30,41,59,.25); }
        .btn-danger { background: var(--danger); }
        .inline { display: flex; gap: 8px; flex-wrap: wrap; }
        .msg { margin-top: 10px; border-radius: var(--radius-sm); padding: 10px 12px; display: none; font-size: .88rem; white-space: pre-line; }
        .ok { background: var(--success-bg); color: var(--success-text); }
        .err { background: var(--error-bg); color: var(--error-text); }
        .hidden { display: none; }
        .section-title { margin: 2px 0 8px; font-size: 1.02rem; font-weight: 600; color: var(--text); }
        .section-subtitle { margin: 0 0 10px; color: var(--muted); font-size: .88rem; }
        .category-regs { margin-top: 12px; }
        .spacer { height: 1px; background: #f1f5f9; margin: 14px 0; }
        .match {
            border-radius: var(--radius-sm);
            padding: 12px 14px;
            margin-bottom: 10px;
            background: #fafafa;
            box-shadow: var(--shadow);
        }
        .tag {
            display: inline-flex;
            border-radius: 999px;
            color: var(--primary);
            background: var(--primary-soft);
            padding: 4px 10px;
            font-size: .75rem;
            font-weight: 700;
        }
        /* —— Painel do atleta: estilo Instagram (3 colunas) —— */
        .layout.athlete-view { background: #fafafa; min-height: 60vh; margin-top: 0; padding: 0; }
        #athlete-panel .athlete-page { max-width: none; padding: 0; }
        .athlete-instagram-layout {
            display: grid;
            grid-template-columns: 236px 1fr 320px;
            gap: 0;
            min-height: calc(100vh - 60px);
            align-items: start;
        }
        @media (max-width: 1200px) {
            .athlete-instagram-layout { grid-template-columns: 72px 1fr 280px; }
        }
        @media (max-width: 960px) {
            .athlete-instagram-layout { grid-template-columns: 1fr; padding-bottom: env(safe-area-inset-bottom, 0); }
            .athlete-nav-left { display: none !important; }
            .athlete-mobile-header { display: flex !important; }
            .athlete-center { order: 1; padding: 16px; padding-left: max(16px, env(safe-area-inset-left)); padding-right: max(16px, env(safe-area-inset-right)); }
            .athlete-right-sidebar { order: 3; padding: 16px; position: static; }
            .athlete-profile-ig { flex-direction: column; gap: 20px; text-align: center; padding: 20px 0; }
            .athlete-profile-ig .avatar-ig { margin: 0 auto; }
            .athlete-profile-ig .info-ig .stats-row { justify-content: center; flex-wrap: wrap; gap: 16px; }
            .athlete-posts-grid { gap: 3px; }
            .athlete-new-post-btn-ig { width: 56px; height: 56px; font-size: 1.75rem; right: 16px; top: 16px; }
            .athlete-feed-view { padding: 16px; max-width: 100%; }
            .athlete-feed-view .feed-post { margin-bottom: 16px; }
            .athlete-feed-view .feed-post-actions button { padding: 10px 0; min-height: 44px; }
        }
        .athlete-mobile-header {
            display: none;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            padding-top: max(12px, env(safe-area-inset-top));
            background: var(--card);
            border-bottom: 1px solid var(--line);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .athlete-mobile-header .brand { font-size: 1.15rem; font-weight: 800; color: var(--text); text-decoration: none; letter-spacing: -0.02em; display: flex; align-items: center; }
        .athlete-mobile-header .brand .brand-logo { height: 48px; width: auto; }
        .athlete-mobile-header .brand span { color: var(--primary); }
        .athlete-hamburger {
            width: 44px;
            height: 44px;
            border: none;
            background: none;
            cursor: pointer;
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
            border-radius: 8px;
        }
        .athlete-hamburger:hover { background: #f1f5f9; }
        .athlete-hamburger span {
            display: block;
            width: 22px;
            height: 2.5px;
            background: var(--text);
            border-radius: 2px;
            transition: transform .2s, opacity .2s;
        }
        .athlete-hamburger.open span:nth-child(1) { transform: translateY(7.5px) rotate(45deg); }
        .athlete-hamburger.open span:nth-child(2) { opacity: 0; }
        .athlete-hamburger.open span:nth-child(3) { transform: translateY(-7.5px) rotate(-45deg); }
        .athlete-nav-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.4);
            z-index: 199;
            opacity: 0;
            transition: opacity .25s;
        }
        .athlete-nav-overlay.show { display: block; opacity: 1; }
        .athlete-nav-drawer {
            position: fixed;
            top: 0;
            left: 0;
            width: min(280px, 85vw);
            height: 100%;
            background: var(--card);
            z-index: 200;
            transform: translateX(-100%);
            transition: transform .25s ease;
            box-shadow: 4px 0 20px rgba(0,0,0,.1);
            padding-top: max(16px, env(safe-area-inset-top));
            overflow-y: auto;
        }
        .athlete-nav-drawer.show { transform: translateX(0); }
        .athlete-nav-drawer .brand-sidebar { display: flex; align-items: center; padding: 20px 24px 16px; font-size: 1.25rem; font-weight: 800; color: var(--text); text-decoration: none; letter-spacing: -0.02em; border-bottom: 1px solid var(--line); margin-bottom: 8px; }
        .athlete-nav-drawer .brand-sidebar .brand-logo { height: 52px; width: auto; }
        .athlete-nav-drawer .brand-sidebar span { color: var(--primary); }
        .athlete-nav-drawer .nav-item { display: flex; align-items: center; gap: 14px; padding: 14px 24px; color: var(--text); text-decoration: none; font-weight: 500; font-size: 1rem; border: none; background: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit; }
        .athlete-nav-drawer .nav-item:hover { background: var(--surface); color: var(--primary); }
        .athlete-nav-drawer .nav-item .icon { font-size: 1.35rem; width: 24px; text-align: center; }
        .athlete-nav-drawer .nav-item.active { font-weight: 700; color: var(--primary); }
        .athlete-nav-left {
            position: sticky; top: 0;
            background: var(--card);
            border-right: 1px solid var(--line);
            padding: 12px 0;
            min-height: 100vh;
        }
        .athlete-nav-left .nav-item {
            display: flex; align-items: center; gap: 14px;
            padding: 12px 24px;
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-family: inherit;
        }
        .athlete-nav-left .nav-item:hover { background: var(--surface); color: var(--primary); }
        .athlete-nav-left .nav-item .icon { font-size: 1.35rem; width: 24px; text-align: center; }
        @media (max-width: 1200px) {
            .athlete-nav-left .nav-item span:not(.icon) { display: none; }
            .athlete-nav-left .nav-item { padding: 12px 20px; justify-content: center; }
        }
        .athlete-center { min-width: 0; padding: 24px 32px 48px; max-width: 640px; margin: 0 auto; width: 100%; }
        .athlete-right-sidebar { position: sticky; top: 24px; padding: 24px 0 24px 24px; }
        .athlete-right-sidebar .card-ig {
            background: var(--card);
            border-radius: var(--radius);
            padding: 16px 0;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            border: 1px solid var(--line);
        }
        .athlete-right-sidebar .card-ig h3 { margin: 0 0 14px; padding: 0 16px; font-size: .9rem; color: var(--muted); font-weight: 600; }
        .athlete-right-sidebar .suggest-item {
            display: flex; align-items: center; gap: 12px;
            padding: 8px 16px;
        }
        .athlete-right-sidebar .suggest-item:hover { background: var(--surface); }
        .athlete-right-sidebar .suggest-item img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
        .athlete-right-sidebar .suggest-item .name { font-weight: 600; font-size: .88rem; color: var(--text); }
        .athlete-right-sidebar .suggest-item .btn-follow { margin-left: auto; font-size: .8rem; padding: 4px 10px; }
        .athlete-right-sidebar .search-wrap { padding: 0 16px 12px; }
        .athlete-right-sidebar .search-wrap input { width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--line); font-size: .9rem; }
        .athlete-right-sidebar .athlete-discover-card { padding: 8px 16px; margin: 0; border: none; border-radius: 0; box-shadow: none; border-bottom: 1px solid var(--line); }
        .athlete-right-sidebar .athlete-discover-card:last-child { border-bottom: none; }
        .athlete-right-sidebar .athlete-discover-card img { width: 36px; height: 36px; }
        .athlete-right-sidebar .athlete-discover-list { flex-direction: column; gap: 0; }
        .athlete-right-sidebar #athlete-discover-list .athlete-discover-card .info strong { font-size: .88rem; }
        .athlete-right-sidebar #athlete-discover-list .athlete-discover-card .info span { font-size: .78rem; }
        /* Perfil cabeçalho estilo Instagram */
        .athlete-profile-ig {
            display: flex;
            gap: 32px;
            padding: 24px 0 32px;
            border-bottom: 1px solid var(--line);
            margin-bottom: 0;
        }
        .athlete-profile-ig .avatar-wrap { flex-shrink: 0; }
        .athlete-profile-ig .avatar-ig {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .athlete-profile-ig .avatar-ig img { width: 100%; height: 100%; object-fit: cover; }
        .athlete-profile-ig .avatar-ig .photo-fallback { display: none; color: var(--muted); font-size: .75rem; }
        .athlete-profile-ig .avatar-ig .photo-fallback.show { display: flex; align-items: center; justify-content: center; }
        .athlete-profile-ig .avatar-ig img.photo-failed { display: none !important; }
        .athlete-profile-ig .info-ig { flex: 1; min-width: 0; }
        .athlete-profile-ig .info-ig .name-row { margin-bottom: 12px; }
        .athlete-profile-ig .info-ig h1 { margin: 0; font-size: 1.75rem; font-weight: 300; color: var(--text); letter-spacing: -0.02em; }
        .athlete-profile-ig .info-ig .stats-row { display: flex; gap: 28px; margin-bottom: 14px; font-size: .95rem; }
        .athlete-profile-ig .info-ig .stats-row a { color: var(--text); font-weight: 600; text-decoration: none; }
        .athlete-profile-ig .info-ig .stats-row a:hover { color: var(--primary); }
        .athlete-profile-ig .info-ig .stats-row span { font-weight: 600; color: var(--text); }
        .athlete-profile-ig .info-ig .bio { font-size: .9rem; line-height: 1.45; color: var(--text); margin-bottom: 12px; }
        .athlete-profile-ig .info-ig .btn-edit { padding: 6px 16px; font-size: .9rem; font-weight: 600; border-radius: 8px; }
        .athlete-sidebar { position: sticky; top: 12px; }
        .athlete-community-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 14px 16px;
            box-shadow: var(--shadow);
            border: 1px solid var(--line);
        }
        .athlete-community-card .athlete-section-title { font-size: .92rem; padding: 4px 0 6px 10px; margin-bottom: 8px; }
        .athlete-community-card .athlete-search-wrap { max-width: 100%; margin-bottom: 12px; }
        .athlete-community-card .athlete-discover-list { gap: 8px; }
        .athlete-community-card .athlete-discover-card { padding: 8px 10px; }
        .athlete-community-card .athlete-teams-grid { grid-template-columns: 1fr; gap: 8px; }
        .athlete-community-card .team-card { padding: 10px 12px; }
        .athlete-main { min-width: 0; }
        .athlete-profile-header { display: none; }
        .athlete-avatar-wrap { flex-shrink: 0; position: relative; }
        .athlete-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(145deg, #f1f5f9 0%, #e2e8f0 100%);
            overflow: hidden;
            border: 3px solid var(--line);
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .athlete-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .athlete-avatar img.photo-failed { display: none !important; }
        .athlete-avatar .photo-fallback { display: none; color: var(--muted); font-size: .65rem; font-weight: 600; text-align: center; padding: 8px; }
        .athlete-avatar .photo-fallback.show { display: flex !important; align-items: center; justify-content: center; }
        .athlete-profile-info { flex: 1; min-width: 0; }
        .athlete-profile-info h1 { margin: 0 0 4px; font-size: 1.15rem; font-weight: 700; color: var(--text); }
        .athlete-profile-info .athlete-details { color: var(--muted); font-size: .82rem; margin-bottom: 8px; }
        .athlete-stats { display: flex; flex-wrap: wrap; gap: 10px 14px; margin-bottom: 8px; }
        .athlete-stats span { font-weight: 700; color: var(--text); font-size: .9rem; }
        .athlete-stats span em { font-style: normal; color: var(--muted); font-weight: 500; font-size: .82rem; }
        .athlete-social-stats { display: flex; gap: 10px; margin-bottom: 8px; }
        .athlete-social-stats a { color: var(--primary); font-size: .82rem; font-weight: 600; text-decoration: none; }
        .athlete-social-stats a:hover { text-decoration: underline; }
        .athlete-profile-info .btn-soft { margin-top: 2px; padding: 6px 12px; font-size: .85rem; }
        .athlete-search-wrap { max-width: 480px; }
        .athlete-search-input { width: 100%; padding: 12px 16px; border-radius: var(--radius-sm); border: 1px solid var(--line); font-size: 1rem; }
        .athlete-search-input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(196,30,58,.1); }
        .athlete-discover-list { display: flex; flex-wrap: wrap; gap: 10px; }
        .athlete-discover-card {
            display: flex; align-items: center; gap: 12px;
            background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-sm);
            padding: 10px 14px; box-shadow: var(--shadow);
        }
        .athlete-discover-card img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .athlete-discover-card .info { flex: 1; min-width: 0; }
        .athlete-discover-card .info { flex: 1; min-width: 0; }
        .athlete-discover-card .info strong { font-size: .9rem; display: block; }
        .athlete-discover-card .info span { font-size: .8rem; color: var(--muted); }
        .athlete-discover-card .btn-follow { flex-shrink: 0; padding: 6px 12px; font-size: .85rem; }
        .athlete-teams-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; }
        .team-card {
            background: var(--card); border: 1px solid var(--line); border-radius: var(--radius);
            padding: 14px; box-shadow: var(--shadow); transition: var(--ease);
        }
        .team-card:hover { box-shadow: var(--shadow-md); border-color: var(--primary); }
        .team-card h4 { margin: 0 0 4px; font-size: 1rem; }
        .team-card .meta { font-size: .82rem; color: var(--muted); }
        .team-card a { color: var(--primary); font-weight: 600; font-size: .88rem; text-decoration: none; }
        .team-card a:hover { text-decoration: underline; }
        .team-page-panel { background: var(--card); border-radius: var(--radius); padding: 20px; margin-bottom: 20px; box-shadow: var(--shadow-md); border: 1px solid var(--line); }
        .team-page-back { margin-bottom: 12px; }
        .team-page-back a { color: var(--muted); font-size: .9rem; text-decoration: none; }
        .team-page-back a:hover { color: var(--primary); }
        .athlete-profile-page-panel { background: var(--card); border-radius: var(--radius); padding: 20px; margin-bottom: 20px; box-shadow: var(--shadow-md); border: 1px solid var(--line); }
        .athlete-profile-page-panel .profile-header { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; flex-wrap: wrap; }
        .athlete-profile-page-panel .profile-avatar { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; background: var(--line); }
        .athlete-profile-page-panel .profile-avatar-wrap { flex-shrink: 0; }
        .athlete-profile-page-panel .profile-info h2 { margin: 0 0 6px; font-size: 1.35rem; color: var(--text); }
        .athlete-profile-page-panel .profile-meta { color: var(--muted); font-size: .9rem; margin-bottom: 12px; }
        .athlete-profile-page-panel .profile-stats { display: flex; gap: 20px; font-size: .9rem; margin-bottom: 14px; }
        .athlete-profile-page-panel .profile-stats span { font-weight: 600; color: var(--text); }
        .athlete-profile-page-panel .profile-stats em { font-style: normal; color: var(--muted); font-weight: 500; }
        .explorar-result-row { display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--line); }
        .explorar-result-row .explorar-result { flex: 1; min-width: 0; text-decoration: none; color: inherit; display: flex; align-items: center; gap: 14px; }
        .explorar-result-row .explorar-follow-btn { flex-shrink: 0; }
        .athlete-follow-modal { position: fixed; inset: 0; z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .athlete-follow-modal-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,.5); }
        .athlete-follow-modal-box { position: relative; background: var(--card); border-radius: var(--radius); max-width: 400px; width: 100%; max-height: 70vh; display: flex; flex-direction: column; box-shadow: var(--shadow-lg); }
        .athlete-follow-modal-head { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid var(--line); }
        .athlete-follow-modal-head h3 { margin: 0; font-size: 1rem; font-weight: 700; }
        .athlete-follow-modal-close { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--muted); line-height: 1; padding: 0 4px; }
        .athlete-follow-modal-close:hover { color: var(--text); }
        .athlete-follow-modal-body { overflow-y: auto; padding: 12px; }
        .athlete-follow-modal-item { display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: var(--radius-sm); transition: background .15s; }
        .athlete-follow-modal-item:hover { background: var(--primary-soft); }
        .athlete-follow-modal-item img { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; }
        .athlete-follow-modal-item .name { font-weight: 600; font-size: .95rem; color: var(--text); }
        .athlete-follow-modal-item .meta { font-size: .82rem; color: var(--muted); }
        .athlete-posts-section-ig { margin-top: 0; }
        .athlete-posts-tabs { display: flex; align-items: center; justify-content: center; gap: 0; border-top: 1px solid var(--line); margin-bottom: 0; }
        .athlete-posts-tabs .tab { padding: 12px 0; margin: 0 24px; font-size: .8rem; font-weight: 600; color: var(--muted); text-decoration: none; border-top: 2px solid transparent; margin-top: -1px; display: flex; align-items: center; gap: 6px; }
        .athlete-posts-tabs .tab.active { color: var(--text); border-top-color: var(--text); }
        .athlete-posts-tabs .tab .icon { font-size: 1.1rem; }
        .athlete-posts-area { position: relative; padding-top: 16px; }
        .athlete-new-post-btn-ig {
            position: absolute;
            top: 16px;
            right: 0;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--primary);
            color: #fff;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
            box-shadow: 0 2px 12px rgba(196,30,58,.4);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform .2s, box-shadow .2s;
        }
        .athlete-new-post-btn-ig:hover { transform: scale(1.05); box-shadow: 0 4px 16px rgba(196,30,58,.5); color: #fff; }
        .athlete-posts-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px; }
        .athlete-posts-grid .post-cell { aspect-ratio: 1; background: #e2e8f0; overflow: hidden; position: relative; cursor: pointer; }
        .athlete-posts-grid .post-cell img, .athlete-posts-grid .post-cell video { width: 100%; height: 100%; object-fit: cover; display: block; }
        .athlete-posts-grid .post-cell .post-type-badge { position: absolute; top: 6px; right: 6px; background: rgba(0,0,0,.6); color: #fff; padding: 2px 6px; border-radius: 4px; font-size: .7rem; }
        .athlete-posts-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 12px; }
        .athlete-posts-header h2 { margin: 0; }
        .athlete-new-post-modal { position: fixed; inset: 0; z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .athlete-new-post-modal .backdrop { position: absolute; inset: 0; background: rgba(0,0,0,.5); }
        .athlete-new-post-modal .box { position: relative; background: var(--card); border-radius: var(--radius); max-width: 420px; width: 100%; padding: 20px; box-shadow: var(--shadow-lg); }
        .athlete-new-post-modal .box h3 { margin: 0 0 16px; font-size: 1.1rem; }
        .athlete-new-post-modal .preview-wrap { margin: 12px 0; border-radius: var(--radius-sm); overflow: hidden; background: var(--surface); min-height: 120px; text-align: center; }
        .athlete-new-post-modal .preview-wrap img, .athlete-new-post-modal .preview-wrap video { max-width: 100%; max-height: 240px; display: block; margin: 0 auto; }
        .team-page-members { margin-top: 16px; }
        .team-page-members li { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid var(--line); }
        .athlete-section { margin-bottom: 14px; }
        .athlete-main .athlete-section { background: var(--card); border-radius: var(--radius); padding: 14px 18px; box-shadow: var(--shadow); border: 1px solid var(--line); }
        .athlete-section-title {
            font-size: .98rem;
            font-weight: 700;
            color: var(--text);
            margin: 0 0 10px;
            padding: 4px 0 6px 10px;
            border-left: 3px solid var(--primary);
            background: rgba(255,255,255,.7);
            border-radius: 0 6px 0 0;
        }
        .athlete-main .athlete-section-title { background: var(--surface); }
        .athlete-sections-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        @media (max-width: 760px) {
            .athlete-sections-grid { grid-template-columns: 1fr; }
        }
        .athlete-feed-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 16px 18px;
            margin-bottom: 10px;
            box-shadow: var(--shadow);
            transition: box-shadow .2s;
        }
        .athlete-feed-card:hover { box-shadow: var(--shadow-md); }
        .athlete-feed-card h4 { margin: 0 0 4px; font-size: 1rem; font-weight: 600; color: var(--text); }
        .athlete-feed-card .meta { color: var(--muted); font-size: .85rem; }
        .container.athlete-view .topbar { display: none !important; }
        .container.athlete-view .hero { display: none !important; }
        .athlete-nav-left .brand-sidebar { display: flex; align-items: center; padding: 20px 24px 16px; font-size: 1.25rem; font-weight: 800; color: var(--text); text-decoration: none; letter-spacing: -0.02em; border-bottom: 1px solid var(--line); margin-bottom: 8px; }
        .athlete-nav-left .brand-sidebar .brand-logo { height: 52px; width: auto; }
        .athlete-nav-left .brand-sidebar span { color: var(--primary); }
        .athlete-nav-left .nav-item.active { font-weight: 700; color: var(--text); }
        .athlete-feed-view { padding: 24px 32px 48px; max-width: 470px; margin: 0 auto; }
        .athlete-feed-view .feed-post { background: var(--card); border-radius: var(--radius); margin-bottom: 24px; box-shadow: var(--shadow); border: 1px solid var(--line); overflow: hidden; }
        .athlete-feed-view .feed-post-header { display: flex; align-items: center; gap: 12px; padding: 12px 16px; }
        .athlete-feed-view .feed-post-header img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
        .athlete-feed-view .feed-post-header .name { font-weight: 600; font-size: .95rem; }
        .athlete-feed-view .feed-post-media-wrap { width: 100%; aspect-ratio: 1; background: #000; overflow: hidden; }
        .athlete-feed-view .feed-post-media-wrap img, .athlete-feed-view .feed-post-media-wrap video { width: 100%; height: 100%; object-fit: contain; display: block; }
        .athlete-feed-view .feed-post-body { padding: 10px 16px 14px; }
        .athlete-feed-view .feed-post-caption { font-size: .9rem; line-height: 1.4; margin-bottom: 6px; }
        .athlete-feed-view .feed-post-date { font-size: .78rem; color: var(--muted); }
        .athlete-feed-view .feed-post-actions { display: flex; align-items: center; gap: 16px; padding: 8px 16px 12px; border-top: 1px solid var(--line); }
        .athlete-feed-view .feed-post-actions button { background: none; border: none; cursor: pointer; font-size: .9rem; color: var(--muted); display: flex; align-items: center; gap: 6px; padding: 4px 0; }
        .athlete-feed-view .feed-post-actions button:hover { color: var(--primary); }
        .athlete-feed-view .feed-post-actions button.liked { color: var(--primary); }
        .athlete-feed-view .feed-event-card .feed-post-media-wrap { aspect-ratio: 16/10; background: #1e293b; }
        .athlete-feed-view .feed-event-card .feed-post-media-wrap img { object-fit: cover; }
        .athlete-feed-view .feed-comments-toggle { font-size: .85rem; color: var(--muted); cursor: pointer; margin-top: 6px; }
        .athlete-feed-view .feed-comments-toggle:hover { color: var(--primary); }
        .athlete-feed-view .feed-comments-box { padding: 12px 16px; background: var(--surface); border-top: 1px solid var(--line); display: none; }
        .athlete-feed-view .feed-comments-box.show { display: block; }
        .athlete-feed-view .feed-comment { font-size: .9rem; margin-bottom: 8px; }
        .athlete-feed-view .feed-comment strong { margin-right: 6px; }
        .athlete-feed-view .feed-add-comment { display: flex; gap: 8px; margin-top: 10px; }
        .athlete-feed-view .feed-add-comment input { flex: 1; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--line); font-size: .9rem; }
        .athlete-explorar-view { padding: 24px 32px 48px; max-width: 560px; margin: 0 auto; }
        .athlete-explorar-view .explorar-search { margin-bottom: 24px; }
        .athlete-explorar-view .explorar-search input { width: 100%; padding: 14px 18px; border-radius: 12px; border: 1px solid var(--line); font-size: 1rem; }
        .athlete-explorar-view .explorar-search input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(196,30,58,.1); }
        .athlete-explorar-view .explorar-section { margin-bottom: 28px; }
        .athlete-explorar-view .explorar-section h3 { margin: 0 0 14px; font-size: 1rem; font-weight: 700; color: var(--text); }
        .athlete-explorar-view .explorar-result { display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 1px solid var(--line); text-decoration: none; color: inherit; }
        .athlete-explorar-view .explorar-result:hover { background: var(--surface); }
        .athlete-explorar-view .explorar-result img { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; }
        .athlete-explorar-view .explorar-result .title { font-weight: 600; font-size: .95rem; }
        .athlete-explorar-view .explorar-result .meta { font-size: .82rem; color: var(--muted); }
        .athlete-cta-box {
            background: linear-gradient(135deg, #fef2f2 0%, #fff5f5 50%, #fff 100%);
            border: 1px solid rgba(196,30,58,.15);
            border-radius: var(--radius);
            padding: 18px 22px;
            margin-bottom: 16px;
            box-shadow: 0 2px 12px rgba(196,30,58,.08);
        }
        .athlete-cta-box .section-title { margin-top: 0; }
        .athlete-resumo-body { display: flex; gap: 24px; align-items: flex-start; flex-wrap: wrap; }
        .athlete-resumo-photo {
            position: relative;
            width: 100px; height: 100px;
            border-radius: 14px;
            background: linear-gradient(145deg, #f1f5f9 0%, #e2e8f0 100%);
            flex-shrink: 0;
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        .athlete-resumo-photo img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .athlete-resumo-photo img.photo-failed { display: none !important; }
        .athlete-resumo-photo .photo-fallback {
            display: none;
            position: absolute;
            inset: 0;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, #f1f5f9 0%, #e2e8f0 100%);
            color: var(--muted);
            font-size: .7rem;
            font-weight: 600;
            text-align: center;
            padding: 8px;
        }
        .athlete-resumo-photo .photo-fallback.show { display: flex !important; }
        .athlete-photo-preview {
            width: 100px; height: 100px; border-radius: 14px; background: #f1f5f9;
            object-fit: cover; flex-shrink: 0;
        }
        .athlete-photo-preview { display: block; margin-bottom: 10px; }
        .athlete-resumo-info { flex: 1; min-width: 200px; }
        .athlete-resumo-info h3 { margin: 0 0 6px; font-size: 1.2rem; font-weight: 700; color: var(--text); }
        /* Finalize cadastro — layout amigável */
        #athlete-complete-cadastro .panel { max-width: 480px; margin: 0 auto; }
        #athlete-complete-cadastro .panel-head { font-size: 1.1rem; }
        #athlete-complete-cadastro .complete-photo-zone {
            text-align: center;
            padding: 24px;
            background: var(--surface);
            border-radius: var(--radius);
            border: 2px dashed var(--line);
            margin-bottom: 20px;
        }
        #athlete-complete-cadastro .complete-photo-zone .athlete-photo-preview { margin: 0 auto 10px; }
        #athlete-panel { grid-column: 1 / -1; }
        /* Na página do atleta, esconder totalmente o bloco "Meus eventos" e conteúdo do organizador */
        .layout.athlete-view #organizer-panel,
        .layout.athlete-view #organizer-content { display: none !important; }
        .athlete-section .card {
            border-radius: var(--radius);
            padding: 14px 18px;
            margin-bottom: 8px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0,0,0,.04);
            background: var(--card);
        }
        .athlete-section .card:hover { box-shadow: var(--shadow-md); }
        .athlete-section .muted { font-size: .9rem; color: var(--muted); }
        /* —— Página Torneios (atleta) —— */
        #athlete-torneios-view { display: none; }
        #athlete-torneios-view.active { display: block; }
        .torneios-page {
            max-width: 600px;
            margin: 0 auto;
            padding: 24px 24px 48px;
        }
        .torneios-back { margin-bottom: 24px; }
        .torneios-back a {
            color: var(--muted);
            font-size: .9rem;
            text-decoration: none;
            font-weight: 500;
            transition: color .2s;
        }
        .torneios-back a:hover { color: var(--primary); }
        .torneios-page-head { margin-bottom: 24px; }
        .torneios-page-head h1 {
            margin: 0 0 6px;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.02em;
        }
        .torneios-page-head .subtitle { color: var(--muted); font-size: .95rem; line-height: 1.45; }
        .torneios-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 16px 20px;
            align-items: flex-end;
            margin-bottom: 24px;
            padding: 16px 0;
            border-bottom: 1px solid var(--line);
        }
        .torneios-filter-group { flex: 1; min-width: 0; max-width: 280px; }
        .torneios-filter-label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .03em;
        }
        .torneios-search-input,
        .torneios-filter-select {
            width: 100%;
            margin: 0;
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--line);
            font-size: .95rem;
            background: var(--card);
            color: var(--text);
            transition: border-color .2s, box-shadow .2s;
        }
        .torneios-search-input::placeholder { color: var(--muted); }
        .torneios-search-input:focus,
        .torneios-filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-soft);
        }
        .torneios-filter-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            font-size: .9rem;
            cursor: pointer;
            color: var(--text);
            margin: 0;
        }
        .torneios-filter-checkbox input[type="checkbox"] { width: auto; accent-color: var(--primary); }
        .torneios-list { display: grid; gap: 20px; }
        .torneio-card {
            position: relative;
            background: var(--card);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid var(--line);
            transition: box-shadow .2s, transform .2s, border-color .2s;
            cursor: pointer;
        }
        .torneio-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
            border-color: var(--primary);
        }
        .torneio-card-banner {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: var(--surface);
        }
        .torneio-card-body { padding: 18px 20px; }
        .torneio-card-body h3 { margin: 0 0 8px; font-size: 1.15rem; font-weight: 700; color: var(--text); line-height: 1.3; }
        .torneio-card-meta { color: var(--muted); font-size: .9rem; line-height: 1.4; }
        .torneio-card .tag-recomendado {
            position: absolute;
            top: 12px;
            right: 12px;
            background: var(--primary);
            color: #fff;
            font-size: .7rem;
            font-weight: 700;
            padding: 5px 10px;
            border-radius: 999px;
        }
        .torneio-detail {
            display: none;
            margin-top: 0;
        }
        .torneio-detail.active { display: block; }
        .torneio-detail-back-wrap { margin-bottom: 20px; }
        .torneio-detail-back-wrap a {
            color: var(--muted);
            font-size: .9rem;
            text-decoration: none;
            font-weight: 500;
        }
        .torneio-detail-back-wrap a:hover { color: var(--primary); }
        .torneio-detail-card {
            background: var(--card);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid var(--line);
            padding: 0;
        }
        .torneio-detail-banner {
            width: 100%;
            max-height: 240px;
            object-fit: cover;
            background: var(--surface);
            display: block;
        }
        .torneio-detail-card .torneio-detail-inner { padding: 20px 24px 24px; }
        .torneio-detail-card h2 {
            margin: 0 0 10px;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.02em;
        }
        .torneio-detail-info { color: var(--muted); font-size: .9rem; margin-bottom: 14px; line-height: 1.45; }
        .torneio-detail-text {
            background: var(--surface);
            padding: 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-size: .9rem;
            color: var(--text);
            white-space: pre-wrap;
            line-height: 1.5;
            border: 1px solid var(--line);
        }
        .torneio-categories-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            margin: 0 0 14px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--line);
        }
        .torneio-category-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius-sm);
            padding: 16px 18px;
            margin-bottom: 12px;
            transition: border-color .2s, background .2s;
        }
        .torneio-category-item:hover { border-color: var(--primary); }
        .torneio-category-item.compatible {
            border-color: var(--primary);
            background: var(--primary-soft);
        }
        .torneio-category-item strong { font-size: .95rem; color: var(--text); display: block; }
        .torneio-category-item .meta { font-size: .85rem; color: var(--muted); margin-top: 4px; }
        .torneio-category-item .tag-compatible { font-size: .7rem; font-weight: 700; color: var(--primary); margin-left: 6px; }
        .torneio-category-item button {
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: .9rem;
            cursor: pointer;
            border: none;
            background: var(--primary);
            color: #fff;
            transition: opacity .2s, transform .2s;
        }
        .torneio-category-item button:hover { opacity: .9; transform: translateY(-1px); }
        .torneio-category-item.compatible button { box-shadow: 0 2px 8px rgba(196,30,58,.25); }
        @media (max-width: 540px) {
            .torneios-page { padding: 16px 16px 40px; }
            .torneios-toolbar { flex-direction: column; align-items: stretch; }
            .torneios-filter-group { max-width: none; }
        }
        @media (max-width: 980px) {
            .layout { grid-template-columns: 1fr; }
            .grid, .grid-3 { grid-template-columns: 1fr; }
        }
        @media (max-width: 540px) {
            .athlete-profile-header { flex-direction: column; text-align: center; }
            .athlete-profile-info .btn-soft { margin-top: 8px; }
        }
    </style>
</head>
<body>
<div class="container">
    <header class="topbar">
        <a href="/" class="brand"><img src="/logo" alt="Vitorum" class="brand-logo" onerror="this.style.display='none';var s=this.nextElementSibling;if(s)s.classList.add('show');"><span class="brand-fallback">Vitorum</span></a>
        <div class="top-actions">
            <span id="role-chip" class="nav-btn role-chip">carregando perfil...</span>
            <a id="nav-organizer" class="link-btn" href="/organizer">Painel organizador</a>
            <a class="link-btn" href="/athlete">Painel atleta</a>
            <a href="#" id="nav-torneios" class="link-btn" style="display:none;">Torneios</a>
            <button type="button" class="theme-toggle" id="theme-toggle-topbar" aria-label="Alternar tema claro/escuro" title="Tema escuro/claro">🌙</button>
            <a href="#" id="logout" class="link-btn danger">Sair</a>
        </div>
    </header>

    <section class="hero">
        <h1 id="title">Painel</h1>
        <p id="welcome">Carregando sessao...</p>
        <div id="global-error" class="msg err"></div>
    </section>

    <section class="layout" id="organizer-panel-wrapper">
        <aside class="panel hidden" id="organizer-panel">
            <div class="panel-head">Meus eventos</div>
            <div class="panel-body org-events-sidebar">
                <div class="muted" style="margin-bottom:12px;">Selecione um evento para administrar categorias e chaves.</div>
                <div id="events-list"></div>
            </div>
        </aside>

        <div id="organizer-content" class="hidden">
            <div id="organizer-dashboard" class="org-dashboard panel" style="margin-bottom:20px;">
                <div class="panel-head">Resumo</div>
                <div class="panel-body">
                    <div class="org-kpi-grid" id="org-kpi-grid">
                        <div class="org-kpi-card"><span class="value" id="org-kpi-events">0</span><span class="label">Eventos</span></div>
                        <div class="org-kpi-card primary"><span class="value" id="org-kpi-registrations">0</span><span class="label">Inscrições</span></div>
                        <div class="org-kpi-card success"><span class="value" id="org-kpi-paid">0</span><span class="label">Pagas</span></div>
                        <div class="org-kpi-card warning"><span class="value" id="org-kpi-pending">0</span><span class="label">Pendentes</span></div>
                    </div>
                    <div class="org-charts-row">
                        <div class="org-chart-wrap"><h3>Inscrições por evento</h3><div class="org-chart-canvas-wrap"><canvas id="org-chart-events"></canvas></div></div>
                        <div class="org-chart-wrap"><h3>Status de pagamento</h3><div class="org-chart-canvas-wrap"><canvas id="org-chart-payment"></canvas></div></div>
                    </div>
                    <div id="org-chart-categories-wrap" class="org-chart-wrap" style="margin-top:20px;display:none;"><h3 id="org-chart-categories-title">Inscrições por categoria</h3><div class="org-chart-canvas-wrap"><canvas id="org-chart-categories"></canvas></div></div>
                </div>
            </div>
            <article class="panel">
                <div class="panel-head">Criar evento</div>
                <div class="panel-body">
                    <form id="event-form">
                        <div class="grid">
                            <div>
                                <label>Nome do evento</label>
                                <input name="name" required>
                            </div>
                            <div>
                                <label>Local</label>
                                <input name="location" required>
                            </div>
                        </div>
                        <label>Banner do evento</label>
                        <input type="file" id="event-banner-file" accept="image/jpeg,image/png,image/gif,image/webp">
                        <input type="hidden" name="banner_url" id="event-banner-url">
                        <div id="event-banner-preview" class="banner-preview" style="margin-top:8px;"></div>
                        <div class="grid">
                            <div>
                                <label>Data</label>
                                <input name="date" type="date" required>
                            </div>
                            <div>
                                <label>Horario de inicio</label>
                                <input name="starts_at" type="datetime-local" placeholder="Opcional">
                            </div>
                        </div>
                        <div class="grid">
                            <div>
                                <label>Prazo de inscricao</label>
                                <input name="registration_deadline" type="datetime-local" required>
                            </div>
                            <div></div>
                        </div>
                        <label>Informacoes para os atletas inscritos</label>
                        <textarea name="athlete_info" rows="3" placeholder="Texto que aparecera para o atleta ao abrir o torneio (local, horario, o que trazer, etc.)"></textarea>
                        <div class="grid">
                            <div>
                                <label>Modalidade</label>
                                <select name="sport_type">
                                    <option value="BJJ">BJJ</option>
                                    <option value="JUDO">JUDO</option>
                                </select>
                            </div>
                            <div>
                                <label>Status inicial</label>
                                <select name="status">
                                    <option value="DRAFT">DRAFT</option>
                                    <option value="OPEN">OPEN</option>
                                </select>
                            </div>
                        </div>
                        <label>Descricao</label>
                        <textarea name="description"></textarea>
                        <button type="submit">Criar campeonato</button>
                        <div id="event-ok" class="msg ok"></div>
                        <div id="event-err" class="msg err"></div>
                    </form>
                </div>
            </article>

            <div id="organizer-select-hint" class="panel" style="margin-top:12px; padding:20px; background: var(--primary-soft); border: 1px solid rgba(196,30,58,.2); border-radius: var(--radius);">
                <p style="margin:0; font-weight:600; color: var(--text);">Para editar o evento (banner, informações) e ver inscritos</p>
                <p style="margin:8px 0 0; color: var(--muted); font-size:.95rem;">Clique em <strong>Selecionar</strong> no evento desejado na lista à esquerda.</p>
            </div>
            <article class="panel" id="event-edit-panel" style="margin-top:12px; display:none;">
                <div class="panel-head" id="event-edit-panel-head">Informações para os atletas</div>
                <div class="panel-body">
                    <p class="muted section-subtitle">Banner, horário e mensagem que o atleta vê ao abrir o torneio.</p>
                    <label>Banner do evento</label>
                    <input type="file" id="event-edit-banner-file" accept="image/jpeg,image/png,image/gif,image/webp">
                    <div id="event-edit-banner-preview" class="banner-preview" style="margin-top:8px;"></div>
                    <input type="hidden" id="event-edit-banner-url">
                    <label>Horario de inicio do evento</label>
                    <input type="datetime-local" id="event-edit-starts_at">
                    <label>Mensagem / instrucoes para atletas</label>
                    <textarea id="event-edit-athlete_info" rows="4" placeholder="Ex: Chegar 30min antes, trazer documento, local do vestiario..."></textarea>
                    <div class="inline" style="margin-top:10px;">
                        <button type="button" id="event-edit-save" class="btn-dark">Salvar informacoes</button>
                    </div>
                    <div id="event-edit-ok" class="msg ok"></div>
                    <div id="event-edit-err" class="msg err"></div>
                </div>
            </article>

            <article class="panel" style="margin-top:12px;">
                <div class="panel-head">
                    <span>Categorias</span>
                    <span class="tag" id="selected-event-label">Nenhum evento selecionado</span>
                </div>
                <div class="panel-body">
                    <form id="category-form" class="hidden">
                        <p class="section-subtitle">Preencha os dados da categoria para o evento selecionado.</p>
                        <div class="grid-3">
                            <div><label>Faixa</label><input name="belt" required></div>
                            <div><label>Peso minimo</label><input name="weight_min" type="number" step="0.01" required></div>
                            <div><label>Peso maximo</label><input name="weight_max" type="number" step="0.01" required></div>
                        </div>
                        <div class="grid-3">
                            <div><label>Idade minima</label><input name="age_min" type="number" required></div>
                            <div><label>Idade maxima</label><input name="age_max" type="number" required></div>
                            <div>
                                <label>Genero</label>
                                <select name="gender">
                                    <option value="MALE">MALE</option>
                                    <option value="FEMALE">FEMALE</option>
                                    <option value="MIXED">MIXED</option>
                                </select>
                            </div>
                        </div>
                        <label>Maximo de participantes</label>
                        <input name="max_participants" type="number" required>
                        <button type="submit">Criar categoria</button>
                        <div id="category-ok" class="msg ok"></div>
                        <div id="category-err" class="msg err"></div>
                    </form>
                    <div id="category-empty" class="muted">Selecione um evento para ver e criar categorias.</div>
                    <div class="spacer"></div>
                    <div id="categories-list"></div>
                </div>
            </article>

            <article class="panel" style="margin-top:12px;">
                <div class="panel-head">Chaves e resultados</div>
                <div class="panel-body">
                    <p id="selected-category-label" class="section-subtitle">Selecione uma categoria para visualizar as lutas.</p>
                    <div id="matches-list"></div>
                </div>
            </article>
        </div>

        <div id="athlete-panel" class="hidden">
            <!-- Tela: finalize seu cadastro (estilo simples, foto em destaque) -->
            <div id="athlete-complete-cadastro" class="hidden">
                <div class="athlete-page">
                    <article class="panel">
                        <div class="panel-head">Complete seu perfil</div>
                        <div class="panel-body">
                            <p class="muted" style="margin-bottom:16px;">Preencha seus dados e adicione uma foto. Assim você pode se inscrever nos campeonatos.</p>
                            <form id="profile-form">
                                <div class="complete-photo-zone">
                                    <label style="margin-top:0;">Sua foto</label>
                                    <div id="profile-photo-preview" class="athlete-photo-preview" style="margin:0 auto 10px;"></div>
                                    <input type="file" id="profile-photo-file" accept="image/jpeg,image/png,image/gif,image/webp">
                                    <input type="hidden" name="photo_url" id="profile-photo-url">
                                </div>
                                <div class="grid">
                                    <div><label>Data de nascimento</label><input name="birth_date" type="date" required></div>
                                    <div><label>Peso (kg)</label><input name="weight" type="number" step="0.01" min="1" required placeholder="ex: 70"></div>
                                </div>
                                <div class="grid">
                                    <div><label>Faixa</label><input name="belt" required placeholder="ex: branca, azul"></div>
                                    <div><label>Academia</label><input name="academy" required placeholder="Nome da sua academia"></div>
                                </div>
                                <label>Gênero</label>
                                <select name="gender">
                                    <option value="MALE">Masculino</option>
                                    <option value="FEMALE">Feminino</option>
                                </select>
                                <div class="inline" style="margin-top:16px;">
                                    <button type="submit">Salvar e continuar</button>
                                </div>
                                <div id="profile-ok" class="msg ok"></div>
                                <div id="profile-err" class="msg err"></div>
                            </form>
                        </div>
                    </article>
                </div>
            </div>

            <!-- Tela principal do atleta: estilo Instagram (3 colunas) -->
            <div id="athlete-dashboard-content" class="hidden">
                <div class="athlete-page">
                    <!-- Mobile: barra com hambúrguer -->
                    <header class="athlete-mobile-header" id="athlete-mobile-header">
                        <a href="/" class="brand"><img src="/logo" alt="Vitorum" class="brand-logo" onerror="this.style.display='none';var s=this.nextElementSibling;if(s)s.classList.add('show');"><span class="brand-fallback">Vitorum</span></a>
                        <button type="button" class="theme-toggle" id="theme-toggle-athlete" aria-label="Alternar tema" title="Tema escuro/claro">🌙</button>
                        <button type="button" class="athlete-hamburger" id="athlete-hamburger-btn" aria-label="Abrir menu">
                            <span></span><span></span><span></span>
                        </button>
                    </header>
                    <!-- Mobile: overlay e drawer retrátil -->
                    <div class="athlete-nav-overlay" id="athlete-nav-overlay"></div>
                    <aside class="athlete-nav-drawer" id="athlete-nav-drawer">
                        <a href="/" class="brand-sidebar"><img src="/logo" alt="Vitorum" class="brand-logo" onerror="this.style.display='none';var s=this.nextElementSibling;if(s)s.classList.add('show');"><span class="brand-fallback">Vitorum</span></a>
                        <a href="#" class="nav-item active" data-view="feed"><span class="icon">🏠</span><span>Página inicial</span></a>
                        <a href="#" class="nav-item" data-view="perfil"><span class="icon">👤</span><span>Perfil</span></a>
                        <a href="#" class="nav-item" data-view="explorar"><span class="icon">🔍</span><span>Explorar</span></a>
                        <a href="#" class="nav-item" data-view="torneios"><span class="icon">🏆</span><span>Torneios</span></a>
                        <a href="#" class="nav-item" id="athlete-drawer-sair"><span class="icon">🚪</span><span>Sair</span></a>
                    </aside>

                    <div class="athlete-instagram-layout">
                        <!-- Coluna esquerda: logo + navegação fixa (desktop) -->
                        <nav class="athlete-nav-left">
                            <a href="/" class="brand-sidebar"><img src="/logo" alt="Vitorum" class="brand-logo" onerror="this.style.display='none';var s=this.nextElementSibling;if(s)s.classList.add('show');"><span class="brand-fallback">Vitorum</span></a>
                            <a href="#" class="nav-item active" id="athlete-nav-feed" data-view="feed"><span class="icon">🏠</span><span>Página inicial</span></a>
                            <a href="#" class="nav-item" id="athlete-nav-perfil" data-view="perfil"><span class="icon">👤</span><span>Perfil</span></a>
                            <a href="#" class="nav-item" id="athlete-nav-explorar" data-view="explorar"><span class="icon">🔍</span><span>Explorar</span></a>
                            <a href="#" class="nav-item" id="athlete-nav-torneios" data-view="torneios"><span class="icon">🏆</span><span>Torneios</span></a>
                            <a href="#" class="nav-item" id="athlete-nav-sair"><span class="icon">🚪</span><span>Sair</span></a>
                        </nav>

                        <!-- Coluna centro: Feed ou Perfil ou Explorar -->
                        <div class="athlete-center">
                            <!-- View: Feed (Página inicial) -->
                            <div id="athlete-view-feed" class="athlete-feed-view" style="display:none;">
                                <div id="feed-posts-list"></div>
                                <p id="feed-empty" class="muted" style="text-align:center;padding:40px 20px;">Siga atletas para ver as publicações deles aqui. Vá em <strong>Explorar</strong> para descobrir.</p>
                            </div>

                            <!-- View: Explorar (busca estilo Instagram) -->
                            <div id="athlete-view-explorar" class="athlete-explorar-view" style="display:none;">
                                <div class="explorar-search">
                                    <input type="text" id="explorar-search-input" placeholder="Buscar atletas, equipes, torneios..." autocomplete="off">
                                </div>
                                <div id="explorar-results" style="display:none;">
                                    <div class="explorar-section" id="explorar-athletes-wrap">
                                        <h3>Atletas</h3>
                                        <div id="explorar-athletes"></div>
                                    </div>
                                    <div class="explorar-section" id="explorar-teams-wrap">
                                        <h3>Equipes</h3>
                                        <div id="explorar-teams"></div>
                                    </div>
                                </div>
                                <div id="explorar-initial">
                                    <div class="explorar-section">
                                        <h3>Sugestões de atletas</h3>
                                        <div id="explorar-discover-athletes"></div>
                                    </div>
                                    <div class="explorar-section">
                                        <h3>Sugestões de equipes</h3>
                                        <div id="explorar-discover-teams"></div>
                                    </div>
                                </div>
                                <!-- Página do atleta (ao clicar em um resultado) -->
                                <div id="athlete-profile-page-panel" class="athlete-profile-page-panel" style="display:none;">
                                    <div class="team-page-back"><a href="#" id="athlete-profile-page-back">← Voltar à busca</a></div>
                                    <div id="athlete-profile-page-content"></div>
                                </div>
                            </div>

                            <!-- View: Torneios (dentro do layout com sidebar) -->
                            <div id="athlete-view-torneios" class="athlete-torneios-view-wrap" style="display:none;">
                                <div class="torneios-page">
                                    <div class="torneios-back"><a href="#" id="torneios-back-link">← Voltar ao painel</a></div>
                                    <header class="torneios-page-head">
                                        <h1>🏆 Torneios abertos</h1>
                                        <p class="subtitle">Busque por nome ou local, filtre por modalidade e veja os torneios recomendados para o seu perfil.</p>
                                    </header>
                                    <div class="torneios-toolbar">
                                        <div class="torneios-filter-group">
                                            <label class="torneios-filter-label">Buscar</label>
                                            <input type="text" id="torneios-search-input" class="torneios-search-input" placeholder="Nome ou local do torneio..." autocomplete="off">
                                        </div>
                                        <div class="torneios-filter-group">
                                            <label class="torneios-filter-label">Modalidade</label>
                                            <select id="torneios-filter-sport" class="torneios-filter-select">
                                                <option value="">Todas</option>
                                                <option value="BJJ">BJJ</option>
                                                <option value="JUDO">JUDO</option>
                                            </select>
                                        </div>
                                        <label class="torneios-filter-checkbox">
                                            <input type="checkbox" id="torneios-filter-recomendados"> Só recomendados para mim
                                        </label>
                                    </div>
                                    <div id="torneios-list" class="torneios-list"></div>
                                    <div id="torneio-detail" class="torneio-detail">
                                        <div class="torneio-detail-back-wrap">
                                            <a href="#" id="torneio-detail-back">← Voltar à lista</a>
                                        </div>
                                        <div id="torneio-detail-content"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- View: Perfil (estilo Instagram) -->
                            <div id="athlete-view-perfil">
                            <!-- Cabeçalho do perfil (estilo Instagram) -->
                            <header class="athlete-profile-ig">
                                <div class="avatar-wrap">
                                    <div class="avatar-ig" id="athlete-resumo-photo"></div>
                                </div>
                                <div class="info-ig">
                                    <div class="name-row">
                                        <h1 id="athlete-resumo-name"></h1>
                                    </div>
                                    <div class="stats-row" id="athlete-resumo-stats">
                                        <span id="athlete-stat-posts">0 publicações</span>
                                        <a href="#" id="athlete-stat-followers">0 seguidores</a>
                                        <a href="#" id="athlete-stat-following">0 seguindo</a>
                                    </div>
                                    <p class="bio" id="athlete-resumo-details"></p>
                                    <div class="athlete-social-stats" id="athlete-social-stats" style="display:none;"></div>
                                    <button type="button" class="btn-soft btn-edit" id="athlete-edit-profile-btn">Editar perfil</button>
                                </div>
                            </header>

                            <article class="panel athlete-section" id="athlete-edit-profile-panel" style="display:none;">
                                <div class="panel-head">Editar perfil</div>
                                <div class="panel-body">
                                    <form id="profile-edit-form">
                                        <div class="grid">
                                            <div><label>Data de nascimento</label><input name="birth_date" type="date" required></div>
                                            <div><label>Peso (kg)</label><input name="weight" type="number" step="0.01" min="1" required></div>
                                        </div>
                                        <div class="grid">
                                            <div><label>Faixa</label><input name="belt" required></div>
                                            <div><label>Academia</label><input name="academy" required></div>
                                        </div>
                                        <label>Sua foto</label>
                                        <input type="file" id="profile-edit-photo-file" accept="image/jpeg,image/png,image/gif,image/webp">
                                        <input type="hidden" name="photo_url" id="profile-edit-photo-url">
                                        <div id="profile-edit-photo-preview" class="athlete-photo-preview" style="margin-top:8px;"></div>
                                        <label>Gênero</label>
                                        <select name="gender">
                                            <option value="MALE">Masculino</option>
                                            <option value="FEMALE">Feminino</option>
                                        </select>
                                        <div class="inline" style="margin-top:10px;">
                                            <button type="submit">Salvar</button>
                                            <button type="button" class="btn-soft" id="athlete-cancel-edit-profile">Cancelar</button>
                                        </div>
                                        <div id="profile-edit-ok" class="msg ok"></div>
                                        <div id="profile-edit-err" class="msg err"></div>
                                    </form>
                                </div>
                            </article>

                            <!-- Abas + grid de publicações (estilo Instagram) -->
                            <section class="athlete-section athlete-posts-section-ig">
                                <div class="athlete-posts-tabs">
                                    <span class="tab active"><span class="icon">▦</span> Publicações</span>
                                </div>
                                <div class="athlete-posts-area">
                                    <button type="button" class="athlete-new-post-btn-ig" id="athlete-new-post-btn" title="Nova publicação">+</button>
                                    <div id="athlete-posts-grid" class="athlete-posts-grid"></div>
                                    <p id="athlete-posts-empty" class="muted" style="display:none;padding:20px 0;text-align:center;font-size:.9rem;">Nenhuma publicação ainda.<br>Clique em <strong>+</strong> para postar fotos ou vídeos dos seus torneios.</p>
                                </div>
                            </section>

                            <section class="athlete-section">
                                <h2 class="athlete-section-title">Onde estou inscrito</h2>
                                <div id="my-registrations"></div>
                            </section>

                            <div class="athlete-sections-grid">
                                <section class="athlete-section">
                                    <h2 class="athlete-section-title">Meus títulos</h2>
                                    <div id="athlete-championships-won"></div>
                                </section>
                                <section class="athlete-section">
                                    <h2 class="athlete-section-title">Histórico</h2>
                                    <div id="athlete-history"></div>
                                </section>
                            </div>

                            <section class="athlete-section">
                                <div class="athlete-cta-box">
                                    <h2 class="athlete-section-title section-title">Nova inscrição</h2>
                                    <p class="muted section-subtitle">Escolha evento e categoria. <a href="#" id="link-ver-torneios" style="font-weight:600;color:var(--primary);">Ver torneios →</a></p>
                                    <div class="grid">
                                        <div><label>Evento</label><select id="event-select"></select></div>
                                        <div><label>Categoria</label><select id="category-select"></select></div>
                                    </div>
                                    <div class="inline" style="margin-top:10px;">
                                        <button id="register-btn">Inscrever-me</button>
                                        <button id="view-bracket-btn" class="btn-soft" type="button">Ver chave</button>
                                    </div>
                                    <div id="reg-ok" class="msg ok"></div>
                                    <div id="reg-err" class="msg err"></div>
                                </div>
                                <div id="athlete-bracket" style="margin-top:10px;"></div>
                            </section>

                            <div id="team-page-panel" class="team-page-panel" style="display:none;">
                                <div class="team-page-back"><a href="#" id="team-page-back">← Voltar</a></div>
                                <div id="team-page-content"></div>
                            </div>
                            </div>
                            <!-- fim athlete-view-perfil -->
                        </div>

                        <!-- Coluna direita: Sugestões para você (preenche espaço) -->
                        <aside class="athlete-right-sidebar">
                            <div class="card-ig">
                                <div class="search-wrap">
                                    <input type="text" id="athlete-community-search" class="athlete-search-input" placeholder="Buscar atletas ou equipes..." autocomplete="off">
                                </div>
                                <div id="athlete-search-results" style="display:none;padding:0 16px 12px;">
                                    <div id="athlete-search-athletes" class="athlete-discover-list"></div>
                                    <div id="athlete-search-teams" class="athlete-teams-grid"></div>
                                </div>
                                <div id="athlete-discover-section">
                                    <h3>Sugestões para você</h3>
                                    <div id="athlete-discover-list"></div>
                                </div>
                            </div>
                            <div class="card-ig" id="athlete-teams-section">
                                <h3>Minhas equipes</h3>
                                <div id="athlete-my-teams" class="athlete-teams-grid" style="grid-template-columns:1fr;gap:6px;"></div>
                                <h3 style="margin-top:14px;">Sugestões de equipes</h3>
                                <div id="athlete-discover-teams" class="athlete-teams-grid" style="grid-template-columns:1fr;gap:6px;"></div>
                                <div id="athlete-create-team-wrap" style="padding:12px 16px 0;">
                                    <button type="button" id="btn-create-team" class="btn-soft">Criar equipe</button>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>

            <!-- Modal: Seguidores / Seguindo (estilo Instagram) -->
            <div id="athlete-follow-modal" class="athlete-follow-modal" style="display:none;">
                <div class="athlete-follow-modal-backdrop"></div>
                <div class="athlete-follow-modal-box">
                    <div class="athlete-follow-modal-head">
                        <h3 id="athlete-follow-modal-title">Seguidores</h3>
                        <button type="button" class="athlete-follow-modal-close" id="athlete-follow-modal-close" aria-label="Fechar">×</button>
                    </div>
                    <div class="athlete-follow-modal-body" id="athlete-follow-modal-list"></div>
                </div>
            </div>

            <!-- Modal: Nova publicação (foto/vídeo) -->
            <div id="athlete-new-post-modal" class="athlete-new-post-modal" style="display:none;">
                <div class="backdrop" id="athlete-new-post-modal-backdrop"></div>
                <div class="box">
                    <h3>Nova publicação</h3>
                    <p class="muted" style="font-size:.9rem;margin:0 0 12px;">Poste uma foto ou vídeo do seu torneio.</p>
                    <form id="athlete-new-post-form">
                        <label>Foto ou vídeo</label>
                        <input type="file" id="athlete-new-post-media" accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm">
                        <div id="athlete-new-post-preview" class="preview-wrap" style="display:none;"></div>
                        <input type="hidden" id="athlete-new-post-media-url" name="media_url">
                        <input type="hidden" id="athlete-new-post-media-type" name="media_type" value="image">
                        <label>Legenda (opcional)</label>
                        <textarea name="caption" id="athlete-new-post-caption" rows="3" placeholder="Conte sobre essa conquista..."></textarea>
                        <div class="inline" style="margin-top:12px;">
                            <button type="submit">Publicar</button>
                            <button type="button" class="btn-soft" id="athlete-new-post-cancel">Cancelar</button>
                        </div>
                        <div id="athlete-new-post-err" class="msg err" style="display:none;margin-top:8px;"></div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
const token = localStorage.getItem("auth_token");
const rawUser = localStorage.getItem("auth_user");
const user = rawUser ? JSON.parse(rawUser) : null;

const layoutWrapper = document.getElementById("organizer-panel-wrapper");
const organizerPanel = document.getElementById("organizer-panel");
const organizerContent = document.getElementById("organizer-content");
const athletePanel = document.getElementById("athlete-panel");
const roleChip = document.getElementById("role-chip");
const title = document.getElementById("title");
const welcome = document.getElementById("welcome");
const globalError = document.getElementById("global-error");

let selectedEventId = null;
let selectedCategoryId = null;
let athleteProfileExists = false;
let athleteEvents = [];
let athleteCategories = [];

function showGlobalError(message) {
    globalError.textContent = message;
    globalError.style.display = "block";
}

async function api(path, options = {}) {
    const response = await fetch(path, {
        ...options,
        credentials: "same-origin",
        headers: {
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            ...(options.body ? { "Content-Type": "application/json" } : {}),
            ...(token ? { "Authorization": `Bearer ${token}` } : {}),
            ...(options.headers || {})
        }
    });

    if (response.status === 401) {
        localStorage.removeItem("auth_token");
        localStorage.removeItem("auth_user");
        window.location.href = "/login";
        throw new Error("Sessão expirada. Faça login novamente.");
    }

    let data = null;
    try {
        data = await response.json();
    } catch (e) {}

    if (!response.ok) {
        let message = data?.message || (data?.errors ? Object.values(data.errors).flat().join("\n") : `Falha na requisicao (${response.status}).`);
        if (data?.error) message += " " + data.error;
        if (data?.file) message += " [" + data.file + "]";
        throw new Error(message);
    }
    return data ?? {};
}

async function uploadFile(endpoint, fieldName, file) {
    const fd = new FormData();
    fd.append(fieldName, file);
    const res = await fetch(endpoint, {
        method: "POST",
        headers: { "Accept": "application/json", ...(token ? { "Authorization": "Bearer " + token } : {}) },
        body: fd
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(data.message || "Upload falhou.");
    return data.url;
}

document.getElementById("logout").addEventListener("click", async (e) => {
    e.preventDefault();
    if (token) {
        try { await api("/api/auth/logout", { method: "POST" }); } catch (e2) {}
    }
    localStorage.removeItem("auth_token");
    localStorage.removeItem("auth_user");
    window.location.href = "/login";
});

if (!token || !user) {
    showGlobalError("Sessao expirada. Faca login novamente.");
    setTimeout(() => window.location.href = "/login", 1200);
}

var navOrganizer = document.getElementById("nav-organizer");
if (navOrganizer) navOrganizer.style.display = user?.role === "organizer" ? "" : "none";

var path = window.location.pathname;
var isOrganizer = user?.role === "organizer";
var isAthlete = user?.role === "athlete";

if (path === "/organizer" && !isOrganizer) {
    window.location.replace("/athlete");
    throw new Error("redirect");
}
if (path === "/athlete" && !isAthlete && !isOrganizer) {
    window.location.replace("/dashboard");
    throw new Error("redirect");
}

var showOrganizer = (path === "/organizer" || path === "/dashboard") && isOrganizer;
var showAthlete = path === "/athlete" || (path === "/dashboard" && isAthlete) || (path === "/athlete" && isOrganizer);

if (showOrganizer) {
    if (layoutWrapper) layoutWrapper.classList.remove("athlete-view");
    var container = document.querySelector(".container");
    if (container) container.classList.remove("athlete-view");
    organizerPanel.classList.remove("hidden");
    organizerContent.classList.remove("hidden");
    var navTorneios = document.getElementById("nav-torneios");
    if (navTorneios) navTorneios.style.display = "none";
    roleChip.textContent = "organizer";
    var heroSection = document.querySelector(".hero");
    if (heroSection) heroSection.classList.remove("hidden");
    title.textContent = "Painel do organizador";
    welcome.textContent = `Bem-vindo, ${user.name}. Administre eventos, categorias e chaves com um fluxo claro.`;
    initOrganizer();
}
if (showAthlete) {
    if (layoutWrapper) layoutWrapper.classList.add("athlete-view");
    var container = document.querySelector(".container");
    if (container) container.classList.add("athlete-view");
    athletePanel.classList.remove("hidden");
    var navTorneios = document.getElementById("nav-torneios");
    if (navTorneios) navTorneios.style.display = "";
    roleChip.textContent = path === "/athlete" && user?.role !== "athlete" ? "organizer (visao atleta)" : "athlete";
    var heroSection = document.querySelector(".hero");
    if (heroSection) heroSection.classList.add("hidden");
    title.textContent = "";
    welcome.textContent = "";
    initAthlete();
}
if (!showOrganizer && !showAthlete) {
    showGlobalError("Perfil de usuario invalido.");
}

async function initOrganizer() {
    const eventForm = document.getElementById("event-form");
    const eventOk = document.getElementById("event-ok");
    const eventErr = document.getElementById("event-err");
    const categoryForm = document.getElementById("category-form");
    const categoryOk = document.getElementById("category-ok");
    const categoryErr = document.getElementById("category-err");

    document.getElementById("event-banner-file").addEventListener("change", async function () {
        const file = this.files[0];
        if (!file) return;
        const preview = document.getElementById("event-banner-preview");
        preview.innerHTML = "<p class='muted'>Enviando...</p>";
        try {
            const url = await uploadFile("/api/upload/banner", "banner", file);
            document.getElementById("event-banner-url").value = url;
            preview.innerHTML = "<img src=\"" + escapeAttr(url) + "\" alt=\"Banner\" style=\"max-width:100%;height:120px;object-fit:cover;border-radius:10px;\">";
        } catch (e) {
            preview.innerHTML = "<span class='msg err' style='display:block;'>" + escapeHtml(e.message) + "</span>";
        }
    });

    eventForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        eventOk.style.display = "none";
        eventErr.style.display = "none";
        try {
            const payload = {
                name: eventForm.name.value,
                description: eventForm.description.value || null,
                athlete_info: eventForm.athlete_info?.value || null,
                date: eventForm.date.value,
                starts_at: eventForm.starts_at?.value || null,
                location: eventForm.location.value,
                banner_url: document.getElementById("event-banner-url").value || eventForm.banner_url?.value || null,
                sport_type: eventForm.sport_type.value,
                registration_deadline: eventForm.registration_deadline.value,
                status: eventForm.status.value
            };
            const created = await api("/api/events", { method: "POST", body: JSON.stringify(payload) });
            eventOk.textContent = `Evento criado: ${created.name}`;
            eventOk.style.display = "block";
            eventForm.reset();
            document.getElementById("event-banner-url").value = "";
            document.getElementById("event-banner-preview").innerHTML = "";
            document.getElementById("event-banner-file").value = "";
            await loadOrganizerEvents();
        } catch (err) {
            eventErr.textContent = err.message;
            eventErr.style.display = "block";
        }
    });

    categoryForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        categoryOk.style.display = "none";
        categoryErr.style.display = "none";
        if (!selectedEventId) return;
        try {
            const payload = {
                belt: categoryForm.belt.value,
                weight_min: Number(categoryForm.weight_min.value),
                weight_max: Number(categoryForm.weight_max.value),
                age_min: Number(categoryForm.age_min.value),
                age_max: Number(categoryForm.age_max.value),
                gender: categoryForm.gender.value,
                max_participants: Number(categoryForm.max_participants.value)
            };
            await api(`/api/events/${selectedEventId}/categories`, { method: "POST", body: JSON.stringify(payload) });
            categoryOk.textContent = "Categoria criada com sucesso.";
            categoryOk.style.display = "block";
            categoryForm.reset();
            await loadCategories(selectedEventId);
        } catch (err) {
            categoryErr.textContent = err.message;
            categoryErr.style.display = "block";
        }
    });

    document.getElementById("event-edit-banner-file").addEventListener("change", async function () {
        const file = this.files[0];
        if (!file) return;
        const preview = document.getElementById("event-edit-banner-preview");
        preview.innerHTML = "<p class='muted'>Enviando...</p>";
        try {
            const url = await uploadFile("/api/upload/banner", "banner", file);
            document.getElementById("event-edit-banner-url").value = url;
            preview.innerHTML = "<img src=\"" + escapeAttr(url) + "\" alt=\"Banner\" style=\"max-width:100%;height:120px;object-fit:cover;border-radius:10px;\">";
        } catch (e) {
            preview.innerHTML = "<span class='msg err' style='display:block;'>" + escapeHtml(e.message) + "</span>";
        }
    });

    document.getElementById("event-edit-save").addEventListener("click", async () => {
        if (!selectedEventId) return;
        const okEl = document.getElementById("event-edit-ok");
        const errEl = document.getElementById("event-edit-err");
        okEl.style.display = "none";
        errEl.style.display = "none";
        try {
            const payload = {
                athlete_info: document.getElementById("event-edit-athlete_info").value || null,
                starts_at: document.getElementById("event-edit-starts_at").value || null
            };
            const bannerUrl = document.getElementById("event-edit-banner-url").value;
            if (bannerUrl) payload.banner_url = bannerUrl;
            await api("/api/events/" + selectedEventId, { method: "PUT", body: JSON.stringify(payload) });
            okEl.textContent = "Informacoes salvas.";
            okEl.style.display = "block";
        } catch (err) {
            errEl.textContent = err.message;
            errEl.style.display = "block";
        }
    });

    await loadOrganizerDashboard();
}

var organizerCharts = { events: null, payment: null, categories: null };

async function loadOrganizerDashboard() {
    const box = document.getElementById("events-list");
    const kpiEvents = document.getElementById("org-kpi-events");
    const kpiReg = document.getElementById("org-kpi-registrations");
    const kpiPaid = document.getElementById("org-kpi-paid");
    const kpiPending = document.getElementById("org-kpi-pending");
    if (box) box.innerHTML = "<p class='muted'>Carregando...</p>";
    try {
        const data = await api("/api/organizer/dashboard");
        window.organizerDashboardData = data;
        if (kpiEvents) kpiEvents.textContent = data.total_events ?? 0;
        if (kpiReg) kpiReg.textContent = data.total_registrations ?? 0;
        if (kpiPaid) kpiPaid.textContent = data.total_paid ?? 0;
        if (kpiPending) kpiPending.textContent = data.total_pending ?? 0;

        var events = data.events || [];
        if (box) {
            if (!events.length) {
                box.innerHTML = "<p class='muted'>Nenhum evento cadastrado ainda.</p>";
            } else {
                box.innerHTML = events.map((ev) => {
                    var reg = ev.registrations_count || 0;
                    var regBadge = reg > 0 ? "<span class=\"reg-badge\">" + reg + " inscrições</span>" : "";
                    return "<article class=\"card\">" +
                        "<img src=\"" + escapeAttr(eventBannerSrc(ev)) + "\" alt=\"Banner\" style=\"width:100%;height:120px;object-fit:cover;border-radius:10px;margin-bottom:8px;\" onerror=\"eventBannerFallback(this)\">" +
                        "<h4>" + escapeHtml(ev.name || "Evento") + "</h4>" +
                        "<div class=\"meta\">" + escapeHtml(ev.date || "") + " • " + escapeHtml(ev.location || "") + " • " + (ev.sport_type || "") + " • " + (ev.status || "") + "</div>" +
                        (regBadge ? "<div style=\"margin-bottom:8px;\">" + regBadge + "</div>" : "") +
                        "<div class=\"inline\">" +
                        "<button onclick=\"selectEvent(" + ev.id + ", '" + escapeSingle(ev.name || "") + "')\" class=\"btn-dark\">Selecionar</button>" +
                        "<button onclick=\"changeEventStatus(" + ev.id + ", 'open')\" class=\"btn-soft\">Abrir inscrições</button>" +
                        "<button onclick=\"changeEventStatus(" + ev.id + ", 'close')\" class=\"btn-soft\">Fechar</button>" +
                        "<button onclick=\"changeEventStatus(" + ev.id + ", 'finalize')\" class=\"btn-danger\">Finalizar</button>" +
                        "</div></article>";
                }).join("");
            }
        }

        if (typeof Chart !== "undefined") {
            if (organizerCharts.events) organizerCharts.events.destroy();
            if (organizerCharts.payment) organizerCharts.payment.destroy();
            var ctxEvents = document.getElementById("org-chart-events");
            var ctxPayment = document.getElementById("org-chart-payment");
            if (ctxEvents && events.length > 0) {
                organizerCharts.events = new Chart(ctxEvents.getContext("2d"), {
                    type: "bar",
                    data: {
                        labels: events.map(function(e) { return (e.name || "Evento").slice(0, 18); }),
                        datasets: [{ label: "Inscrições", data: events.map(function(e) { return e.registrations_count || 0; }), backgroundColor: "rgba(196,30,58,0.7)", borderColor: "#c41e3a", borderWidth: 1 }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                });
            }
            if (ctxPayment && (data.total_pending > 0 || data.total_paid > 0)) {
                organizerCharts.payment = new Chart(ctxPayment.getContext("2d"), {
                    type: "doughnut",
                    data: {
                        labels: ["Pagas", "Pendentes"],
                        datasets: [{ data: [data.total_paid || 0, data.total_pending || 0], backgroundColor: ["#065f46", "#b45309"], borderWidth: 0 }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: "bottom" } } }
                });
            }
        }
        if (selectedEventId) updateOrganizerCategoryChart(selectedEventId);
    } catch (err) {
        if (box) box.innerHTML = "<div class=\"msg err\" style=\"display:block;\">Erro ao carregar: " + escapeHtml(err.message) + "</div>";
    }
}

function updateOrganizerCategoryChart(eventId) {
    var wrap = document.getElementById("org-chart-categories-wrap");
    var titleEl = document.getElementById("org-chart-categories-title");
    var data = window.organizerDashboardData;
    if (!wrap || !data || !data.events) return;
    var ev = data.events.find(function(e) { return e.id === eventId; });
    if (!ev || !ev.categories || ev.categories.length === 0) { wrap.style.display = "none"; return; }
    wrap.style.display = "block";
    if (titleEl) titleEl.textContent = "Inscrições por categoria — " + (ev.name || "Evento");
    if (organizerCharts.categories) organizerCharts.categories.destroy();
    var ctx = document.getElementById("org-chart-categories");
    if (ctx && typeof Chart !== "undefined") {
        var labels = ev.categories.map(function(c) { return (c.belt || "") + " " + (c.gender || ""); });
        organizerCharts.categories = new Chart(ctx.getContext("2d"), {
            type: "bar",
            data: {
                labels: labels,
                datasets: [{ label: "Inscrições", data: ev.categories.map(function(c) { return c.registrations_count || 0; }), backgroundColor: "rgba(196,30,58,0.7)", borderColor: "#c41e3a", borderWidth: 1 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });
    }
}

async function loadOrganizerEvents() {
    await loadOrganizerDashboard();
}

function escapeSingle(text) {
    return String(text).replace(/'/g, "\\'");
}

async function changeEventStatus(eventId, action) {
    try {
        await api(`/api/events/${eventId}/${action === "open" ? "open-registration" : action === "close" ? "close-registration" : "finalize"}`, { method: "POST" });
        await loadOrganizerDashboard();
    } catch (err) {
        alert(err.message);
    }
}

async function selectEvent(eventId, eventName) {
    selectedEventId = eventId;
    var selectedLabel = document.getElementById("selected-event-label");
    var categoryForm = document.getElementById("category-form");
    var categoryEmpty = document.getElementById("category-empty");
    var editPanel = document.getElementById("event-edit-panel");
    var selectHint = document.getElementById("organizer-select-hint");
    var editPanelHead = document.getElementById("event-edit-panel-head");
    if (selectedLabel) selectedLabel.textContent = "Evento: " + (eventName || "");
    if (categoryForm) categoryForm.classList.remove("hidden");
    if (categoryEmpty) categoryEmpty.classList.add("hidden");
    if (selectHint) selectHint.style.display = "none";
    editPanel.style.display = "block";
    document.getElementById("event-edit-ok").style.display = "none";
    document.getElementById("event-edit-err").style.display = "none";
    var errEl = document.getElementById("event-edit-err");
    try {
        var ev = await api("/api/events/" + eventId);
        var startsAt = document.getElementById("event-edit-starts_at");
        var athleteInfo = document.getElementById("event-edit-athlete_info");
        var bannerUrlEl = document.getElementById("event-edit-banner-url");
        var bannerPreview = document.getElementById("event-edit-banner-preview");
        bannerUrlEl.value = ev.banner_url || "";
        bannerPreview.innerHTML = "<img src=\"" + escapeAttr(eventBannerSrc(ev)) + "\" alt=\"Banner\" style=\"max-width:100%;height:120px;object-fit:cover;border-radius:10px;\" onerror=\"eventBannerFallback(this)\">";
        document.getElementById("event-edit-banner-file").value = "";
        if (ev.starts_at) {
            var d = new Date(ev.starts_at);
            var y = d.getFullYear(), m = String(d.getMonth() + 1).padStart(2, "0"), day = String(d.getDate()).padStart(2, "0");
            var h = String(d.getHours()).padStart(2, "0"), min = String(d.getMinutes()).padStart(2, "0");
            startsAt.value = y + "-" + m + "-" + day + "T" + h + ":" + min;
        } else {
            startsAt.value = "";
        }
        athleteInfo.value = ev.athlete_info || "";
        if (errEl) { errEl.style.display = "none"; errEl.textContent = ""; }
        if (editPanelHead) editPanelHead.textContent = "Editar evento: " + (ev.name || eventName || "");
        editPanel.scrollIntoView({ behavior: "smooth", block: "start" });
    } catch (e) {
        if (errEl) { errEl.textContent = "Erro ao carregar evento: " + (e.message || ""); errEl.style.display = "block"; errEl.classList.add("msg", "err"); }
    }
    await loadCategories(eventId);
    updateOrganizerCategoryChart(eventId);
}
window.selectEvent = selectEvent;
window.changeEventStatus = changeEventStatus;

async function loadCategories(eventId) {
    const box = document.getElementById("categories-list");
    box.innerHTML = "<p class='muted'>Carregando categorias...</p>";
    try {
        const categories = await api(`/api/events/${eventId}/categories`);
        if (!categories.length) {
            box.innerHTML = "<p class='muted'>Nenhuma categoria cadastrada neste evento.</p>";
            return;
        }
        box.innerHTML = categories.map((cat) => `
            <article class="card">
                <h4>${cat.belt} • ${cat.gender} • ${cat.weight_min}kg-${cat.weight_max}kg</h4>
                <div class="meta">Idade ${cat.age_min}-${cat.age_max} • max ${cat.max_participants} • chave ${cat.bracket_generated ? "gerada" : "nao gerada"}</div>
                <div class="inline">
                    <button onclick="generateBracket(${cat.id})" class="btn-dark">Gerar chave</button>
                    <button onclick="loadMatches(${cat.id}, '${escapeSingle(cat.belt)}')" class="btn-soft">Ver lutas</button>
                    <button onclick="loadCategoryRegistrations(${cat.id}, '${escapeSingle(cat.belt)}')" class="btn-soft">Ver inscritos</button>
                </div>
                <div id="category-regs-${cat.id}" class="category-regs"></div>
            </article>
        `).join("");
    } catch (err) {
        box.innerHTML = `<div class="msg err" style="display:block;">Erro ao carregar categorias: ${err.message}</div>`;
    }
}

async function generateBracket(categoryId) {
    try {
        await api(`/api/categories/${categoryId}/generate-bracket`, { method: "POST" });
        alert("Chave gerada com sucesso.");
        if (selectedEventId) await loadCategories(selectedEventId);
        await loadMatches(categoryId, "categoria");
    } catch (err) {
        alert(err.message);
    }
}
window.generateBracket = generateBracket;

async function loadCategoryRegistrations(categoryId, categoryName) {
    const box = document.getElementById("category-regs-" + categoryId);
    if (!box) return;
    if (box.innerHTML && box.dataset.loaded === "1") {
        box.innerHTML = "";
        box.dataset.loaded = "0";
        return;
    }
    box.innerHTML = "<p class='muted'>Carregando inscritos...</p>";
    try {
        const regs = await api(`/api/categories/${categoryId}/registrations`);
        box.dataset.loaded = "1";
        if (!regs.length) {
            box.innerHTML = "<p class='muted'>Nenhum inscrito nesta categoria.</p>";
            return;
        }
        box.innerHTML = "<div class='section-subtitle'>Inscritos (" + regs.length + ")</div>" + regs.map((r) => `
            <div class="card" style="display:flex;justify-content:space-between;align-items:center;gap:8px;">
                <div>
                    <strong>${escapeHtml(r.athlete?.user?.name || "Atleta #" + r.athlete_id)}</strong>
                    <span class="meta">${r.athlete?.academy || ""} • Faixa ${r.athlete?.belt || "-"} • ${r.payment_status}</span>
                </div>
                <div>
                    ${r.payment_status === "PAID" ? "<span class=\"tag\">Pago</span>" : "<button type=\"button\" class=\"btn-soft\" onclick=\"setRegistrationPayment(" + r.id + ", 'PAID', " + categoryId + ")\">Marcar como pago</button>"}
                </div>
            </div>
        `).join("");
    } catch (err) {
        box.innerHTML = "<div class='msg err' style='display:block;'>" + escapeHtml(err.message) + "</div>";
    }
}
window.loadCategoryRegistrations = loadCategoryRegistrations;

async function setRegistrationPayment(registrationId, paymentStatus, categoryId) {
    try {
        await api(`/api/registrations/${registrationId}`, {
            method: "PATCH",
            body: JSON.stringify({ payment_status: paymentStatus })
        });
        if (categoryId) {
            const box = document.getElementById("category-regs-" + categoryId);
            if (box) box.dataset.loaded = "0";
            loadCategoryRegistrations(categoryId, "");
        }
    } catch (err) {
        alert(err.message);
    }
}
window.setRegistrationPayment = setRegistrationPayment;

async function loadMatches(categoryId, categoryName) {
    selectedCategoryId = categoryId;
    document.getElementById("selected-category-label").textContent = `Categoria selecionada: ${categoryName}`;
    const box = document.getElementById("matches-list");
    box.innerHTML = "<p class='muted'>Carregando lutas...</p>";
    try {
        const matches = await api(`/api/categories/${categoryId}/matches`);
        if (!matches.length) {
            box.innerHTML = "<p class='muted'>Nenhuma luta encontrada nesta categoria.</p>";
            return;
        }
        function athleteName(a) {
            if (!a) return "-";
            return (a.user && a.user.name) ? a.user.name : (a.academy || "Atleta #" + a.id);
        }
        box.innerHTML = matches.map((m) => {
            const canSetWinner = m.status === "PENDING" && m.athlete_1_id && m.athlete_2_id;
            const options = [
                m.athlete1 ? `<option value="${m.athlete1.id}">${escapeHtml(athleteName(m.athlete1))}</option>` : "",
                m.athlete2 ? `<option value="${m.athlete2.id}">${escapeHtml(athleteName(m.athlete2))}</option>` : ""
            ].join("");
            return `
                <article class="match">
                    <strong>Round ${m.round_number} • Luta ${m.match_number}</strong>
                    <div>Atleta 1: ${escapeHtml(athleteName(m.athlete1))}</div>
                    <div>Atleta 2: ${escapeHtml(athleteName(m.athlete2))}</div>
                    <div>Status: ${m.status}</div>
                    <div>Vencedor: ${escapeHtml(athleteName(m.winner))}</div>
                    ${canSetWinner ? `
                        <div class="inline" style="margin-top:8px;">
                            <select id="winner-${m.id}">${options}</select>
                            <button onclick="setResult(${m.id})">Registrar resultado</button>
                        </div>
                    ` : ""}
                </article>
            `;
        }).join("");
    } catch (err) {
        box.innerHTML = `<div class="msg err" style="display:block;">Erro ao carregar lutas: ${err.message}</div>`;
    }
}
window.loadMatches = loadMatches;

async function setResult(matchId) {
    const select = document.getElementById(`winner-${matchId}`);
    if (!select) return;
    try {
        await api(`/api/matches/${matchId}/result`, { method: "POST", body: JSON.stringify({ winner_id: Number(select.value) }) });
        alert("Resultado registrado.");
        if (selectedCategoryId) await loadMatches(selectedCategoryId, "categoria");
    } catch (err) {
        alert(err.message);
    }
}
window.setResult = setResult;

let athleteDashboard = null;

const athleteDashboardContent = document.getElementById("athlete-dashboard-content");

function showTorneiosView() {
    if (athleteDashboardContent) athleteDashboardContent.style.display = "block";
    showAthleteView("torneios");
    document.getElementById("torneio-detail").classList.remove("active");
    loadTorneiosList();
}
function showAthleteDashboard() {
    if (athleteDashboardContent) athleteDashboardContent.style.display = "";
}

let allTorneiosData = [];

function torneiosMatchSearch(ev, q) {
    if (!q || !q.trim()) return true;
    var t = (ev.name || "") + " " + (ev.location || "") + " " + (ev.sport_type || "");
    return t.toLowerCase().indexOf(q.trim().toLowerCase()) >= 0;
}

function applyTorneiosFilters() {
    var q = (document.getElementById("torneios-search-input") && document.getElementById("torneios-search-input").value) || "";
    var sport = (document.getElementById("torneios-filter-sport") && document.getElementById("torneios-filter-sport").value) || "";
    var soRecomendados = document.getElementById("torneios-filter-recomendados") && document.getElementById("torneios-filter-recomendados").checked;
    return allTorneiosData.filter(function(ev) {
        if (!torneiosMatchSearch(ev, q)) return false;
        if (sport && ev.sport_type !== sport) return false;
        if (soRecomendados && !(ev.compatible_categories_count > 0)) return false;
        return true;
    });
}

function renderTorneioCard(ev) {
    var dateStr = ev.date ? new Date(ev.date).toLocaleDateString("pt-BR") : "";
    var img = "<img class=\"torneio-card-banner\" src=\"" + escapeAttr(eventBannerSrc(ev)) + "\" alt=\"\" onerror=\"eventBannerFallback(this)\">";
    var tagRec = (ev.compatible_categories_count > 0) ? "<span class=\"tag-recomendado\">Recomendado</span>" : "";
    return "<article class=\"torneio-card\" data-event-id=\"" + ev.id + "\">" + tagRec + img + "<div class=\"torneio-card-body\"><h3>" + escapeHtml(ev.name || "Evento") + "</h3><p class=\"torneio-card-meta\">" + escapeHtml(ev.location || "") + " · " + dateStr + " · " + (ev.sport_type || "") + (ev.compatible_categories_count > 0 ? " · " + ev.compatible_categories_count + " categoria(s) para você" : "") + "</p></div></article>";
}

function renderTorneiosList() {
    var filtered = applyTorneiosFilters();
    var recommended = filtered.filter(function(e) { return e.compatible_categories_count > 0; });
    var others = filtered.filter(function(e) { return e.compatible_categories_count === 0; });
    var box = document.getElementById("torneios-list");
    if (!filtered.length) {
        box.innerHTML = "<p class='muted'>Nenhum torneio encontrado. Tente mudar a busca ou os filtros.</p>";
        return;
    }
    var html = "";
    if (recommended.length) {
        html += "<h3 class=\"torneios-section-title\">Recomendados para você</h3><p class=\"muted section-subtitle\" style=\"margin-top:0;\">Torneios com categorias compatíveis com seu perfil (faixa, peso, idade, gênero).</p>";
        html += recommended.map(renderTorneioCard).join("");
    }
    if (others.length) {
        html += "<h3 class=\"torneios-section-title\">Outros torneios</h3>";
        html += others.map(renderTorneioCard).join("");
    }
    box.innerHTML = html;
    box.querySelectorAll(".torneio-card").forEach(function(card) {
        var id = Number(card.dataset.eventId);
        var ev = allTorneiosData.find(function(e) { return e.id === id; });
        card.addEventListener("click", function() { openTorneioDetail(ev || id); });
    });
}

async function loadTorneiosList() {
    var box = document.getElementById("torneios-list");
    box.innerHTML = "<p class=\"muted\" style=\"text-align:center;padding:40px 16px;\">Carregando torneios...</p>";
    try {
        var events = await api("/api/events-for-athlete");
        allTorneiosData = events || [];
        if (!allTorneiosData.length) {
            box.innerHTML = "<p class='muted'>Nenhum torneio aberto no momento. Volte em breve!</p>";
            return;
        }
        renderTorneiosList();
    } catch (err) {
        box.innerHTML = "<p class='msg err' style='display:block;'>Erro ao carregar torneios: " + escapeHtml(err.message) + "</p>";
    }
}

function setupTorneiosFiltersListeners() {
    var searchEl = document.getElementById("torneios-search-input");
    var sportEl = document.getElementById("torneios-filter-sport");
    var recEl = document.getElementById("torneios-filter-recomendados");
    if (searchEl) searchEl.addEventListener("input", renderTorneiosList);
    if (searchEl) searchEl.addEventListener("keyup", renderTorneiosList);
    if (sportEl) sportEl.addEventListener("change", renderTorneiosList);
    if (recEl) recEl.addEventListener("change", renderTorneiosList);
}

let currentTorneioDetail = null;

async function openTorneioDetail(evOrId) {
    var listEl = document.getElementById("torneios-list");
    var detailEl = document.getElementById("torneio-detail");
    var contentEl = document.getElementById("torneio-detail-content");
    listEl.style.display = "none";
    detailEl.classList.add("active");
    var ev = typeof evOrId === "object" && evOrId !== null ? evOrId : null;
    if (!ev) {
        contentEl.innerHTML = "<p class='muted'>Carregando...</p>";
        try {
            ev = await api("/api/events/" + evOrId);
        } catch (err) {
            contentEl.innerHTML = "<p class='msg err' style='display:block;'>" + escapeHtml(err.message) + "</p>";
            return;
        }
    }
    currentTorneioDetail = ev;
    var dateStr = ev.date ? new Date(ev.date).toLocaleDateString("pt-BR", { day: "numeric", month: "long", year: "numeric" }) : "";
    var startsStr = ev.starts_at ? new Date(ev.starts_at).toLocaleTimeString("pt-BR", { hour: "2-digit", minute: "2-digit" }) : "—";
    var bannerHtml = "<img class=\"torneio-detail-banner\" src=\"" + escapeAttr(eventBannerSrc(ev)) + "\" alt=\"\" onerror=\"eventBannerFallback(this)\">";
    var infoHtml = "<p class=\"torneio-detail-info\">" + escapeHtml(ev.location || "—") + " · " + dateStr + "</p>";
    if (ev.athlete_info) infoHtml += "<div class=\"torneio-detail-text\">" + escapeHtml(ev.athlete_info).replace(/\n/g, "<br>") + "</div>";
    infoHtml += "<p class=\"torneio-detail-info\"><strong>Horário de início:</strong> " + startsStr + "</p>";
    var categoriesHtml = "<h3 class=\"torneio-categories-title\">Categorias</h3>";
    var cats = ev.categories || [];
    if (!cats.length) categoriesHtml += "<p class=\"muted\">Nenhuma categoria disponível no momento.</p>";
    else {
        cats.forEach(function(c) {
            var compatClass = c.compatible ? " compatible" : "";
            var compatLabel = c.compatible ? " <span class=\"tag-compatible\">Compatível com seu perfil</span>" : "";
            var weightStr = c.weight_min != null ? c.weight_min + "–" + c.weight_max + " kg" : "";
            var catLine = [c.belt, c.gender, weightStr].filter(Boolean).join(" · ");
            categoriesHtml += "<div class=\"torneio-category-item" + compatClass + "\"><div><strong>" + escapeHtml(catLine || "Categoria") + "</strong>" + compatLabel + "<p class=\"meta\">Idade " + (c.age_min != null ? c.age_min + "–" + c.age_max : "—") + "</p></div><button type=\"button\" data-category-id=\"" + c.id + "\">Inscrever-me</button></div>";
        });
    }
    contentEl.innerHTML = bannerHtml + "<div class=\"torneio-detail-card\"><div class=\"torneio-detail-inner\"><h2>" + escapeHtml(ev.name || "Evento") + "</h2>" + infoHtml + categoriesHtml + "</div></div>";
    contentEl.querySelectorAll("button[data-category-id]").forEach(function(btn) {
        btn.addEventListener("click", function() { registerInCategoryFromTorneio(Number(btn.dataset.categoryId)); });
    });
}

function backTorneioList() {
    document.getElementById("torneios-list").style.display = "";
    document.getElementById("torneio-detail").classList.remove("active");
}

async function registerInCategoryFromTorneio(categoryId) {
    try {
        await api("/api/registrations", { method: "POST", body: JSON.stringify({ category_id: categoryId }) });
        alert("Inscrição realizada! Confira em \"Onde estou inscrito\" no seu painel.");
        backTorneioList();
        loadTorneiosList();
    } catch (err) {
        alert(err.message);
    }
}

function athletePhotoUrl(a) {
    if (!a || !a.photo_url) return "";
    return imageSrc(a.photo_url, "athletes");
}

var communitySearchTimeout = null;

function setupCommunitySearch() {
    var input = document.getElementById("athlete-community-search");
    var searchResults = document.getElementById("athlete-search-results");
    var discoverSection = document.getElementById("athlete-discover-section");
    var teamsSection = document.getElementById("athlete-teams-section");
    if (!input) return;
    input.addEventListener("input", function() {
        var q = (input.value || "").trim();
        if (communitySearchTimeout) clearTimeout(communitySearchTimeout);
        if (q.length === 0) {
            searchResults.style.display = "none";
            if (discoverSection) discoverSection.style.display = "";
            if (teamsSection) teamsSection.style.display = "";
            loadDiscoverAthletes();
            loadTeams();
            return;
        }
        discoverSection.style.display = "none";
        teamsSection.style.display = "none";
        communitySearchTimeout = setTimeout(function() {
            runCommunitySearch(q);
        }, 320);
    });
}

async function runCommunitySearch(q) {
    var searchResults = document.getElementById("athlete-search-results");
    var athletesBox = document.getElementById("athlete-search-athletes");
    var teamsBox = document.getElementById("athlete-search-teams");
    searchResults.style.display = "block";
    athletesBox.innerHTML = "<p class=\"muted\">Buscando...</p>";
    teamsBox.innerHTML = "";
    try {
        var data = await api("/api/search?q=" + encodeURIComponent(q));
        var athletes = data.athletes || [];
        var teams = data.teams || [];
        if (!athletes.length) {
            athletesBox.innerHTML = "<p class=\"muted\">Nenhum atleta encontrado.</p>";
        } else {
            athletesBox.innerHTML = athletes.map(function(a) {
                var img = athletePhotoUrl(a) ? "<img src=\"" + escapeAttr(athletePhotoUrl(a)) + "\" alt=\"\">" : "<div style=\"width:40px;height:40px;border-radius:50%;background:var(--line);\"></div>";
                return "<div class=\"athlete-discover-card\">" + img + "<div class=\"info\"><strong>" + escapeHtml(a.name || "Atleta") + "</strong><span>" + escapeHtml(a.academy || "") + " • " + escapeHtml(a.belt || "") + "</span></div><button type=\"button\" class=\"btn-follow\" data-athlete-id=\"" + a.id + "\">Seguir</button></div>";
            }).join("");
            athletesBox.querySelectorAll(".btn-follow").forEach(function(btn) {
                btn.addEventListener("click", function() { followAthlete(Number(btn.dataset.athleteId)); });
            });
        }
        if (!teams.length) {
            teamsBox.innerHTML = "<p class=\"muted\">Nenhuma equipe encontrada.</p>";
        } else {
            teamsBox.innerHTML = teams.map(function(t) {
                var action = t.is_member
                    ? "<a href=\"#\" class=\"team-link\" data-team-id=\"" + t.id + "\">Ver equipe</a>"
                    : "<button type=\"button\" class=\"btn-join-team\" data-team-id=\"" + t.id + "\">Entrar na equipe</button>";
                return "<div class=\"team-card\"><h4>" + escapeHtml(t.name) + "</h4><p class=\"meta\">" + (t.members_count || 0) + " membros</p>" + action + "</div>";
            }).join("");
            teamsBox.querySelectorAll(".btn-join-team").forEach(function(btn) {
                btn.addEventListener("click", function() { joinTeam(Number(btn.dataset.teamId)); });
            });
            teamsBox.querySelectorAll(".team-link").forEach(function(link) {
                link.addEventListener("click", function(e) { e.preventDefault(); showTeamPage(Number(link.dataset.teamId)); });
            });
        }
    } catch (err) {
        athletesBox.innerHTML = "<p class=\"muted\">Erro na busca. Tente fazer login novamente.</p>";
        teamsBox.innerHTML = "";
    }
}

async function loadDiscoverAthletes() {
    var box = document.getElementById("athlete-discover-list");
    if (!box) return;
    try {
        var list = await api("/api/athlete-follows/discover");
        if (!list.length) {
            box.innerHTML = "<p class=\"muted\">Nenhum atleta para sugerir no momento.</p>";
            return;
        }
        box.innerHTML = list.map(function(a) {
            var img = athletePhotoUrl(a) ? "<img src=\"" + escapeAttr(athletePhotoUrl(a)) + "\" alt=\"\">" : "<div style=\"width:40px;height:40px;border-radius:50%;background:var(--line);\"></div>";
            return "<div class=\"athlete-discover-card\" data-athlete-id=\"" + a.id + "\">" + img + "<div class=\"info\"><strong>" + escapeHtml(a.name || "Atleta") + "</strong><span>" + escapeHtml(a.academy || "") + " • " + escapeHtml(a.belt || "") + "</span></div><button type=\"button\" class=\"btn-follow\" data-athlete-id=\"" + a.id + "\">Seguir</button></div>";
        }).join("");
        box.querySelectorAll(".btn-follow").forEach(function(btn) {
            btn.addEventListener("click", function() { followAthlete(Number(btn.dataset.athleteId)); });
        });
    } catch (err) {
        box.innerHTML = "<p class=\"muted\">Não foi possível carregar sugestões. Verifique se você está logado.</p>";
    }
}

async function followAthlete(athleteId) {
    try {
        await api("/api/athletes/" + athleteId + "/follow", { method: "POST" });
        await loadAthleteDashboard();
        loadDiscoverAthletes();
    } catch (err) {
        alert(err.message);
    }
}

function postMediaSrc(url) {
    if (!url) return "";
    if (/^https?:\/\//i.test(url)) {
        if (isEmbedBlockedUrl(url)) return "";
        return url;
    }
    var base = window.location.origin;
    if (url.indexOf("/") === -1) return base + "/uploads/posts/" + url;
    return base + (url.startsWith("/") ? url : "/" + url);
}

function renderAthletePostsGrid(posts) {
    var grid = document.getElementById("athlete-posts-grid");
    var empty = document.getElementById("athlete-posts-empty");
    if (!grid) return;
    if (!posts || !posts.length) {
        grid.innerHTML = "";
        if (empty) empty.style.display = "block";
        return;
    }
    if (empty) empty.style.display = "none";
    grid.innerHTML = posts.map(function(post) {
        var src = postMediaSrc(post.media_url);
        var isVideo = (post.media_type || "image") === "video";
        var thumb = src ? (isVideo
            ? "<video src=\"" + escapeAttr(src) + "\" muted preload=\"metadata\"></video><span class=\"post-type-badge\">Vídeo</span>"
            : "<img src=\"" + escapeAttr(src) + "\" alt=\"\"><span class=\"post-type-badge\">Foto</span>") : "<div style=\"width:100%;height:100%;background:var(--line);display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:12px;\">Mídia indisponível</div>";
        return "<div class=\"post-cell\" data-post-id=\"" + post.id + "\" data-src=\"" + escapeAttr(src || "") + "\" data-type=\"" + (post.media_type || "image") + "\" title=\"" + escapeAttr((post.caption || "").slice(0, 50)) + "\">" + thumb + "</div>";
    }).join("");
    grid.querySelectorAll(".post-cell").forEach(function(cell) {
        cell.addEventListener("click", function() {
            var src = cell.dataset.src;
            var type = cell.dataset.type;
            if (type === "video") window.open(src, "_blank"); else window.open(src, "_blank");
        });
    });
}

function renderMyTeams(teams) {
    var box = document.getElementById("athlete-my-teams");
    if (!box) return;
    if (!teams.length) {
        box.innerHTML = "<p class=\"muted\">Você ainda não está em nenhuma equipe. Crie uma ou descubra equipes abaixo.</p>";
        return;
    }
    box.innerHTML = teams.map(function(t) {
        return "<div class=\"team-card\"><h4>" + escapeHtml(t.name) + "</h4><p class=\"meta\">" + (t.members_count || 0) + " membros</p><a href=\"#\" class=\"team-link\" data-team-id=\"" + t.id + "\">Ver equipe</a></div>";
    }).join("");
    box.querySelectorAll(".team-link").forEach(function(link) {
        link.addEventListener("click", function(e) { e.preventDefault(); showTeamPage(Number(link.dataset.teamId)); });
    });
}

async function loadTeams() {
    try {
        var data = await api("/api/teams");
        renderMyTeams(data.my_teams || []);
        var box = document.getElementById("athlete-discover-teams");
        if (!box) return;
        var list = data.discover || [];
        if (!list.length) {
            box.innerHTML = "<p class=\"muted\">Nenhuma equipe para descobrir no momento.</p>";
            return;
        }
        box.innerHTML = list.map(function(t) {
            var isMine = (data.my_teams || []).some(function(m) { return m.id === t.id; });
            return "<div class=\"team-card\"><h4>" + escapeHtml(t.name) + "</h4><p class=\"meta\">" + (t.members_count || 0) + " membros</p>" + (isMine ? "<a href=\"#\" class=\"team-link\" data-team-id=\"" + t.id + "\">Ver equipe</a>" : "<button type=\"button\" class=\"btn-join-team\" data-team-id=\"" + t.id + "\">Entrar</button>") + "</div>";
        }).join("");
        box.querySelectorAll(".team-link").forEach(function(link) {
            link.addEventListener("click", function(e) { e.preventDefault(); showTeamPage(Number(link.dataset.teamId)); });
        });
        box.querySelectorAll(".btn-join-team").forEach(function(btn) {
            btn.addEventListener("click", function() { joinTeam(Number(btn.dataset.teamId)); });
        });
    } catch (err) {
        var box = document.getElementById("athlete-discover-teams");
        if (box) box.innerHTML = "<p class=\"muted\">Não foi possível carregar equipes.</p>";
    }
}

async function createTeam() {
    var name = prompt("Nome da equipe:");
    if (!name || !name.trim()) return;
    try {
        await api("/api/teams", { method: "POST", body: JSON.stringify({ name: name.trim() }) });
        await loadAthleteDashboard();
        loadTeams();
    } catch (err) {
        alert(err.message);
    }
}

async function joinTeam(teamId) {
    try {
        await api("/api/teams/" + teamId + "/join", { method: "POST" });
        await loadAthleteDashboard();
        loadTeams();
    } catch (err) {
        alert(err.message);
    }
}

async function leaveTeam(teamId) {
    if (!confirm("Sair desta equipe?")) return;
    try {
        await api("/api/teams/" + teamId + "/leave", { method: "POST" });
        document.getElementById("team-page-panel").style.display = "none";
        await loadAthleteDashboard();
        loadTeams();
    } catch (err) {
        alert(err.message);
    }
}

async function showTeamPage(teamId) {
    var panel = document.getElementById("team-page-panel");
    var content = document.getElementById("team-page-content");
    panel.style.display = "block";
    content.innerHTML = "<p class=\"muted\">Carregando...</p>";
    try {
        var data = await api("/api/teams/" + teamId);
        var t = data.team;
        var members = (t.members || []).map(function(m) {
            return "<li><strong>" + escapeHtml(m.user?.name || "Membro") + "</strong> " + (m.pivot?.role === "admin" ? " (admin)" : "") + "</li>";
        }).join("");
        var actions = "";
        if (data.is_member) {
            actions = data.is_owner ? "<p class=\"muted\">Você é o dono desta equipe.</p>" : "<button type=\"button\" class=\"btn-soft\" id=\"team-leave-btn\">Sair da equipe</button>";
        } else {
            actions = "<button type=\"button\" id=\"team-join-btn\">Entrar na equipe</button>";
        }
        content.innerHTML = "<h2>" + escapeHtml(t.name) + "</h2><p class=\"muted\">" + escapeHtml(t.description || "Sem descrição.") + "</p><p class=\"meta\">" + (t.members_count || 0) + " membros</p>" + actions + "<ul class=\"team-page-members\">" + (members || "<li class=\"muted\">Nenhum membro listado.</li>") + "</ul>";
        if (data.is_member && !data.is_owner) {
            var leaveBtn = document.getElementById("team-leave-btn");
            if (leaveBtn) leaveBtn.addEventListener("click", function() { leaveTeam(teamId); });
        }
        if (!data.is_member) {
            var joinBtn = document.getElementById("team-join-btn");
            if (joinBtn) joinBtn.addEventListener("click", function() { joinTeam(teamId); loadTeamPageAfterJoin(teamId); });
        }
    } catch (err) {
        content.innerHTML = "<p class=\"msg err\" style=\"display:block;\">" + escapeHtml(err.message) + "</p>";
    }
}

function loadTeamPageAfterJoin(teamId) {
    showTeamPage(teamId);
}

async function openFollowModal(type) {
    var modal = document.getElementById("athlete-follow-modal");
    var titleEl = document.getElementById("athlete-follow-modal-title");
    var listEl = document.getElementById("athlete-follow-modal-list");
    if (!modal || !titleEl || !listEl) return;
    titleEl.textContent = type === "followers" ? "Seguidores" : "Seguindo";
    listEl.innerHTML = "<p class=\"muted\" style=\"padding:12px;\">Carregando...</p>";
    modal.style.display = "flex";
    try {
        var url = type === "followers" ? "/api/athlete-follows/followers" : "/api/athlete-follows/following";
        var list = await api(url);
        if (!list || list.length === 0) {
            listEl.innerHTML = "<p class=\"muted\" style=\"padding:12px;\">Nenhum " + (type === "followers" ? "seguidor" : "seguindo") + ".</p>";
        } else {
            listEl.innerHTML = list.map(function(a) {
                var photo = imageSrc(a.photo_url, 'athletes') ? "<img src=\"" + escapeAttr(imageSrc(a.photo_url, 'athletes')) + "\" alt=\"\">" : "<div style=\"width:44px;height:44px;border-radius:50%;background:var(--line);display:flex;align-items:center;justify-content:center;font-size:1rem;color:var(--muted);\">?</div>";
                var meta = [a.academy, a.belt].filter(Boolean).join(" • ");
                return "<div class=\"athlete-follow-modal-item\">" + photo + "<div><div class=\"name\">" + escapeHtml(a.name || "Atleta") + "</div>" + (meta ? "<div class=\"meta\">" + escapeHtml(meta) + "</div>" : "") + "</div></div>";
            }).join("");
        }
    } catch (err) {
        listEl.innerHTML = "<p class=\"msg err\" style=\"margin:12px;\">" + escapeHtml(err.message) + "</p>";
    }
}

function showAthleteView(viewName) {
    var feedView = document.getElementById("athlete-view-feed");
    var perfilView = document.getElementById("athlete-view-perfil");
    var explorarView = document.getElementById("athlete-view-explorar");
    var torneiosView = document.getElementById("athlete-view-torneios");
    if (feedView) feedView.style.display = viewName === "feed" ? "block" : "none";
    if (perfilView) perfilView.style.display = viewName === "perfil" ? "block" : "none";
    if (explorarView) explorarView.style.display = viewName === "explorar" ? "block" : "none";
    if (torneiosView) torneiosView.style.display = viewName === "torneios" ? "block" : "none";
    document.querySelectorAll(".athlete-nav-left .nav-item[data-view], .athlete-nav-drawer .nav-item[data-view]").forEach(function(el) {
        el.classList.toggle("active", el.dataset.view === viewName);
    });
    if (viewName === "feed") loadFeed();
    if (viewName === "explorar") { loadExplorarDiscover(); setupExplorarSearch(); }
    if (viewName === "torneios") { backTorneioList(); loadTorneiosList(); }
}

async function loadFeed() {
    var listEl = document.getElementById("feed-posts-list");
    var emptyEl = document.getElementById("feed-empty");
    if (!listEl) return;
    listEl.innerHTML = "<p class=\"muted\" style=\"text-align:center;padding:24px;\">Carregando...</p>";
    emptyEl.style.display = "none";
    try {
        var posts = await api("/api/feed");
        if (!posts || posts.length === 0) {
            var discover = await api("/api/feed/discover");
            if (discover && discover.length > 0) {
                emptyEl.style.display = "none";
                listEl.innerHTML = "<p class=\"muted\" style=\"margin-bottom:16px;font-size:.9rem;\">Nenhuma publicação de quem você segue. Confira estes torneios e curta ou comente:</p>" + discover.map(function(ev) {
                    var bannerUrl = ev.banner_url ? imageSrc(ev.banner_url, "banners") : "";
                    var bannerHtml = bannerUrl ? "<img class=\"feed-post-media\" src=\"" + escapeAttr(bannerUrl) + "\" alt=\"\" onerror=\"event.target.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22200%22 viewBox=%220 0 400 200%22%3E%3Crect fill=%22%231e293b%22 width=%22400%22 height=%22200%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 fill=%22%2394a3b8%22 text-anchor=%22middle%22 dy=%22.3em%22 font-size=%2216%22%3ETorneio%3C/text%3E%3C/svg%3E';\">" : "<div class=\"feed-post-media\" style=\"background:#1e293b;height:100%;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:14px;\">Torneio</div>";
                    var dateStr = ev.date ? new Date(ev.date).toLocaleDateString("pt-BR", { day: "numeric", month: "short", year: "numeric" }) : "";
                    var likedClass = ev.user_has_liked ? " liked" : "";
                    return "<article class=\"feed-post feed-event-card\" data-event-id=\"" + ev.id + "\"><div class=\"feed-post-header\"><div style=\"width:36px;height:36px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;\">🏆</div><span class=\"name\">" + escapeHtml(ev.name || "Torneio") + "</span></div><div class=\"feed-post-media-wrap\">" + bannerHtml + "</div><div class=\"feed-post-body\"><p class=\"feed-post-caption\">" + escapeHtml(ev.location || "") + " · " + escapeHtml(dateStr) + "</p><span class=\"feed-post-date\">" + escapeHtml(ev.sport_type || "") + "</span></div><div class=\"feed-post-actions\"><button type=\"button\" class=\"feed-like-btn" + likedClass + "\" data-event-id=\"" + ev.id + "\" data-liked=\"" + (ev.user_has_liked ? "1" : "0") + "\"><span>❤</span><span class=\"like-count\">" + (ev.likes_count || 0) + "</span> Curtir</button><button type=\"button\" class=\"feed-comment-btn\" data-event-id=\"" + ev.id + "\"><span>💬</span><span class=\"comment-count\">" + (ev.comments_count || 0) + "</span> Comentar</button></div><div class=\"feed-comments-toggle\" data-event-id=\"" + ev.id + "\">Ver comentários</div><div class=\"feed-comments-box\" id=\"feed-comments-" + ev.id + "\"><div class=\"feed-comments-list\"></div><div class=\"feed-add-comment\"><input type=\"text\" placeholder=\"Adicione um comentário...\" data-event-id=\"" + ev.id + "\"><button type=\"button\" class=\"btn-soft\">Enviar</button></div></div></article>";
                }).join("");
                listEl.querySelectorAll(".feed-like-btn").forEach(function(btn) { btn.addEventListener("click", function() { toggleEventLike(btn); }); });
                listEl.querySelectorAll(".feed-comment-btn").forEach(function(btn) { btn.addEventListener("click", function() { var box = document.getElementById("feed-comments-" + btn.dataset.eventId); if (box) { box.classList.toggle("show"); loadEventComments(btn.dataset.eventId); } }); });
                listEl.querySelectorAll(".feed-comments-toggle").forEach(function(el) { el.addEventListener("click", function() { var box = document.getElementById("feed-comments-" + el.dataset.eventId); if (box) { box.classList.toggle("show"); loadEventComments(el.dataset.eventId); } }); });
                listEl.querySelectorAll(".feed-add-comment button").forEach(function(btn) {
                    var input = btn.closest(".feed-add-comment").querySelector("input");
                    if (!input) return;
                    btn.addEventListener("click", function() { submitEventComment(input.dataset.eventId, input, function() { loadEventComments(input.dataset.eventId); input.value = ""; }); });
                });
            } else {
                listEl.innerHTML = "";
                emptyEl.style.display = "block";
            }
            return;
        }
        emptyEl.style.display = "none";
        listEl.innerHTML = posts.map(function(p) {
            var author = p.author || {};
            var name = author.name || "Atleta";
            var photo = imageSrc(author.photo_url, "athletes") ? "<img src=\"" + escapeAttr(imageSrc(author.photo_url, "athletes")) + "\" alt=\"\">" : "<div style=\"width:36px;height:36px;border-radius:50%;background:var(--line);\"></div>";
            var mediaUrl = postMediaSrc(p.media_url);
            var mediaHtml = mediaUrl ? ((p.media_type || "image") === "video"
                ? "<video class=\"feed-post-media\" src=\"" + escapeAttr(mediaUrl) + "\" controls></video>"
                : "<img class=\"feed-post-media\" src=\"" + escapeAttr(mediaUrl) + "\" alt=\"\">") : "<div class=\"feed-post-media\" style=\"background:var(--surface);display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:14px;\">Mídia indisponível</div>";
            var dateStr = p.created_at ? new Date(p.created_at).toLocaleDateString("pt-BR", { day: "numeric", month: "short", year: "numeric" }) : "";
            return "<article class=\"feed-post\"><div class=\"feed-post-header\">" + photo + "<span class=\"name\">" + escapeHtml(name) + "</span></div><div class=\"feed-post-media-wrap\">" + mediaHtml + "</div><div class=\"feed-post-body\"><p class=\"feed-post-caption\">" + escapeHtml(p.caption || "") + "</p><span class=\"feed-post-date\">" + escapeHtml(dateStr) + "</span></div></article>";
        }).join("");
    } catch (err) {
        listEl.innerHTML = "<p class=\"msg err\" style=\"text-align:center;padding:24px;\">" + escapeHtml(err.message) + "</p>";
    }
}

function toggleEventLike(btnEl) {
    var eventId = btnEl.dataset.eventId;
    var liked = btnEl.dataset.liked === "1";
    (liked ? api("/api/events/" + eventId + "/like", { method: "DELETE" }) : api("/api/events/" + eventId + "/like", { method: "POST" })).then(function(res) {
        btnEl.dataset.liked = res.user_has_liked ? "1" : "0";
        btnEl.classList.toggle("liked", res.user_has_liked);
        var countEl = btnEl.querySelector(".like-count");
        if (countEl) countEl.textContent = res.likes_count;
    }).catch(function(e) { alert(e.message); });
}

async function loadEventComments(eventId) {
    var box = document.getElementById("feed-comments-" + eventId);
    if (!box) return;
    var listEl = box.querySelector(".feed-comments-list");
    if (!listEl) return;
    try {
        var comments = await api("/api/events/" + eventId + "/comments");
        listEl.innerHTML = comments.length ? comments.map(function(c) {
            var dateStr = c.created_at ? new Date(c.created_at).toLocaleDateString("pt-BR", { day: "numeric", month: "short" }) : "";
            return "<div class=\"feed-comment\"><strong>" + escapeHtml(c.user_name || "") + "</strong>" + escapeHtml(c.body) + " <span class=\"feed-post-date\">" + escapeHtml(dateStr) + "</span></div>";
        }).join("") : "<p class=\"muted\" style=\"font-size:.85rem;\">Nenhum comentário ainda.</p>";
    } catch (e) {
        listEl.innerHTML = "<p class=\"msg err\" style=\"font-size:.85rem;\">" + escapeHtml(e.message) + "</p>";
    }
}

function submitEventComment(eventId, inputEl, onSuccess) {
    var body = (inputEl && inputEl.value || "").trim();
    if (!body) return;
    api("/api/events/" + eventId + "/comments", { method: "POST", body: JSON.stringify({ body: body }) }).then(function() {
        var btn = inputEl.closest(".feed-post-actions") ? null : inputEl.closest(".feed-add-comment").querySelector("button");
        var countSpan = document.querySelector(".feed-event-card[data-event-id=\"" + eventId + "\"] .comment-count");
        if (countSpan) countSpan.textContent = (parseInt(countSpan.textContent, 10) || 0) + 1;
        if (onSuccess) onSuccess();
    }).catch(function(e) { alert(e.message); });
}

var explorarSearchTimeout = null;
function setupExplorarSearch() {
    var input = document.getElementById("explorar-search-input");
    if (!input || input._explorarSetup) return;
    input._explorarSetup = true;
    input.addEventListener("input", function() {
        var q = (input.value || "").trim();
        if (explorarSearchTimeout) clearTimeout(explorarSearchTimeout);
        if (q.length === 0) {
            document.getElementById("explorar-results").style.display = "none";
            document.getElementById("explorar-initial").style.display = "block";
            return;
        }
        explorarSearchTimeout = setTimeout(function() { runExplorarSearch(q); }, 320);
    });
}

async function runExplorarSearch(q) {
    document.getElementById("explorar-results").style.display = "block";
    document.getElementById("explorar-initial").style.display = "none";
    var athletesEl = document.getElementById("explorar-athletes");
    var teamsEl = document.getElementById("explorar-teams");
    athletesEl.innerHTML = "<p class=\"muted\">Buscando...</p>";
    teamsEl.innerHTML = "";
    try {
        var data = await api("/api/search?q=" + encodeURIComponent(q));
        var athletes = data.athletes || [];
        var teams = data.teams || [];
        athletesEl.innerHTML = athletes.length ? athletes.map(function(a) {
            var img = imageSrc(a.photo_url, "athletes") ? "<img src=\"" + escapeAttr(imageSrc(a.photo_url, "athletes")) + "\" alt=\"\">" : "<div style=\"width:48px;height:48px;border-radius:50%;background:var(--line);\"></div>";
            return "<a href=\"#\" class=\"explorar-result\" data-athlete-id=\"" + a.id + "\">" + img + "<div><div class=\"title\">" + escapeHtml(a.name || "Atleta") + "</div><div class=\"meta\">" + escapeHtml((a.academy || "") + " • " + (a.belt || "")) + "</div></div></a>";
        }).join("") : "<p class=\"muted\">Nenhum atleta encontrado para \"" + escapeHtml(q) + "\". Tente outro nome, academia ou faixa.</p>";
        teamsEl.innerHTML = teams.length ? teams.map(function(t) {
            return "<a href=\"#\" class=\"explorar-result\" data-team-id=\"" + t.id + "\"><div style=\"width:48px;height:48px;border-radius:50%;background:var(--line);display:flex;align-items:center;justify-content:center;font-size:1.2rem;\">👥</div><div><div class=\"title\">" + escapeHtml(t.name) + "</div><div class=\"meta\">" + (t.members_count || 0) + " membros</div></div></a>";
        }).join("") : "<p class=\"muted\">Nenhuma equipe encontrada para \"" + escapeHtml(q) + "\".</p>";
        document.getElementById("explorar-athletes-wrap").style.display = athletes.length ? "block" : "none";
        document.getElementById("explorar-teams-wrap").style.display = teams.length ? "block" : "none";
        document.querySelectorAll("#explorar-athletes .explorar-result[data-athlete-id]").forEach(function(el) {
            el.addEventListener("click", function(e) { e.preventDefault(); showAthleteProfilePage(Number(el.dataset.athleteId)); });
        });
        document.querySelectorAll("#explorar-athletes .explorar-follow-btn").forEach(function(btn) {
            btn.addEventListener("click", function(e) { e.preventDefault(); e.stopPropagation(); followAthleteFromExplorar(Number(btn.dataset.athleteId)); });
        });
        document.querySelectorAll("#explorar-teams .explorar-result[data-team-id]").forEach(function(el) {
            el.addEventListener("click", function(e) { e.preventDefault(); showTeamPage(Number(el.dataset.teamId)); });
        });
    } catch (err) {
        athletesEl.innerHTML = "<p class=\"msg err\">" + escapeHtml(err.message) + ". Verifique se você está logado.</p>";
    }
}

function followAthleteFromExplorar(athleteId) {
    api("/api/athletes/" + athleteId + "/follow", { method: "POST" }).then(function() {
        if (window._lastViewedAthleteId === athleteId) showAthleteProfilePage(athleteId);
        else { var q = document.getElementById("explorar-search-input").value.trim(); if (q) runExplorarSearch(q); else loadExplorarDiscover(); }
    }).catch(function(e) { alert(e.message); });
}

async function showAthleteProfilePage(athleteId) {
    var panel = document.getElementById("athlete-profile-page-panel");
    var content = document.getElementById("athlete-profile-page-content");
    var explorarResults = document.getElementById("explorar-results");
    var explorarInitial = document.getElementById("explorar-initial");
    if (!panel || !content) return;
    window._lastViewedAthleteId = athleteId;
    window._explorarFromSearch = explorarResults && explorarResults.style.display === "block";
    panel.style.display = "block";
    if (explorarResults) explorarResults.style.display = "none";
    if (explorarInitial) explorarInitial.style.display = "none";
    content.innerHTML = "<p class=\"muted\">Carregando...</p>";
    try {
        var data = await api("/api/athletes/" + athleteId);
        var photoHtml = imageSrc(data.photo_url, "athletes") ? "<img src=\"" + escapeAttr(imageSrc(data.photo_url, "athletes")) + "\" alt=\"\" class=\"profile-avatar\">" : "<div class=\"profile-avatar\" style=\"display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:2rem;\">?</div>";
        var meta = [data.academy, data.belt].filter(Boolean).join(" • ") || "—";
        var btnLabel = data.following ? "Deixar de seguir" : "Seguir";
        var btnClass = data.following ? "btn-soft" : "";
        content.innerHTML = "<div class=\"profile-header\"><div class=\"profile-avatar-wrap\">" + photoHtml + "</div><div class=\"profile-info\"><h2>" + escapeHtml(data.name || "Atleta") + "</h2><p class=\"profile-meta\">" + escapeHtml(meta) + "</p><div class=\"profile-stats\"><span>" + (data.followers_count || 0) + " <em>seguidores</em></span><span>" + (data.following_count || 0) + " <em>seguindo</em></span></div><button type=\"button\" class=\"" + btnClass + "\" id=\"athlete-profile-page-follow-btn\" data-athlete-id=\"" + athleteId + "\" data-following=\"" + (data.following ? "1" : "0") + "\">" + btnLabel + "</button></div></div>";
        var followBtn = document.getElementById("athlete-profile-page-follow-btn");
        if (followBtn) followBtn.addEventListener("click", function() { toggleFollowOnProfilePage(athleteId, followBtn); });
    } catch (err) {
        content.innerHTML = "<p class=\"msg err\" style=\"display:block;\">" + escapeHtml(err.message) + "</p>";
    }
}

async function toggleFollowOnProfilePage(athleteId, btnEl) {
    var isFollowing = btnEl.dataset.following === "1";
    try {
        if (isFollowing) {
            await api("/api/athletes/" + athleteId + "/unfollow", { method: "DELETE" });
            btnEl.textContent = "Seguir";
            btnEl.dataset.following = "0";
            btnEl.classList.remove("btn-soft");
        } else {
            await api("/api/athletes/" + athleteId + "/follow", { method: "POST" });
            btnEl.textContent = "Deixar de seguir";
            btnEl.dataset.following = "1";
            btnEl.classList.add("btn-soft");
        }
        showAthleteProfilePage(athleteId);
    } catch (e) { alert(e.message); }
}

async function loadExplorarDiscover() {
    var athletesEl = document.getElementById("explorar-discover-athletes");
    var teamsEl = document.getElementById("explorar-discover-teams");
    if (!athletesEl) return;
    try {
        var athletes = await api("/api/athlete-follows/discover");
        athletesEl.innerHTML = athletes.length ? athletes.map(function(a) {
            var img = imageSrc(a.photo_url, "athletes") ? "<img src=\"" + escapeAttr(imageSrc(a.photo_url, "athletes")) + "\" alt=\"\">" : "<div style=\"width:48px;height:48px;border-radius:50%;background:var(--line);\"></div>";
            return "<div class=\"explorar-result-row\"><a href=\"#\" class=\"explorar-result\" data-athlete-id=\"" + a.id + "\" style=\"cursor:pointer;flex:1;text-decoration:none;color:inherit;display:flex;align-items:center;gap:14px;\">" + img + "<div><div class=\"title\">" + escapeHtml(a.name || "Atleta") + "</div><div class=\"meta\">" + escapeHtml((a.academy || "") + " • " + (a.belt || "")) + "</div></div></a><button type=\"button\" class=\"btn-soft explorar-follow-btn\" data-athlete-id=\"" + a.id + "\">Seguir</button></div>";
        }).join("") : "<p class=\"muted\">Nenhuma sugestão no momento.</p>";
        document.querySelectorAll("#explorar-discover-athletes .explorar-result[data-athlete-id]").forEach(function(el) {
            el.addEventListener("click", function(e) { e.preventDefault(); showAthleteProfilePage(Number(el.dataset.athleteId)); });
        });
        document.querySelectorAll("#explorar-discover-athletes .explorar-follow-btn").forEach(function(btn) {
            btn.addEventListener("click", function(e) { e.preventDefault(); e.stopPropagation(); followAthleteFromExplorar(Number(btn.dataset.athleteId)); });
        });
        var teamsData = await api("/api/teams");
        var discoverTeams = teamsData.discover || [];
        teamsEl.innerHTML = discoverTeams.length ? discoverTeams.map(function(t) {
            return "<a href=\"#\" class=\"explorar-result\" data-team-id=\"" + t.id + "\"><div style=\"width:48px;height:48px;border-radius:50%;background:var(--line);display:flex;align-items:center;justify-content:center;\">👥</div><div><div class=\"title\">" + escapeHtml(t.name) + "</div><div class=\"meta\">" + (t.members_count || 0) + " membros</div></div></a>";
        }).join("") : "<p class=\"muted\">Nenhuma equipe para descobrir.</p>";
        document.querySelectorAll("#explorar-discover-teams .explorar-result[data-team-id]").forEach(function(el) {
            el.addEventListener("click", function(e) { e.preventDefault(); showTeamPage(Number(el.dataset.teamId)); });
        });
    } catch (err) {
        athletesEl.innerHTML = "<p class=\"muted\">Não foi possível carregar sugestões. Verifique se você está logado.</p>";
    }
}

async function initAthlete() {
    document.getElementById("event-select").addEventListener("change", loadCategoriesFromSelectedEvent);
    document.getElementById("register-btn").addEventListener("click", registerInCategory);
    document.getElementById("view-bracket-btn").addEventListener("click", viewAthleteBracket);

    document.getElementById("nav-torneios").addEventListener("click", function(e) { e.preventDefault(); showTorneiosView(); });
    var navTorneiosLeft = document.getElementById("athlete-nav-torneios");
    if (navTorneiosLeft) navTorneiosLeft.addEventListener("click", function(e) { e.preventDefault(); showTorneiosView(); });
    document.querySelectorAll(".athlete-nav-left .nav-item[data-view]").forEach(function(el) {
        el.addEventListener("click", function(e) { e.preventDefault(); showAthleteView(el.dataset.view); });
    });
    var navSair = document.getElementById("athlete-nav-sair");
    if (navSair) navSair.addEventListener("click", function(e) { e.preventDefault(); document.getElementById("logout")?.click(); });

    var hamburgerBtn = document.getElementById("athlete-hamburger-btn");
    var navDrawer = document.getElementById("athlete-nav-drawer");
    var navOverlay = document.getElementById("athlete-nav-overlay");
    function closeDrawer() {
        if (navDrawer) navDrawer.classList.remove("show");
        if (navOverlay) navOverlay.classList.remove("show");
        if (hamburgerBtn) hamburgerBtn.classList.remove("open");
    }
    function openDrawer() {
        if (navDrawer) navDrawer.classList.add("show");
        if (navOverlay) navOverlay.classList.add("show");
        if (hamburgerBtn) hamburgerBtn.classList.add("open");
    }
    if (hamburgerBtn) hamburgerBtn.addEventListener("click", function(e) { e.preventDefault(); navDrawer.classList.contains("show") ? closeDrawer() : openDrawer(); });
    if (navOverlay) navOverlay.addEventListener("click", closeDrawer);
    if (navDrawer) {
        navDrawer.querySelectorAll(".nav-item[data-view]").forEach(function(el) {
            el.addEventListener("click", function(e) {
                e.preventDefault();
                var view = el.dataset.view;
                if (view === "torneios") showTorneiosView(); else showAthleteView(view);
                closeDrawer();
                navDrawer.querySelectorAll(".nav-item").forEach(function(n) { n.classList.remove("active"); if (n.dataset.view === view) n.classList.add("active"); });
            });
        });
        var drawerSair = document.getElementById("athlete-drawer-sair");
        if (drawerSair) drawerSair.addEventListener("click", function(e) { e.preventDefault(); closeDrawer(); document.getElementById("logout")?.click(); });
    }
    document.getElementById("torneios-back-link").addEventListener("click", function(e) { e.preventDefault(); showAthleteDashboard(); showAthleteView("feed"); });
    document.getElementById("torneio-detail-back").addEventListener("click", function(e) { e.preventDefault(); backTorneioList(); });
    setupTorneiosFiltersListeners();
    var linkVerTorneios = document.getElementById("link-ver-torneios");
    if (linkVerTorneios) linkVerTorneios.addEventListener("click", function(e) { e.preventDefault(); showTorneiosView(); });

    var btnCreateTeam = document.getElementById("btn-create-team");
    if (btnCreateTeam) btnCreateTeam.addEventListener("click", createTeam);
    var teamPageBack = document.getElementById("team-page-back");
    if (teamPageBack) teamPageBack.addEventListener("click", function(e) { e.preventDefault(); document.getElementById("team-page-panel").style.display = "none"; });
    var athleteProfilePageBack = document.getElementById("athlete-profile-page-back");
    if (athleteProfilePageBack) athleteProfilePageBack.addEventListener("click", function(e) {
        e.preventDefault();
        var panel = document.getElementById("athlete-profile-page-panel");
        var explorarResults = document.getElementById("explorar-results");
        var explorarInitial = document.getElementById("explorar-initial");
        if (panel) panel.style.display = "none";
        if (window._explorarFromSearch && explorarResults) { explorarResults.style.display = "block"; if (explorarInitial) explorarInitial.style.display = "none"; }
        else { if (explorarInitial) explorarInitial.style.display = "block"; if (explorarResults) explorarResults.style.display = "none"; }
    });

    document.getElementById("athlete-panel").addEventListener("click", function(e) {
        if (e.target.id === "athlete-stat-followers") { e.preventDefault(); openFollowModal("followers"); }
        if (e.target.id === "athlete-stat-following") { e.preventDefault(); openFollowModal("following"); }
    });
    var followModal = document.getElementById("athlete-follow-modal");
    if (followModal) {
        document.getElementById("athlete-follow-modal-close").addEventListener("click", function() { followModal.style.display = "none"; });
        followModal.querySelector(".athlete-follow-modal-backdrop").addEventListener("click", function() { followModal.style.display = "none"; });
    }

    var newPostBtn = document.getElementById("athlete-new-post-btn");
    var newPostModal = document.getElementById("athlete-new-post-modal");
    if (newPostBtn && newPostModal) {
        newPostBtn.addEventListener("click", function() {
            document.getElementById("athlete-new-post-form").reset();
            document.getElementById("athlete-new-post-media-url").value = "";
            document.getElementById("athlete-new-post-media-type").value = "image";
            document.getElementById("athlete-new-post-preview").style.display = "none";
            document.getElementById("athlete-new-post-preview").innerHTML = "";
            document.getElementById("athlete-new-post-err").style.display = "none";
            newPostModal.style.display = "flex";
        });
        document.getElementById("athlete-new-post-cancel").addEventListener("click", function() { newPostModal.style.display = "none"; });
        document.getElementById("athlete-new-post-modal-backdrop").addEventListener("click", function() { newPostModal.style.display = "none"; });
        document.getElementById("athlete-new-post-media").addEventListener("change", async function() {
            var file = this.files[0];
            var preview = document.getElementById("athlete-new-post-preview");
            var urlInput = document.getElementById("athlete-new-post-media-url");
            var typeInput = document.getElementById("athlete-new-post-media-type");
            if (!file) { preview.style.display = "none"; preview.innerHTML = ""; urlInput.value = ""; return; }
            preview.style.display = "block";
            preview.innerHTML = "<p class=\"muted\">Enviando...</p>";
            try {
                var url = await uploadFile("/api/upload/post-media", "media", file);
                urlInput.value = url;
                typeInput.value = file.type.startsWith("video/") ? "video" : "image";
                if (typeInput.value === "video") {
                    preview.innerHTML = "<video src=\"" + escapeAttr(url) + "\" controls style=\"max-width:100%;max-height:240px;\"></video>";
                } else {
                    preview.innerHTML = "<img src=\"" + escapeAttr(url) + "\" alt=\"\" style=\"max-width:100%;max-height:240px;\">";
                }
            } catch (e) {
                preview.innerHTML = "<p class=\"msg err\">" + escapeHtml(e.message) + "</p>";
                urlInput.value = "";
            }
        });
        document.getElementById("athlete-new-post-form").addEventListener("submit", async function(e) {
            e.preventDefault();
            var urlInput = document.getElementById("athlete-new-post-media-url");
            if (!urlInput.value) {
                document.getElementById("athlete-new-post-err").textContent = "Selecione uma foto ou vídeo.";
                document.getElementById("athlete-new-post-err").style.display = "block";
                return;
            }
            document.getElementById("athlete-new-post-err").style.display = "none";
            try {
                await api("/api/athlete-posts", {
                    method: "POST",
                    body: JSON.stringify({
                        media_url: urlInput.value,
                        media_type: document.getElementById("athlete-new-post-media-type").value,
                        caption: document.getElementById("athlete-new-post-caption").value || null
                    })
                });
                newPostModal.style.display = "none";
                await loadAthleteDashboard();
            } catch (err) {
                document.getElementById("athlete-new-post-err").textContent = err.message;
                document.getElementById("athlete-new-post-err").style.display = "block";
            }
        });
    }

    document.getElementById("profile-form").addEventListener("submit", async (e) => {
        e.preventDefault();
        await submitProfileForm(e.target, "profile-ok", "profile-err", true);
    });

    document.getElementById("athlete-edit-profile-btn").addEventListener("click", () => {
        document.getElementById("athlete-edit-profile-panel").style.display = "block";
        fillProfileEditForm(athleteDashboard?.profile);
    });
    document.getElementById("athlete-cancel-edit-profile").addEventListener("click", () => {
        document.getElementById("athlete-edit-profile-panel").style.display = "none";
    });
    document.getElementById("profile-edit-form").addEventListener("submit", async (e) => {
        e.preventDefault();
        await submitProfileForm(e.target, "profile-edit-ok", "profile-edit-err", false);
        document.getElementById("athlete-edit-profile-panel").style.display = "none";
    });

    document.getElementById("profile-photo-file").addEventListener("change", async function () {
        const file = this.files[0];
        if (!file) return;
        const preview = document.getElementById("profile-photo-preview");
        preview.innerHTML = "<p class='muted'>Enviando...</p>";
        try {
            const url = await uploadFile("/api/upload/athlete-photo", "photo", file);
            document.getElementById("profile-photo-url").value = url;
            preview.innerHTML = "<img src=\"" + escapeAttr(url) + "\" alt=\"Foto\" style=\"width:100px;height:100px;object-fit:cover;border-radius:12px;\">";
        } catch (e) {
            preview.innerHTML = "<span class='msg err' style='display:block;'>" + escapeHtml(e.message) + "</span>";
        }
    });

    document.getElementById("profile-edit-photo-file").addEventListener("change", async function () {
        const file = this.files[0];
        if (!file) return;
        const preview = document.getElementById("profile-edit-photo-preview");
        preview.innerHTML = "<p class='muted'>Enviando...</p>";
        try {
            const url = await uploadFile("/api/upload/athlete-photo", "photo", file);
            document.getElementById("profile-edit-photo-url").value = url;
            preview.innerHTML = "<img src=\"" + escapeAttr(url) + "\" alt=\"Foto\" style=\"width:100px;height:100px;object-fit:cover;border-radius:12px;\">";
        } catch (e) {
            preview.innerHTML = "<span class='msg err' style='display:block;'>" + escapeHtml(e.message) + "</span>";
        }
    });

    await loadAthleteDashboard();
}

function ageFromBirthDate(birthDateStr) {
    if (!birthDateStr) return null;
    const d = new Date(birthDateStr);
    const today = new Date();
    let age = today.getFullYear() - d.getFullYear();
    const m = today.getMonth() - d.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < d.getDate())) age--;
    return age;
}

function fillProfileEditForm(profile) {
    if (!profile) return;
    const form = document.getElementById("profile-edit-form");
    form.birth_date.value = profile.birth_date ? profile.birth_date.slice(0, 10) : "";
    form.weight.value = profile.weight ?? "";
    form.belt.value = profile.belt ?? "";
    form.academy.value = profile.academy ?? "";
    form.gender.value = profile.gender ?? "MALE";
    const photoInput = document.getElementById("profile-edit-photo-url");
    if (photoInput) photoInput.value = profile.photo_url ?? "";
    const fileInput = document.getElementById("profile-edit-photo-file");
    if (fileInput) fileInput.value = "";
    const prev = document.getElementById("profile-edit-photo-preview");
    if (prev) prev.innerHTML = imageSrc(profile.photo_url, 'athletes') ? "<img src=\"" + escapeAttr(imageSrc(profile.photo_url, 'athletes')) + "\" alt=\"Foto\" style=\"width:100px;height:100px;object-fit:cover;border-radius:12px;\">" : "";
}

async function submitProfileForm(form, okId, errId, reloadDashboard) {
    const okEl = document.getElementById(okId);
    const errEl = document.getElementById(errId);
    okEl.style.display = "none";
    errEl.style.display = "none";
    try {
        const payload = {
            birth_date: form.birth_date.value,
            weight: Number(form.weight.value),
            belt: form.belt.value,
            academy: form.academy.value,
            gender: form.gender.value,
            photo_url: form.photo_url?.value?.trim() || null
        };
        const method = athleteDashboard?.completed ? "PUT" : "POST";
        await api("/api/athlete-profile", { method, body: JSON.stringify(payload) });
        okEl.textContent = "Perfil salvo com sucesso.";
        okEl.style.display = "block";
        if (reloadDashboard) await loadAthleteDashboard();
    } catch (error) {
        errEl.textContent = error.message;
        errEl.style.display = "block";
    }
}

async function loadAthleteDashboard() {
    const completeBlock = document.getElementById("athlete-complete-cadastro");
    const dashboardBlock = document.getElementById("athlete-dashboard-content");
    try {
        athleteDashboard = await api("/api/athlete-dashboard");
    } catch (e) {
        completeBlock.classList.remove("hidden");
        dashboardBlock.classList.add("hidden");
        completeBlock.querySelector(".panel-body").insertAdjacentHTML("afterbegin",
            "<div class=\"msg err\" style=\"display:block;\">Erro ao carregar: " + e.message + "</div>");
        return;
    }

    if (!athleteDashboard.completed || !athleteDashboard.profile) {
        completeBlock.classList.remove("hidden");
        dashboardBlock.classList.add("hidden");
        document.getElementById("profile-form").photo_url.value = "";
        return;
    }

    completeBlock.classList.add("hidden");
    dashboardBlock.classList.remove("hidden");
    athleteProfileExists = true;

    const p = athleteDashboard.profile;
    const name = athleteDashboard.user_name || user?.name || "Atleta";
    const age = ageFromBirthDate(p.birth_date);
    const details = [
        age != null ? age + " anos" : "",
        p.belt ? "Faixa " + p.belt : "",
        p.weight ? p.weight + " kg" : "",
        p.academy ? "Academia " + p.academy : ""
    ].filter(Boolean).join(" • ");
    const titlesCount = athleteDashboard.championships_won?.length || 0;
    const regsCount = athleteDashboard.my_registrations_count || 0;
    const followersCount = athleteDashboard.followers_count || 0;
    const followingCount = athleteDashboard.following_count || 0;
    const postsCount = athleteDashboard.posts_count || 0;

    const photoEl = document.getElementById("athlete-resumo-photo");
    if (p.photo_url) {
        var photoSrc = imageSrc(p.photo_url, 'athletes');
        photoEl.innerHTML = "<img class=\"resumo-photo-img\" src=\"" + escapeAttr(photoSrc) + "\" alt=\"Foto\" onerror=\"this.classList.add('photo-failed');var s=this.nextElementSibling;if(s)s.classList.add('show');\"><span class=\"photo-fallback\">Sem foto</span>";
    } else {
        photoEl.innerHTML = "<span class=\"photo-fallback show\">Sem foto</span>";
    }

    document.getElementById("athlete-resumo-name").textContent = name;
    var detailsStr = details || "Complete seu perfil para ver mais.";
    if (titlesCount || regsCount) detailsStr += " · " + titlesCount + " títulos · " + regsCount + " inscrições";
    document.getElementById("athlete-resumo-details").textContent = detailsStr;
    var postsEl = document.getElementById("athlete-stat-posts");
    if (postsEl) postsEl.textContent = postsCount + " publicações";
    var followersEl = document.getElementById("athlete-stat-followers");
    var followingEl = document.getElementById("athlete-stat-following");
    if (followersEl) { followersEl.textContent = followersCount + " seguidores"; followersEl.href = "#"; followersEl.dataset.count = followersCount; }
    if (followingEl) { followingEl.textContent = followingCount + " seguindo"; followingEl.href = "#"; followingEl.dataset.count = followingCount; }

    renderMyTeams(athleteDashboard.my_teams || []);
    renderAthletePostsGrid(athleteDashboard.my_posts || []);
    loadDiscoverAthletes();
    loadTeams();
    setupCommunitySearch();
    var createTeamWrap = document.getElementById("athlete-create-team-wrap");
    if (createTeamWrap) createTeamWrap.style.display = (user && user.role === "organizer") ? "" : "none";

    const championshipsEl = document.getElementById("athlete-championships-won");
    const championships = athleteDashboard.championships_won || [];
    if (championships.length === 0) {
        championshipsEl.innerHTML = "<p class=\"muted\">Nenhum titulo ainda.</p>";
    } else {
        championshipsEl.innerHTML = championships.map((c) => `
            <div class="card">
                <h4>${escapeHtml(c.event?.name || "Evento")}</h4>
                <div class="meta">Categoria ${escapeHtml(c.category?.belt || "-")} • ${c.event?.date ? new Date(c.event.date).toLocaleDateString("pt-BR") : ""}</div>
            </div>
        `).join("");
    }

    const regsEl = document.getElementById("my-registrations");
    const regs = athleteDashboard.my_registrations || [];
    if (regs.length === 0) {
        regsEl.innerHTML = "<p class=\"muted\">Voce ainda nao possui inscricoes.</p>";
    } else {
        regsEl.innerHTML = regs.map((r) => {
            const ev = r.category?.event || {};
            const dateStr = ev.date ? new Date(ev.date).toLocaleDateString("pt-BR") : "-";
            const startsStr = ev.starts_at ? new Date(ev.starts_at).toLocaleString("pt-BR") : "-";
            const loc = escapeHtml(ev.location || "-");
            const info = escapeHtml(ev.athlete_info || "").replace(/\n/g, "<br>");
            const catId = r.category?.id || "";
            return `
            <article class="card registration-card" data-category-id="${catId}">
                <div class="registration-card-head" onclick="toggleRegistrationDetail(this)" style="cursor:pointer;display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                    <div>
                        <h4 style="margin:0 0 4px;">${escapeHtml(ev.name || "Evento")} • ${escapeHtml(r.category?.belt || "-")}</h4>
                        <div class="meta">Pagamento: ${r.payment_status} • Clique para ver detalhes e chave</div>
                    </div>
                    <span class="registration-toggle" style="flex-shrink:0;">▼</span>
                </div>
                <div class="registration-card-body" style="display:none;margin-top:12px;padding-top:12px;border-top:1px solid var(--line);">
                    <p style="margin:6px 0;"><strong>Data:</strong> ${dateStr}</p>
                    <p style="margin:6px 0;"><strong>Local:</strong> ${loc}</p>
                    <p style="margin:6px 0;"><strong>Horario de inicio:</strong> ${startsStr}</p>
                    ${info ? `<div class="athlete-info-box" style="margin:10px 0;padding:10px;background:var(--bg);border-radius:10px;white-space:pre-wrap;">${info}</div>` : ""}
                    <button type="button" class="btn-soft" onclick="event.stopPropagation();loadBracketInRegistrationCard(this, ${catId})">Ver chave da categoria</button>
                    <div class="registration-bracket" style="margin-top:10px;"></div>
                </div>
            </article>`;
        }).join("");
    }

    const historyEl = document.getElementById("athlete-history");
    const history = athleteDashboard.history_events || [];
    if (history.length === 0) {
        historyEl.innerHTML = "<p class=\"muted\">Nenhum campeonato no historico.</p>";
    } else {
        historyEl.innerHTML = history.map((ev) => `
            <div class="card">
                <h4>${escapeHtml(ev.name || "Evento")}</h4>
                <div class="meta">${ev.location || ""} • ${ev.date ? new Date(ev.date).toLocaleDateString("pt-BR") : ""} • ${ev.status || ""}</div>
            </div>
        `).join("");
    }

    await loadAthleteEvents();
    showAthleteView("feed");
}

function toggleRegistrationDetail(headEl) {
    const card = headEl.closest(".registration-card");
    const body = card.querySelector(".registration-card-body");
    const toggle = card.querySelector(".registration-toggle");
    const isOpen = body.style.display !== "none";
    body.style.display = isOpen ? "none" : "block";
    toggle.textContent = isOpen ? "▼" : "▲";
}

async function loadBracketInRegistrationCard(btnEl, categoryId) {
    const card = btnEl.closest(".registration-card");
    const box = card.querySelector(".registration-bracket");
    if (!box) return;
    box.innerHTML = "<p class='muted'>Carregando chave...</p>";
    try {
        const matches = await api("/api/categories/" + categoryId + "/matches");
        if (!matches.length) {
            box.innerHTML = "<p class='muted'>Chave ainda nao gerada.</p>";
            return;
        }
        function athleteName(a) {
            if (!a) return "-";
            return (a.user && a.user.name) ? a.user.name : (a.academy || "Atleta #" + a.id);
        }
        box.innerHTML = matches.map((m) => `
            <div class="match" style="margin-bottom:8px;">
                <strong>Round ${m.round_number} • Luta ${m.match_number}</strong>
                <div class="meta">${m.athlete1 ? athleteName(m.athlete1) : "-"} vs ${m.athlete2 ? athleteName(m.athlete2) : "-"}</div>
                <div class="meta">${m.status}${m.winner_id ? " • Vencedor: " + athleteName(m.winner) : ""}</div>
            </div>
        `).join("");
    } catch (e) {
        box.innerHTML = "<p class='msg err' style='display:block;'>" + escapeHtml(e.message) + "</p>";
    }
}
window.toggleRegistrationDetail = toggleRegistrationDetail;
window.loadBracketInRegistrationCard = loadBracketInRegistrationCard;

function isEmbedBlockedUrl(url) {
    if (!url || typeof url !== "string") return true;
    try {
        var u = url.toLowerCase();
        if (/^https?:\/\//i.test(u) && (u.indexOf("facebook.com") >= 0 || u.indexOf("fbcdn.net") >= 0 || u.indexOf("fb.me") >= 0)) return true;
        return false;
    } catch (e) { return true; }
}
function imageSrc(url, folder) {
    if (!url) return "";
    if (/^https?:\/\//i.test(url)) {
        if (isEmbedBlockedUrl(url)) return "";
        return url;
    }
    var base = window.location.origin;
    if (url.indexOf("/") === -1) return base + "/uploads/" + (folder || "banners") + "/" + url;
    return base + (url.startsWith("/") ? url : "/" + url);
}
var DEFAULT_TOURNAMENT_BANNER_SVG = "data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22640%22 height=%22240%22 viewBox=%220 0 640 240%22%3E%3Cdefs%3E%3ClinearGradient id=%22g%22 x1=%220%25%22 y1=%220%25%22 x2=%22100%25%22 y2=%22100%25%22/%3E%3Cstop offset=%220%25%22 stop-color=%22%231e293b%22/%3E%3Cstop offset=%22100%25%22 stop-color=%22%230f172a%22/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width=%22640%22 height=%22240%22 fill=%22url(%23g)%22/%3E%3Ctext x=%2250%25%22 y=%2245%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%23f8fafc%22 font-size=%2232%22 font-family=%22Arial%22 font-weight=%22bold%22%3ETORNEIO%3C/text%3E%3Ctext x=%2250%25%22 y=%2258%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%23c41e3a%22 font-size=%2218%22 font-family=%22Arial%22%3ECombat%3C/text%3E%3C/svg%3E";
function eventBannerSrc(ev) {
    if (ev && ev.banner_url) return imageSrc(ev.banner_url, "banners");
    return DEFAULT_TOURNAMENT_BANNER_SVG;
}
function eventBannerFallback(imgEl) {
    if (imgEl && imgEl.src !== DEFAULT_TOURNAMENT_BANNER_SVG) { imgEl.onerror = null; imgEl.src = DEFAULT_TOURNAMENT_BANNER_SVG; }
}

function escapeAttr(s) {
    if (!s) return "";
    const div = document.createElement("div");
    div.textContent = s;
    return div.innerHTML.replace(/"/g, "&quot;");
}
function escapeHtml(s) {
    if (!s) return "";
    const div = document.createElement("div");
    div.textContent = s;
    return div.innerHTML;
}

function updateProfilePhotoPreview(inputEl, previewContainerId) {
    const container = document.getElementById(previewContainerId);
    if (!container) return;
    const url = inputEl?.value?.trim();
    if (url) {
        container.innerHTML = "<img src=\"" + escapeAttr(url) + "\" alt=\"Preview\" style=\"width:100%;height:100%;border-radius:12px;object-fit:cover;\">";
        container.style.display = "block";
    } else {
        container.innerHTML = "";
        container.style.display = "none";
    }
}

async function loadAthleteEvents() {
    const eventSelect = document.getElementById("event-select");
    try {
        athleteEvents = await api("/api/events");
        eventSelect.innerHTML = athleteEvents.map((e) => `<option value="${e.id}">${e.name} (${e.status})</option>`).join("");
        await loadCategoriesFromSelectedEvent();
    } catch (error) {
        eventSelect.innerHTML = "<option value=''>Erro ao carregar eventos</option>";
    }
}

async function loadCategoriesFromSelectedEvent() {
    const eventId = document.getElementById("event-select").value;
    const categorySelect = document.getElementById("category-select");
    if (!eventId) {
        categorySelect.innerHTML = "<option value=''>Selecione evento</option>";
        return;
    }
    try {
        athleteCategories = await api(`/api/events/${eventId}/categories`);
        categorySelect.innerHTML = athleteCategories.map((c) =>
            `<option value="${c.id}">${c.belt} | ${c.gender} | ${c.weight_min}-${c.weight_max}kg</option>`
        ).join("");
    } catch (error) {
        categorySelect.innerHTML = "<option value=''>Erro ao carregar categorias</option>";
    }
}

async function registerInCategory(e) {
    e.preventDefault();
    const ok = document.getElementById("reg-ok");
    const err = document.getElementById("reg-err");
    ok.style.display = "none";
    err.style.display = "none";
    try {
        const payload = {
            category_id: Number(document.getElementById("category-select").value)
        };
        await api("/api/registrations", { method: "POST", body: JSON.stringify(payload) });
        ok.textContent = "Inscricao realizada com sucesso.";
        ok.style.display = "block";
        await loadAthleteDashboard();
    } catch (error) {
        err.textContent = error.message;
        err.style.display = "block";
    }
}

async function loadMyRegistrations() {
    const box = document.getElementById("my-registrations");
    box.innerHTML = "<p class='muted'>Carregando inscricoes...</p>";
    try {
        const regs = await api("/api/registrations");
        if (!regs.length) {
            box.innerHTML = "<p class='muted'>Voce ainda nao possui inscricoes.</p>";
            return;
        }
        box.innerHTML = regs.map((r) => `
            <article class="card">
                <h4>${r.category?.event?.name || "Evento"} • categoria ${r.category?.belt || "-"}</h4>
                <div class="meta">Pagamento: ${r.payment_status} • genero ${r.category?.gender || "-"}</div>
            </article>
        `).join("");
    } catch (error) {
        box.innerHTML = `<div class="msg err" style="display:block;">Erro ao carregar inscricoes: ${error.message}</div>`;
    }
}

function athleteDisplayName(a) {
    if (!a) return "-";
    return (a.user && a.user.name) ? a.user.name : (a.academy || "Atleta #" + a.id);
}

async function viewAthleteBracket() {
    const box = document.getElementById("athlete-bracket");
    const categoryId = document.getElementById("category-select").value;
    if (!categoryId) return;
    box.innerHTML = "<p class='muted'>Carregando chave...</p>";
    try {
        const matches = await api(`/api/categories/${categoryId}/matches`);
        if (!matches.length) {
            box.innerHTML = "<p class='muted'>Chave ainda nao gerada para esta categoria.</p>";
            return;
        }
        box.innerHTML = matches.map((m) => `
            <article class="match">
                <strong>Round ${m.round_number} • Luta ${m.match_number}</strong>
                <div>${athleteDisplayName(m.athlete1)} vs ${athleteDisplayName(m.athlete2)}</div>
                <div class="meta">Status: ${m.status}${m.winner ? " • Vencedor: " + athleteDisplayName(m.winner) : ""}</div>
            </article>
        `).join("");
    } catch (error) {
        box.innerHTML = `<div class="msg err" style="display:block;">Erro ao carregar chave: ${error.message}</div>`;
    }
}
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
    if (e.target.closest && e.target.closest(".theme-toggle")) {
      setTheme(getTheme() === "dark" ? "light" : "dark");
    }
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
<?php /**PATH /home/u494944867/domains/vitorum.com.br/resources/views/dashboard.blade.php ENDPATH**/ ?>