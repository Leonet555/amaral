<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar | Vitorum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: Inter, Arial, sans-serif; background: #f8fafc; margin: 0; color: #0f172a; }
        .wrap { min-height: 100vh; display: grid; place-items: center; padding: 24px; }
        .card { width: min(440px, 100%); background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 10px 24px rgba(15,23,42,.08); }
        h1 { margin: 0 0 8px; font-size: 1.55rem; }
        p { margin: 0 0 16px; color: #64748b; }
        label { display: block; margin: 12px 0 6px; font-weight: 600; font-size: .9rem; }
        input { width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 11px 12px; font-size: .95rem; }
        button { margin-top: 14px; width: 100%; background: #c41e3a; color: #fff; border: 0; border-radius: 10px; padding: 11px 12px; font-weight: 600; cursor: pointer; transition: all .25s cubic-bezier(.4,0,.2,1); }
        button:hover { transform: translateY(-1px); background: #a01830; }
        .help { margin-top: 12px; font-size: .9rem; color: #64748b; }
        .help a { color: #c41e3a; text-decoration: none; }
        .error { margin-top: 12px; background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; border-radius: 10px; padding: 10px; display: none; }
        .ok { margin-top: 12px; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; border-radius: 10px; padding: 10px; display: none; }
        .auth-logo { display: block; text-align: center; margin-bottom: 20px; }
        .auth-logo img { height: 72px; width: auto; }
    </style>
</head>
<body>
<div class="wrap">
    <form class="card" id="login-form">
        <a href="/" class="auth-logo"><img src="/logo" alt="Vitorum"></a>
        <h1>Entrar na plataforma</h1>
        <p>Acesse sua conta para gerenciar eventos e categorias.</p>

        <label for="email">E-mail</label>
        <input id="email" name="email" type="email" required>

        <label for="password">Senha</label>
        <input id="password" name="password" type="password" required>

        <button type="submit">Entrar</button>
        <div id="error" class="error"></div>
        <div id="ok" class="ok"></div>
        <div class="help">Nao tem conta? <a href="/register">Criar conta</a></div>
    </form>
</div>

<script>
const form = document.getElementById("login-form");
const errorBox = document.getElementById("error");
const okBox = document.getElementById("ok");

async function readJsonSafe(response) {
    const text = await response.text();
    if (!text) return {};
    try {
        return JSON.parse(text);
    } catch {
        return null;
    }
}

form.addEventListener("submit", async (event) => {
    event.preventDefault();
    errorBox.style.display = "none";
    okBox.style.display = "none";

    const payload = {
        email: form.email.value,
        password: form.password.value
    };

    try {
        const response = await fetch("/api/auth/login", {
            method: "POST",
            headers: { "Content-Type": "application/json", "Accept": "application/json" },
            body: JSON.stringify(payload)
        });
        const data = await readJsonSafe(response);

        if (!response.ok) {
            if (data === null || response.status >= 500) {
                throw new Error(
                    "Erro no servidor (código " + response.status + "). Verifique banco de dados e .env."
                );
            }
            throw new Error(data.message || "Falha no login.");
        }

        if (!data.token || !data.user) {
            throw new Error("Resposta inválida do servidor.");
        }

        localStorage.setItem("auth_token", data.token);
        localStorage.setItem("auth_user", JSON.stringify(data.user));
        okBox.textContent = "Login realizado com sucesso.";
        okBox.style.display = "block";
        window.location.href = data.user?.role === "athlete" ? "/athlete" : "/organizer";
    } catch (error) {
        errorBox.textContent = error.message;
        errorBox.style.display = "block";
    }
});
</script>
</body>
</html>
<?php /**PATH /home/u494944867/domains/vitorum.com.br/resources/views/auth/login.blade.php ENDPATH**/ ?>