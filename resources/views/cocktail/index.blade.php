<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cócteles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    @php
        if (!function_exists('cocktail_mix_color')) {
            function cocktail_mix_color(?string $hexColor, float $opacity = 1): string {
                $hex = $hexColor ?: '#191919';
                $hex = str_replace('#', '', $hex);
                if (strlen($hex) === 3) {
                    $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
                }
                $int = hexdec($hex);
                $r = ($int >> 16) & 255;
                $g = ($int >> 8) & 255;
                $b = $int & 255;
                $opacity = max(0, min(1, $opacity));
                return "rgba({$r}, {$g}, {$b}, {$opacity})";
            }
        }

        $cocktailCardColor = cocktail_mix_color($settings->card_bg_color_cocktails ?? '#191919', $settings->card_opacity_cocktails ?? 0.95);
        $accentColor = $settings->button_color_cocktails ?? '#ff5c5c';
        $textColor = $settings->text_color_cocktails ?? '#ffffff';
        $accentSoftBackground = cocktail_mix_color($accentColor, 0.2);
        $overlayColor = $settings->overlay_color_cocktails ?? 'rgba(0,0,0,0.45)';
    @endphp
    <style>
        body {
            font-family: {{ $settings->font_family_cocktails ?? '\'Inter\', sans-serif' }};
            min-height: 100vh;
            margin: 0;
            @if($settings && $settings->background_image_cocktails)
                background: none;
            @else
                background: radial-gradient(circle at top, #1f1b2e, #0b0a13);
            @endif
            background-size: cover;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            @if($settings && $settings->background_image_cocktails)
                background: url('{{ asset('storage/' . $settings->background_image_cocktails) }}') no-repeat center center;
                background-size: cover;
            @else
                background: {{ $settings->overlay_color_cocktails ?? 'rgba(0,0,0,0.45)' }};
            @endif
        }
        .content-layer {
            position: relative;
            z-index: 1;
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
<body class="text-white content-layer">
    <header class="max-w-6xl mx-auto px-4 pt-10 flex flex-col items-center gap-6">
        <img src="{{ asset('storage/' . ($settings->logo ?? 'default-logo.png')) }}" alt="Logo" class="w-40 md:w-56 object-contain">
            <button type="button"
                id="openDrawer"
                class="fixed left-4 top-4 z-50 w-12 h-12 rounded-full flex items-center justify-center text-2xl shadow-lg text-white focus:ring-4 focus:ring-amber-300 lg:hidden"
                style="background-color: {{ $settings->button_color_cocktails ?? '#ff5c5c' }};"
                aria-controls="cocktailDrawer">
            🍸
        </button>
    </header>

    @if($settings->cocktail_hero_image)
        <div class="max-w-5xl mx-auto px-4 mt-6">
            <img src="{{ asset('storage/' . $settings->cocktail_hero_image) }}" alt="Destacado de cócteles" class="hero-media shadow-2xl border border-white/20">
        </div>
    @endif

    <!-- Menú lateral desktop -->
    <div class="hidden lg:block">
        <aside class="fixed top-0 left-0 z-30 w-64 h-screen p-6 overflow-y-auto bg-white text-slate-900 shadow-2xl space-y-3">
            @foreach ($cocktailCategories as $category)
                <a href="#category{{ $category->id }}"
                   class="flex items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3 hover:bg-slate-100 transition text-sm font-semibold category-link"
                   data-category-link="#category{{ $category->id }}">
                    <i class="fa-solid fa-martini-glass-citrus" style="color: {{ $accentColor }};"></i>
                    {{ $category->name }}
                </a>
            @endforeach
        </aside>
    </div>

    <!-- Drawer móvil -->
    <aside id="cocktailDrawer"
           class="lg:hidden fixed inset-0 z-50 p-6 overflow-y-auto transition-transform -translate-y-full bg-white text-slate-800 shadow-2xl"
           tabindex="-1">
        <div class="flex items-center justify-end mb-6">
            <button type="button"
                    data-close-drawer
                    aria-controls="cocktailDrawer"
                    class="text-slate-500 hover:text-slate-900 text-2xl">
                &times;
            </button>
        </div>
        <div class="grid grid-cols-2 gap-4">
            @foreach ($cocktailCategories as $category)
                <button class="rounded-2xl border px-4 py-3 text-sm font-semibold text-left bg-white hover:bg-slate-50"
                        style="color: {{ $settings->text_color_cocktails ?? '#111' }}; border-color: rgba(0,0,0,0.1);"
                        data-category-link="#category{{ $category->id }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </aside>

    <div id="cocktailOverlay" class="fixed inset-0 z-40 hidden lg:hidden" style="background-color: {{ $overlayColor }};"></div>

    <!-- Chips móviles -->
    <div class="lg:hidden content-layer sticky top-20 z-30 px-4">
        <div class="flex gap-3 overflow-x-auto py-3 snap-x snap-mandatory">
            @foreach ($cocktailCategories as $category)
                <button class="category-chip snap-start whitespace-nowrap px-4 py-2 rounded-full border text-sm font-semibold backdrop-blur-md hover:scale-105 transition"
                        style="color: {{ $textColor }}; border-color: {{ $accentColor }}; background-color: {{ $accentSoftBackground }};"
                        data-category-link="#category{{ $category->id }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>

    <main class="max-w-6xl mx-auto px-4 pb-28 space-y-12 mt-10 lg:ml-72">
        @foreach ($cocktailCategories as $category)
            <section id="category{{ $category->id }}" class="space-y-6 category-section">
                <div class="flex flex-col items-center">
                    <p class="text-xs uppercase tracking-[0.35em] text-white/60">Selección</p>
                    <h2 class="mt-2 text-3xl font-bold text-center px-6 py-2 rounded-full shadow"
                        style="color: {{ $settings->category_name_text_color_cocktails ?? '#f9f9f9' }}; background-color: {{ $settings->category_name_bg_color_cocktails ?? 'rgba(255,255,255,0.1)' }}; border: 1px solid {{ $settings->category_name_bg_color_cocktails ?? 'rgba(255,255,255,0.3)' }};">
                        {{ $category->name }}
                    </h2>
                </div>
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($category->items->where('visible', true) as $item)
                        <article id="cocktail{{ $item->id }}" class="rounded-3xl border p-4 flex gap-4 items-center shadow-xl hover:shadow-2xl transition hover:-translate-y-1 cursor-pointer"
                                 data-cocktail-card
                                 data-name="{{ $item->name }}"
                                 data-description="{{ strip_tags($item->description) }}"
                                 data-price="{{ number_format($item->price, 2) }}"
                                 data-image="{{ asset('storage/' . $item->image) }}"
                                 style="background-color: {{ $cocktailCardColor }}; color: {{ $textColor }}; border-color: rgba(255,255,255,0.15);">
                            <div class="relative shrink-0">
                                <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('storage/' . ($settings->logo ?? 'default-logo.png')) }}" alt="{{ $item->name }}"
                                     class="w-24 h-24 rounded-full object-cover bg-white/5"
                                     style="border: 4px solid rgba(255,255,255,0.15); padding: 4px;">
                                <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 px-3 py-1 text-xs rounded-full text-slate-900 font-semibold border border-white/40"
                                      style="background-color: {{ $accentColor }};">
                                    ${{ number_format($item->price, 0) }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold" style="color: {{ $textColor }};">{{ $item->name }}</h3>
                                <p class="text-sm leading-relaxed line-clamp-3"
                                   style="color: {{ $textColor }}; opacity: 0.8;">
                                    {{ $item->description }}
                                </p>
                            <div class="mt-3 flex flex-wrap gap-2 text-xs font-semibold">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full border"
                                      style="background-color: {{ $accentSoftBackground }}; border-color: {{ $accentColor }}; color: {{ $textColor }};">
                                    <i class="fa-solid fa-droplet"></i> {{ $item->volume ?? 'Clásico' }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full border border-white/15"
                                      style="background-color: rgba(255,255,255,0.12); color: {{ $textColor }};">
                                    <i class="fa-solid fa-star" style="color: {{ $accentColor }};"></i> Mix ideal
                                </span>
                            </div>
                            @if($item->dishes->count())
                                <div class="mt-4 border border-white/10 rounded-2xl p-3">
                                    <p class="text-xs uppercase tracking-[0.3em] text-white/60 mb-2">Platos sugeridos</p>
                                    <div class="flex flex-wrap gap-2 text-xs text-white/85">
                                        @foreach($item->dishes as $dish)
                                            <span class="px-3 py-1 rounded-full bg-white/10 border border-white/20">{{ $dish->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
                </div>
            </section>
        @endforeach
    </main>

    <!-- Floating buttons -->
    <div class="fixed bottom-6 left-0 right-0 flex justify-center z-40">
        <div class="flex items-center gap-4 px-4 py-2 rounded-3xl backdrop-blur-lg border border-white/20 shadow-2xl"
             style="background-color: rgba(0,0,0,0.55);">
            <a href="/" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
               style="background-color: {{ $settings->button_color_cocktails ?? '#ff5c5c' }};">
                <i class="fas fa-home text-lg"></i><span>Inicio</span>
            </a>
            <a href="/menu" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
               style="background-color: {{ $settings->button_color_cocktails ?? '#ff5c5c' }};">
                <i class="fas fa-utensils text-lg"></i><span>Menú</span>
            </a>
            <a href="/coffee" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
               style="background-color: {{ $settings->button_color_cocktails ?? '#ff5c5c' }};">
                <i class="fas fa-mug-saucer text-lg"></i><span>Café</span>
            </a>
        </div>
    </div>

    <!-- Modal detalle cóctel -->
    <div id="cocktailDetailsModal" tabindex="-1" class="hidden fixed inset-0 z-50 overflow-y-auto overflow-x-hidden justify-center items-center">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-3xl shadow dark:bg-gray-700 text-slate-900">
                <button type="button" class="absolute top-3 right-3 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-full text-sm w-8 h-8" onclick="closeCocktailModal()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="grid md:grid-cols-2 gap-4 p-6">
                    <img id="modalImage" src="" alt="Cóctel" class="w-full h-56 object-cover rounded-2xl">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-amber-500 mb-2">Cóctel</p>
                        <h3 id="modalTitle" class="text-2xl font-semibold text-slate-900"></h3>
                        <p id="modalPrice" class="text-lg font-bold text-slate-800 mt-2"></p>
                        <p id="modalDescription" class="text-sm text-slate-600 mt-4 leading-relaxed"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal promocional -->
    <div id="cocktailPopupModal" tabindex="-1" class="hidden fixed inset-0 z-50 overflow-y-auto overflow-x-hidden justify-center items-center">
        <div class="relative p-4 w-full max-w-3xl max-h-full">
            <div class="relative bg-white rounded-3xl shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 right-3 text-gray-400 hover:text-gray-900" onclick="closePopupModal()">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
                <div class="p-4">
                    <img id="popupImage" src="" alt="Promoción" class="w-full h-auto rounded-2xl">
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.js"></script>
    <script>
        let cocktailModalInstance;
        let promoModalInstance;
        let drawerOpen = false;

        const openCocktailModal = (card) => {
            const modalEl = document.getElementById('cocktailDetailsModal');
            if (!cocktailModalInstance) {
                cocktailModalInstance = new Modal(modalEl);
            }
            document.getElementById('modalTitle').textContent = card.dataset.name;
            document.getElementById('modalDescription').textContent = card.dataset.description || 'Prueba nuestra mezcla especial.';
            document.getElementById('modalPrice').textContent = '$' + card.dataset.price;
            document.getElementById('modalImage').src = card.dataset.image;
            cocktailModalInstance.show();
        };

        const closeCocktailModal = () => {
            if (cocktailModalInstance) {
                cocktailModalInstance.hide();
            }
        };

        const showPopupModal = (imageSrc) => {
            const modalEl = document.getElementById('cocktailPopupModal');
            if (!promoModalInstance) {
                promoModalInstance = new Modal(modalEl, { closable: true });
            }
            document.getElementById('popupImage').src = imageSrc;
            promoModalInstance.show();
        };

        const closePopupModal = () => {
            if (promoModalInstance) {
                promoModalInstance.hide();
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            const drawerEl = document.getElementById('cocktailDrawer');
            const overlayEl = document.getElementById('cocktailOverlay');
            const openBtn = document.getElementById('openDrawer');
            const closeBtn = document.querySelector('[data-close-drawer]');

            const openDrawer = () => {
                drawerEl?.classList.remove('-translate-y-full');
                overlayEl?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                drawerOpen = true;
            };

            const closeDrawer = () => {
                drawerEl?.classList.add('-translate-y-full');
                overlayEl?.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                drawerOpen = false;
            };

            openBtn?.addEventListener('click', () => {
                if (drawerOpen) {
                    closeDrawer();
                } else {
                    openDrawer();
                }
            });
            closeBtn?.addEventListener('click', closeDrawer);
            overlayEl?.addEventListener('click', closeDrawer);

            document.querySelectorAll('[data-cocktail-card]').forEach(card => {
                card.addEventListener('click', () => openCocktailModal(card));
            });

            document.querySelectorAll('[data-category-link]').forEach(link => {
                link.addEventListener('click', e => {
                    e.preventDefault();
                    const targetRef = link.dataset.categoryLink || link.getAttribute('href');
                    const section = document.querySelector(targetRef);
                    section?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    closeDrawer();
                });
            });

            const popups = @json($popups);
            const now = new Date();
            const today = now.getDay();

            popups.forEach(popup => {
                const start = new Date(popup.start_date);
                const end = new Date(popup.end_date);
                const repeatDays = popup.repeat_days ? popup.repeat_days.split(',').map(Number) : [];
                if (
                    popup.active &&
                    popup.view === 'cocktails' &&
                    now >= start && now <= end &&
                    (repeatDays.length === 0 || repeatDays.includes(today))
                ) {
                    const imageUrl = '{{ asset('storage') }}/' + popup.image;
                    showPopupModal(imageUrl);
                }
            });
        });
    </script>
</body>
</html>
