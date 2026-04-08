# Tournament Management SaaS API

## Configuração local

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

## Restaurar .env em produção (ex.: vitorum.com.br)

Se o `.env` foi perdido após vincular ao domínio:

1. **Copie o template** (no servidor, na pasta do projeto):
   ```bash
   cp env.vitorum.txt .env
   ```
   Ou crie o `.env` manualmente usando o conteúdo de `env.vitorum.txt`.

2. **Ajuste as variáveis** no `.env`:
   - `APP_URL=https://vitorum.com.br`
   - `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` conforme seu provedor de banco.

3. **Gere a chave da aplicação**:
   ```bash
   php artisan key:generate
   ```

4. **Sanctum (login/sessão)** – o template já inclui:
   - `SANCTUM_STATEFUL_DOMAINS=vitorum.com.br,www.vitorum.com.br`

5. **Opcional:** em produção use `APP_DEBUG=false` e `APP_ENV=production`.

## API Authentication

```bash
POST /api/auth/register
POST /api/auth/login
POST /api/auth/logout
```

## Organizer Routes

```bash
GET    /api/events
POST   /api/events
PUT    /api/events/{event}
DELETE /api/events/{event}
POST   /api/events/{event}/open-registration
POST   /api/events/{event}/close-registration
POST   /api/events/{event}/finalize

GET    /api/events/{event}/categories
POST   /api/events/{event}/categories
PUT    /api/categories/{category}
DELETE /api/categories/{category}
POST   /api/categories/{category}/generate-bracket
GET    /api/categories/{category}/matches
POST   /api/matches/{match}/result
```

## Athlete Routes

```bash
GET  /api/athlete-profile
POST /api/athlete-profile
PUT  /api/athlete-profile
POST /api/registrations
```
