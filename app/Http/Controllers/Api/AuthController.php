<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in([User::ROLE_ORGANIZER, User::ROLE_ATHLETE])],
        ]);

        $user = User::create($data);
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 422);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $mailerName = config('mail.default', 'smtp');
        $mailerCfg = config("mail.mailers.{$mailerName}", []);
        if (($mailerCfg['transport'] ?? '') === 'smtp') {
            if (! filled($mailerCfg['username'] ?? null) || ! filled($mailerCfg['password'] ?? null)) {
                return response()->json([
                    'message' => 'O servidor ainda não tem senha de e-mail: no .env preencha MAIL_PASSWORD com a senha da caixa system@vitorum.com.br (Hostinger → E-mails). Confirme MAIL_USERNAME=system@vitorum.com.br. Depois execute: php artisan config:clear',
                ], 503);
            }
        }

        try {
            $status = Password::sendResetLink($request->only('email'));
        } catch (QueryException $e) {
            Log::error('forgot_password_db', ['message' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erro na base de dados. No servidor, execute: php artisan migrate (cria a tabela password_reset_tokens).',
            ], 503);
        } catch (Throwable $e) {
            Log::error('forgot_password_mail', [
                'exception' => $e::class,
                'message' => $e->getMessage(),
            ]);

            $broker = config('auth.defaults.passwords');
            $table = config("auth.passwords.{$broker}.table", 'password_reset_tokens');
            $email = (string) $request->input('email');

            // Fallback automático: se falhar no mailer padrão SMTP (geralmente 465),
            // tenta novamente com o mailer "hostinger" (porta 587).
            if ($mailerName !== 'hostinger' && config('mail.mailers.hostinger')) {
                DB::table($table)->where('email', $email)->delete();
                $originalMailer = $mailerName;

                try {
                    Config::set('mail.default', 'hostinger');
                    $fallbackStatus = Password::sendResetLink(['email' => $email]);

                    if (in_array($fallbackStatus, [Password::RESET_LINK_SENT, Password::INVALID_USER], true)) {
                        return response()->json([
                            'message' => 'Se este e-mail estiver cadastrado, você receberá um link para redefinir a senha em instantes.',
                        ]);
                    }
                } catch (Throwable $fallbackException) {
                    Log::error('forgot_password_mail_fallback', [
                        'exception' => $fallbackException::class,
                        'message' => $fallbackException->getMessage(),
                    ]);
                } finally {
                    Config::set('mail.default', $originalMailer);
                }
            }

            // Sem apagar, o utilizador fica bloqueado por minutos (429) mesmo sem receber link.
            DB::table($table)->where('email', $email)->delete();

            return response()->json([
                'message' => 'Não foi possível conectar ao SMTP. Verifique MAIL_PASSWORD e execute php artisan config:clear. Hostinger: MAIL_HOST=smtp.hostinger.com, MAIL_PORT=465 (ou MAIL_MAILER=hostinger para porta 587). Se o erro persistir, tente MAIL_VERIFY_SSL=false no .env.',
            ], 503);
        }

        if ($status === Password::RESET_THROTTLED) {
            return response()->json([
                'message' => 'Aguarde alguns minutos antes de solicitar outro link.',
            ], 429);
        }

        return response()->json([
            'message' => 'Se este e-mail estiver cadastrado, você receberá um link para redefinir a senha em instantes.',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Senha redefinida com sucesso. Você já pode entrar.',
            ]);
        }

        $messages = [
            Password::INVALID_TOKEN => 'Este link de recuperação é inválido ou expirou. Solicite um novo.',
            Password::INVALID_USER => 'Não encontramos uma conta com este e-mail.',
        ];

        return response()->json([
            'message' => $messages[$status] ?? 'Não foi possível redefinir a senha. Tente novamente.',
        ], 422);
    }
}
