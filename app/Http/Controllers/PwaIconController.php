<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class PwaIconController extends Controller
{
    /** Ícone PNG para PWA com logo Vitorum (V). Usa arquivo em public/icons/ se existir, senão gera. */
    public function icon192()
    {
        return $this->serveOrGenerateIcon(192);
    }

    public function icon512()
    {
        return $this->serveOrGenerateIcon(512);
    }

    private function serveOrGenerateIcon(int $size): Response
    {
        $path = public_path("icons/icon-{$size}.png");
        if (File::isFile($path)) {
            return response()->file($path, [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        $logoPath = public_path('images/logo.png');
        if (File::isFile($logoPath) && function_exists('imagecreatefrompng')) {
            $png = $this->resizeLogoPng($logoPath, $size);
            if ($png !== null) {
                return response($png, 200, [
                    'Content-Type' => 'image/png',
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }
        }

        if (function_exists('imagecreatetruecolor')) {
            $png = $this->generateVitorumIconPng($size);
            if ($png !== null) {
                return response($png, 200, [
                    'Content-Type' => 'image/png',
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }
        }

        return $this->fallbackSvg($size);
    }

    /** Redimensiona logo.png para tamanho quadrado. */
    private function resizeLogoPng(string $logoPath, int $size): ?string
    {
        $src = @imagecreatefrompng($logoPath);
        if (!$src) {
            return null;
        }
        $w = imagesx($src);
        $h = imagesy($src);
        $img = imagecreatetruecolor($size, $size);
        if (!$img) {
            imagedestroy($src);
            return null;
        }
        imagealphablending($img, false);
        imagesavealpha($img, true);
        $trans = imagecolorallocatealpha($img, 196, 30, 58, 0);
        imagefill($img, 0, 0, $trans);
        imagecopyresampled($img, $src, 0, 0, 0, 0, $size, $size, $w, $h);
        imagedestroy($src);
        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        imagedestroy($img);
        return $png;
    }

    /** Gera PNG com fundo vermelho e letra V (Vitorum). */
    private function generateVitorumIconPng(int $size): ?string
    {
        $img = @imagecreatetruecolor($size, $size);
        if (!$img) {
            return null;
        }
        $red = imagecolorallocate($img, 196, 30, 58);
        $white = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $red);

        $m = (int) round($size * 0.12);
        $cx = (int) ($size / 2);
        $topY = $m;
        $bottomY = $size - $m;
        $leftX = $m;
        $rightX = $size - $m;
        $innerY = $bottomY - (int) round($size * 0.15);

        $pointsLeft = [$leftX, $topY, $cx, $bottomY, $cx, $innerY];
        $pointsRight = [$rightX, $topY, $cx, $bottomY, $cx, $innerY];
        imagefilledpolygon($img, $pointsLeft, 3, $white);
        imagefilledpolygon($img, $pointsRight, 3, $white);

        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        imagedestroy($img);
        return $png;
    }

    private function fallbackSvg(int $size): Response
    {
        $svg = '<?xml version="1.0"?><svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '"><rect width="' . $size . '" height="' . $size . '" fill="#c41e3a" rx="' . round($size * 0.12) . '"/><text x="50%" y="58%" fill="white" font-size="' . round($size * 0.42) . '" font-family="Arial,sans-serif" font-weight="bold" text-anchor="middle" dominant-baseline="middle">V</text></svg>';

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
