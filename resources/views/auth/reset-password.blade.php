<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova senha | Vitorum</title>
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
        input { width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 11px 12px; font-size: .95rem; box-sizing: border-box; }
        button { margin-top: 14px; width: 100%; background: #c41e3a; color: #fff; border: 0; border-radius: 10px; padding: 11px 12px; font-weight: 600; cursor: pointer; transition: all .25s cubic-bezier(.4,0,.2,1); }
        button:hover { transform: translateY(-1px); background: #a01830; }
        button:disabled { opacity: .65; cursor: not-allowed; transform: none; }
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
    <form class="card" id="reset-form">
        <a href="/" class="auth-logo"><img src="/logo" alt="Vitorum"></a>
        <h1>Definir nova senha</h1>
        <p>Escolha uma nova senha para sua conta.</p>

        <input type="hidden" id="token" name="token" value="{{ $token }}">

        <label for="email">E-mail</label>
        <input id="email" name="email" type="email" required autocomplete="email" value="{{ e(request('email', '')) }}">

        <label for="password">Nova senha</label>
        <input id="password" name="password" type="password" minlength="6" required autocomplete="new-password">

        <label for="password_confirmation">Confirmar senha</label>
        <input id="password_confirmation" name="password_confirmation" type="password" minlength="6" required autocomplete="new-password">

        <button type="submit" id="submit-btn">Salvar nova senha</button>
        <div id="error" class="error"></div>
        <div id="ok" class="ok"></div>
        <div class="help"><a href="/login">Ir para o login</a></div>
    </form>
</div>

<script>
const form = document.getElementById("reset-form");
const errorBox = document.getElementById("error");
const okBox = document.getElementById("ok");
const submitBtn = document.getElementById("submit-btn");

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

    if (form.password.value !== form.password_confirmation.value) {
        errorBox.textContent = "As senhas não coincidem.";
        errorBox.style.display = "block";
        return;
    }

    submitBtn.disabled = true;

    try {
        const response = await fetch("/api/auth/reset-password", {
            method: "POST",
            headers: { "Content-Type": "application/json", "Accept": "application/json" },
            body: JSON.stringify({
                token: form.token.value,
                email: form.email.value.trim(),
                password: form.password.value,
                password_confirmation: form.password_confirmation.value
            })
        });
        const data = await readJsonSafe(response);

        if (!response.ok) {
            if (data === null || response.status >= 500) {
                throw new Error("Erro no servidor (código " + response.status + ").");
            }
            throw new Error(data.message || "Não foi possível redefinir a senha.");
        }

        okBox.textContent = data.message || "Senha alterada com sucesso.";
        okBox.style.display = "block";
        setTimeout(() => { window.location.href = "/login"; }, 1500);
    } catch (error) {
        errorBox.textContent = error.message;
        errorBox.style.display = "block";
    } finally {
        submitBtn.disabled = false;
    }
});
</script>
</body>
</html>
