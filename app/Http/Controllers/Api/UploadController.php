<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class UploadController extends Controller
{
    private const MAX_SIZE_KB = 2048;

    /**
     * Serve arquivos de upload (banners e fotos) via Laravel.
     * Usado quando o servidor nao serve public/uploads diretamente (ex.: doc root nao e public).
     */
    public function serve(Request $request, string $folder, string $filename)
    {
        if (!in_array($folder, ['banners', 'athletes', 'posts'], true)) {
            abort(404);
        }
        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $filename)) {
            abort(404);
        }
        $path = public_path("uploads/{$folder}/{$filename}");
        if (!is_file($path)) {
            abort(404);
        }
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $mimes = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp', 'mp4' => 'video/mp4', 'webm' => 'video/webm'];
        $mime = $mimes[$ext] ?? 'application/octet-stream';

        return response()->file($path, ['Content-Type' => $mime]);
    }

    /**
     * Upload de banner do evento (organizador).
     * Arquivos em public/uploads; URL aponta para rota que serve o arquivo.
     */
    public function banner(Request $request)
    {
        $request->validate([
            'banner' => ['required', 'file', 'image', 'mimes:jpeg,png,gif,webp', 'max:' . self::MAX_SIZE_KB],
        ]);

        $file = $request->file('banner');
        $path = $this->storeInPublic($file, 'uploads/banners');

        return response()->json(['url' => $this->fullUrl($request, $path)]);
    }

    /**
     * Upload de foto do atleta.
     */
    public function athletePhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'file', 'image', 'mimes:jpeg,png,gif,webp', 'max:' . self::MAX_SIZE_KB],
        ]);

        $file = $request->file('photo');
        $path = $this->storeInPublic($file, 'uploads/athletes');

        return response()->json(['url' => $this->fullUrl($request, $path)]);
    }

    /** Upload de mídia para publicação do atleta (foto ou vídeo). */
    public function postMedia(Request $request)
    {
        $request->validate([
            'media' => ['required', 'file', 'max:20480'], // 20MB para vídeo
        ]);

        $file = $request->file('media');
        $mime = $file->getMimeType();
        $allowedImages = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowedVideos = ['video/mp4', 'video/webm'];

        if (!in_array($mime, array_merge($allowedImages, $allowedVideos), true)) {
            return response()->json(['message' => 'Arquivo deve ser imagem (JPEG, PNG, GIF, WebP) ou vídeo (MP4, WebM).'], 422);
        }

        $path = $this->storeInPublic($file, 'uploads/posts');
        return response()->json(['url' => $this->fullUrl($request, $path)]);
    }

    /** Garante URL absoluta para o frontend nao solicitar so o filename (404). */
    private function fullUrl(Request $request, string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        $base = rtrim($request->getSchemeAndHttpHost(), '/');

        return $base . '/' . $path;
    }

    private function storeInPublic($file, string $directory): string
    {
        $dir = public_path($directory);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $name = Str::ulid() . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);

        return $directory . '/' . $name;
    }
}
