@php
    $menuLabel = trim($settings->tab_label_menu ?? $settings->button_label_menu ?? 'Menú');
    $cocktailLabel = trim($settings->tab_label_cocktails ?? $settings->button_label_cocktails ?? 'Cócteles');
    $coffeeLabel = trim($settings->tab_label_wines ?? $settings->button_label_wines ?? 'Café & Brunch');
    $navLinks = [
        ['href' => url('/'), 'icon' => 'fas fa-home', 'label' => 'Inicio'],
        ['href' => url('/menu'), 'icon' => 'fas fa-utensils', 'label' => $menuLabel],
        ['href' => url('/cocktails'), 'icon' => 'fas fa-cocktail', 'label' => $cocktailLabel],
        ['href' => url('/coffee'), 'icon' => 'fas fa-mug-saucer', 'label' => $coffeeLabel],
    ];
@endphp

<div class="fixed bottom-5 left-0 right-0 z-50 flex justify-center content-layer">
    <div class="flex flex-wrap lg:flex-nowrap items-center gap-4 px-4 py-2 rounded-3xl backdrop-blur-lg border border-white/20 shadow-2xl"
         style="background-color: {{ $background ?? 'rgba(0,0,0,0.55)' }};">
        @foreach($navLinks as $link)
            <a href="{{ $link['href'] }}"
               class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
               style="background-color: {{ $buttonColor ?? '#000' }};">
                <i class="{{ $link['icon'] }} text-lg"></i>
                <span>{{ $link['label'] }}</span>
            </a>
        @endforeach
    </div>
</div>
