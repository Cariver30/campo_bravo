@php
    $palette = [
        'blue' => '#397db5',
        'cream' => '#fff2b3',
        'violet' => '#762d79',
        'amber' => '#ffb723',
    ];
    $categories = $cocktailCategories ?? collect();
    $textColor = $settings->text_color_cocktails ?? $palette['cream'];
    $buttonColor = $settings->button_color_cocktails ?? $palette['violet'];
    $cardBg = $settings->card_bg_color_cocktails ?? 'rgba(255, 183, 35, 0.18)';
    $cardOpacity = $settings->card_opacity_cocktails ?? 0.9;
    $categoryBg = $settings->category_name_bg_color_cocktails ?? 'rgba(118, 45, 121, 0.35)';
    $categoryText = $settings->category_name_text_color_cocktails ?? $palette['cream'];
    $categoryFontSize = $settings->category_name_font_size_cocktails ?? 30;
    $cocktailBackgroundDisabled = (bool) ($settings->disable_background_cocktails ?? false);
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
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $settings->tab_label_cocktails ?? 'Cócteles' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        :root {
            --cocktail-text-color: {{ $textColor }};
            --cocktail-accent-color: {{ $buttonColor }};
            --cocktail-blue: {{ $palette['blue'] }};
            --cocktail-cream: {{ $palette['cream'] }};
            --cocktail-violet: {{ $palette['violet'] }};
            --cocktail-amber: {{ $palette['amber'] }};
        }
        html, body {
            min-height: 100vh;
        }

        body {
            font-family: {{ $settings->font_family_cocktails ?? 'ui-sans-serif' }};
            color: var(--cocktail-text-color);
            @if($cocktailBackgroundDisabled)
                background: transparent;
            @elseif($settings && $settings->background_image_cocktails)
                background: none;
            @else
                background: radial-gradient(circle at top, rgba(255, 183, 35, 0.25), rgba(118, 45, 121, 0.6));
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
            @if($cocktailBackgroundDisabled)
                display: none;
            @elseif($settings && $settings->background_image_cocktails)
                background: url('{{ asset('storage/' . $settings->background_image_cocktails) }}') no-repeat center center;
                background-size: cover;
            @else
                background: linear-gradient(160deg, rgba(118, 45, 121, 0.5), rgba(255, 183, 35, 0.35));
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
            color: var(--cocktail-accent-color);
            transform: scale(1.05);
            font-weight: 600;
        }
        .sidebar-panel {
            background: rgba(118, 45, 121, 0.2);
            color: {{ $settings->sidebar_text_color_cocktails ?? $palette['cream'] }};
            border-right: 1px solid rgba(255, 183, 35, 0.25);
            backdrop-filter: blur(6px);
        }
        .mobile-menu-panel {
            background: rgba(118, 45, 121, 0.2);
            color: {{ $settings->sidebar_text_color_cocktails ?? $palette['cream'] }};
        }
        .category-chip {
            background-color: rgba(255, 183, 35, 0.2);
            border: 1px solid rgba(118, 45, 121, 0.3);
            color: var(--cocktail-text-color);
        }
        .category-chip:hover,
        .category-chip.active {
            background-color: rgba(118, 45, 121, 0.35);
            color: var(--cocktail-cream);
        }
        .scroll-hint {
            scrollbar-width: none;
        }
        .scroll-hint::-webkit-scrollbar {
            display: none;
        }
        .scroll-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 242, 179, 0.9);
            color: {{ $palette['violet'] }};
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.2);
            border: 1px solid rgba(118, 45, 121, 0.2);
            z-index: 5;
        }
        .scroll-arrow svg {
            width: 18px;
            height: 18px;
        }
        .scroll-arrow-left {
            left: 6px;
        }
        .scroll-arrow-right {
            right: 6px;
        }

        .drink-card {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
            color: var(--cocktail-text-color);
            border: 1px solid rgba(255, 183, 35, 0.25);
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
            border: 1px solid rgba(255, 183, 35, 0.3);
        }

        @media (max-width: 768px) {
            body {
                background-position: center top;
                background-attachment: fixed;
            }
        }
        @media (min-width: 1024px) {
            body.desktop-with-sidebar {
                padding-left: 16rem;
            }
            body.sidebar-collapsed {
                padding-left: 0;
            }
            body.desktop-with-sidebar .sidebar-panel {
                transform: translateX(0);
                transition: transform 0.2s ease;
            }
            body.sidebar-collapsed .sidebar-panel {
                transform: translateX(-100%);
            }
            body.desktop-with-sidebar .floating-nav {
                left: 16rem;
            }
            body.sidebar-collapsed .floating-nav {
                left: 0;
            }
            body.desktop-with-sidebar .floating-nav-inner {
                justify-content: center;
            }
        }
    </style>
</head>
<body class="desktop-with-sidebar">
<button id="toggleMenu"
        class="fixed left-4 top-4 z-[70] w-12 h-12 rounded-full flex items-center justify-center shadow-lg lg:hidden"
        style="background-color: var(--cocktail-accent-color); color: {{ $palette['cream'] }}; z-index: 9999;">
    <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
        <path d="M4 7h16"></path>
        <path d="M4 12h16"></path>
        <path d="M4 17h16"></path>
    </svg>
</button>

<div class="text-center py-6 relative content-layer">
    <img src="{{ $logoFallback }}" class="mx-auto h-28" alt="Logo">

    <button id="toggleDesktopMenu"
            class="hidden lg:flex fixed left-6 top-6 z-50 h-11 w-11 items-center justify-center rounded-full text-lg shadow-lg"
            style="background-color: var(--cocktail-accent-color); color: {{ $palette['cream'] }};">
        ☰
    </button>

    <div class="hidden lg:block">
        <div class="fixed top-0 left-0 h-full w-64 sidebar-panel p-6 space-y-2 shadow-lg overflow-y-auto backdrop-blur-sm">
            @foreach ($categories as $category)
                <a href="#category{{ $category->id }}" class="block text-lg font-semibold hover:text-blue-500 category-nav-link" data-category-target="category{{ $category->id }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>
</div>

@if($settings->cocktail_hero_image)
    <div class="max-w-4xl mx-auto px-4 pb-8 content-layer">
        <img src="{{ asset('storage/' . $settings->cocktail_hero_image) }}" alt="Destacado de cócteles" class="hero-media shadow-2xl border" style="border-color: rgba(255, 183, 35, 0.3);">
    </div>
@endif

<div id="categoryMenu"
     class="lg:hidden fixed inset-0 mobile-menu-panel px-6 py-8 space-y-6 overflow-y-auto transform -translate-y-full transition-transform duration-300 z-[60]">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold tracking-[0.25em] uppercase" style="color: {{ $palette['amber'] }};">Categorías</h2>
        <button id="closeMenu" class="text-2xl" style="color: {{ $palette['violet'] }};">&times;</button>
    </div>
    <div class="grid grid-cols-2 gap-4">
        @foreach ($categories as $category)
            <button class="rounded-2xl border py-4 px-3 text-sm font-semibold text-left shadow category-nav-link"
                    style="border-color: rgba(255, 183, 35, 0.35); background-color: rgba(118, 45, 121, 0.15); color: {{ $palette['cream'] }};"
                    data-category-target="category{{ $category->id }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>
</div>
<div id="menuOverlay" class="fixed inset-0 z-50 hidden lg:hidden" style="background-color: rgba(118, 45, 121, 0.5);"></div>

@if($categories->count())
    <div class="lg:hidden content-layer sticky top-20 z-30 px-4">
        <div class="relative">
            <button type="button" class="scroll-arrow scroll-arrow-left hidden" aria-label="Desplazar a la izquierda">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M12.5 5l-5 5 5 5"></path>
                </svg>
            </button>
            <button type="button" class="scroll-arrow scroll-arrow-right hidden" aria-label="Desplazar a la derecha">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M7.5 5l5 5-5 5"></path>
                </svg>
            </button>
            <div class="flex gap-3 overflow-x-auto py-3 snap-x snap-mandatory scroll-hint">
                @foreach ($categories as $category)
                    <button class="category-chip snap-start whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold backdrop-blur-md hover:scale-105 transition category-nav-link"
                            data-category-target="category{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
@endif

<div class="max-w-5xl mx-auto px-4 pb-32 content-layer">
    @forelse ($categories as $category)
        <section id="category{{ $category->id }}" class="mb-10 category-section" data-category-id="category{{ $category->id }}">
            <h2 class="text-3xl font-bold text-center mb-6"
                style="background-color: {{ $categoryBg }};
                       color: {{ $categoryText }};
                       font-size: {{ $categoryFontSize }}px;
                       border-radius: 10px; padding: 10px;">
                {{ $category->name }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($category->items->where('visible', true) as $drink)
                    @php
                        $drinkExtras = $drink->extras->where('active', true);
                        $drinkExtrasPayload = $drinkExtras->map(function ($extra) {
                            return [
                                'name' => $extra->name,
                                'price' => number_format($extra->price, 2, '.', ''),
                                'description' => $extra->description,
                            ];
                        });
                    @endphp
                    <div id="drink{{ $drink->id }}" onclick="openDrinkModal(this)"
                         class="drink-card rounded-lg p-4 shadow-lg relative flex items-center cursor-pointer hover:scale-105 transition"
                         style="background-color: {{ $cardBg }}; opacity: {{ $cardOpacity }};"
                         data-name="{{ $drink->name }}"
                         data-description="{{ strip_tags($drink->description) }}"
                         data-price="${{ number_format($drink->price, 2) }}"
                         data-image="{{ $resolveMedia($drink->image) }}"
                         data-extras='@json($drinkExtrasPayload)'>

                        <span class="absolute top-2 right-2 text-xs px-2 py-1 rounded"
                              style="background-color: rgba(118, 45, 121, 0.45); color: {{ $palette['cream'] }};">Ver más</span>

                        <img src="{{ $resolveMedia($drink->image) }}"
                             alt="{{ $drink->name }}"
                             class="h-24 w-24 rounded-full object-cover mr-4 border"
                             style="border-color: rgba(118, 45, 121, 0.25);">

                        <div class="flex-1">
                            <h3 class="text-xl font-bold">{{ $drink->name }}</h3>
                            <p class="text-sm mb-2">${{ number_format($drink->price, 2) }}</p>


                            @if (!empty($drink->volume) || !empty($drink->garnish))
                                <div class="flex flex-wrap gap-2 text-xs">
                                    @if(!empty($drink->volume))
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full border"
                                              style="border-color: rgba(255, 183, 35, 0.35); background-color: rgba(118, 45, 121, 0.18);">
                                            <i class="fas fa-glass-whiskey text-[var(--cocktail-accent-color)]"></i> {{ $drink->volume }}
                                        </span>
                                    @endif
                                    @if(!empty($drink->garnish))
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full border"
                                              style="border-color: rgba(255, 183, 35, 0.35); background-color: rgba(118, 45, 121, 0.18);">
                                            <i class="fas fa-leaf text-[var(--cocktail-accent-color)]"></i> {{ $drink->garnish }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @empty
        <div class="text-center py-20" style="color: rgba(255, 242, 179, 0.85);">
            No hay cócteles configurados. Usa el panel para añadir elementos a esta vista.
        </div>
    @endforelse
</div>

@include('components.floating-nav', [
    'settings' => $settings,
    'background' => $settings->floating_bar_bg_cocktails ?? 'rgba(118, 45, 121, 0.55)',
    'buttonColor' => $settings->floating_bar_button_color_cocktails ?? $buttonColor
])

<div id="drinkDetailsModal" tabindex="-1" aria-hidden="true" role="dialog" aria-modal="true"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
     style="background-color: rgba(118, 45, 121, 0.55);">
    <div class="relative w-full max-w-xl max-h-[90vh]">
        <div class="rounded-lg shadow-lg p-6 relative overflow-y-auto max-h-[90vh]"
             style="background-color: {{ $palette['cream'] }}; color: {{ $palette['violet'] }};">
            <button onclick="closeDrinkModal()" class="absolute top-3 right-3 text-xl font-bold"
                    style="color: {{ $palette['violet'] }};">
                ✕
            </button>

            <img id="drinkModalImage" class="w-full h-60 object-cover rounded-lg mb-4" alt="Imagen del cóctel">

            <h3 id="drinkModalTitle" class="text-2xl font-bold mb-2"></h3>
            <p id="drinkModalDescription" class="mb-2"></p>
            <p id="drinkModalPrice" class="font-semibold text-lg mb-4"></p>

            <div id="drinkModalExtras" class="hidden mt-4">
                <h4 class="text-lg font-semibold mb-2" style="color: var(--cocktail-accent-color);">Extras sugeridos</h4>
                <ul id="drinkExtrasList" class="space-y-2 text-sm" style="color: {{ $palette['violet'] }};"></ul>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.js"></script>
<script>
    const menuModal = document.getElementById('categoryMenu');
    const overlay = document.getElementById('menuOverlay');
    const toggleMenuBtn = document.getElementById('toggleMenu');
    const toggleDesktopMenuBtn = document.getElementById('toggleDesktopMenu');
    const closeMenuBtn = document.getElementById('closeMenu');
    const navLinks = document.querySelectorAll('.category-nav-link');
    const scrollAreas = document.querySelectorAll('.scroll-hint');

    const setupScrollArrows = (container) => {
        const wrapper = container.parentElement;
        const leftBtn = wrapper?.querySelector('.scroll-arrow-left');
        const rightBtn = wrapper?.querySelector('.scroll-arrow-right');
        if (!leftBtn || !rightBtn) {
            return;
        }
        const update = () => {
            const maxScroll = container.scrollWidth - container.clientWidth;
            const hasOverflow = maxScroll > 4;
            leftBtn.classList.toggle('hidden', !hasOverflow || container.scrollLeft <= 2);
            rightBtn.classList.toggle('hidden', !hasOverflow || container.scrollLeft >= maxScroll - 2);
        };
        leftBtn.addEventListener('click', () => {
            container.scrollBy({ left: -180, behavior: 'smooth' });
        });
        rightBtn.addEventListener('click', () => {
            container.scrollBy({ left: 180, behavior: 'smooth' });
        });
        container.addEventListener('scroll', update);
        window.addEventListener('resize', update);
        update();
    };

    const openMenu = () => {
        menuModal?.classList.remove('-translate-y-full');
        overlay?.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    const closeMenu = () => {
        menuModal?.classList.add('-translate-y-full');
        overlay?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    toggleMenuBtn?.addEventListener('click', () => {
        if (menuModal?.classList.contains('-translate-y-full')) {
            openMenu();
        } else {
            closeMenu();
        }
    });
    scrollAreas.forEach(setupScrollArrows);
    toggleDesktopMenuBtn?.addEventListener('click', () => {
        document.body.classList.toggle('sidebar-collapsed');
    });

    closeMenuBtn?.addEventListener('click', closeMenu);
    overlay?.addEventListener('click', closeMenu);

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

    const cardObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                cardObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });

    document.querySelectorAll('.drink-card').forEach(card => cardObserver.observe(card));

    function openDrinkModal(el) {
        const name = el.dataset.name;
        const description = el.dataset.description;
        const price = el.dataset.price;
        const fallbackImage = "{{ $logoFallback }}";
        const image = el.dataset.image && !el.dataset.image.endsWith('/storage/') ? el.dataset.image : fallbackImage;
        const extras = el.dataset.extras ? JSON.parse(el.dataset.extras) : [];

        document.getElementById('drinkModalTitle').textContent = name;
        document.getElementById('drinkModalDescription').textContent = description;
        document.getElementById('drinkModalPrice').textContent = price;
        document.getElementById('drinkModalImage').src = image;

        const extrasSection = document.getElementById('drinkModalExtras');
        const extrasList = document.getElementById('drinkExtrasList');
        extrasList.innerHTML = '';
        if (extras.length) {
            extras.forEach(extra => {
                const li = document.createElement('li');
                li.className = 'flex flex-col gap-1 rounded-xl px-3 py-2';
                li.style.border = '1px solid rgba(118, 45, 121, 0.25)';
                li.style.backgroundColor = 'rgba(255, 183, 35, 0.25)';
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
                li.appendChild(row);
                if (extra.description) {
                    const desc = document.createElement('p');
                    desc.className = 'text-xs';
                    desc.style.color = '{{ $palette['blue'] }}';
                    desc.textContent = extra.description;
                    li.appendChild(desc);
                }
                extrasList.appendChild(li);
            });
            extrasSection.classList.remove('hidden');
        } else {
            extrasSection.classList.add('hidden');
        }

        if (!window.drinkModalInstance) {
            window.drinkModalInstance = new Modal(document.getElementById('drinkDetailsModal'));
        }
        window.drinkModalInstance.show();
    }

    function closeDrinkModal() {
        if (window.drinkModalInstance) {
            window.drinkModalInstance.hide();
        }
    }
</script>
</body>
</html>
