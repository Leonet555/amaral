<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class LogoController extends Controller
{
    /**
     * Serve a logo do Vitorum. Se existir public/images/logo.png, devolve esse arquivo.
     * Caso contrário, devolve um SVG de fallback (evita 404).
     */
    public function show(): Response
    {
        $path = public_path('images/logo.png');

        if (File::isFile($path)) {
            return response()->file($path, [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        return response($this->fallbackSvg(), 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    private function fallbackSvg(): string
    {
        return <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="180" height="56" viewBox="0 0 180 56" fill="none">
  <defs>
    <linearGradient id="gold" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" style="stop-color:#f4e4bc"/>
      <stop offset="50%" style="stop-color:#d4af37"/>
      <stop offset="100%" style="stop-color:#b8960c"/>
    </linearGradient>
    <linearGradient id="blue" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#2a5a8f"/>
      <stop offset="100%" style="stop-color:#1a3a5f"/>
    </linearGradient>
  </defs>
  <!-- Estrela -->
  <path d="M90 4 L92 10 L98 10 L93 14 L95 20 L90 16 L85 20 L87 14 L82 10 L88 10 Z" fill="url(#gold)"/>
  <!-- V central -->
  <path d="M78 12 L90 38 L102 12 L97 12 L90 28 L83 12 Z" fill="url(#gold)"/>
  <!-- Asas esquerda -->
  <path d="M50 18 L78 22 L78 26 L58 24 L42 32 L38 28 L52 20 Z" fill="url(#blue)"/>
  <!-- Asas direita -->
  <path d="M130 18 L102 22 L102 26 L122 24 L138 32 L142 28 L128 20 Z" fill="url(#blue)"/>
  <!-- Texto VITORUM -->
  <text x="90" y="50" text-anchor="middle" font-family="Arial, sans-serif" font-size="14" font-weight="800" fill="#2d3748" letter-spacing="0.12em">VITORUM</text>
  <!-- Linha dourada -->
  <path d="M60 54 Q90 52 120 54" stroke="url(#gold)" stroke-width="2.5" stroke-linecap="round" fill="none"/>
</svg>
SVG;
    }
}
