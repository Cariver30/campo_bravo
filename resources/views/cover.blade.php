<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Página de Inicio</title>

    <!-- Tailwind + Flowbite -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        :root {
            --accent-color: {{ $settings->button_color_cover ?? '#FF5722' }};
            --cover-text-color: {{ $settings->text_color_cover ?? '#ffffff' }};
        }
        body {
            font-family: {{ $settings->font_family_cover ?? 'ui-sans-serif' }};
            @if($settings && $settings->background_image_cover)
                background: url('{{ asset('storage/' . $settings->background_image_cover) }}') no-repeat center center fixed;
            @endif
            background-size: cover;
        }
        .vip-button {
            position: relative;
            width: 12rem;
            height: 3rem;
            border-radius: 9999px;
            font-weight: 600;
            color: #fff;
            background: var(--accent-color);
            transition: transform .2s ease, box-shadow .2s ease;
            animation: vip-glow 1.5s infinite;
            overflow: hidden;
        }
        .vip-button::after {
            content: '';
            position: absolute;
            inset: 4px;
            border-radius: 9999px;
            border: 2px dashed rgba(255,255,255,0.65);
            animation: vip-blink 2s linear infinite;
            pointer-events: none;
        }
        .vip-button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 18px rgba(255,255,255,0.35);
        }
        @keyframes vip-glow {
            0%, 100% { box-shadow: 0 0 12px rgba(255,255,255,0.15); }
            50% { box-shadow: 0 0 20px rgba(255,255,255,0.45); }
        }
        @keyframes vip-blink {
            0% { opacity: 0.25; }
            50% { opacity: 1; }
            100% { opacity: 0.25; }
        }
    </style>
</head>
@php
    if (!function_exists('cover_card_color')) {
        function cover_card_color(?string $hex, $opacity)
        {
            $opacity = is_numeric($opacity) ? max(0, min(1, $opacity)) : 0.85;
            if (!$hex) {
                return "rgba(0,0,0,{$opacity})";
            }
            $hex = ltrim($hex, '#');
            if (strlen($hex) === 3) {
                $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
            }
            if (strlen($hex) !== 6) {
                return "rgba(0,0,0,{$opacity})";
            }
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            return "rgba({$r},{$g},{$b},{$opacity})";
        }
    }
    $coverCardBackground = cover_card_color($settings->card_bg_color_cover ?? null, $settings->card_opacity_cover ?? 0.85);
@endphp
<body class="relative min-h-screen bg-black/50 text-white flex flex-col items-center justify-center">

    <!-- Logo centrado arriba -->
    <div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50">
        <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo del Restaurante" class="w-52 max-w-xs mx-auto">
    </div>

    <!-- Contenedor central -->
    <main class="z-20 w-full px-4">
        @if(session('notification_success'))
            <div id="subscriptionStatus" class="max-w-md mx-auto mb-6 bg-emerald-500/20 border border-emerald-400/30 rounded-2xl px-4 py-3 text-sm text-emerald-100">
                {{ session('notification_success') }}
            </div>
        @endif

        <div class="max-w-6xl mx-auto space-y-10" style="color: var(--cover-text-color);">
            <section class="rounded-3xl p-8 backdrop-blur space-y-8 border border-white/10" style="background-color: {{ $coverCardBackground }};">
                <div class="flex flex-col lg:flex-row gap-8">
                    <div class="flex-1 space-y-4">
                        <p class="text-amber-300 uppercase tracking-[0.45em] text-xs">Café · desayuno · brunch</p>
                        <h1 class="text-4xl lg:text-5xl font-semibold leading-tight" style="font-family: {{ $settings->font_family_cover ?? 'ui-sans-serif' }};">
                            Bienvenido a Café Negro. Aquí el visitante decide rápido a qué experiencia ir.
                        </h1>
                        <p class="text-white/75 text-lg">Todos los colores, tipografías y textos provienen del panel de configuraciones. Ajusta allá y verás los cambios inmediatamente.</p>
                    </div>
                    <div class="w-full max-w-md space-y-4 bg-white/5 border border-white/10 rounded-2xl p-5">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-white/60 mb-1">Horarios</p>
                            <p class="text-white/80 whitespace-pre-line text-sm">{{ $settings->business_hours ?? "Viernes y sábado 12pm – 10pm\nDomingo 12pm – 8pm" }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-sm text-white/80">
                            <div class="rounded-xl border border-white/15 p-3">
                                <p class="text-white/60 uppercase text-xs tracking-[0.3em] mb-1">Teléfono</p>
                                <p>{{ $settings->phone_number ?? '787-000-0000' }}</p>
                            </div>
                            <div class="rounded-xl border border-white/15 p-3">
                                <p class="text-white/60 uppercase text-xs tracking-[0.3em] mb-1">Ubicación</p>
                                <p>Café Negro · Miramar</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-4">
                    <article class="bg-white/5 border border-white/10 rounded-2xl p-5">
                        <p class="text-xs uppercase tracking-[0.3em] text-white/60 mb-2">Origen invitado</p>
                        <h3 class="text-2xl font-semibold">Huehuetenango</h3>
                        <p class="text-white/70 text-sm">Notas a cacao oscuro, miel y flor de azahar.</p>
                    </article>
                    <article class="bg-white/5 border border-white/10 rounded-2xl p-5">
                        <p class="text-xs uppercase tracking-[0.3em] text-white/60 mb-2">Método estrella</p>
                        <h3 class="text-2xl font-semibold">V60</h3>
                        <p class="text-white/70 text-sm">Extracción lenta con servicio en mesa.</p>
                    </article>
                    <article class="bg-white/5 border border-white/10 rounded-2xl p-5">
                        <p class="text-xs uppercase tracking-[0.3em] text-white/60 mb-2">Experiencia</p>
                        <h3 class="text-2xl font-semibold">Brunch Board</h3>
                        <p class="text-white/70 text-sm">Maridaje salado/dulce + flight de bebidas.</p>
                    </article>
                </div>

                <div class="grid md:grid-cols-3 gap-4 pt-2">
                @php
                    $defaultCaptions = [
                        'Café de origen servido en mesa',
                        'Desayunos y brunch artesanales',
                        'Mimosas y mocktails frescos',
                    ];
                    $heroImages = collect([
                        ['src' => $settings->cover_gallery_image_1 ?? null],
                        ['src' => $settings->cover_gallery_image_2 ?? null],
                        ['src' => $settings->cover_gallery_image_3 ?? null],
                    ])->filter(fn($img) => !empty($img['src']))->values();
                    if ($heroImages->isEmpty()) {
                        $heroImages = collect([
                            ['src' => 'https://images.unsplash.com/photo-1507133750040-4a8f57021571?auto=format&fit=crop&w=800&q=80'],
                            ['src' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=800&q=80'],
                            ['src' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80'],
                        ]);
                    }
                    $heroImages = $heroImages->map(function ($image, $index) use ($defaultCaptions) {
                        $image['caption'] = $image['caption'] ?? ($defaultCaptions[$index] ?? $defaultCaptions[0]);
                        return $image;
                    });
                @endphp
                @foreach($heroImages as $image)
                    <figure class="overflow-hidden rounded-2xl border border-white/10 bg-white/5">
                        <img src="{{ $image['src'] }}" alt="{{ $image['caption'] }}" class="w-full h-48 object-cover">
                            <figcaption class="px-4 py-3 text-sm text-white/80">{{ $image['caption'] }}</figcaption>
                        </figure>
                    @endforeach
                </div>
            </section>

            <section class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $ctaLabel = function ($value, $default) {
                        if (is_null($value)) {
                            return $default;
                        }
                        $trimmed = trim($value);
                        return $trimmed === '' ? null : $trimmed;
                    };
                    $ctaCards = collect([
                        ['title' => $ctaLabel($settings->button_label_menu ?? null, 'Menú'), 'subtitle' => 'Carta principal', 'copy' => 'Brunch, platos signature y acompañantes.', 'action' => url('/menu'), 'image' => $settings->cta_image_menu ? asset('storage/' . $settings->cta_image_menu) : null],
                        ['title' => $ctaLabel($settings->button_label_wines ?? null, 'Cafe'), 'subtitle' => 'Barra de especialidad', 'copy' => 'Filtrados, bebidas frías y vuelos guiados.', 'action' => url('/coffee'), 'image' => $settings->cta_image_cafe ? asset('storage/' . $settings->cta_image_cafe) : null],
                        ['title' => $ctaLabel($settings->button_label_cocktails ?? null, 'Cócteles'), 'subtitle' => 'Mixología', 'copy' => 'Cócteles tropicales, mocktails y clásicos.', 'action' => url('/cocktails'), 'image' => $settings->cta_image_cocktails ? asset('storage/' . $settings->cta_image_cocktails) : null],
                        ['title' => $ctaLabel($settings->button_label_events ?? null, 'Eventos especiales'), 'subtitle' => 'Calendario', 'copy' => 'Pop-ups, catas privadas y residencias.', 'action' => route('experiences.index'), 'image' => $settings->cta_image_events ? asset('storage/' . $settings->cta_image_events) : null],
                        ['title' => $ctaLabel($settings->button_label_reservations ?? null, 'Reservas'), 'subtitle' => 'Agenda', 'copy' => 'Reserva tu mesa o un flight privado.', 'action' => route('reservations.app'), 'image' => $settings->cta_image_reservations ? asset('storage/' . $settings->cta_image_reservations) : null],
                    ])->filter(fn($card) => filled($card['title']))->values();
                @endphp
                @foreach($ctaCards as $card)
                    <article class="bg-white/5 border border-white/10 rounded-2xl p-0 overflow-hidden flex flex-col" style="background-color: {{ $coverCardBackground }};">
                        @if(!empty($card['image']))
                            <div class="h-40 overflow-hidden">
                                <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="p-5 flex flex-col gap-3">
                        <p class="text-xs uppercase tracking-[0.35em] text-white/60">{{ $card['subtitle'] }}</p>
                        <h3 class="text-2xl font-semibold">{{ $card['title'] }}</h3>
                        <p class="text-white/70 text-sm flex-1">{{ $card['copy'] }}</p>
                        <button onclick="window.location.href='{{ $card['action'] }}'"
                                class="w-full rounded-full py-3 font-semibold transition"
                                style="background-color: var(--accent-color); font-size: {{ $settings->button_font_size_cover ?? 18 }}px;">
                            Abrir sección
                        </button>
                        </div>
                    </article>
                @endforeach
                <article class="bg-white/5 border border-white/10 rounded-2xl p-5 flex flex-col gap-3" style="background-color: {{ $coverCardBackground }};">
                    <p class="text-xs uppercase tracking-[0.3em] text-white/60">Lista VIP</p>
                    <p class="text-white/80 text-sm flex-1">Recibe lanzamientos de micro lotes, eventos privados y cenas a puerta cerrada.</p>
                    <button id="openNotifyModal"
                            class="vip-button"
                            style="font-size: {{ $settings->button_font_size_cover ?? 18 }}px;">
                        {{ $settings->button_label_vip ?? 'Lista VIP' }}
                    </button>
                    <p id="notifyStatus" class="text-xs text-white/70 hidden">Ya estás suscrito a las alertas ✉️</p>
                </article>
            </section>

            <section class="grid lg:grid-cols-[minmax(0,1fr)_320px] gap-10">
                @php $initialGroup = $featuredGroups->first(); @endphp
                <div class="bg-white/5 border border-white/10 rounded-3xl p-6 backdrop-blur space-y-6">
                    <div>
                        <p class="text-xs uppercase tracking-[0.4em] text-white/60">Lo más vendido</p>
                        <h3 class="text-3xl font-semibold">{{ $initialGroup['title'] ?? 'Selección del chef' }}</h3>
                        <p class="text-white/70 text-sm">{{ $initialGroup['subtitle'] ?? 'Los favoritos de la semana.' }}</p>
                    </div>
                    @if($featuredGroups->isNotEmpty())
                        <div class="flex flex-wrap gap-3 text-sm text-white/80">
                            @foreach($featuredGroups as $group)
                                <button class="px-4 py-2 rounded-full border hover:bg-white/10 transition {{ $loop->first ? 'bg-white/15 border-white/60' : '' }}"
                                        data-featured-tab="{{ $group['slug'] }}"
                                        style="border-color: {{ $settings->button_color_cover ?? '#ffffff' }}44; color: var(--cover-text-color);">
                                    {{ $group['title'] }}
                                </button>
                            @endforeach
                        </div>
                        <div class="space-y-6">
                            <div>
                                <p id="featuredTag" class="text-xs uppercase tracking-[0.35em] text-amber-300 mb-2">{{ $initialGroup['subtitle'] ?? '' }}</p>
                                <h3 id="featuredTitle" class="text-3xl font-semibold">{{ $initialGroup['title'] ?? 'Sin datos' }}</h3>
                                <p id="featuredDescription" class="text-white/70 mt-2">{{ $initialGroup['source_label'] ?? '' }}</p>
                            </div>
                            <div id="featuredItems" class="space-y-4">
                                @forelse($initialGroup['items'] ?? [] as $item)
                                    <a href="{{ $item['link'] ?? '#' }}" class="flex items-start justify-between gap-4 pb-3 border-b border-white/10 group">
                                        <div class="flex items-start gap-3">
                                            @if(!empty($item['image']))
                                                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-14 h-14 rounded-xl object-cover border border-white/20">
                                            @else
                                                <div class="w-14 h-14 rounded-xl border border-white/20 flex items-center justify-center text-lg">☆</div>
                                            @endif
                                            <div>
                                                <p class="text-lg font-semibold group-hover:text-amber-200 transition">{{ $item['title'] }}</p>
                                                <p class="text-white/60 text-sm">{{ $item['subtitle'] }}</p>
                                            </div>
                                        </div>
                                        @if(!empty($item['price']))
                                            <span class="text-amber-300 font-semibold">${{ number_format($item['price'], 2) }}</span>
                                        @endif
                                    </a>
                                @empty
                                    <p class="text-white/60 text-sm">Marca platos o bebidas como destacados dentro de la categoría seleccionada.</p>
                                @endforelse
                            </div>
                        </div>
                    @else
                        <div class="p-6 border border-white/10 rounded-2xl bg-black/20">
                            <p class="text-white/70 text-sm">Estamos preparando nuevas experiencias. Vuelve pronto para descubrir los rituales de café y brunch más pedidos.</p>
                        </div>
                    @endif
                </div>
                <aside class="bg-gradient-to-br from-amber-500/30 via-orange-400/20 to-rose-400/20 border border-white/10 rounded-3xl p-6">
                    <p class="text-xs uppercase tracking-[0.4em] text-white/60 mb-3">Barista highlight</p>
                    <h4 class="text-2xl font-semibold mb-2">Espresso + Tonic</h4>
                    <p class="text-white/80 mb-6">Shot doble, reducción cítrica y espuma de mandarina. Refrescante y brillante para quienes buscan energía fría.</p>
                    <ul class="space-y-3 text-sm text-white/80">
                        <li class="flex items-center gap-2"><i class="fas fa-mug-hot text-white"></i> Espresso etíope natural</li>
                        <li class="flex items-center gap-2"><i class="fas fa-ice-cubes text-white"></i> Tonic botánico</li>
                        <li class="flex items-center gap-2"><i class="fas fa-lemon text-white"></i> Ralladura cítrica y bitters</li>
                    </ul>
                </aside>
            </section>
        </div>
    </main>

    <!-- Redes sociales abajo -->
    <footer class="fixed bottom-6 left-0 right-0 z-40">
        <div class="flex justify-center gap-6">
            <a href="{{ $settings->facebook_url ?? '#' }}" target="_blank" 
               class="w-12 h-12 bg-[{{ $settings->button_color_cover ?? '#000' }}] text-white flex items-center justify-center rounded-full transition hover:scale-110 hover:bg-white hover:text-black">
                <i class="fab fa-facebook-f"></i>
            </a>
            
            <a href="{{ $settings->instagram_url ?? '#' }}" target="_blank" 
               class="w-12 h-12 bg-[{{ $settings->button_color_cover ?? '#000' }}] text-white flex items-center justify-center rounded-full transition hover:scale-110 hover:bg-white hover:text-black">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="tel:{{ $settings->phone_number ?? '#' }}" 
               class="w-12 h-12 bg-[{{ $settings->button_color_cover ?? '#000' }}] text-white flex items-center justify-center rounded-full transition hover:scale-110 hover:bg-white hover:text-black">
                <i class="fas fa-phone"></i>
            </a>
        </div>
    </footer>

    <!-- Modal de notificación -->
    <div id="notifyModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center px-4 {{ ($errors->has('name') || $errors->has('email')) ? '' : 'hidden' }} z-50">
        <div class="bg-white text-slate-900 rounded-3xl w-full max-w-md p-6 relative">
            <button id="closeNotifyModal" class="absolute top-4 right-4 text-2xl text-slate-500 hover:text-slate-800">&times;</button>
            <p class="text-xs uppercase tracking-[0.35em] text-amber-500 mb-2">Experiencias</p>
            <h2 class="text-2xl font-semibold mb-2">Recibe las alertas VIP</h2>
            <p class="text-sm text-slate-500 mb-4">Entérate primero de nuevas experiencias, cenas especiales y eventos privados.</p>
            <form action="{{ route('experiences.notify.cover') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <input type="text" name="name" placeholder="Tu nombre" value="{{ old('name') }}"
                           class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <input type="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}"
                           class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-slate-900 text-white py-3 rounded-2xl font-semibold hover:bg-slate-800 transition">
                    Quiero recibir noticias
                </button>
                <p class="text-xs text-slate-400 text-center">Prometemos solo enviar experiencias relevantes.</p>
            </form>
        </div>
    </div>

    @php
        $featuredGroupsPayload = $featuredGroups->mapWithKeys(function ($group) {
            $items = collect($group['items'] ?? [])->map(function ($item) {
                return [
                    'title' => $item['title'] ?? '',
                    'subtitle' => $item['subtitle'] ?? '',
                    'price' => isset($item['price']) ? number_format($item['price'], 2) : null,
                    'image' => $item['image'] ?? null,
                    'link' => $item['link'] ?? '#',
                ];
            });

            return [
                $group['slug'] => [
                    'title' => $group['title'] ?? '',
                    'subtitle' => $group['subtitle'] ?? '',
                    'source' => $group['source_label'] ?? '',
                    'items' => $items,
                ],
            ];
        });
    @endphp
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('notifyModal');
            const openBtn = document.getElementById('openNotifyModal');
            const closeBtn = document.getElementById('closeNotifyModal');
            const statusBadge = document.getElementById('notifyStatus');
            const flash = document.getElementById('subscriptionStatus');
            const isRegistered = localStorage.getItem('eventNotifyRegistered') === '1';

            const openModal = () => {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };
            const closeModal = () => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            openBtn?.addEventListener('click', openModal);
            closeBtn?.addEventListener('click', closeModal);
            modal?.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });

            if (flash) {
                localStorage.setItem('eventNotifyRegistered', '1');
            }

            if (isRegistered && statusBadge) {
                statusBadge.classList.remove('hidden');
                openBtn.textContent = 'Actualizar datos';
            } else if (window.innerWidth < 768 && !modal.classList.contains('hidden')) {
                // already open due to errors
            } else if (window.innerWidth < 768 && !isRegistered) {
                setTimeout(() => {
                    if (modal.classList.contains('hidden')) {
                        openModal();
                    }
                }, 2000);
            }

            const featuredData = @json($featuredGroupsPayload);

            const featuredButtons = document.querySelectorAll('[data-featured-tab]');
            const tagEl = document.getElementById('featuredTag');
            const titleEl = document.getElementById('featuredTitle');
            const descriptionEl = document.getElementById('featuredDescription');
            const itemsEl = document.getElementById('featuredItems');

            const renderFeatured = (slug) => {
                const group = featuredData[slug];
                if (!group || !tagEl || !titleEl || !descriptionEl || !itemsEl) {
                    return;
                }

                tagEl.textContent = group.subtitle || '';
                titleEl.textContent = group.title || '';
                descriptionEl.textContent = group.source || '';
                itemsEl.innerHTML = (group.items || []).length
                    ? group.items.map(item => `
                        <a href="${item.link || '#'}" class="flex items-start justify-between gap-4 pb-3 border-b border-white/10 group">
                            <div class="flex items-start gap-3">
                                ${item.image
                                    ? `<img src="${item.image}" alt="${item.title || ''}" class="w-14 h-14 rounded-xl object-cover border border-white/20">`
                                    : `<div class="w-14 h-14 rounded-xl border border-white/20 flex items-center justify-center text-lg">☆</div>`
                                }
                                <div>
                                    <p class="text-lg font-semibold group-hover:text-amber-200 transition">${item.title ?? ''}</p>
                                    <p class="text-white/60 text-sm">${item.subtitle ?? ''}</p>
                                </div>
                            </div>
                            ${item.price ? `<span class="text-amber-300 font-semibold">$${item.price}</span>` : ''}
                        </a>
                    `).join('')
                    : '<p class="text-white/60 text-sm">Agrega elementos destacados desde el panel para mostrarlos aquí.</p>';

                featuredButtons.forEach(btn => {
                    if (btn.dataset.featuredTab === slug) {
                        btn.classList.add('bg-white/15', 'border-white/60');
                    } else {
                        btn.classList.remove('bg-white/15', 'border-white/60');
                    }
                });
            };

            featuredButtons.forEach(btn => {
                btn.addEventListener('click', () => renderFeatured(btn.dataset.featuredTab));
            });

            if (featuredButtons.length) {
                renderFeatured(featuredButtons[0].dataset.featuredTab);
            }

            // If validation errors opened the modal, highlight status
            if (!modal.classList.contains('hidden')) {
                document.body.classList.add('overflow-hidden');
            }
        });
    </script>

</body>
</html>
