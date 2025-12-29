@php
    $palette = [
        'blue' => '#397db5',
        'cream' => '#fff2b3',
        'violet' => '#762d79',
        'amber' => '#ffb723',
    ];
    $menuTextColor = $settings->text_color_menu ?? $palette['violet'];
    $menuAccentColor = $settings->button_color_menu ?? $palette['blue'];
    $menuCardBg = $settings->card_bg_color_menu ?? 'rgba(255, 183, 35, 0.20)';
    $menuChipBg = $settings->category_name_bg_color_menu ?? 'rgba(57, 125, 181, 0.12)';
    $menuChipText = $settings->category_name_text_color_menu ?? $palette['blue'];
    $menuCategoryBg = $settings->category_name_bg_color_menu ?? 'rgba(255, 242, 179, 0.9)';
    $menuCategoryText = $settings->category_name_text_color_menu ?? $palette['violet'];
    $menuCategoryFontSize = $settings->category_name_font_size_menu ?? 30;
    $menuSubcategoryBg = $settings->subcategory_bg_color_menu ?? 'rgba(57, 125, 181, 0.12)';
    $menuSubcategoryText = $settings->subcategory_text_color_menu ?? ($settings->text_color_menu ?? $palette['violet']);
    $menuBackgroundDisabled = (bool) ($settings->disable_background_menu ?? false);
    $logoPlaceholderSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><rect width="200" height="200" fill="#762d79"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#fff2b3" font-family="Arial, sans-serif" font-size="36">LOGO</text></svg>';
    $logoFallback = $settings && $settings->logo
        ? asset('storage/' . $settings->logo)
        : 'data:image/svg+xml,' . rawurlencode($logoPlaceholderSvg);
    $resolveMedia = function (?string $path) use ($logoFallback) {
        if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }
        return $logoFallback;
    };
    if (\Illuminate\Support\Facades\Route::has('cava.index')) {
        $cavaRouteUrl = route('cava.index');
    } elseif (\Illuminate\Support\Facades\Route::has('coffee.index')) {
        $cavaRouteUrl = route('coffee.index');
    } else {
        $cavaRouteUrl = url('/cava');
    }
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menú</title>

    <!-- Tailwind + Flowbite -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        :root {
            --menu-text-color: {{ $menuTextColor }};
            --menu-accent-color: {{ $menuAccentColor }};
            --menu-cream: {{ $palette['cream'] }};
            --menu-amber: {{ $palette['amber'] }};
            --menu-blue: {{ $palette['blue'] }};
            --menu-violet: {{ $palette['violet'] }};
        }
        html, body {
            min-height: 100vh;
        }

        body {
            font-family: {{ $settings->font_family_menu ?? 'ui-sans-serif' }};
            color: var(--menu-text-color);
            @if($menuBackgroundDisabled)
                background: transparent;
            @elseif($settings && $settings->background_image_menu)
                background: none;
            @else
                background: linear-gradient(140deg, {{ $palette['cream'] }} 0%, {{ $palette['amber'] }} 55%, {{ $palette['amber'] }} 100%);
            @endif
            background-size: cover;
            background-attachment: fixed;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            @if($menuBackgroundDisabled)
                display: none;
            @elseif($settings && $settings->background_image_menu)
                background: url('{{ asset('storage/' . $settings->background_image_menu) }}') no-repeat center center;
                background-size: cover;
            @else
                background: radial-gradient(circle at 20% 20%, rgba(57, 125, 181, 0.28), rgba(118, 45, 121, 0.18) 55%, rgba(57, 125, 181, 0.12));
            @endif
            z-index: -1;
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
            color: var(--menu-accent-color);
            transform: scale(1.05);
            font-weight: 600;
        }

        .sidebar-panel {
            background: rgba(255, 242, 179, 0.95);
            color: {{ $settings->sidebar_text_color_menu ?? $palette['violet'] }};
            border-right: 1px solid rgba(118, 45, 121, 0.18);
        }

        .mobile-menu-panel {
            background: rgba(255, 242, 179, 0.95);
            color: {{ $settings->sidebar_text_color_menu ?? $palette['violet'] }};
        }

        .category-chip {
            background-color: {{ $menuChipBg }};
            border: 1px solid rgba(118, 45, 121, 0.25);
            color: {{ $menuChipText }};
        }

        .category-chip:hover,
        .category-chip.active {
            background-color: {{ $settings->button_color_menu ?? 'rgba(57, 125, 181, 0.25)' }};
            color: var(--menu-accent-color);
        }

        .dish-card {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
            color: var(--menu-text-color);
            border: 1px solid rgba(118, 45, 121, 0.2);
        }

        .dish-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .subcategory-banner {
            background: {{ $menuSubcategoryBg }};
            color: {{ $menuSubcategoryText }};
            border-radius: 1.5rem;
            padding: 1.25rem 1.75rem;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(6px);
            position: relative;
            overflow: hidden;
        }

        .subcategory-banner::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 80% 15%, rgba(255, 255, 255, 0.35), transparent 60%);
            opacity: .9;
            pointer-events: none;
        }

        .subcategory-banner h3 {
            font-size: 1.65rem;
            letter-spacing: 0.05em;
            color: inherit;
        }

        .subcategory-label {
            text-transform: uppercase;
            font-size: 0.65rem;
            letter-spacing: 0.45em;
            color: rgba(255, 255, 255, 0.8);
        }

        .hero-media {
            width: 100%;
            max-height: 420px;
            aspect-ratio: 16 / 9;
            object-fit: cover;
        }

        @media (max-width: 768px) {
            body {
                background-position: center top;
                background-attachment: fixed;
            }
        }
    </style>
</head>
<body class="min-h-screen">

<!-- LOGO + BOTÓN MENU -->
<div class="text-center py-6 relative content-layer">
    <img src="{{ $logoFallback }}" class="mx-auto h-28" alt="Logo del Restaurante">

    <!-- Toggle menú -->
    <button id="toggleMenu"
        class="fixed left-4 top-4 z-50 w-12 h-12 rounded-full flex items-center justify-center text-xl shadow-lg lg:hidden"
        style="background-color: {{ $settings->button_color_menu ?? $menuAccentColor }}; color: {{ $palette['cream'] }};">
        🍽️
    </button>

    <!-- Menú lateral desktop -->
    <div class="hidden lg:block">
        <div class="fixed top-0 left-0 h-full w-64 sidebar-panel p-6 space-y-2 shadow-lg overflow-y-auto backdrop-blur-sm">
            @foreach ($categories as $category)
                <a href="#category{{ $category->id }}" class="block text-lg font-semibold hover:text-blue-500 category-nav-link" data-category-target="category{{ $category->id }}">{{ $category->name }}</a>
            @endforeach
        </div>
    </div>
</div>

@if($settings->menu_hero_image)
    <div class="max-w-4xl mx-auto px-4 pb-8 content-layer">
        <img src="{{ asset('storage/' . $settings->menu_hero_image) }}" alt="Destacado del menú" class="hero-media rounded-3xl shadow-2xl border" style="border-color: rgba(57, 125, 181, 0.25);">
    </div>
@endif

<!-- Menú flotante móvil -->
<div id="categoryMenu"
    class="lg:hidden fixed inset-0 mobile-menu-panel px-6 py-8 space-y-6 overflow-y-auto transform -translate-y-full transition-transform duration-300 z-[60]">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold tracking-[0.25em] uppercase" style="color: {{ $palette['blue'] }};">Categorías</h2>
        <button id="closeMenu" class="text-2xl" style="color: {{ $palette['violet'] }};">&times;</button>
    </div>
    <div class="grid grid-cols-2 gap-4">
        @foreach ($categories as $category)
            <button class="rounded-2xl border py-4 px-3 text-sm font-semibold text-left shadow category-nav-link"
                    style="border-color: rgba(118, 45, 121, 0.18); background-color: rgba(255, 242, 179, 0.85); color: {{ $palette['violet'] }};"
                    data-category-target="category{{ $category->id }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>
</div>
<div id="menuOverlay" class="fixed inset-0 z-50 hidden lg:hidden" style="background-color: rgba(118, 45, 121, 0.55);"></div>

<!-- Carrusel de chips -->
<div class="lg:hidden content-layer sticky top-20 z-30 px-4">
    <div class="flex gap-3 overflow-x-auto py-3 snap-x snap-mandatory">
        @foreach ($categories as $category)
            <button class="category-chip snap-start whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold backdrop-blur-md hover:scale-105 transition category-nav-link"
                    data-category-target="category{{ $category->id }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>
</div>

<!-- CONTENIDO DE CATEGORÍAS Y PLATOS -->
<div class="max-w-5xl mx-auto px-4 pb-32 content-layer">
    @foreach ($categories as $category)
        @php
            $categoryUngrouped = $category->dishes->where('visible', true);
        @endphp
        <section id="category{{ $category->id }}" class="mb-10 category-section" data-category-id="category{{ $category->id }}">
            <h2 class="text-3xl font-bold text-center mb-6"
                style="background-color: {{ $menuCategoryBg }};
                       color: {{ $menuCategoryText }};
                       font-size: {{ $menuCategoryFontSize }}px;
                       border-radius: 10px; padding: 10px;">
                {{ $category->name }}
            </h2>

            @foreach ($category->subcategories as $subcategory)
                @php
                    $subcategoryDishes = $subcategory->dishes->where('visible', true);
                @endphp
                @if ($subcategoryDishes->count())
                    <div class="mb-10">
                        <div class="subcategory-banner mb-6 flex items-center justify-between">
                            <div>
                                <p class="subcategory-label mb-1">Selección especial</p>
                                <h3 class="font-semibold">{{ $subcategory->name }}</h3>
                            </div>
                            <span class="text-sm font-semibold opacity-85">{{ $subcategoryDishes->count() }} platos</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($subcategoryDishes as $dish)
                                @include('menu.partials.dish-card', ['dish' => $dish])
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            @if ($categoryUngrouped->count())
                @if ($category->subcategories->count())
                    <p class="text-sm uppercase tracking-[0.3em] mb-3" style="color: {{ $menuSubcategoryText }};">
                        Más dentro de {{ $category->name }}
                    </p>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($categoryUngrouped as $dish)
                        @include('menu.partials.dish-card', ['dish' => $dish])
                    @endforeach
                </div>
            @elseif($category->subcategories->every(fn($sub) => $sub->dishes->where('visible', true)->isEmpty()))
                <p class="text-center text-sm text-white/70">No hay platos publicados para esta sección.</p>
            @endif
        </section>
    @endforeach
</div>

<!-- BOTONES FLOTANTES -->
@include('components.floating-nav', [
    'settings' => $settings,
    'background' => $settings->floating_bar_bg_menu ?? 'rgba(118, 45, 121, 0.65)',
    'buttonColor' => $settings->button_color_menu ?? $menuAccentColor
])

<!-- MODAL DE DETALLE DEL PLATO -->
<div id="dishDetailsModal" tabindex="-1" aria-hidden="true" role="dialog" aria-modal="true"
    class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
    style="background-color: rgba(57, 125, 181, 0.55);">
    <div class="relative w-full max-w-xl max-h-[90vh]">
        <div class="rounded-lg shadow-lg p-6 relative overflow-y-auto max-h-[90vh]"
             style="background-color: {{ $palette['cream'] }}; color: {{ $palette['violet'] }};">

            <button onclick="closeDishModal()" class="absolute top-3 right-3 text-xl font-bold"
                    style="color: {{ $palette['violet'] }};">
                ✕
            </button>

            <img id="modalImage" class="w-full h-60 object-cover rounded-lg mb-4" alt="Imagen del plato">

            <h3 id="modalTitle" class="text-2xl font-bold mb-2"></h3>
            <p id="modalDescription" class="mb-2"></p>
            <p id="modalPrice" class="font-semibold text-lg mb-4"></p>

            <div id="modalWines" class="mt-4 hidden">
                <h4 class="text-lg font-semibold mb-2" style="color: {{ $settings->button_color_menu ?? $palette['amber'] }};">Bebidas sugeridas ☕</h4>
                <ul id="wineList" class="list-disc list-inside" style="color: {{ $settings->text_color_menu ?? $palette['violet'] }};"></ul>
            </div>

            <div id="modalPairings" class="mt-4 hidden">
                <h4 class="text-lg font-semibold mb-2" style="color: {{ $settings->button_color_menu ?? $palette['amber'] }};">Combínalo con</h4>
                <ul id="pairingList" class="list-disc list-inside" style="color: {{ $settings->text_color_menu ?? $palette['violet'] }};"></ul>
            </div>

            <div id="modalExtras" class="mt-4 hidden">
                <h4 class="text-lg font-semibold mb-2" style="color: {{ $settings->button_color_menu ?? $palette['amber'] }};">Extras sugeridos</h4>
                <ul id="extrasList" class="space-y-2 text-sm" style="color: {{ $settings->text_color_menu ?? $palette['violet'] }};"></ul>
            </div>
        </div>
    </div>
</div>

<div id="menuPopupModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4"
     style="background-color: rgba(57, 125, 181, 0.55);">
    <div class="rounded-3xl w-full max-w-2xl p-4 relative"
         style="background-color: {{ $palette['cream'] }}; color: {{ $palette['violet'] }};">
        <button type="button" class="absolute top-3 right-3 text-2xl"
                style="color: {{ $palette['violet'] }};" onclick="closeMenuPopup()">&times;</button>
        <div class="space-y-3">
            <h3 id="menuPopupTitle" class="text-xl font-semibold text-center"></h3>
            <img id="menuPopupImage" src="" alt="Anuncio" class="w-full rounded-2xl object-cover">
        </div>
    </div>
</div>

<!-- Flowbite JS -->
<script src="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.js"></script>

<script>
    let menuPopupInstance;
    document.addEventListener('DOMContentLoaded', function () {
        console.log('✅ Menú cargado con Tailwind y Flowbite');
        window.cavaBaseRoute = @json($cavaRouteUrl);

        // Botón toggle menú lateral
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

        // Navegación con scroll suave
        navLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const targetAttr = this.dataset.categoryTarget || this.getAttribute('href');
                const sectionId = targetAttr?.startsWith('#') ? targetAttr : `#${targetAttr}`;
                const target = document.querySelector(sectionId);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                    closeMenu();
                }
            });
        });

        // Resaltar link activo según la categoría visible
        const sectionObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.dataset.categoryId;
                    navLinks.forEach(link => {
                        link.classList.toggle('active', link.dataset.categoryTarget === id);
                    });
                }
            });
        }, { threshold: 0.3, rootMargin: '-10% 0px -55% 0px' });

        document.querySelectorAll('.category-section').forEach(section => {
            sectionObserver.observe(section);
        });

        // Animar tarjetas al aparecer
        const cardObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    cardObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        document.querySelectorAll('.dish-card').forEach(card => cardObserver.observe(card));

        const menuPopups = @json($popups ?? []);
        const now = new Date();
        const today = now.getDay();

        menuPopups.forEach(popup => {
            const start = popup.start_date ? new Date(popup.start_date) : null;
            const end = popup.end_date ? new Date(popup.end_date) : null;
            const repeatDays = popup.repeat_days ? popup.repeat_days.split(',').map(day => parseInt(day, 10)) : [];
            const withinDates = (!start || now >= start) && (!end || now <= end);
            const matchesDay = repeatDays.length === 0 || repeatDays.includes(today);

            if (popup.active && popup.view === 'menu' && withinDates && matchesDay) {
                showMenuPopup(popup);
            }
        });
    });

    // Función para abrir modal con datos del plato
    function openDishModal(el) {
        const name = el.dataset.name;
        const description = el.dataset.description;
        const price = el.dataset.price;
        const fallbackImage = "{{ $logoFallback }}";
        const image = el.dataset.image && !el.dataset.image.endsWith('/storage/') ? el.dataset.image : fallbackImage;
        const wines = el.dataset.wines;
        const pairings = el.dataset.recommended;
        const extras = el.dataset.extras ? JSON.parse(el.dataset.extras) : [];

        document.getElementById('modalTitle').textContent = name;
        document.getElementById('modalDescription').textContent = description;
        document.getElementById('modalPrice').textContent = price;
        document.getElementById('modalImage').src = image;

        const wineList = document.getElementById('wineList');
        wineList.innerHTML = '';

        if (wines && wines.trim() !== '') {
            wines.split('|').forEach(token => {
                const [wineId, wineName] = token.split('::');
                const li = document.createElement('li');
                const link = document.createElement('a');
                link.textContent = (wineName || token).trim();
                const cavaBaseRoute = window.cavaBaseRoute || '';
                link.href = cavaBaseRoute + '#wine' + (wineId || '').trim();
                link.className = 'hover:underline';
                link.style.color = '{{ $settings->button_color_menu ?? $palette['amber'] }}';
                li.appendChild(link);
                wineList.appendChild(li);
            });
            document.getElementById('modalWines').classList.remove('hidden');
        } else {
            document.getElementById('modalWines').classList.add('hidden');
        }

        const pairingList = document.getElementById('pairingList');
        pairingList.innerHTML = '';
        if (pairings && pairings.trim() !== '') {
            pairings.split('|').forEach(token => {
                const [dishId, dishName] = token.split('::');
                const li = document.createElement('li');
                const link = document.createElement('a');
                link.textContent = (dishName || token).trim();
                link.href = '#dish' + (dishId || '').trim();
                link.className = 'hover:underline';
                link.style.color = '{{ $settings->button_color_menu ?? $palette['amber'] }}';
                li.appendChild(link);
                pairingList.appendChild(li);
            });
            document.getElementById('modalPairings').classList.remove('hidden');
        } else {
            document.getElementById('modalPairings').classList.add('hidden');
        }

        const extrasSection = document.getElementById('modalExtras');
        const extrasList = document.getElementById('extrasList');
        extrasList.innerHTML = '';
        if (extras && extras.length) {
            extras.forEach(extra => {
                const li = document.createElement('li');
                li.className = 'flex flex-col gap-1 rounded-xl px-3 py-2';
                li.style.border = '1px solid rgba(118, 45, 121, 0.25)';
                li.style.backgroundColor = 'rgba(255, 242, 179, 0.4)';
                const row = document.createElement('div');
                row.className = 'flex items-center justify-between text-sm font-semibold';
                const nameSpan = document.createElement('span');
                nameSpan.textContent = extra.name || 'Extra';
                const priceSpan = document.createElement('span');
                const priceValue = parseFloat(extra.price ?? 0);
                priceSpan.textContent = priceValue ? `$${priceValue.toFixed(2)}` : '';
                row.appendChild(nameSpan);
                row.appendChild(priceSpan);
                li.appendChild(row);
                if (extra.description) {
                    const desc = document.createElement('p');
                    desc.className = 'text-xs';
                    desc.style.color = '{{ $settings->text_color_menu ?? $palette['violet'] }}';
                    desc.textContent = extra.description;
                    li.appendChild(desc);
                }
                extrasList.appendChild(li);
            });
            extrasSection.classList.remove('hidden');
        } else {
            extrasSection.classList.add('hidden');
        }

        // Mostrar el modal con Flowbite
        const modalEl = document.getElementById('dishDetailsModal');
        if (window.dishModalInstance) {
            window.dishModalInstance.show();
        } else {
            window.dishModalInstance = new Modal(modalEl);
            window.dishModalInstance.show();
        }
    }

    function closeDishModal() {
        if (window.dishModalInstance) {
            window.dishModalInstance.hide();
        }
    }

    function showMenuPopup(popup) {
        const modalEl = document.getElementById('menuPopupModal');
        if (!modalEl) return;
        if (!menuPopupInstance) {
            menuPopupInstance = new Modal(modalEl, { closable: true });
        }
        const basePath = '{{ asset('storage') }}/';
        document.getElementById('menuPopupTitle').textContent = popup.title || '';
        document.getElementById('menuPopupImage').src = popup.image ? basePath + popup.image : '';
        menuPopupInstance.show();
    }

    function closeMenuPopup() {
        if (menuPopupInstance) {
            menuPopupInstance.hide();
        }
    }
</script>

</body>
</html>
