<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar conta | Vitorum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: Inter, Arial, sans-serif; background: #f8fafc; margin: 0; color: #0f172a; }
        .wrap { min-height: 100vh; display: grid; place-items: center; padding: 24px; }
        .card { width: min(460px, 100%); background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 10px 24px rgba(15,23,42,.08); }
        h1 { margin: 0 0 8px; font-size: 1.55rem; }
        p { margin: 0 0 16px; color: #64748b; }
        label { display: block; margin: 12px 0 6px; font-weight: 600; font-size: .9rem; }
        input, select { width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 11px 12px; font-size: .95rem; }
        button { margin-top: 14px; width: 100%; background: #c41e3a; color: #fff; border: 0; border-radius: 10px; padding: 11px 12px; font-weight: 600; cursor: pointer; transition: all .25s cubic-bezier(.4,0,.2,1); }
        button:hover { transform: translateY(-1px); background: #a01830; }
        .help { margin-top: 12px; font-size: .9rem; color: #64748b; }
        .help a { color: #c41e3a; text-decoration: none; }
        .error { margin-top: 12px; background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; border-radius: 10px; padding: 10px; display: none; white-space: pre-line; }
        .auth-logo { display: block; text-align: center; margin-bottom: 20px; }
        .auth-logo img { height: 72px; width: auto; }
    </style>
</head>
<body>
<div class="wrap">
    <form class="card" id="register-form">
        <a href="/" class="auth-logo"><img src="/logo" alt="Vitorum"></a>
        <h1>Criar conta</h1>
        <p>Comece agora e organize torneios com fluxo profissional.</p>

        <label for="name">Nome</label>
        <input id="name" name="name" type="text" required>

        <label for="email">E-mail</label>
        <input id="email" name="email" type="email" required>

        <label for="password">Senha</label>
        <input id="password" name="password" type="password" minlength="6" required>

        <label for="role">Perfil</label>
        <select id="role" name="role">
            <option value="organizer">Organizador</option>
            <option value="athlete">Atleta</option>
        </select>

        <button type="submit">Criar conta</button>
        <div id="error" class="error"></div>
        <div class="help">Ja possui conta? <a href="/login">Entrar</a></div>
    </form>
</div>

<script>
const form = document.getElementById("register-form");
const errorBox = document.getElementById("error");

function buildErrorMessage(data) {
    if (data.message) return data.message;
    if (data.errors) return Object.values(data.errors).flat().join("\n");
    return "Falha ao criar conta.";
}

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

    const payload = {
        name: form.name.value,
        email: form.email.value,
        password: form.password.value,
        role: form.role.value
    };

    try {
        const response = await fetch("/api/auth/register", {
            method: "POST",
            headers: { "Content-Type": "application/json", "Accept": "application/json" },
            body: JSON.stringify(payload)
        });
        const data = await readJsonSafe(response);

        if (!response.ok) {
            if (data === null || response.status >= 500) {
                throw new Error(
                    "Erro no servidor (código " + response.status + "). " +
                    "Se o cadastro ainda não funcionar, verifique a conexão MySQL no painel e no arquivo .env (DB_*), e as migrações (php artisan migrate)."
                );
            }
            throw new Error(buildErrorMessage(data));
        }

        if (!data.token || !data.user) {
            throw new Error("Resposta inválida do servidor.");
        }

        localStorage.setItem("auth_token", data.token);
        localStorage.setItem("auth_user", JSON.stringify(data.user));
        window.location.href = data.user?.role === "athlete" ? "/athlete" : "/organizer";
    } catch (error) {
        errorBox.textContent = error.message || "Falha ao criar conta.";
        errorBox.style.display = "block";
    }
});
</script>
</body>
</html>
