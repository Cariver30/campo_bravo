<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoverCarouselItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
            'image' => ['required', 'image'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $path = $request->file('image')->store('cover_carousel', 'public');

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
}
