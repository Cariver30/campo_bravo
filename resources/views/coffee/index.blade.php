@php
    if (!function_exists('coffee_mix_color')) {
        function coffee_mix_color(?string $hexColor, float $opacity = 1): string {
            $hex = $hexColor ?: '#0f172a';
            $hex = str_replace('#', '', $hex);
            if (strlen($hex) === 3) {
                $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
            }
            $int = hexdec($hex);
            $r = ($int >> 16) & 255;
            $g = ($int >> 8) & 255;
            $b = $int & 255;
            return "rgba({$r}, {$g}, {$b}, {$opacity})";
        }
    }

    $coffeeTextColor = $settings->text_color_wines ?? '#ffffff';
    $coffeeSoftText = coffee_mix_color($coffeeTextColor, 0.7);
    $coffeeAccentColor = $settings->button_color_wines ?? '#f59e0b';
    $coffeeCardBackground = coffee_mix_color($settings->card_bg_color_wines ?? '#111827', $settings->card_opacity_wines ?? 0.85);
    $coffeeBorderColor = coffee_mix_color($coffeeTextColor, 0.18);
    $coffeeChipBg = coffee_mix_color($coffeeTextColor, 0.12);
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Café &amp; Brunch</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        :root {
            --coffee-text: {{ $coffeeTextColor }};
            --coffee-soft: {{ $coffeeSoftText }};
            --coffee-accent: {{ $coffeeAccentColor }};
            --coffee-card-bg: {{ $coffeeCardBackground }};
            --coffee-card-border: {{ $coffeeBorderColor }};
            --coffee-chip-bg: {{ $coffeeChipBg }};
        }

        body {
            color: var(--coffee-text);
        }

        body {
            font-family: {{ $settings->font_family_wines ?? 'ui-sans-serif' }};
            @if($settings && $settings->background_image_wines)
                background: none;
            @else
                background: radial-gradient(circle at top, #f4ede0, #d2b48c);
            @endif
            background-size: cover;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            @if($settings && $settings->background_image_wines)
                background: url('{{ asset("storage/" . $settings->background_image_wines) }}') no-repeat center center;
                background-size: cover;
            @else
                background: rgba(5, 5, 5, 0.55);
            @endif
        }

        .coffee-card {
            background-color: var(--coffee-card-bg);
            border: 1px solid var(--coffee-card-border);
            color: var(--coffee-text);
        }

        .coffee-chip {
            background-color: var(--coffee-chip-bg);
            border: 1px solid var(--coffee-card-border);
            color: var(--coffee-text);
        }

        .coffee-soft {
            color: var(--coffee-soft);
        }

        .coffee-accent {
            color: var(--coffee-accent);
        }

        .coffee-cta {
            background-color: var(--coffee-accent);
            color: #0f172a;
        }
    </style>
</head>
<body class="min-h-screen pb-32">

    <header class="max-w-6xl mx-auto px-4 py-12 space-y-6">
        <div class="text-center">
            <img src="{{ asset('storage/' . ($settings->logo ?? 'default-logo.png')) }}" class="mx-auto h-28" alt="Logo Café">
        </div>

        @if($popups->count())
            <div class="grid md:grid-cols-{{ min(3, $popups->count()) }} gap-4 mt-10">
                @foreach($popups as $popup)
                    <article class="coffee-card rounded-3xl p-4 flex items-center gap-3 backdrop-blur">
                        <img src="{{ asset('storage/' . $popup->image) }}" alt="{{ $popup->title }}" class="w-16 h-16 rounded-2xl object-cover">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] coffee-accent mb-1">{{ $popup->view === 'coffee' ? 'Café' : ucfirst($popup->view) }}</p>
                            <h3 class="text-lg font-semibold">{{ $popup->title }}</h3>
                            <p class="coffee-soft text-sm">{{ \Illuminate\Support\Str::limit(strip_tags($popup->description ?? ''), 80) }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </header>

    @if($settings->coffee_hero_image)
        <div class="max-w-5xl mx-auto px-4 mb-8">
            <img src="{{ asset('storage/' . $settings->coffee_hero_image) }}" alt="Destacado café" class="w-full rounded-3xl border shadow-2xl object-cover" style="border-color: var(--coffee-card-border);">
        </div>
    @endif

    <main class="max-w-6xl mx-auto px-4 py-10 space-y-12">
        @if($filters && $wines->count())
            <div class="text-center space-y-2">
                <p class="text-xs uppercase tracking-[0.35em] coffee-accent">Selección personalizada</p>
                <h2 class="text-3xl font-semibold">Resultados para tu estado de ánimo cafetero</h2>
                <p class="coffee-soft">{{ $wines->count() }} bebidas encontradas.</p>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($wines as $coffee)
                    <article id="coffee{{ $coffee->id }}" class="coffee-card rounded-3xl p-5 backdrop-blur relative overflow-hidden">
                        <div class="absolute inset-0 opacity-10" style="background: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.35), transparent 60%);"></div>
                        <div class="relative space-y-3">
                            <div class="flex items-center gap-4">
                                <img src="{{ $coffee->image ? asset('storage/' . $coffee->image) : asset('storage/' . ($settings->logo ?? 'default-logo.png')) }}" class="h-16 w-16 rounded-2xl object-cover border" style="border-color: var(--coffee-card-border);" alt="{{ $coffee->name }}">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] coffee-soft">{{ $coffee->type->name ?? 'Método signature' }}</p>
                                    <h3 class="text-xl font-semibold">{{ $coffee->name }}</h3>
                                </div>
                                <span class="ml-auto coffee-accent font-semibold text-lg">${{ number_format($coffee->price, 2) }}</span>
                            </div>
                            <p class="coffee-soft text-sm">{{ $coffee->description }}</p>
                            <div class="flex flex-wrap gap-2 text-xs coffee-soft">
                                <span class="coffee-chip px-3 py-1 rounded-full">{{ $coffee->region->name ?? 'Origen mixto' }}</span>
                                @if($coffee->grapes->count())
                                    <span class="coffee-chip px-3 py-1 rounded-full">{{ $coffee->grapes->pluck('name')->implode(', ') }}</span>
                                @endif
                            </div>
                            @if($coffee->dishes->count())
                                <div class="pt-3" style="border-top: 1px solid var(--coffee-card-border);">
                                    <p class="text-xs uppercase tracking-[0.3em] coffee-accent mb-2">Acompañantes sugeridos</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($coffee->dishes as $dish)
                                            <span class="coffee-chip px-3 py-1 rounded-full text-xs">{{ $dish->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @elseif($filters && $wines->isEmpty())
            <div class="text-center py-20 space-y-4">
                <p class="text-2xl font-semibold">No encontramos bebidas con esas notas.</p>
                <p class="coffee-soft">Intenta ajustar el método, origen o rango de precio.</p>
            </div>
        @else
            @foreach($wineCategories as $category)
                <section id="category{{ $category->id }}" class="space-y-6">
                    <div class="coffee-card rounded-3xl p-6 text-center">
                        <p class="text-xs uppercase tracking-[0.4em] coffee-soft">Colección</p>
                        <h2 class="text-3xl font-semibold">{{ $category->name }}</h2>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($category->items as $coffee)
                            <article id="coffee{{ $coffee->id }}" class="coffee-card rounded-3xl p-5 backdrop-blur flex flex-col gap-4">
                                <div class="flex items-start gap-4">
                                    <img src="{{ $coffee->image ? asset('storage/' . $coffee->image) : asset('storage/' . ($settings->logo ?? 'default-logo.png')) }}" class="h-16 w-16 rounded-2xl object-cover border" style="border-color: var(--coffee-card-border);" alt="{{ $coffee->name }}">
                                    <div>
                                        <h3 class="text-xl font-semibold">{{ $coffee->name }}</h3>
                                        <p class="coffee-soft text-sm">{{ $coffee->type->name ?? 'Método signature' }} · {{ $coffee->region->name ?? 'Origen mixto' }}</p>
                                    </div>
                                    <span class="ml-auto coffee-accent font-semibold">${{ number_format($coffee->price, 2) }}</span>
                                </div>
                                <p class="coffee-soft text-sm flex-1">{{ $coffee->description }}</p>
                                @if($coffee->grapes->count())
                                    <div class="flex flex-wrap gap-2 text-xs coffee-soft">
                                        @foreach($coffee->grapes as $note)
                                            <span class="coffee-chip px-3 py-1 rounded-full">{{ $note->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                @if($coffee->dishes->count())
                                    <div class="rounded-2xl p-3" style="background-color: var(--coffee-chip-bg); border: 1px solid var(--coffee-card-border);">
                                        <p class="text-xs uppercase tracking-[0.4em] coffee-soft mb-2">Brunch pairing</p>
                                        <div class="flex flex-wrap gap-2 text-xs">
                                            @foreach($coffee->dishes as $dish)
                                                <span class="coffee-chip px-3 py-1 rounded-full">{{ $dish->name }}</span>
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

    <div class="fixed bottom-5 left-0 right-0 flex justify-center z-50">
        <div class="flex items-center gap-4 px-4 py-2 rounded-3xl backdrop-blur-lg shadow-2xl"
             style="background-color: rgba(0,0,0,0.6); border: 1px solid var(--coffee-card-border);">
            <a href="/" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold transition hover:scale-105"
               style="background-color: var(--coffee-accent); color: #0f172a;">
                <i class="fas fa-home text-lg"></i><span>Inicio</span>
            </a>
            <a href="/menu" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold transition hover:scale-105"
               style="background-color: var(--coffee-accent); color: #0f172a;">
                <i class="fas fa-utensils text-lg"></i><span>Menú</span>
            </a>
            <a href="/cocktails" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold transition hover:scale-105"
               style="background-color: var(--coffee-accent); color: #0f172a;">
                <i class="fas fa-champagne-glasses text-lg"></i><span>Brunch Bar</span>
            </a>
        </div>
    </div>

    <script src="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.js"></script>
</body>
</html>
