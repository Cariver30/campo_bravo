<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Página de Inicio</title>
    <meta name="description" content="Prosecco Restaurante ofrece cocina creativa dentro del campo de golf de Bayamón, combinando ingredientes frescos, cava curada y experiencias gastronómicas únicas.">

    <!-- Tailwind + Flowbite -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    @php
        if (!function_exists('cover_card_color')) {
            function cover_card_color(?string $hex, $opacity)
            {
                $opacity = is_numeric($opacity) ? max(0, min(1, $opacity)) : 0.85;
                if (!$hex) {
                    return "rgba(57,125,181,{$opacity})";
                }
                $hex = ltrim($hex, '#');
                if (strlen($hex) === 3) {
                    $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
                }
                if (strlen($hex) !== 6) {
                    return "rgba(57,125,181,{$opacity})";
                }
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
                return "rgba({$r},{$g},{$b},{$opacity})";
            }
        }
        $palette = [
            'blue' => '#397db5',
            'cream' => '#fff2b3',
            'violet' => '#762d79',
            'amber' => '#ffb723',
        ];
        $coverBaseColor = $settings->text_color_cover ?? $palette['cream'];
        $coverBodyColor = $settings->text_color_cover_secondary ?? $coverBaseColor;
        $coverCardBackground = $settings->card_bg_color_cover
            ? cover_card_color($settings->card_bg_color_cover, $settings->card_opacity_cover ?? 0.85)
            : 'rgba(57, 125, 181, 0.25)';
        $coverBackgroundDisabled = (bool) ($settings->disable_background_cover ?? false);
        $pointsPerVisit = $settings->loyalty_points_per_visit ?? 10;
        $showLoyaltyCard = $settings->show_cover_loyalty_card ?? true;
        $loyaltyLabel = trim($settings->cover_loyalty_label ?? '') ?: 'Fidelidad';
        $loyaltyTitle = trim($settings->cover_loyalty_title ?? '') ?: "Suma {$pointsPerVisit} pts por visita";
        $loyaltyCopy = trim($settings->cover_loyalty_description ?? '') ?: 'Escanea el QR del mesero y canjea tus visitas por experiencias personalizadas.';
    @endphp
    <style>
        :root {
            --accent-color: {{ $settings->button_color_cover ?? $palette['amber'] }};
            --cover-heading-color: {{ $coverBaseColor }};
            --cover-body-color: {{ $coverBodyColor }};
            --cover-body-soft: {{ cover_card_color($coverBodyColor, 0.65) }};
            --cover-blue: {{ $palette['blue'] }};
            --cover-violet: {{ $palette['violet'] }};
            --cover-cream: {{ $palette['cream'] }};
        }
        body {
            font-family: {{ $settings->font_family_cover ?? 'ui-sans-serif' }};
            color: var(--cover-body-color);
            @if($coverBackgroundDisabled)
                background: transparent;
            @elseif($settings && $settings->background_image_cover)
                background: none;
            @else
                background: linear-gradient(140deg, var(--cover-blue) 0%, var(--cover-violet) 70%);
            @endif
            background-size: cover;
            min-height: 100vh;
        }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            width: 100vw;
            height: 100vh;
            @if($coverBackgroundDisabled)
                display: none;
            @elseif($settings && $settings->background_image_cover)
                background: url('{{ asset('storage/' . $settings->background_image_cover) }}') no-repeat center center;
                background-size: cover;
            @else
                background: radial-gradient(circle at 20% 20%, rgba(255, 242, 179, 0.2), rgba(118, 45, 121, 0.3) 50%, rgba(57, 125, 181, 0.35));
            @endif
        }
        .cover-theme {
            color: var(--cover-body-color);
        }
        .cover-text-primary {
            color: var(--cover-heading-color);
        }
        .cover-text-muted {
            color: var(--cover-body-color);
        }
        .cover-text-soft {
            color: var(--cover-body-soft);
        }
        .vip-button {
            position: relative;
            width: 12rem;
            height: 3rem;
            border-radius: 9999px;
            font-weight: 600;
            color: {{ $palette['violet'] }};
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
            border: 2px dashed rgba(255, 242, 179, 0.65);
            animation: vip-blink 2s linear infinite;
            pointer-events: none;
        }
        .vip-button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 18px rgba(255, 183, 35, 0.35);
        }
        @keyframes vip-glow {
            0%, 100% { box-shadow: 0 0 12px rgba(255, 242, 179, 0.15); }
            50% { box-shadow: 0 0 20px rgba(255, 183, 35, 0.45); }
        }
        @keyframes vip-blink {
            0% { opacity: 0.25; }
            50% { opacity: 1; }
            100% { opacity: 0.25; }
        }
        .card-surface {
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 242, 179, 0.2);
            border-radius: 1.5rem;
        }
        .info-card {
            background-color: rgba(57, 125, 181, 0.18);
            border: 1px solid rgba(118, 45, 121, 0.25);
            border-radius: 1rem;
        }
        .social-icon {
            background-color: var(--accent-color);
            color: {{ $palette['violet'] }};
            transition: transform .2s ease, background-color .2s ease, color .2s ease;
        }
        .social-icon:hover {
            background-color: var(--cover-cream);
            color: {{ $palette['violet'] }};
            transform: scale(1.05);
        }
        .modal-overlay {
            background-color: rgba(57, 125, 181, 0.55);
        }
        .modal-surface {
            background-color: {{ $palette['cream'] }};
            color: {{ $palette['violet'] }};
        }
        .vip-modal {
            width: min(92vw, 420px);
            max-height: 90vh;
            overflow-y: auto;
        }
        .form-input {
            border: 1px solid rgba(118, 45, 121, 0.3);
            border-radius: 1rem;
            padding: 0.65rem 1rem;
            color: {{ $palette['violet'] }};
            background-color: rgba(255, 242, 179, 0.35);
        }
        .form-input:focus {
            outline: 2px solid var(--accent-color);
        }
    </style>
</head>
<body class="relative min-h-screen flex flex-col items-center cover-theme">

    <header class="w-full py-6 flex justify-center z-30">
        <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo del Restaurante" class="w-52 max-w-xs mx-auto drop-shadow-lg">
    </header>

    <!-- Contenedor central -->
    <main class="z-20 w-full px-4 pb-16">
        @if(session('notification_success'))
            <div id="subscriptionStatus" class="max-w-md mx-auto mb-6 rounded-2xl px-4 py-3 text-sm"
                 style="background-color: rgba(57, 125, 181, 0.25); border: 1px solid rgba(118, 45, 121, 0.3); color: {{ $palette['cream'] }};">
                {{ session('notification_success') }}
            </div>
        @endif

        <div class="max-w-6xl mx-auto space-y-10" style="color: var(--cover-body-color);">
            <section class="rounded-3xl p-8 backdrop-blur space-y-8 border"
                     style="background-color: {{ $coverCardBackground }}; border-color: rgba(255, 242, 179, 0.2);">
                <div class="flex flex-col lg:flex-row gap-8">
                    @php
                        $heroKicker = trim($settings->cover_hero_kicker ?? '') ?: 'Café · desayuno · brunch';
                        $heroTitle = trim($settings->cover_hero_title ?? '') ?: 'Bienvenido a Café Negro. Aquí el visitante decide rápido a qué experiencia ir.';
                        $heroParagraph = trim($settings->cover_hero_paragraph ?? '') ?: 'Todos los colores, tipografías y textos provienen del panel de configuraciones. Ajusta allá y verás los cambios inmediatamente.';
                        $locationText = trim($settings->cover_location_text ?? '') ?: 'Café Negro · Miramar';
                    @endphp
                    <div class="flex-1 space-y-4">
                        <p class="uppercase tracking-[0.45em] text-xs" style="color: {{ $palette['amber'] }};">{{ $heroKicker }}</p>
                        <h1 class="text-4xl lg:text-5xl font-semibold leading-tight cover-text-primary" style="font-family: {{ $settings->font_family_cover ?? 'ui-sans-serif' }};">
                            {{ $heroTitle }}
                        </h1>
                        <p class="cover-text-muted text-lg">{{ $heroParagraph }}</p>
                    </div>
                    <div class="w-full max-w-md space-y-4">
                        <article class="space-y-4 rounded-2xl p-5 card-surface"
                                 style="background-color: rgba(57, 125, 181, 0.18);">
                            <div>
                                <p class="text-xs uppercase tracking-[0.35em] cover-text-soft mb-1">Horarios</p>
                                <p class="cover-text-muted whitespace-pre-line text-sm">{{ $settings->business_hours ?? "Viernes y sábado 12pm – 10pm\nDomingo 12pm – 8pm" }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm cover-text-muted">
                                <div class="info-card p-3">
                                    <p class="cover-text-soft uppercase text-xs tracking-[0.3em] mb-1">Teléfono</p>
                                    <p>{{ $settings->phone_number ?? '787-000-0000' }}</p>
                                </div>
                                <div class="info-card p-3">
                                    <p class="cover-text-soft uppercase text-xs tracking-[0.3em] mb-1">Ubicación</p>
                                    <p>{{ $locationText }}</p>
                                </div>
                            </div>
                        </article>
                        @if($showLoyaltyCard)
                            <article class="card-surface rounded-2xl p-5 flex flex-col gap-3"
                                     style="background-color: rgba(118, 45, 121, 0.25);">
                                <div class="flex items-start gap-3">
                                    <svg viewBox="0 0 64 64" class="w-10 h-10" style="color: {{ $palette['amber'] }};">
                                        <circle cx="32" cy="32" r="30" stroke="currentColor" stroke-width="2" fill="none"></circle>
                                        <path d="M18 32h28M32 18v28" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.35em] cover-text-soft mb-1">{{ $loyaltyLabel }}</p>
                                        <h3 class="text-xl font-semibold cover-text-primary">{{ $loyaltyTitle }}</h3>
                                        <p class="cover-text-muted text-sm">{{ $loyaltyCopy }}</p>
                                    </div>
                                </div>
                            </article>
                        @endif
                    </div>
                </div>
            </section>

            @php
                $initialGroup = $featuredGroups->first();
                $featuredCardBgHex = $settings->featured_card_bg_color ?? $palette['violet'];
                $featuredCardBg = cover_card_color($featuredCardBgHex, 0.65);
                $featuredCardText = $settings->featured_card_text_color ?? $palette['cream'];
                $featuredMutedText = cover_card_color($featuredCardText, 0.75);
                $featuredBorderColor = cover_card_color($featuredCardText, 0.2);
                $featuredTabBgHex = $settings->featured_tab_bg_color ?? $palette['amber'];
                $featuredTabBg = cover_card_color($featuredTabBgHex, 0.2);
                $featuredTabText = $settings->featured_tab_text_color ?? $palette['violet'];
            @endphp

            <section class="rounded-3xl p-6 backdrop-blur space-y-6 border" style="background-color: {{ $featuredCardBg }}; color: {{ $featuredCardText }}; border-color: {{ $featuredBorderColor }}; font-family: {{ $settings->font_family_cover ?? 'inherit' }};">
                <div>
                    <p class="text-xs uppercase tracking-[0.4em]" style="color: {{ $featuredMutedText }};">Lo más vendido</p>
                    <h3 class="text-3xl font-semibold">{{ $initialGroup['title'] ?? 'Selección del chef' }}</h3>
                    <p class="text-sm" style="color: {{ $featuredMutedText }};">{{ $initialGroup['subtitle'] ?? 'Los favoritos de la semana.' }}</p>
                </div>
                @if($featuredGroups->isNotEmpty())
                    <div class="flex flex-wrap gap-3 text-sm">
                        @foreach($featuredGroups as $group)
                            <button class="px-4 py-2 rounded-full transition"
                                    data-featured-tab="{{ $group['slug'] }}"
                                    data-active-bg="{{ $featuredTabBg }}"
                                    data-inactive-bg="transparent"
                                    data-text="{{ $featuredCardText }}"
                                    data-border="{{ $featuredBorderColor }}"
                                    style="border: 1px solid {{ $featuredBorderColor }}; color: {{ $featuredCardText }}; background-color: {{ $loop->first ? $featuredTabBg : 'transparent' }};">
                                {{ $group['title'] }}
                            </button>
                        @endforeach
                    </div>
                    <div class="space-y-6">
                        <div>
                            <p id="featuredTag" class="text-xs uppercase tracking-[0.35em] mb-2" style="color: {{ $featuredMutedText }};">{{ $initialGroup['subtitle'] ?? '' }}</p>
                            <h3 id="featuredTitle" class="text-3xl font-semibold">{{ $initialGroup['title'] ?? 'Sin datos' }}</h3>
                            <p id="featuredDescription" class="mt-2" style="color: {{ $featuredMutedText }};">{{ $initialGroup['source_label'] ?? '' }}</p>
                        </div>
                        <div id="featuredItems" class="space-y-4">
                            @forelse($initialGroup['items'] ?? [] as $item)
                                <a href="{{ $item['link'] ?? '#' }}" class="flex items-start justify-between gap-4 pb-3 group" style="color: {{ $featuredCardText }}; border-bottom: 1px solid {{ $featuredBorderColor }};">
                                    <div class="flex items-start gap-3">
                                        @if(!empty($item['image']))
                                            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-14 h-14 rounded-xl object-cover border" style="border-color: {{ $featuredBorderColor }};">
                                        @else
                                            <div class="w-14 h-14 rounded-xl flex items-center justify-center text-lg" style="border:1px solid {{ $featuredBorderColor }};">☆</div>
                                        @endif
                                        <div>
                                            <p class="text-lg font-semibold">{{ $item['title'] }}</p>
                                            <p class="text-sm" style="color: {{ $featuredMutedText }};">{{ $item['subtitle'] }}</p>
                                        </div>
                                    </div>
                                    @if(!empty($item['price']))
                                        <span class="font-semibold" style="color: {{ $settings->button_color_cover ?? $featuredCardText }};">${{ number_format($item['price'], 2) }}</span>
                                    @endif
                                </a>
                            @empty
                                <p class="cover-text-soft text-sm">Marca platos o bebidas como destacados dentro de la categoría seleccionada.</p>
                            @endforelse
                        </div>
                    </div>
                @else
                    <div class="p-6 border rounded-2xl"
                         style="border-color: rgba(255, 242, 179, 0.2); background-color: rgba(57, 125, 181, 0.15);">
                        <p class="text-sm">Estamos preparando nuevas experiencias. Vuelve pronto para descubrir los rituales de café y brunch más pedidos.</p>
                    </div>
                @endif

            </section>

            <section class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-10">
                @php
                    $ctaLabel = function ($value, $default) {
                        if (is_null($value)) {
                            return $default;
                        }
                        $trimmed = trim($value);
                        return $trimmed === '' ? null : $trimmed;
                    };
                    $ctaText = function (string $key, string $field, string $default) use ($settings) {
                        $value = $settings->{'cover_cta_'.$key.'_'.$field} ?? null;
                        $value = is_string($value) ? trim($value) : '';
                        return $value === '' ? $default : $value;
                    };
                    $ctaCards = collect([
                        ['key' => 'menu', 'title' => $ctaLabel($settings->button_label_menu ?? $settings->tab_label_menu ?? null, 'Menú'), 'subtitle' => $ctaText('menu', 'subtitle', 'Carta principal'), 'copy' => $ctaText('menu', 'copy', 'Brunch, platos signature y acompañantes.'), 'button_label' => $ctaText('menu', 'button_text', 'Abrir sección'), 'action' => url('/menu'), 'image' => $settings->cta_image_menu ? asset('storage/' . $settings->cta_image_menu) : null, 'visible' => $settings->show_cta_menu ?? true, 'type' => 'link'],
                        ['key' => 'cafe', 'title' => $ctaLabel($settings->button_label_wines ?? $settings->tab_label_wines ?? null, 'Cava de vinos'), 'subtitle' => $ctaText('cafe', 'subtitle', 'Cava de vinos'), 'copy' => $ctaText('cafe', 'copy', 'Más de 90 etiquetas, flights guiados y sommeliers on demand.'), 'button_label' => $ctaText('cafe', 'button_text', 'Abrir sección'), 'action' => url('/cava'), 'image' => $settings->cta_image_cafe ? asset('storage/' . $settings->cta_image_cafe) : null, 'visible' => $settings->show_cta_cafe ?? true, 'type' => 'link'],
                        ['key' => 'cocktails', 'title' => $ctaLabel($settings->tab_label_cocktails ?? $settings->button_label_cocktails ?? null, 'Cócteles'), 'subtitle' => $ctaText('cocktails', 'subtitle', 'Mixología'), 'copy' => $ctaText('cocktails', 'copy', 'Cócteles tropicales, mocktails y clásicos.'), 'button_label' => $ctaText('cocktails', 'button_text', 'Abrir sección'), 'action' => url('/cocktails'), 'image' => $settings->cta_image_cocktails ? asset('storage/' . $settings->cta_image_cocktails) : null, 'visible' => $settings->show_cta_cocktails ?? true, 'type' => 'link'],
                        ['key' => 'events', 'title' => $ctaLabel($settings->button_label_events ?? null, 'Eventos especiales'), 'subtitle' => $ctaText('events', 'subtitle', 'Calendario'), 'copy' => $ctaText('events', 'copy', 'Pop-ups, catas privadas y residencias.'), 'button_label' => $ctaText('events', 'button_text', 'Abrir sección'), 'action' => route('experiences.index'), 'image' => $settings->cta_image_events ? asset('storage/' . $settings->cta_image_events) : null, 'visible' => $settings->show_cta_events ?? true, 'type' => 'link'],
                        ['key' => 'reservations', 'title' => $ctaLabel($settings->button_label_reservations ?? null, 'Reservas'), 'subtitle' => $ctaText('reservations', 'subtitle', 'Agenda'), 'copy' => $ctaText('reservations', 'copy', 'Reserva tu mesa o un flight privado.'), 'button_label' => $ctaText('reservations', 'button_text', 'Abrir sección'), 'action' => route('reservations.app'), 'image' => $settings->cta_image_reservations ? asset('storage/' . $settings->cta_image_reservations) : null, 'visible' => $settings->show_cta_reservations ?? true, 'type' => 'link'],
                        ['key' => 'vip', 'title' => $ctaLabel($settings->button_label_vip ?? null, 'Lista VIP'), 'subtitle' => $ctaText('vip', 'subtitle', 'Alertas privadas'), 'copy' => $ctaText('vip', 'copy', 'Recibe lanzamientos de micro lotes, cenas a puerta cerrada y flights sorpresas.'), 'button_label' => $ctaText('vip', 'button_text', $ctaLabel($settings->button_label_vip ?? null, 'Lista VIP')), 'action' => '#', 'image' => null, 'visible' => $settings->show_cta_vip ?? true, 'type' => 'vip'],
                    ])->filter(fn($card) => ($card['visible'] ?? true) && filled($card['title']))->map(function ($card) use ($settings, $coverCardBackground) {
                        $bg = $settings->{'cover_cta_'.$card['key'].'_bg_color'} ?? null;
                        $text = $settings->{'cover_cta_'.$card['key'].'_text_color'} ?? null;
                        $card['bg_color'] = $bg ?: $coverCardBackground;
                        $card['text_color'] = $text ?: 'var(--cover-body-color)';
                        return $card;
                    })->values();
                @endphp
                @foreach($ctaCards as $card)
                    <article class="border rounded-2xl p-0 overflow-hidden flex flex-col"
                             style="background-color: {{ $card['bg_color'] }}; color: {{ $card['text_color'] }}; border-color: rgba(255, 242, 179, 0.2);">
                        @if(!empty($card['image']))
                            <div class="h-40 overflow-hidden">
                                <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="p-5 flex flex-col gap-3">
                            <p class="text-xs uppercase tracking-[0.35em]" style="opacity: 0.8;">{{ $card['subtitle'] }}</p>
                            <h3 class="text-2xl font-semibold">{{ $card['title'] }}</h3>
                            <p class="text-sm flex-1">{{ $card['copy'] }}</p>
                            @if($card['type'] === 'vip')
                                <button data-open-notify
                                        class="w-full rounded-full py-3 font-semibold transition vip-button"
                                        style="background-color: var(--accent-color); font-size: {{ $settings->button_font_size_cover ?? 18 }}px;">
                                    {{ $card['button_label'] ?? $card['title'] }}
                                </button>
                            @else
                                <button onclick="window.location.href='{{ $card['action'] }}'"
                                        class="w-full rounded-full py-3 font-semibold transition"
                                        style="background-color: var(--accent-color); font-size: {{ $settings->button_font_size_cover ?? 18 }}px;">
                                    {{ $card['button_label'] ?? 'Abrir sección' }}
                                </button>
                            @endif
                        </div>
                    </article>
                @endforeach
            </section>
        </div>
    </main>

    <!-- Redes sociales abajo -->
    <footer class="fixed.bottom-6 left-0 right-0 z-40">
        <div class="flex justify-center gap-6">
            <a href="{{ $settings->facebook_url ?? '#' }}" target="_blank" 
               class="w-12 h-12 flex items-center justify-center rounded-full social-icon">
                <i class="fab fa-facebook-f"></i>
            </a>
            
            <a href="{{ $settings->instagram_url ?? '#' }}" target="_blank" 
               class="w-12 h-12 flex items-center justify-center rounded-full social-icon">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="tel:{{ $settings->phone_number ?? '#' }}" 
               class="w-12 h-12 flex items-center justify-center rounded-full social-icon">
                <i class="fas fa-phone"></i>
            </a>
        </div>
    </footer>

    <!-- Modal de notificación -->
    <div id="notifyModal" class="fixed inset-0 modal-overlay backdrop-blur-sm flex items-center.justify-center px-4 {{ ($errors->has('name') || $errors->has('email')) ? '' : 'hidden' }} z-50">
        <div class="modal-surface vip-modal rounded-3xl w-full max-w-md p-6 relative">
            <button id="closeNotifyModal" class="absolute top-4 right-4 text-2xl" style="color: {{ $palette['violet'] }};">&times;</button>
            <p class="text-xs uppercase tracking-[0.35em] mb-2" style="color: {{ $palette['amber'] }};">Experiencias</p>
            <h2 class="text-2xl font-semibold mb-2">Recibe las alertas VIP</h2>
            <p class="text-sm mb-4" style="color: {{ $palette['blue'] }};">Entérate primero de nuevas experiencias, cenas especiales y eventos privados.</p>
            <form action="{{ route('experiences.notify.cover') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <input type="text" name="name" placeholder="Tu nombre" value="{{ old('name') }}"
                           class="w-full form-input">
                    @error('name')
                        <p class="text-xs mt-1" style="color: {{ $palette['violet'] }};">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <input type="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}"
                           class="w-full form-input">
                    @error('email')
                        <p class="text-xs mt-1" style="color: {{ $palette['violet'] }};">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full py-3 rounded-2xl font-semibold transition"
                        style="background-color: var(--accent-color); color: {{ $palette['violet'] }};">
                    Quiero recibir noticias
                </button>
                <p class="text-xs text-center" style="color: {{ $palette['blue'] }};">Prometemos solo enviar experiencias relevantes.</p>
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

    <div id="coverPopupModal" class="hidden fixed inset-0 modal-overlay z-50 flex items-center justify-center px-4">
        <div class="modal-surface rounded-3xl w-full max-w-2xl p-4 relative">
            <button type="button" class="absolute top-3 right-3 text-2xl" style="color: {{ $palette['violet'] }};" onclick="closeCoverPopup()">&times;</button>
            <div class="space-y-3">
                <h3 id="coverPopupTitle" class="text-xl font-semibold text-center"></h3>
                <img id="coverPopupImage" src="" alt="Anuncio" class="w-full rounded-2xl object-cover">
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.js"></script>
    <script>
        let coverPopupInstance;
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('notifyModal');
            const openButtons = document.querySelectorAll('[data-open-notify]');
            const closeBtn = document.getElementById('closeNotifyModal');
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

            openButtons.forEach(btn => btn.addEventListener('click', openModal));
            closeBtn?.addEventListener('click', closeModal);
            modal?.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });

            if (flash) {
                localStorage.setItem('eventNotifyRegistered', '1');
            }

            if (isRegistered) {
                openButtons.forEach(btn => btn.textContent = 'Actualizar datos');
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
            const featuredTextColor = "{{ $featuredCardText }}";
            const featuredMutedColor = "{{ $featuredMutedText }}";
            const featuredBorderColor = "{{ $featuredBorderColor }}";
            const featuredAccentColor = "{{ $settings->button_color_cover ?? $featuredCardText }}";

            const featuredButtons = document.querySelectorAll('[data-featured-tab]');
            const tagEl = document.getElementById('featuredTag');
            const titleEl = document.getElementById('featuredTitle');
            const descriptionEl = document.getElementById('featuredDescription');
            const itemsEl = document.getElementById('featuredItems');
            const coverPopups = @json($popups ?? []);
            const now = new Date();
            const today = now.getDay();

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
                        <a href="${item.link || '#'}" class="flex items-start justify-between gap-4 pb-3 group" style="color:${featuredTextColor}; border-bottom:1px solid ${featuredBorderColor};">
                            <div class="flex items-start gap-3">
                                ${item.image
                                    ? `<img src="${item.image}" alt="${item.title || ''}" class="w-14 h-14 rounded-xl object-cover border" style="border-color:${featuredBorderColor};">`
                                    : `<div class="w-14 h-14 rounded-xl flex items-center justify-center text-lg" style="border:1px solid ${featuredBorderColor};">☆</div>`
                                }
                                <div>
                                    <p class="text-lg font-semibold">${item.title ?? ''}</p>
                                    <p class="text-sm" style="color:${featuredMutedColor};">${item.subtitle ?? ''}</p>
                                </div>
                            </div>
                            ${item.price ? `<span class="font-semibold" style="color:${featuredAccentColor};">$${item.price}</span>` : ''}
                        </a>
                    `).join('')
                    : '<p class="cover-text-soft text-sm">Agrega elementos destacados desde el panel para mostrarlos aquí.</p>';

                featuredButtons.forEach(btn => {
                    btn.style.backgroundColor = btn.dataset.featuredTab === slug ? btn.dataset.activeBg : btn.dataset.inactiveBg;
                    btn.style.borderColor = featuredBorderColor;
                    btn.style.color = featuredTextColor;
                });
            };

            featuredButtons.forEach(btn => {
                btn.addEventListener('click', () => renderFeatured(btn.dataset.featuredTab));
            });

            if (featuredButtons.length) {
                renderFeatured(featuredButtons[0].dataset.featuredTab);
            }

            coverPopups.forEach(popup => {
                const start = popup.start_date ? new Date(popup.start_date) : null;
                const end = popup.end_date ? new Date(popup.end_date) : null;
                const repeatDays = popup.repeat_days ? popup.repeat_days.split(',').map(day => parseInt(day, 10)) : [];
                const withinDates = (!start || now >= start) && (!end || now <= end);
                const matchesDay = repeatDays.length === 0 || repeatDays.includes(today);

                if (popup.active && popup.view === 'cover' && withinDates && matchesDay) {
                    showCoverPopup(popup);
                }
            });

            // If validation errors opened the modal, highlight status
            if (!modal.classList.contains('hidden')) {
                document.body.classList.add('overflow-hidden');
            }
        });

        function showCoverPopup(popup) {
            const modalEl = document.getElementById('coverPopupModal');
            if (!modalEl) {
                return;
            }
            if (!coverPopupInstance) {
                coverPopupInstance = new Modal(modalEl, { closable: true });
            }
            const basePath = '{{ asset('storage') }}/';
            document.getElementById('coverPopupTitle').textContent = popup.title || '';
            document.getElementById('coverPopupImage').src = popup.image ? basePath + popup.image : '';
            coverPopupInstance.show();
        }

        function closeCoverPopup() {
            if (coverPopupInstance) {
                coverPopupInstance.hide();
            }
        }
    </script>

</body>
</html>
