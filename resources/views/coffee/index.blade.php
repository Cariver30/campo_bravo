@php
    $drinkTextColor = $settings->text_color_wines ?? '#ffffff';
    $drinkAccentColor = $settings->button_color_wines ?? '#FFB347';
    $drinkCardBg = $settings->card_bg_color_wines ?? '#1f2937';
    $drinkCardOpacity = $settings->card_opacity_wines ?? 0.9;
    $categoryBgColor = $settings->category_name_bg_color_wines ?? 'rgba(15, 23, 42, 0.85)';
    $categoryTextColor = $settings->category_name_text_color_wines ?? '#ffffff';
    $categoryFontSize = $settings->category_name_font_size_wines ?? 28;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bebidas</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        :root {
            --drink-text-color: {{ $drinkTextColor }};
            --drink-accent-color: {{ $drinkAccentColor }};
        }
        html, body {
            min-height: 100vh;
        }
        body {
            font-family: {{ $settings->font_family_wines ?? 'ui-sans-serif' }};
            color: var(--drink-text-color);
            @if($settings && $settings->background_image_wines)
                background: none;
            @else
                background: radial-gradient(circle at top, #f0e7d9, #c7b299);
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
            @if($settings && $settings->background_image_wines)
                background: url('{{ asset('storage/' . $settings->background_image_wines) }}') no-repeat center center;
                background-size: cover;
            @else
                background: rgba(0,0,0,0.55);
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
        .drink-card {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
            background-color: {{ $drinkCardBg }};
            color: var(--drink-text-color);
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
        }
        .tag-chip {
            background-color: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
        }
        @media (max-width: 768px) {
            body {
                background-position: center top;
                background-attachment: fixed;
            }
        }
    </style>
</head>
<body class="bg-black/70 text-white">

<!-- LOGO + BOTÓN MENU -->
<div class="text-center py-6 relative content-layer">
    <img src="{{ asset('storage/' . ($settings->logo ?? 'default-logo.png')) }}" class="mx-auto h-24" alt="Logo del concepto">

    <button id="toggleMenu"
            class="fixed left-4 top-4 z-50 w-12 h-12 rounded-full flex items-center justify-center text-xl shadow-lg text-white lg:hidden"
            style="background-color: var(--drink-accent-color);">
        🍸
    </button>

    <div class="hidden lg:block">
        <div class="fixed top-0 left-0 h-full w-64 bg-white text-slate-900 p-6 space-y-2 shadow-lg overflow-y-auto">
            @foreach(($wineCategories ?? collect()) as $category)
                <a href="#category{{ $category->id }}" class="block text-lg font-semibold hover:text-amber-500 category-nav-link" data-category-target="category{{ $category->id }}">{{ $category->name }}</a>
            @endforeach
        </div>
    </div>
</div>

@if($settings->coffee_hero_image)
    <div class="max-w-4xl mx-auto px-4 pb-8 content-layer">
        <img src="{{ asset('storage/' . $settings->coffee_hero_image) }}" alt="Destacado de bebidas" class="hero-media shadow-2xl border border-white/10">
    </div>
@endif

<!-- Menú lateral móvil -->
<div id="categoryMenu"
    class="lg:hidden fixed inset-0 bg-white text-slate-900 px-6 py-8 space-y-6 overflow-y-auto transform -translate-y-full transition-transform duration-300 z-[60]">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold tracking-[0.25em] uppercase text-slate-500">Categorías</h2>
        <button id="closeMenu" class="text-2xl text-slate-500 hover:text-slate-900">&times;</button>
    </div>
    <div class="grid grid-cols-2 gap-4">
        @foreach(($wineCategories ?? collect()) as $category)
            <button class="rounded-2xl border border-slate-200 py-4 px-3 text-sm font-semibold text-left shadow bg-white hover:bg-slate-50 category-nav-link"
                    data-category-target="category{{ $category->id }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>
</div>
<div id="menuOverlay" class="fixed inset-0 bg-black/60 z-50 hidden lg:hidden"></div>

<!-- Carrusel de chips -->
@if(($wineCategories ?? collect())->count())
    <div class="lg:hidden content-layer sticky top-20 z-30 px-4">
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

<!-- LISTADO DE BEBIDAS -->
<div class="max-w-5xl mx-auto px-4 pb-32 content-layer">
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
                        $drinkImage = $drink->image ? asset('storage/' . $drink->image) : asset('storage/' . ($settings->logo ?? 'default-logo.png'));
                        $drinkNotes = $drink->grapes?->pluck('name')->implode(', ');
                        $drinkPairs = $drink->dishes?->map(fn($dish) => $dish->id.'::'.$dish->name)->implode('|');
                    @endphp
                    <div id="drink{{ $drink->id }}" onclick="openDrinkModal(this)"
                         class="drink-card rounded-2xl p-4 shadow-lg relative flex flex-col gap-3 cursor-pointer hover:scale-105 transition border border-white/10"
                         style="opacity: {{ $drinkCardOpacity }};"
                         data-name="{{ $drink->name }}"
                         data-description="{{ $drink->description }}"
                         data-price="${{ number_format($drink->price, 2) }}"
                         data-image="{{ $drinkImage }}"
                         data-region="{{ $drink->region->name ?? '' }}"
                         data-method="{{ $drink->type->name ?? '' }}"
                         data-notes="{{ $drinkNotes }}"
                         data-pairings="{{ $drinkPairs }}">

                        <span class="absolute top-2 right-2 text-xs bg-black/60 text-white px-2 py-1 rounded-full">Ver más</span>

                        <div class="flex items-center gap-3">
                            <img src="{{ $drinkImage }}"
                                 alt="{{ $drink->name }}"
                                 class="h-20 w-20 rounded-2xl object-cover border border-white/10">
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
                            <div class="pt-3 border-t border-white/15">
                                <p class="text-xs uppercase tracking-[0.3em] opacity-80 mb-2">Maridajes sugeridos</p>
                                <div class="flex flex-wrap gap-2 text-xs">
                                    @foreach($drink->dishes as $dish)
                                        <a href="{{ route('menu') }}#dish{{ $dish->id }}" class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-white/15 bg-white/5 hover:border-white/40 transition">
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
        <div class="text-center py-20 text-white/80">
            No hay bebidas configuradas. Usa el panel para añadir elementos a la barra.
        </div>
    @endforelse
</div>

@include('components.floating-nav', [
    'settings' => $settings,
    'background' => $settings->floating_bar_bg_wines ?? 'rgba(0,0,0,0.55)',
    'buttonColor' => $settings->button_color_wines ?? '#000'
])

<!-- MODAL DETALLE BEBIDA -->
<div id="drinkDetailsModal" tabindex="-1" aria-hidden="true" role="dialog" aria-modal="true"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-black/70">
    <div class="relative w-full max-w-xl">
        <div class="bg-white rounded-lg shadow-lg text-gray-900 p-6 relative">
            <button onclick="closeDrinkModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-xl font-bold">
                ✕
            </button>

            <img id="drinkModalImage" class="w-full h-60 object-cover rounded-lg mb-4" alt="Imagen de la bebida">

            <h3 id="drinkModalTitle" class="text-2xl font-bold mb-2"></h3>
            <p id="drinkModalSpecs" class="text-sm text-gray-600 mb-1"></p>
            <p id="drinkModalDescription" class="mb-2"></p>
            <p id="drinkModalPrice" class="font-semibold text-lg mb-4"></p>

            <div id="drinkModalNotes" class="mb-4 hidden">
                <h4 class="text-lg font-semibold mb-2" style="color: {{ $drinkAccentColor }};">Notas de cata</h4>
                <p id="drinkNotesText" class="text-sm text-gray-700"></p>
            </div>

            <div id="drinkModalPairings" class="mt-4 hidden">
                <h4 class="text-lg font-semibold mb-2" style="color: {{ $drinkAccentColor }};">Acompañar con</h4>
                <ul id="pairingList" class="list-disc list-inside text-gray-700"></ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal promocional de pop-ups -->
<div id="drinkPopupModal" tabindex="-1" aria-hidden="true" role="dialog" aria-modal="true"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-black/70">
    <div class="relative w-full max-w-3xl">
        <div class="bg-white rounded-3xl shadow-lg text-slate-900 p-4 relative">
            <button onclick="closeDrinkPopup()" class="absolute top-4 right-4 text-2xl text-slate-500 hover:text-slate-900">&times;</button>
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
        const now = new Date();
        const today = now.getDay();

        popups.forEach(popup => {
            const start = popup.start_date ? new Date(popup.start_date) : null;
            const end = popup.end_date ? new Date(popup.end_date) : null;
            const repeatDays = popup.repeat_days ? popup.repeat_days.split(',').map(day => parseInt(day, 10)) : [];
            const withinDates = (!start || now >= start) && (!end || now <= end);
            const matchesDay = repeatDays.length === 0 || repeatDays.includes(today);

            if (popup.active && popup.view === 'coffee' && withinDates && matchesDay) {
                showDrinkPopup(popup);
            }
        });
    });

    function openDrinkModal(el) {
        const fallbackImage = "{{ asset('storage/' . ($settings->logo ?? 'default-logo.png')) }}";
        const name = el.dataset.name;
        const description = el.dataset.description;
        const price = el.dataset.price;
        const image = el.dataset.image && !el.dataset.image.endsWith('/storage/') ? el.dataset.image : fallbackImage;
        const region = el.dataset.region;
        const method = el.dataset.method;
        const notes = el.dataset.notes;
        const pairings = el.dataset.pairings;

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
                    link.className = 'text-amber-500 hover:underline';
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
