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
            --menu-text-color: {{ $settings->text_color_menu ?? '#ffffff' }};
            --menu-accent-color: {{ $settings->button_color_menu ?? '#FFB347' }};
        }
        html, body {
            min-height: 100vh;
        }

        body {
            font-family: {{ $settings->font_family_menu ?? 'ui-sans-serif' }};
            @if($settings && $settings->background_image_menu)
                background: url('{{ asset('storage/' . $settings->background_image_menu) }}') no-repeat center center fixed;
            @else
                background: radial-gradient(circle at top, #f3eada, #d9c7a1);
            @endif
            background-size: cover;
            background-attachment: fixed;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
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
            color: {{ $settings->button_color_menu ?? '#FFB347' }};
            transform: scale(1.05);
            font-weight: 600;
        }

        .dish-card {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
            color: var(--menu-text-color);
        }

        .dish-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            body {
                background-position: center top;
                background-attachment: fixed;
            }
        }
    </style>
</head>
<body class="text-white bg-black/70">

<!-- LOGO + BOTÓN MENU -->
<div class="text-center py-6 relative content-layer">
    <img src="{{ asset('storage/' . ($settings->logo ?? 'default-logo.png')) }}" class="mx-auto h-28" alt="Logo del Restaurante">

    <!-- Toggle menú -->
    <button id="toggleMenu"
        class="fixed left-4 top-4 z-50 w-12 h-12 rounded-full flex items-center justify-center text-xl shadow-lg text-white lg:hidden"
        style="background-color: {{ $settings->button_color_menu ?? '#000' }};">
        🍽️
    </button>

    <!-- Menú lateral desktop -->
    <div class="hidden lg:block">
        <div class="fixed top-0 left-0 h-full w-64 bg-white text-black p-6 space-y-2 shadow-lg overflow-y-auto">
            @foreach ($categories as $category)
                <a href="#category{{ $category->id }}" class="block text-lg font-semibold hover:text-blue-500 category-nav-link" data-category-target="category{{ $category->id }}">{{ $category->name }}</a>
            @endforeach
        </div>
    </div>
</div>

<!-- Menú flotante móvil -->
<div id="categoryMenu"
    class="lg:hidden fixed inset-0 bg-white text-slate-900 px-6 py-8 space-y-6 overflow-y-auto transform -translate-y-full transition-transform duration-300 z-[60]">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold tracking-[0.25em] uppercase text-slate-500">Categorías</h2>
        <button id="closeMenu" class="text-2xl text-slate-500 hover:text-slate-900">&times;</button>
    </div>
    <div class="grid grid-cols-2 gap-4">
        @foreach ($categories as $category)
            <button class="rounded-2xl border border-slate-200 py-4 px-3 text-sm font-semibold text-left shadow bg-white hover:bg-slate-50 category-nav-link"
                    data-category-target="category{{ $category->id }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>
</div>
<div id="menuOverlay" class="fixed inset-0 bg-black/60 z-50 hidden lg:hidden"></div>

<!-- Carrusel de chips -->
<div class="lg:hidden content-layer sticky top-20 z-30 px-4">
    <div class="flex gap-3 overflow-x-auto py-3 snap-x snap-mandatory">
        @foreach ($categories as $category)
            <button class="category-chip snap-start whitespace-nowrap px-4 py-2 rounded-full border border-white/20 bg-black/40 text-sm font-semibold backdrop-blur-md hover:scale-105 transition category-nav-link"
                    data-category-target="category{{ $category->id }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>
</div>

<!-- CONTENIDO DE CATEGORÍAS Y PLATOS -->
<div class="max-w-5xl mx-auto px-4 pb-32 content-layer">
    @foreach ($categories as $category)
        <section id="category{{ $category->id }}" class="mb-10 category-section" data-category-id="category{{ $category->id }}">
            <h2 class="text-3xl font-bold text-center mb-6"
                style="background-color: {{ $settings->category_name_bg_color_menu ?? 'rgba(254, 90, 90, 0.8)' }};
                       color: {{ $settings->category_name_text_color_menu ?? '#f9f9f9' }};
                       font-size: {{ $settings->category_name_font_size_menu ?? 30 }}px;
                       border-radius: 10px; padding: 10px;">
                {{ $category->name }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($category->dishes->where('visible', true) as $dish)
                    <div onclick="openDishModal(this)"
                        class="dish-card rounded-lg p-4 shadow-lg relative flex items-center cursor-pointer hover:scale-105 transition"
                        style="background-color: {{ $settings->card_bg_color_menu ?? '#191919' }};
                               opacity: {{ $settings->card_opacity_menu ?? 0.9 }};"
                        data-name="{{ $dish->name }}"
                        data-description="{{ $dish->description }}"
                        data-price="${{ number_format($dish->price, 2) }}"
                        data-image="{{ asset('storage/' . $dish->image) }}"
                        data-wines="{{ e($dish->wines->map(fn($wine) => $wine->id.'::'.$wine->name)->implode('|')) }}">

                        <span class="absolute top-2 right-2 text-xs bg-gray-700 text-white px-2 py-1 rounded">Ver más</span>


                        <img src="{{ asset('storage/' . $dish->image) }}"
                             alt="{{ $dish->name }}"
                             class="h-24 w-24 rounded-full object-cover mr-4">

                        <div class="flex-1">
                            <h3 class="text-xl font-bold">{{ $dish->name }}</h3>
                            <p class="text-sm mb-2">${{ number_format($dish->price, 2) }}</p>

                            @if ($dish->wines && $dish->wines->count())
                                <div class="mt-3">
                                    <p class="text-xs uppercase tracking-[0.2em] mb-2" style="color: {{ $settings->text_color_menu ?? '#fefefe' }};">Maridajes sugeridos</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($dish->wines as $wine)
                                            <a href="{{ route('wines.index') }}#wine{{ $wine->id }}"
                                               class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold border transition hover:scale-105"
                                               style="background-color: {{ $settings->category_name_bg_color_menu ?? 'rgba(254, 90, 90, 0.2)' }}; border-color: {{ $settings->button_color_menu ?? '#FFB347' }}; color: {{ $settings->text_color_menu ?? '#ffffff' }};">
                                                <i class="fas fa-wine-glass-alt" style="color: {{ $settings->button_color_menu ?? '#FFB347' }};"></i>
                                                {{ $wine->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach
</div>

<!-- BOTONES FLOTANTES -->
<div class="fixed bottom-5 left-0 right-0 z-50 flex justify-center content-layer">
    <div class="flex items-center gap-4 px-4 py-2 rounded-3xl backdrop-blur-lg border border-white/20 shadow-2xl"
         style="background-color: {{ $settings->floating_bar_bg_menu ?? 'rgba(0,0,0,0.55)' }};">
        @php $actionColor = $settings->button_color_menu ?? '#000'; @endphp
        <a href="/" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
           style="background-color: {{ $actionColor }};">
            <i class="fas fa-home text-lg"></i><span>Inicio</span>
        </a>
        <a href="/menu" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
           style="background-color: {{ $actionColor }};">
            <i class="fas fa-utensils text-lg"></i><span>Menú</span>
        </a>
        <a href="/cocktails" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
           style="background-color: {{ $actionColor }};">
            <i class="fas fa-cocktail text-lg"></i><span>Cócteles</span>
        </a>
        <a href="/wines" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
           style="background-color: {{ $actionColor }};">
            <i class="fas fa-wine-glass text-lg"></i><span>Vinos</span>
        </a>
    </div>
</div>

<!-- MODAL DE DETALLE DEL PLATO -->
<div id="dishDetailsModal" tabindex="-1" aria-hidden="true" role="dialog" aria-modal="true"
    class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-black/70">
    <div class="relative w-full max-w-xl">
        <div class="bg-white rounded-lg shadow-lg text-gray-900 p-6 relative">

            <button onclick="closeDishModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-xl font-bold">
                ✕
            </button>

            <img id="modalImage" class="w-full h-60 object-cover rounded-lg mb-4" alt="Imagen del plato">

            <h3 id="modalTitle" class="text-2xl font-bold mb-2"></h3>
            <p id="modalDescription" class="mb-2"></p>
            <p id="modalPrice" class="font-semibold text-lg mb-4"></p>

            <div id="modalWines" class="mt-4 hidden">
                <h4 class="text-lg font-semibold mb-2" style="color: {{ $settings->button_color_menu ?? '#FFB347' }};">Vinos sugeridos 🍷</h4>
                <ul id="wineList" class="list-disc list-inside" style="color: {{ $settings->text_color_menu ?? '#111' }};"></ul>
            </div>
        </div>
    </div>
</div>

<!-- Flowbite JS -->
<script src="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('✅ Menú cargado con Tailwind y Flowbite');

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
    });

    // Función para abrir modal con datos del plato
    function openDishModal(el) {
        const name = el.dataset.name;
        const description = el.dataset.description;
        const price = el.dataset.price;
        const image = el.dataset.image;
        const wines = el.dataset.wines;

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
                link.href = '{{ route('wines.index') }}#wine' + (wineId || '').trim();
                link.className = 'text-amber-500 hover:underline';
                li.appendChild(link);
                wineList.appendChild(li);
            });
            document.getElementById('modalWines').classList.remove('hidden');
        } else {
            document.getElementById('modalWines').classList.add('hidden');
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
</script>

</body>
</html>
