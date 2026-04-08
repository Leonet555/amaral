/**
 * Fight Company Kids — React (UMD) no painel do atleta.
 * Cadastro → formulário de evento de campeonato.
 */
(function () {
    function getAuth() {
        try {
            var t = localStorage.getItem("auth_token");
            var u = localStorage.getItem("auth_user");
            if (!t || !u) return { token: null, user: null };
            return { token: t, user: JSON.parse(u) };
        } catch (e) {
            return { token: null, user: null };
        }
    }

    function setAuth(token, user) {
        localStorage.setItem("auth_token", token);
        localStorage.setItem("auth_user", JSON.stringify(user));
    }

    function apiJson(path, options) {
        var auth = getAuth();
        var headers = {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "Content-Type": "application/json",
        };
        if (auth.token) headers.Authorization = "Bearer " + auth.token;
        return fetch(path, Object.assign({ credentials: "same-origin", headers: headers }, options || {})).then(function (res) {
            return res.text().then(function (text) {
                var data = null;
                if (text) {
                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        data = null;
                    }
                }
                if (!res.ok) {
                    var msg = (data && data.message) || "Erro " + res.status;
                    throw new Error(typeof msg === "string" ? msg : JSON.stringify(msg));
                }
                return data;
            });
        });
    }

    function FightCompanyKidsApp() {
        var h = React.createElement;
        var useState = React.useState;

        var a0 = getAuth();
        var startsAsAthlete = !!(a0.token && a0.user && a0.user.role === "athlete");

        var stepSt = useState(startsAsAthlete ? "event" : "register");
        var step = stepSt[0];
        var setStep = stepSt[1];
        var errSt = useState("");
        var err = errSt[0];
        var setErr = errSt[1];
        var okSt = useState("");
        var ok = okSt[0];
        var setOk = okSt[1];

        var regNameSt = useState("");
        var regEmailSt = useState("");
        var regPassSt = useState("");
        var regName = regNameSt[0];
        var setRegName = regNameSt[1];
        var regEmail = regEmailSt[0];
        var setRegEmail = regEmailSt[1];
        var regPass = regPassSt[0];
        var setRegPass = regPassSt[1];

        var evNameSt = useState("");
        var descSt = useState("");
        var dateSt = useState("");
        var locSt = useState("");
        var sportSt = useState("BJJ");
        var deadlineSt = useState("");
        var startsSt = useState("");
        var evName = evNameSt[0];
        var setEvName = evNameSt[1];
        var desc = descSt[0];
        var setDesc = descSt[1];
        var evDate = dateSt[0];
        var setEvDate = dateSt[1];
        var loc = locSt[0];
        var setLoc = locSt[1];
        var sport = sportSt[0];
        var setSport = sportSt[1];
        var deadline = deadlineSt[0];
        var setDeadline = deadlineSt[1];
        var startsAt = startsSt[0];
        var setStartsAt = startsSt[1];

        function onRegister(ev) {
            ev.preventDefault();
            setErr("");
            setOk("");
            apiJson("/api/auth/register", {
                method: "POST",
                body: JSON.stringify({
                    name: regName.trim(),
                    email: regEmail.trim(),
                    password: regPass,
                    role: "athlete",
                }),
            })
                .then(function (data) {
                    if (!data.token || !data.user) throw new Error("Resposta inválida do servidor.");
                    setAuth(data.token, data.user);
                    setStep("event");
                    setOk("Conta criada. Agora cadastre o campeonato.");
                })
                .catch(function (e) {
                    setErr(e.message || "Falha no cadastro.");
                });
        }

        function onEventSubmit(ev) {
            ev.preventDefault();
            setErr("");
            setOk("");
            var payload = {
                name: evName.trim(),
                description: desc.trim() || null,
                date: evDate,
                location: loc.trim(),
                sport_type: sport,
                registration_deadline: deadline,
            };
            if (startsAt) payload.starts_at = startsAt;
            apiJson("/api/fight-company-kids/events", { method: "POST", body: JSON.stringify(payload) })
                .then(function () {
                    setOk("Campeonato enviado como rascunho. Categorias e abertura de inscrições podem ser feitas no painel do organizador, se você tiver acesso.");
                    setEvName("");
                    setDesc("");
                    setEvDate("");
                    setLoc("");
                    setDeadline("");
                    setStartsAt("");
                    setSport("BJJ");
                })
                .catch(function (e) {
                    setErr(e.message || "Não foi possível salvar.");
                });
        }

        var loginLink = h(
            "a",
            {
                href: "/login",
                onClick: function (e) {
                    e.preventDefault();
                    window.location.href = "/login";
                },
            },
            "Entrar"
        );

        var headerBlock = h(
            React.Fragment,
            null,
            h("h2", { className: "fck-title" }, "Fight Company Kids"),
            h("p", { className: "fck-subtitle" }, "Programa de campeonatos infantis. Cadastre-se ou acesse sua conta."),
            h("p", { className: "fck-login-hint" }, "Já tem conta? ", loginLink)
        );

        var regForm = h(
            "form",
            { className: "fck-form", onSubmit: onRegister },
            h("div", { className: "fck-field" }, h("label", null, "Nome completo"), h("input", { type: "text", required: true, value: regName, onChange: function (e) { return setRegName(e.target.value); } })),
            h("div", { className: "fck-field" }, h("label", null, "E-mail"), h("input", { type: "email", required: true, autoComplete: "email", value: regEmail, onChange: function (e) { return setRegEmail(e.target.value); } })),
            h("div", { className: "fck-field" }, h("label", null, "Senha (mín. 6 caracteres)"), h("input", { type: "password", required: true, minLength: 6, autoComplete: "new-password", value: regPass, onChange: function (e) { return setRegPass(e.target.value); } })),
            h("button", { type: "submit", className: "fck-btn" }, "Cadastrar e continuar")
        );

        var evForm = h(
            "form",
            { className: "fck-form", onSubmit: onEventSubmit },
            h("h3", { className: "fck-section-title" }, "Dados do campeonato"),
            h("div", { className: "fck-field" }, h("label", null, "Nome do campeonato"), h("input", { type: "text", required: true, placeholder: "Ex.: Copa Kids 2026", value: evName, onChange: function (e) { return setEvName(e.target.value); } })),
            h("div", { className: "fck-field" }, h("label", null, "Descrição (opcional)"), h("textarea", { rows: 3, value: desc, onChange: function (e) { return setDesc(e.target.value); } })),
            h("div", { className: "fck-field" }, h("label", null, "Data do evento"), h("input", { type: "date", required: true, value: evDate, onChange: function (e) { return setEvDate(e.target.value); } })),
            h("div", { className: "fck-field" }, h("label", null, "Início (opcional)"), h("input", { type: "datetime-local", value: startsAt, onChange: function (e) { return setStartsAt(e.target.value); } })),
            h("div", { className: "fck-field" }, h("label", null, "Local"), h("input", { type: "text", required: true, placeholder: "Cidade / academia", value: loc, onChange: function (e) { return setLoc(e.target.value); } })),
            h("div", { className: "fck-field" }, h("label", null, "Modalidade"), h("select", { value: sport, onChange: function (e) { return setSport(e.target.value); } }, h("option", { value: "BJJ" }, "BJJ"), h("option", { value: "JUDO" }, "Judo"))),
            h("div", { className: "fck-field" }, h("label", null, "Prazo final de inscrições"), h("input", { type: "date", required: true, value: deadline, onChange: function (e) { return setDeadline(e.target.value); } })),
            h("button", { type: "submit", className: "fck-btn" }, "Enviar cadastro do campeonato")
        );

        return h(
            "div",
            { className: "fck-app" },
            headerBlock,
            err ? h("div", { className: "fck-msg err" }, err) : null,
            ok ? h("div", { className: "fck-msg ok" }, ok) : null,
            step === "register" ? regForm : evForm
        );
    }

    function mount() {
        var el = document.getElementById("fight-company-kids-root");
        if (!el || typeof React === "undefined" || typeof ReactDOM === "undefined") return;
        if (el._fckMounted) return;
        el._fckMounted = true;
        var root = ReactDOM.createRoot(el);
        root.render(React.createElement(FightCompanyKidsApp));
    }

    window.FCK_tryMount = mount;

    if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", mount);
    else mount();
})();
