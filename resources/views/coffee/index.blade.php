@php
    $categoryBgColor = $settings->category_name_bg_color_wines ?? 'rgba(15, 23, 42, 0.85)';
    $categoryTextColor = $settings->category_name_text_color_wines ?? '#ffffff';
    $categoryFontSize = $settings->category_name_font_size_wines ?? 28;
    $cardBgColor = $settings->card_bg_color_wines ?? '#101828';
    $cardOpacity = $settings->card_opacity_wines ?? 0.9;
    $textColor = $settings->text_color_wines ?? '#ffffff';
    $buttonColor = $settings->button_color_wines ?? '#f97316';
    $filteredWines = $wines ?? collect();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café &amp; Brunch</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        :root {
            --coffee-text-color: {{ $textColor }};
            --coffee-accent-color: {{ $buttonColor }};
            --coffee-card-bg: {{ $cardBgColor }};
        }

        html, body {
            min-height: 100vh;
        }

        body {
            font-family: {{ $settings->font_family_wines ?? 'ui-sans-serif' }};
            color: var(--coffee-text-color);
            @if($settings && $settings->background_image_wines)
                background: none;
            @else
                background: radial-gradient(circle at top, #f0e7d9, #c7b299);
            @endif
            background-size: cover;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            @if($settings && $settings->background_image_wines)
                background: url('{{ asset('storage/' . $settings->background_image_wines) }}') no-repeat center center;
                background-size: cover;
            @else
                background: rgba(4, 4, 4, 0.65);
            @endif
        }

        .category-nav-link {
            transition: color 0.3s ease, transform 0.3s ease;
            transform-origin: left center;
        }

        .category-nav-link.active {
            color: var(--coffee-accent-color);
            transform: scale(1.05);
            font-weight: 600;
        }

        .drink-card {
            background-color: var(--coffee-card-bg);
            color: var(--coffee-text-color);
            opacity: {{ $cardOpacity }};
            border: 1px solid rgba(255,255,255,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .drink-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.25);
        }

        html {
            scroll-behavior: smooth;
        }

        .hero-media {
            width: 100%;
            max-height: 420px;
            aspect-ratio: 16 / 9;
            object-fit: cover;
            border-radius: 1.5rem;
        }
    </style>
</head>
<body class="bg-black/60">

    <div class="text-center py-6 relative">
        <img src="{{ asset('storage/' . ($settings->logo ?? 'default-logo.png')) }}" class="mx-auto h-24" alt="Logo Café Negro">

        <button id="toggleMenu"
            class="fixed left-4 top-4 z-50 w-12 h-12 rounded-full flex items-center justify-center text-xl shadow-lg text-white lg:hidden"
            style="background-color: var(--coffee-accent-color);">
            ☕
        </button>

        <div class="hidden lg:block">
            <div class="fixed top-0 left-0 h-full w-64 bg-white text-slate-900 p-6 space-y-2 shadow-lg overflow-y-auto">
                @foreach (($wineCategories ?? collect()) as $category)
                    <a href="#category{{ $category->id }}" class="block text-lg font-semibold hover:text-amber-500 category-nav-link" data-category-target="category{{ $category->id }}">{{ $category->name }}</a>
                @endforeach
            </div>
        </div>
    </div>

    @if($popups->count())
        <div class="max-w-5xl mx-auto px-4 pb-6">
            <div class="grid gap-4 md:grid-cols-{{ min(3, $popups->count()) }}">
                @foreach($popups as $popup)
                    <article class="drink-card rounded-3xl p-4 flex items-center gap-3 backdrop-blur">
                        <img src="{{ asset('storage/' . $popup->image) }}" alt="{{ $popup->title }}" class="w-16 h-16 rounded-2xl object-cover">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-white/60 mb-1">{{ strtoupper($popup->view) }}</p>
                            <h3 class="text-lg font-semibold">{{ $popup->title }}</h3>
                            <p class="text-sm text-white/70">{{ \Illuminate\Support\Str::limit(strip_tags($popup->description ?? ''), 80) }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    @endif

    @if($settings->coffee_hero_image)
        <div class="max-w-5xl mx-auto px-4 pb-8">
            <img src="{{ asset('storage/' . $settings->coffee_hero_image) }}" alt="Destacado café" class="hero-media shadow-2xl border border-white/10">
        </div>
    @endif

    <div id="categoryMenu"
        class="lg:hidden fixed inset-0 bg-white text-slate-900 px-6 py-8 space-y-6 overflow-y-auto transform -translate-y-full transition-transform duration-300 z-[60]">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold tracking-[0.25em] uppercase text-slate-500">Categorías</h2>
            <button id="closeMenu" class="text-2xl text-slate-500 hover:text-slate-900">&times;</button>
        </div>
        <div class="grid grid-cols-2 gap-4">
            @foreach (($wineCategories ?? collect()) as $category)
                <button class="rounded-2xl border border-slate-200 py-4 px-3 text-sm font-semibold text-left shadow bg-white hover:bg-slate-50 category-nav-link"
                        data-category-target="category{{ $category->id }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>
    <div id="menuOverlay" class="fixed inset-0 bg-black/60 z-50 hidden lg:hidden"></div>

    @if(!$filters && isset($wineCategories) && $wineCategories->count())
        <div class="lg:hidden sticky top-20 z-30 px-4">
            <div class="flex gap-3 overflow-x-auto py-3 snap-x snap-mandatory">
                @foreach ($wineCategories as $category)
                    <button class="category-chip snap-start whitespace-nowrap px-4 py-2 rounded-full border border-white/20 bg-black/40 text-sm font-semibold backdrop-blur-md hover:scale-105 transition category-nav-link"
                            data-category-target="category{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    <main class="max-w-5xl mx-auto px-4 pb-28 space-y-12">
        @if($filters)
            <section class="space-y-4">
                <div class="text-center space-y-2">
                    <p class="text-xs uppercase tracking-[0.35em] text-white/60">Selección personalizada</p>
                    <h2 class="text-3xl font-semibold">Resultados para tu mood cafetero</h2>
                    <p class="text-white/70">{{ $filteredWines->count() }} bebidas encontradas.</p>
                </div>

                @if($filteredWines->isEmpty())
                    <div class="text-center py-20 text-white/70">
                        No encontramos bebidas con esos filtros. Ajusta región, método o precio.
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($filteredWines as $coffee)
                            <article id="coffee{{ $coffee->id }}" class="drink-card rounded-3xl p-5 backdrop-blur flex flex-col gap-4">
                                <div class="flex items-start gap-4">
                                    <img src="{{ $coffee->image ? asset('storage/' . $coffee->image) : asset('storage/' . ($settings->logo ?? 'default-logo.png')) }}" class="h-20 w-20 rounded-2xl object-cover border border-white/10" alt="{{ $coffee->name }}">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.3em] text-white/60">{{ $coffee->type->name ?? 'Método signature' }}</p>
                                        <h3 class="text-xl font-semibold">{{ $coffee->name }}</h3>
                                        <p class="text-sm text-white/70">{{ $coffee->region->name ?? 'Origen mixto' }}</p>
                                    </div>
                                    <span class="ml-auto text-lg font-semibold" style="color: var(--coffee-accent-color);">${{ number_format($coffee->price, 2) }}</span>
                                </div>
                                <p class="text-sm text-white/80">{{ $coffee->description }}</p>
                                @if($coffee->dishes->count())
                                    <div class="pt-3 border-t border-white/10">
                                        <p class="text-xs uppercase tracking-[0.3em] text-white/50 mb-2">Acompañantes sugeridos</p>
                                        <div class="flex flex-wrap gap-2 text-xs">
                                            @foreach($coffee->dishes as $dish)
                                                <span class="px-3 py-1 rounded-full border border-white/15 bg-white/5">{{ $dish->name }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        @else
            @foreach(($wineCategories ?? collect()) as $category)
                <section id="category{{ $category->id }}" class="space-y-6 category-section" data-category-id="category{{ $category->id }}">
                    <h2 class="text-3xl font-bold text-center mb-6"
                        style="background-color: {{ $categoryBgColor }};
                               color: {{ $categoryTextColor }};
                               font-size: {{ $categoryFontSize }}px;
                               border-radius: 10px; padding: 12px;">
                        {{ $category->name }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($category->items as $coffee)
                            <article id="coffee{{ $coffee->id }}" class="drink-card rounded-2xl p-5 flex flex-col gap-3">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $coffee->image ? asset('storage/' . $coffee->image) : asset('storage/' . ($settings->logo ?? 'default-logo.png')) }}" class="h-20 w-20 rounded-2xl object-cover border border-white/10" alt="{{ $coffee->name }}">
                                    <div class="flex-1">
                                        <p class="text-xs uppercase tracking-[0.3em] text-white/60">{{ $coffee->type->name ?? 'Método signature' }}</p>
                                        <h3 class="text-xl font-semibold">{{ $coffee->name }}</h3>
                                        <p class="text-sm text-white/70">{{ $coffee->region->name ?? 'Origen mixto' }}</p>
                                    </div>
                                    <span class="text-lg font-semibold" style="color: var(--coffee-accent-color);">${{ number_format($coffee->price, 2) }}</span>
                                </div>

                                <p class="text-white/80 text-sm">{{ $coffee->description }}</p>

                                @if($coffee->grapes->count())
                                    <div class="flex flex-wrap gap-2 text-xs text-white/70">
                                        @foreach($coffee->grapes as $note)
                                            <span class="px-3 py-1 rounded-full border border-white/15 bg-white/5">{{ $note->name }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                @if($coffee->dishes->count())
                                    <div class="pt-3 border-t border-white/10">
                                        <p class="text-xs uppercase tracking-[0.3em] text-white/50 mb-2">Acompañantes sugeridos</p>
                                        <div class="flex flex-wrap gap-2 text-xs">
                                            @foreach($coffee->dishes as $dish)
                                                <a href="{{ route('menu') }}#dish{{ $dish->id }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-full border border-white/15 bg-white/5 hover:border-white/40 transition">
                                                    <i class="fa-solid fa-utensils text-xs" style="color: var(--coffee-accent-color);"></i>
                                                    {{ $dish->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </section>
            @endforeach
        @endif
    </main>

    <!-- BOTONES FLOTANTES -->
    <div class="fixed bottom-5 left-0 right-0 z-50 flex justify-center">
        <div class="flex items-center gap-4 px-4 py-2 rounded-3xl backdrop-blur-lg border border-white/15 shadow-2xl"
             style="background-color: {{ $settings->floating_bar_bg_wines ?? 'rgba(0,0,0,0.55)' }};">
            @php $coffeeActionColor = $settings->button_color_wines ?? '#000'; @endphp
            <a href="/" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
               style="background-color: {{ $coffeeActionColor }};">
                <i class="fas fa-home text-lg"></i><span>Inicio</span>
            </a>
            <a href="{{ url('/menu') }}" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
               style="background-color: {{ $coffeeActionColor }};">
                <i class="fas fa-utensils text-lg"></i><span>{{ $settings->button_label_menu ?? 'Menú' }}</span>
            </a>
            <a href="{{ url('/cocktails') }}" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
               style="background-color: {{ $coffeeActionColor }};">
                <i class="fas fa-cocktail text-lg"></i><span>{{ $settings->button_label_cocktails ?? 'Cócteles' }}</span>
            </a>
            <a href="{{ url('/coffee') }}" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
               style="background-color: {{ $coffeeActionColor }};">
                <i class="fas fa-mug-saucer text-lg"></i><span>{{ $settings->button_label_wines ?? 'Cafe' }}</span>
            </a>
        </div>
    </div>

    <script>
        const toggleButton = document.getElementById('toggleMenu');
        const closeButton = document.getElementById('closeMenu');
        const categoryMenu = document.getElementById('categoryMenu');
        const menuOverlay = document.getElementById('menuOverlay');
        const categoryLinks = document.querySelectorAll('.category-nav-link');

        if (toggleButton) {
            toggleButton.addEventListener('click', () => {
                categoryMenu.classList.remove('-translate-y-full');
                menuOverlay.classList.remove('hidden');
            });
        }
        if (closeButton) {
            closeButton.addEventListener('click', closeMenu);
        }
        if (menuOverlay) {
            menuOverlay.addEventListener('click', closeMenu);
        }

        function closeMenu() {
            categoryMenu.classList.add('-translate-y-full');
            menuOverlay.classList.add('hidden');
        }

        categoryLinks.forEach(link => {
            link.addEventListener('click', () => {
                const targetId = link.getAttribute('data-category-target');
                document.getElementById(targetId)?.scrollIntoView({ behavior: 'smooth' });
                closeMenu();
            });
        });

        const categorySections = document.querySelectorAll('.category-section');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    categoryLinks.forEach(link => link.classList.remove('active'));
                    const target = entry.target.getAttribute('data-category-id');
                    document.querySelectorAll(`[data-category-target="${target}"]`).forEach(link => link.classList.add('active'));
                }
            });
        }, { threshold: 0.35 });

        categorySections.forEach(section => observer.observe(section));
    </script>
</body>
</html>
