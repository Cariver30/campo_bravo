@php
    $menuLabel = trim($settings->tab_label_menu ?? $settings->button_label_menu ?? 'Menú');
    $cocktailLabel = trim($settings->tab_label_cocktails ?? $settings->button_label_cocktails ?? 'Cócteles');
    $cavaLabel = trim($settings->tab_label_wines ?? $settings->button_label_wines ?? 'Cava de vinos');
    if (\Illuminate\Support\Facades\Route::has('cava.index')) {
        $cavaRouteUrl = route('cava.index');
    } elseif (\Illuminate\Support\Facades\Route::has('coffee.index')) {
        $cavaRouteUrl = route('coffee.index');
    } else {
        $cavaRouteUrl = url('/cava');
    }
    $navLinks = [
        ['href' => url('/'), 'icon' => 'fas fa-home', 'label' => 'Inicio', 'visible' => true],
        ['href' => url('/menu'), 'icon' => 'fas fa-utensils', 'label' => $menuLabel, 'visible' => $settings->show_tab_menu ?? true],
        ['href' => url('/cocktails'), 'icon' => 'fas fa-cocktail', 'label' => $cocktailLabel, 'visible' => $settings->show_tab_cocktails ?? true],
        ['href' => $cavaRouteUrl, 'icon' => 'fas fa-wine-glass', 'label' => $cavaLabel, 'visible' => $settings->show_tab_wines ?? true],
        ['href' => route('loyalty.invitations.show'), 'icon' => 'fas fa-gift', 'label' => trim($settings->tab_label_loyalty ?? 'Fidelidad'), 'visible' => $settings->show_tab_loyalty ?? false],
    ];
@endphp

<div class="fixed bottom-5 left-0 right-0 z-50 content-layer px-4">
    <div class="flex w-full flex-nowrap items-center gap-4 px-4 py-2 rounded-3xl backdrop-blur-lg border border-white/20 shadow-2xl overflow-x-auto"
         style="background-color: {{ $background ?? 'rgba(0,0,0,0.55)' }};">
        @foreach($navLinks as $link)
            @continue(!($link['visible'] ?? true))
            <a href="{{ $link['href'] }}"
               class="flex flex-shrink-0 items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105 whitespace-nowrap"
               style="background-color: {{ $buttonColor ?? '#000' }};">
                <i class="{{ $link['icon'] }} text-lg"></i>
                <span>{{ $link['label'] }}</span>
            </a>
        @endforeach
    </div>
</div>
