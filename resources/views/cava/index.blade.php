@php
    $palette = [
        'blue' => '#397db5',
        'cream' => '#fff2b3',
        'violet' => '#762d79',
        'amber' => '#ffb723',
    ];
    $drinkTextColor = $settings->text_color_wines ?? $palette['cream'];
    $drinkAccentColor = $settings->button_color_wines ?? $palette['amber'];
    $drinkCardBg = $settings->card_bg_color_wines ?? 'rgba(118, 45, 121, 0.35)';
    $drinkCardOpacity = $settings->card_opacity_wines ?? 0.9;
    $categoryBgColor = $settings->category_name_bg_color_wines ?? 'rgba(57, 125, 181, 0.35)';
    $categoryTextColor = $settings->category_name_text_color_wines ?? $palette['cream'];
    $categoryFontSize = $settings->category_name_font_size_wines ?? 28;
    $winesBackgroundDisabled = (bool) ($settings->disable_background_wines ?? false);
    $logoPlaceholderSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><rect width="200" height="200" fill="#762d79"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#fff2b3" font-family="Arial, sans-serif" font-size="36">LOGO</text></svg>';
    $logoFallback = $settings && $settings->logo
        ? asset('storage/' . $settings->logo)
        : 'data:image/svg+xml,' . rawurlencode($logoPlaceholderSvg);
    $filtersActive = $filters ?? false;
    $categoriesForFilters = $filterCategories ?? collect();
    $selectedFilters = $selectedFilters ?? [];
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>La Cava de vinos</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        :root {
            --drink-text-color: {{ $drinkTextColor }};
            --drink-accent-color: {{ $drinkAccentColor }};
            --drink-blue: {{ $palette['blue'] }};
            --drink-violet: {{ $palette['violet'] }};
            --drink-cream: {{ $palette['cream'] }};
            --drink-amber: {{ $palette['amber'] }};
        }
        html, body {
            min-height: 100vh;
        }
        body {
            font-family: {{ $settings->font_family_wines ?? 'ui-sans-serif' }};
            color: var(--drink-text-color);
            @if($winesBackgroundDisabled)
                background: transparent;
            @elseif($settings && $settings->background_image_wines)
                background: none;
            @else
                background: linear-gradient(150deg, var(--drink-violet) 0%, var(--drink-blue) 70%);
            @endif
            background-size: cover;
            background-attachment: fixed;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            @if($winesBackgroundDisabled)
                display: none;
            @elseif($settings && $settings->background_image_wines)
                background: url('{{ asset('storage/' . $settings->background_image_wines) }}') no-repeat center center;
                background-size: cover;
            @else
                background: radial-gradient(circle at 20% 20%, rgba(255, 242, 179, 0.22), rgba(118, 45, 121, 0.25) 50%, rgba(57, 125, 181, 0.35));
            @endif
        }
        .content-layer {
            position: relative;
            z-index: 1;
        }
        html {
            scroll-behavior: smooth;
        }
        .category-nav-link {
            transition: color 0.3s ease, transform 0.3s ease;
            transform-origin: left center;
        }
        .category-nav-link.active {
            color: var(--drink-accent-color);
            transform: scale(1.05);
            font-weight: 600;
        }
        .sidebar-panel {
            background: rgba(57, 125, 181, 0.15);
            color: {{ $settings->sidebar_text_color_wines ?? $palette['cream'] }};
            border-right: 1px solid rgba(255, 242, 179, 0.25);
            backdrop-filter: blur(6px);
        }
        .mobile-menu-panel {
            background: rgba(57, 125, 181, 0.2);
            color: {{ $settings->sidebar_text_color_wines ?? $palette['cream'] }};
        }
        .category-chip {
            background-color: rgba(118, 45, 121, 0.2);
            border: 1px solid rgba(255, 242, 179, 0.3);
            color: var(--drink-text-color);
        }
        .category-chip.active,
        .category-chip:hover {
            background-color: rgba(57, 125, 181, 0.35);
            color: var(--drink-cream);
        }
        .filter-panel {
            background: rgba(118, 45, 121, 0.38);
            border: 1px solid rgba(255, 242, 179, 0.35);
            backdrop-filter: blur(6px);
        }
        .filter-input {
            background-color: rgba(57, 125, 181, 0.2);
            border: 1px solid rgba(255, 242, 179, 0.35);
            color: var(--drink-text-color);
            outline: none;
        }
        .filter-input::placeholder {
            color: rgba(255, 242, 179, 0.7);
        }
        .filter-input:focus {
            border-color: var(--drink-accent-color);
            box-shadow: 0 0 0 2px rgba(255, 183, 35, 0.25);
        }
        .filter-chip {
            background-color: rgba(118, 45, 121, 0.25);
            border: 1px solid rgba(255, 242, 179, 0.4);
            color: var(--drink-text-color);
        }
        .drink-card {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
            background-color: {{ $drinkCardBg }};
            color: var(--drink-text-color);
            border: 1px solid rgba(255, 242, 179, 0.2);
            backdrop-filter: blur(6px);
        }
        .drink-card.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .hero-media {
            width: 100%;
            max-height: 420px;
            aspect-ratio: 16 / 9;
            object-fit: cover;
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 242, 179, 0.25);
        }
        .tag-chip {
            background-color: rgba(255, 242, 179, 0.18);
            border: 1px solid rgba(57, 125, 181, 0.3);
        }
        @media (max-width: 768px) {
            body {
                background-position: center top;
                background-attachment: fixed;
            }
        }
    </style>
</head>
<body>

<!-- LOGO + BOTÓN MENU -->
<div class="text-center py-6 relative content-layer">
    <img src="{{ $logoFallback }}" class="mx-auto h-24" alt="Logo del concepto">

    <button id="toggleMenu"
            class="fixed left-4 top-4 z-50 w-12 h-12 rounded-full flex items-center justify-center text-xl shadow-lg lg:hidden"
            style="background-color: var(--drink-accent-color); color: var(--drink-cream);">
        🍷
    </button>

    <div class="hidden lg:block">
        <div class="fixed top-0 left-0 h-full w-64 sidebar-panel p-6 space-y-2 shadow-lg overflow-y-auto">
            @foreach(($wineCategories ?? collect()) as $category)
                <a href="#category{{ $category->id }}" class="block text-lg font-semibold hover:text-amber-500 category-nav-link" data-category-target="category{{ $category->id }}">{{ $category->name }}</a>
            @endforeach
        </div>
    </div>
</div>

@if($filtersActive)
    @php
        $chips = [];
        if ($selected->get('q')) {
            $chips[] = 'Coincidencia: ' . $selected->get('q');
        }
        if ($selected->get('category') && $categoriesForFilters->count()) {
            $chips[] = 'Categoría: ' . optional($categoriesForFilters->firstWhere('id', $selected->get('category')))->name;
        }
        if ($selected->get('type') && $types->count()) {
            $chips[] = 'Método: ' . optional($types->firstWhere('id', $selected->get('type')))->name;
        }
        if ($selected->get('region') && $regions->count()) {
            $chips[] = 'Región: ' . optional($regions->firstWhere('id', $selected->get('region')))->name;
        }
        if ($selected->get('grape') && $grapes->count()) {
            $chips[] = 'Uva: ' . optional($grapes->firstWhere('id', $selected->get('grape')))->name;
        }
        if ($selected->get('max_price')) {
            $chips[] = 'Hasta $' . number_format($selected->get('max_price'), 0);
        }
    @endphp
    @if(count(array_filter($chips)))
        <div class="max-w-5xl mx-auto px-4 pb-4 content-layer">
            <div class="flex flex-wrap gap-2 text-sm" style="color: {{ $palette['cream'] }};">
                <span class="px-3 py-1 rounded-full filter-chip">Filtros activos</span>
                @foreach($chips as $chip)
                    @if($chip)
                        <span class="px-3 py-1 rounded-full filter-chip">{{ $chip }}</span>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
@endif

@if($settings->coffee_hero_image)
    <div class="max-w-4xl mx-auto px-4 pb-8 content-layer">
        <img src="{{ asset('storage/' . $settings->coffee_hero_image) }}" alt="Destacado de bebidas" class="hero-media shadow-2xl border" style="border-color: rgba(255, 242, 179, 0.25);">
    </div>
@endif

@php $selected = collect($selectedFilters); @endphp
<div class="max-w-5xl mx-auto px-4 pb-6 content-layer">
    <form method="GET" action="{{ route('cava.index') }}"
          class="filter-panel rounded-3xl p-6 space-y-4">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[180px]">
                <label class="text-xs uppercase tracking-[0.3em] mb-1 block" style="color: rgba(255, 242, 179, 0.8);">Buscar vino</label>
                <input type="text" name="q" value="{{ $selected->get('q') }}"
                       class="w-full rounded-2xl px-4 py-2 filter-input"
                       placeholder="Nombre, nota o estilo">
            </div>
            <div class="flex-1 min-w-[180px]">
                <label class="text-xs uppercase tracking-[0.3em] mb-1 block" style="color: rgba(255, 242, 179, 0.8);">Categoría</label>
                <select name="category"
                        class="w-full rounded-2xl px-4 py-2 filter-input">
                    <option value="">Todas</option>
                    @foreach($categoriesForFilters as $category)
                        <option value="{{ $category->id }}" {{ (string) $selected->get('category') === (string) $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[180px]">
                <label class="text-xs uppercase tracking-[0.3em] mb-1 block" style="color: rgba(255, 242, 179, 0.8);">Método</label>
                <select name="type"
                        class="w-full rounded-2xl px-4 py-2 filter-input">
                    <option value="">Todos</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ (string) $selected->get('type') === (string) $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[180px]">
                <label class="text-xs uppercase tracking-[0.3em] mb-1 block" style="color: rgba(255, 242, 179, 0.8);">Región</label>
                <select name="region"
                        class="w-full rounded-2xl px-4 py-2 filter-input">
                    <option value="">Todas</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}" {{ (string) $selected->get('region') === (string) $region->id ? 'selected' : '' }}>
                            {{ $region->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[180px]">
                <label class="text-xs uppercase tracking-[0.3em] mb-1 block" style="color: rgba(255, 242, 179, 0.8);">Uva</label>
                <select name="grape"
                        class="w-full rounded-2xl px-4 py-2 filter-input">
                    <option value="">Todas</option>
                    @foreach($grapes as $grape)
                        <option value="{{ $grape->id }}" {{ (string) $selected->get('grape') === (string) $grape->id ? 'selected' : '' }}>
                            {{ $grape->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="text-xs uppercase tracking-[0.3em] mb-1 block" style="color: rgba(255, 242, 179, 0.8);">Precio máximo</label>
                <input type="number" step="1" min="0" name="max_price" value="{{ $selected->get('max_price') }}"
                       class="w-full rounded-2xl px-4 py-2 filter-input"
                       placeholder="Ej. 75">
            </div>
        </div>
        <div class="flex flex-wrap gap-4 justify-end pt-2">
            <a href="{{ route('cava.index') }}"
               class="px-4 py-2 rounded-full transition"
               style="border: 1px solid rgba(255, 242, 179, 0.35); color: {{ $palette['cream'] }};">
                Limpiar filtros
            </a>
            <button type="submit"
                    class="px-6 py-2 rounded-full font-semibold shadow-lg"
                    style="background-color: var(--drink-accent-color); color: {{ $palette['violet'] }};">
                Aplicar filtros
            </button>
        </div>
    </form>
</div>

<!-- Menú lateral móvil -->
<div id="categoryMenu"
    class="lg:hidden fixed inset-0 mobile-menu-panel px-6 py-8 space-y-6 overflow-y-auto transform -translate-y-full transition-transform duration-300 z-[60]">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold tracking-[0.25em] uppercase" style="color: {{ $palette['cream'] }};">Categorías</h2>
        <button id="closeMenu" class="text-2xl" style="color: {{ $palette['blue'] }};">&times;</button>
    </div>
    <div class="grid grid-cols-2 gap-4">
        @foreach(($wineCategories ?? collect()) as $category)
            <button class="rounded-2xl border py-4 px-3 text-sm font-semibold text-left shadow category-nav-link"
                    style="border-color: rgba(255, 242, 179, 0.35); background-color: rgba(57, 125, 181, 0.1); color: {{ $palette['cream'] }};"
                    data-category-target="category{{ $category->id }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>
</div>
<div id="menuOverlay" class="fixed inset-0 z-50 hidden lg:hidden" style="background-color: rgba(57, 125, 181, 0.5);"></div>

<!-- Carrusel de chips -->
@if(($wineCategories ?? collect())->count())
    <div class="lg:hidden content-layer sticky top-20 z-30 px-4">
        <div class="flex gap-3 overflow-x-auto py-3 snap-x snap-mandatory">
            @foreach ($wineCategories as $category)
                <button class="category-chip snap-start whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold backdrop-blur-md hover:scale-105 transition category-nav-link"
                        data-category-target="category{{ $category->id }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>
@endif

<!-- LISTADO DE BEBIDAS -->
<div class="max-w-5xl mx-auto px-4 pb-32 content-layer">
    @if(!$filtersActive)
        @forelse(($wineCategories ?? collect()) as $category)
            <section id="category{{ $category->id }}" class="mb-10 category-section" data-category-id="category{{ $category->id }}">
                <h2 class="text-3xl font-bold text-center mb-6"
                    style="background-color: {{ $categoryBgColor }};
                           color: {{ $categoryTextColor }};
                           font-size: {{ $categoryFontSize }}px;
                           border-radius: 10px; padding: 10px;">
                    {{ $category->name }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach(($category->items ?? collect()) as $drink)
                        @php
                            $drinkImage = $drink->image ? asset('storage/' . $drink->image) : $logoFallback;
                            $drinkNotes = $drink->grapes?->pluck('name')->implode(', ');
                            $drinkPairs = $drink->dishes?->map(fn($dish) => $dish->id.'::'.$dish->name)->implode('|');
                            $drinkExtras = $drink->extras->where('active', true);
                            $drinkExtrasPayload = $drinkExtras->map(function ($extra) {
                                return [
                                    'name' => $extra->name,
                                    'price' => number_format($extra->price, 2, '.', ''),
                                    'description' => $extra->description,
                                ];
                            });
                        @endphp
                        <div id="wine{{ $drink->id }}" onclick="openDrinkModal(this)"
                             class="drink-card rounded-2xl p-4 shadow-lg relative flex flex-col gap-3 cursor-pointer hover:scale-105 transition border"
                             style="opacity: {{ $drinkCardOpacity }};"
                             data-name="{{ $drink->name }}"
                             data-description="{{ $drink->description }}"
                             data-price="${{ number_format($drink->price, 2) }}"
                             data-image="{{ $drinkImage }}"
                             data-region="{{ $drink->region->name ?? '' }}"
                             data-method="{{ $drink->type->name ?? '' }}"
                             data-notes="{{ $drinkNotes }}"
                             data-pairings="{{ $drinkPairs }}"
                             data-extras='@json($drinkExtrasPayload)'>

                            <span class="absolute top-2 right-2 text-xs px-2 py-1 rounded-full"
                                  style="background-color: rgba(57, 125, 181, 0.45); color: {{ $palette['cream'] }};">Ver más</span>

                            <div class="flex items-center gap-3">
                            <img src="{{ $drinkImage }}"
                                 alt="{{ $drink->name }}"
                                 class="h-20 w-20 rounded-2xl object-cover border"
                                 style="border-color: rgba(255, 242, 179, 0.25);">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold">{{ $drink->name }}</h3>
                                <p class="text-sm opacity-80">{{ $drink->type->name ?? 'Especialidad de la barra' }}</p>
                                <p class="text-sm opacity-70">{{ $drink->region->name ?? 'Origen mixto' }}</p>
                            </div>
                            <span class="text-lg font-semibold" style="color: var(--drink-accent-color);">
                                ${{ number_format($drink->price, 2) }}
                            </span>
                        </div>

                        <p class="text-sm opacity-90">{{ $drink->description }}</p>


                        @if($drink->grapes && $drink->grapes->count())
                            <div class="flex flex-wrap gap-2 text-xs">
                                @foreach($drink->grapes as $note)
                                    <span class="tag-chip px-3 py-1 rounded-full">{{ $note->name }}</span>
                                @endforeach
                            </div>
                        @endif

                        @if($drink->dishes && $drink->dishes->count())
                            <div class="pt-3 border-t" style="border-color: rgba(255, 242, 179, 0.2);">
                                <p class="text-xs uppercase tracking-[0.3em] opacity-80 mb-2">Maridajes sugeridos</p>
                                <div class="flex flex-wrap gap-2 text-xs">
                                    @foreach($drink->dishes as $dish)
                                        <a href="{{ route('menu') }}#dish{{ $dish->id }}" class="inline-flex items-center gap-2 px-3 py-1 rounded-full border transition"
                                           style="border-color: rgba(255, 242, 179, 0.25); background-color: rgba(118, 45, 121, 0.18);">
                                            {{ $dish->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @empty
        <div class="text-center py-20" style="color: rgba(255, 242, 179, 0.8);">
            No hay bebidas configuradas. Usa el panel para añadir elementos a la barra.
        </div>
    @endforelse
@else
    @php $filteredWines = $wines ?? collect(); @endphp
    @if($filteredWines->isEmpty())
        <div class="text-center py-20" style="color: rgba(255, 242, 179, 0.8);">
            No encontramos coincidencias para esos filtros. Ajusta tu búsqueda o explora las categorías.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($filteredWines as $drink)
                @php
                    $drinkImage = $drink->image ? asset('storage/' . $drink->image) : $logoFallback;
                    $drinkNotes = $drink->grapes?->pluck('name')->implode(', ');
                    $drinkPairs = $drink->dishes?->map(fn($dish) => $dish->id.'::'.$dish->name)->implode('|');
                    $drinkExtras = $drink->extras->where('active', true);
                    $drinkExtrasPayload = $drinkExtras->map(function ($extra) {
                        return [
                            'name' => $extra->name,
                            'price' => number_format($extra->price, 2, '.', ''),
                            'description' => $extra->description,
                        ];
                    });
                @endphp
                <div id="wine{{ $drink->id }}" onclick="openDrinkModal(this)"
                     class="drink-card rounded-2xl p-4 shadow-lg relative flex flex-col gap-3 cursor-pointer hover:scale-105 transition border"
                     style="opacity: {{ $drinkCardOpacity }};"
                     data-name="{{ $drink->name }}"
                     data-description="{{ $drink->description }}"
                     data-price="${{ number_format($drink->price, 2) }}"
                     data-image="{{ $drinkImage }}"
                     data-region="{{ $drink->region->name ?? '' }}"
                     data-method="{{ $drink->type->name ?? '' }}"
                     data-notes="{{ $drinkNotes }}"
                     data-pairings="{{ $drinkPairs }}"
                     data-extras='@json($drinkExtrasPayload)'>

                    <span class="absolute top-2 right-2 text-xs px-2 py-1 rounded-full"
                          style="background-color: rgba(57, 125, 181, 0.45); color: {{ $palette['cream'] }};">Ver más</span>

                    <div class="flex items-center gap-3">
                        <img src="{{ $drinkImage }}"
                             alt="{{ $drink->name }}"
                             class="h-20 w-20 rounded-2xl object-cover border"
                             style="border-color: rgba(255, 242, 179, 0.25);">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold">{{ $drink->name }}</h3>
                            <p class="text-sm opacity-80">{{ $drink->type->name ?? 'Especialidad de la barra' }}</p>
                            <p class="text-sm opacity-70">{{ $drink->region->name ?? 'Origen mixto' }}</p>
                        </div>
                        <span class="text-lg font-semibold" style="color: var(--drink-accent-color);">
                            ${{ number_format($drink->price, 2) }}
                        </span>
                    </div>

                    <p class="text-sm opacity-90">{{ $drink->description }}</p>

                    @if($drink->grapes && $drink->grapes->count())
                        <div class="flex flex-wrap gap-2 text-xs">
                            @foreach($drink->grapes as $note)
                                <span class="tag-chip px-3 py-1 rounded-full">{{ $note->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if($drink->dishes && $drink->dishes->count())
                        <div class="pt-3 border-t" style="border-color: rgba(255, 242, 179, 0.2);">
                            <p class="text-xs uppercase tracking-[0.3em] opacity-80 mb-2">Maridajes sugeridos</p>
                            <div class="flex flex-wrap gap-2 text-xs">
                                @foreach($drink->dishes as $dish)
                                    <a href="{{ route('menu') }}#dish{{ $dish->id }}" class="inline-flex items-center gap-2 px-3 py-1 rounded-full border transition"
                                       style="border-color: rgba(255, 242, 179, 0.25); background-color: rgba(118, 45, 121, 0.18);">
                                        {{ $dish->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
@endif
</div>

@include('components.floating-nav', [
    'settings' => $settings,
    'background' => $settings->floating_bar_bg_wines ?? 'rgba(57, 125, 181, 0.55)',
    'buttonColor' => $settings->button_color_wines ?? $drinkAccentColor
])

<!-- MODAL DETALLE BEBIDA -->
<div id="drinkDetailsModal" tabindex="-1" aria-hidden="true" role="dialog" aria-modal="true"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
     style="background-color: rgba(57, 125, 181, 0.55);">
    <div class="relative w-full max-w-xl max-h-[90vh]">
        <div class="rounded-lg shadow-lg p-6 relative overflow-y-auto max-h-[90vh]"
             style="background-color: {{ $palette['cream'] }}; color: {{ $palette['violet'] }};">
            <button onclick="closeDrinkModal()" class="absolute top-3 right-3 text-xl font-bold"
                    style="color: {{ $palette['violet'] }};">
                ✕
            </button>

            <img id="drinkModalImage" class="w-full h-60 object-cover rounded-lg mb-4" alt="Imagen de la bebida">

            <h3 id="drinkModalTitle" class="text-2xl font-bold mb-2"></h3>
            <p id="drinkModalSpecs" class="text-sm mb-1" style="color: {{ $palette['blue'] }};"></p>
            <p id="drinkModalDescription" class="mb-2"></p>
            <p id="drinkModalPrice" class="font-semibold text-lg mb-4"></p>

            <div id="drinkModalExtras" class="hidden mb-4">
                <h4 class="text-lg font-semibold mb-2" style="color: {{ $drinkAccentColor }};">Extras sugeridos</h4>
                <ul id="drinkModalExtrasList" class="space-y-2 text-sm" style="color: {{ $palette['violet'] }};"></ul>
            </div>

            <div id="drinkModalNotes" class="mb-4 hidden">
                <h4 class="text-lg font-semibold mb-2" style="color: {{ $drinkAccentColor }};">Notas de cata</h4>
                <p id="drinkNotesText" class="text-sm" style="color: {{ $palette['violet'] }};"></p>
            </div>

            <div id="drinkModalPairings" class="mt-4 hidden">
                <h4 class="text-lg font-semibold mb-2" style="color: {{ $drinkAccentColor }};">Acompañar con</h4>
                <ul id="pairingList" class="list-disc list-inside" style="color: {{ $palette['violet'] }};"></ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal promocional de pop-ups -->
<div id="drinkPopupModal" tabindex="-1" aria-hidden="true" role="dialog" aria-modal="true"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
     style="background-color: rgba(57, 125, 181, 0.55);">
    <div class="relative w-full max-w-3xl">
        <div class="rounded-3xl shadow-lg p-4 relative"
             style="background-color: {{ $palette['cream'] }}; color: {{ $palette['violet'] }};">
            <button onclick="closeDrinkPopup()" class="absolute top-4 right-4 text-2xl"
                    style="color: {{ $palette['violet'] }};">&times;</button>
            <div class="space-y-3">
                <h3 id="drinkPopupTitle" class="text-xl font-semibold text-center"></h3>
                <img id="drinkPopupImage" class="w-full rounded-2xl object-cover" alt="Promoción especial">
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.js"></script>
<script>
    let drinkPopupInstance;

    document.addEventListener('DOMContentLoaded', function () {
        const toggleMenuBtn = document.getElementById('toggleMenu');
        const categoryMenu = document.getElementById('categoryMenu');
        const menuOverlay = document.getElementById('menuOverlay');
        const closeMenuBtn = document.getElementById('closeMenu');
        const navLinks = document.querySelectorAll('.category-nav-link');

        const openMenu = () => {
            categoryMenu?.classList.remove('-translate-y-full');
            menuOverlay?.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };
        const closeMenu = () => {
            categoryMenu?.classList.add('-translate-y-full');
            menuOverlay?.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        toggleMenuBtn?.addEventListener('click', () => {
            if (categoryMenu?.classList.contains('-translate-y-full')) {
                openMenu();
            } else {
                closeMenu();
            }
        });
        closeMenuBtn?.addEventListener('click', closeMenu);
        menuOverlay?.addEventListener('click', closeMenu);

        navLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.dataset.categoryTarget || this.getAttribute('href');
                const target = document.querySelector(targetId.startsWith('#') ? targetId : `#${targetId}`);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                    closeMenu();
                }
            });
        });

        const sectionObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.dataset.categoryId;
                    navLinks.forEach(link => link.classList.toggle('active', link.dataset.categoryTarget === id));
                }
            });
        }, { threshold: 0.3, rootMargin: '-10% 0px -55% 0px' });

        document.querySelectorAll('.category-section').forEach(section => sectionObserver.observe(section));

        const cardObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    cardObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        document.querySelectorAll('.drink-card').forEach(card => cardObserver.observe(card));

        const popups = @json($popups);
        const cavaViews = ['coffee', 'wines', 'cava'];
        const now = new Date();
        const today = now.getDay();

        popups.forEach(popup => {
            const start = popup.start_date ? new Date(popup.start_date) : null;
            const end = popup.end_date ? new Date(popup.end_date) : null;
            const repeatDays = popup.repeat_days ? popup.repeat_days.split(',').map(day => parseInt(day, 10)) : [];
            const withinDates = (!start || now >= start) && (!end || now <= end);
            const matchesDay = repeatDays.length === 0 || repeatDays.includes(today);

            if (popup.active && cavaViews.includes(popup.view) && withinDates && matchesDay) {
                showDrinkPopup(popup);
            }
        });
    });

    function openDrinkModal(el) {
        const fallbackImage = "{{ $logoFallback }}";
        const name = el.dataset.name;
        const description = el.dataset.description;
        const price = el.dataset.price;
        const image = el.dataset.image && !el.dataset.image.endsWith('/storage/') ? el.dataset.image : fallbackImage;
        const region = el.dataset.region;
        const method = el.dataset.method;
        const notes = el.dataset.notes;
        const pairings = el.dataset.pairings;
        const extras = el.dataset.extras ? JSON.parse(el.dataset.extras) : [];

        document.getElementById('drinkModalTitle').textContent = name;
        document.getElementById('drinkModalDescription').textContent = description;
        document.getElementById('drinkModalPrice').textContent = price;
        document.getElementById('drinkModalImage').src = image;
        document.getElementById('drinkModalSpecs').textContent = [method, region].filter(Boolean).join(' · ');

        const notesSection = document.getElementById('drinkModalNotes');
        if (notes) {
            document.getElementById('drinkNotesText').textContent = notes;
            notesSection.classList.remove('hidden');
        } else {
            notesSection.classList.add('hidden');
        }

        const pairingSection = document.getElementById('drinkModalPairings');
        const pairingList = document.getElementById('pairingList');
        pairingList.innerHTML = '';
        if (pairings) {
            pairings.split('|').forEach(pair => {
                const [dishId, dishName] = pair.split('::');
                if (dishName) {
                    const li = document.createElement('li');
                    const link = document.createElement('a');
                    link.textContent = dishName.trim();
                    link.href = '{{ route('menu') }}#dish' + (dishId || '').trim();
                    link.className = 'hover:underline';
                    link.style.color = '{{ $drinkAccentColor }}';
                    li.appendChild(link);
                    pairingList.appendChild(li);
                }
            });
            if (pairingList.childElementCount > 0) {
                pairingSection.classList.remove('hidden');
            } else {
                pairingSection.classList.add('hidden');
            }
        } else {
            pairingSection.classList.add('hidden');
        }

        const extrasSection = document.getElementById('drinkModalExtras');
        const extrasList = document.getElementById('drinkModalExtrasList');
        extrasList.innerHTML = '';
        if (extras.length) {
            extras.forEach(extra => {
                const wrapper = document.createElement('li');
                wrapper.className = 'flex flex-col gap-1 rounded-xl px-3 py-2';
                wrapper.style.border = '1px solid rgba(57, 125, 181, 0.25)';
                wrapper.style.backgroundColor = 'rgba(255, 242, 179, 0.45)';
                const row = document.createElement('div');
                row.className = 'flex items-center justify-between text-sm font-semibold';
                row.style.color = '{{ $palette['violet'] }}';
                const nameSpan = document.createElement('span');
                nameSpan.textContent = extra.name || 'Extra';
                const priceSpan = document.createElement('span');
                const priceValue = parseFloat(extra.price ?? 0);
                priceSpan.textContent = priceValue ? `$${priceValue.toFixed(2)}` : '';
                row.appendChild(nameSpan);
                row.appendChild(priceSpan);
                wrapper.appendChild(row);
                if (extra.description) {
                    const desc = document.createElement('p');
                    desc.className = 'text-xs';
                    desc.style.color = '{{ $palette['blue'] }}';
                    desc.textContent = extra.description;
                    wrapper.appendChild(desc);
                }
                extrasList.appendChild(wrapper);
            });
            extrasSection.classList.remove('hidden');
        } else {
            extrasSection.classList.add('hidden');
        }

        const modalEl = document.getElementById('drinkDetailsModal');
        if (window.drinkModalInstance) {
            window.drinkModalInstance.show();
        } else {
            window.drinkModalInstance = new Modal(modalEl);
            window.drinkModalInstance.show();
        }
    }

    function closeDrinkModal() {
        if (window.drinkModalInstance) {
            window.drinkModalInstance.hide();
        }
    }

    function showDrinkPopup(popup) {
        const modalEl = document.getElementById('drinkPopupModal');
        if (!drinkPopupInstance) {
            drinkPopupInstance = new Modal(modalEl, { closable: true });
        }
        const imageBase = '{{ asset('storage') }}/';
        document.getElementById('drinkPopupImage').src = popup.image ? imageBase + popup.image : '';
        document.getElementById('drinkPopupTitle').textContent = popup.title || 'Especial del día';
        drinkPopupInstance.show();
    }

    function closeDrinkPopup() {
        if (drinkPopupInstance) {
            drinkPopupInstance.hide();
        }
    }
</script>

</body>
</html>
