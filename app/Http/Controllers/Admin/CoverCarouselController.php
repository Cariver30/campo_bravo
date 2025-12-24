<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoverCarouselItem;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CoverCarouselController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'subtitle' => filled($request->input('subtitle')) ? $request->input('subtitle') : null,
            'link_label' => filled($request->input('link_label')) ? $request->input('link_label') : null,
            'link_url' => filled($request->input('link_url')) ? $request->input('link_url') : null,
        ]);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'link_label' => ['nullable', 'string', 'max:80'],
            'link_url' => ['nullable', 'url'],
            'image' => ['nullable', 'image'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('cover_carousel', 'public');
        } else {
            $path = $this->resolveFallbackImagePath();
            if (! $path) {
                return back()
                    ->withInput()
                    ->withErrors(['image' => 'Debes subir una imagen para esta tarjeta del carrusel o cargar un logo en configuraciones.']);
            }
        }

        CoverCarouselItem::create([
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
            'link_label' => $data['link_label'] ?? null,
            'link_url' => $data['link_url'] ?? null,
            'image_path' => $path,
            'position' => $data['position'] ?? 0,
            'visible' => true,
        ]);

        return redirect()->route('admin.new-panel', [
            'section' => 'general',
            'focus' => 'cover-carousel-panel',
        ])->with('success', 'Elemento añadido al carrusel.');
    }

    public function update(Request $request, CoverCarouselItem $coverCarouselItem): RedirectResponse
    {
        $request->merge([
            'subtitle' => filled($request->input('subtitle')) ? $request->input('subtitle') : null,
            'link_label' => filled($request->input('link_label')) ? $request->input('link_label') : null,
            'link_url' => filled($request->input('link_url')) ? $request->input('link_url') : null,
        ]);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'link_label' => ['nullable', 'string', 'max:80'],
            'link_url' => ['nullable', 'url'],
            'position' => ['nullable', 'integer', 'min:0'],
            'visible' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image'],
        ]);

        if ($request->hasFile('image')) {
            $coverCarouselItem->image_path = $request->file('image')->store('cover_carousel', 'public');
        }

        $coverCarouselItem->fill([
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
            'link_label' => $data['link_label'] ?? null,
            'link_url' => $data['link_url'] ?? null,
            'position' => $data['position'] ?? $coverCarouselItem->position,
            'visible' => $request->boolean('visible', $coverCarouselItem->visible),
        ])->save();

        return redirect()->route('admin.new-panel', [
            'section' => 'general',
            'focus' => 'cover-carousel-panel',
        ])->with('success', 'Elemento actualizado.');
    }

    public function destroy(CoverCarouselItem $coverCarouselItem): RedirectResponse
    {
        $coverCarouselItem->delete();

        return redirect()->route('admin.new-panel', [
            'section' => 'general',
            'focus' => 'cover-carousel-panel',
        ])->with('success', 'Elemento eliminado.');
    }

    protected function resolveFallbackImagePath(): ?string
    {
        $settings = Setting::first();
        if ($settings && $settings->logo) {
            return $settings->logo;
        }

        $placeholderPath = 'cover_carousel/default-placeholder.svg';
        try {
            if (! Storage::disk('public')->exists($placeholderPath)) {
                Storage::disk('public')->put($placeholderPath, $this->defaultPlaceholderSvg());
            }
        } catch (\Throwable $e) {
            // ignore write errors, still return the relative path
        }

        return $placeholderPath;
    }

    protected function defaultPlaceholderSvg(): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="800" height="600" viewBox="0 0 800 600">
    <defs>
        <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#1e1b4b;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#0f172a;stop-opacity:1" />
        </linearGradient>
    </defs>
    <rect width="800" height="600" fill="url(#grad)"/>
    <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle"
          font-family="Arial, Helvetica, sans-serif" font-size="48" fill="#ffffff" opacity="0.85">
        Café Negro
    </text>
    <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle"
          font-family="Arial, Helvetica, sans-serif" font-size="20" fill="#ffffff" opacity="0.65">
        Imagen pendiente
    </text>
</svg>
SVG;
    }
}
