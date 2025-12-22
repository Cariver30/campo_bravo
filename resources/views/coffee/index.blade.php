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
        body {
            font-family: {{ $settings->font_family_wines ?? 'ui-sans-serif' }};
            @if($settings && $settings->background_image_wines)
                background: url('{{ asset("storage/" . $settings->background_image_wines) }}') no-repeat center center fixed;
            @else
                background: radial-gradient(circle at top, #f4ede0, #d2b48c);
            @endif
            background-size: cover;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(5, 5, 5, 0.55);
            z-index: -1;
        }
    </style>
</head>
<body class="text-white min-h-screen pb-32">

    <header class="max-w-6xl mx-auto px-4 py-12 space-y-6">
        <div class="text-center space-y-4">
            <img src="{{ asset('storage/' . ($settings->logo ?? 'default-logo.png')) }}" class="mx-auto h-28" alt="Logo Café">
            <p class="text-amber-300 uppercase tracking-[0.4em] text-xs">Café · Desayunos · Brunch</p>
            <h1 class="text-4xl md:text-5xl font-semibold leading-tight">Barra de café curada, desayunos artesanales y pequeños rituales de mañana.</h1>
            <p class="text-white/80 max-w-3xl mx-auto">Seleccionamos micro lotes latinoamericanos, métodos clásicos y bebidas creativas frías para acompañar panadería de temporada y opciones plant-based.</p>
        </div>

        @if($popups->count())
            <div class="grid md:grid-cols-{{ min(3, $popups->count()) }} gap-4 mt-10">
                @foreach($popups as $popup)
                    <article class="bg-white/10 border border-white/15 rounded-3xl p-4 flex items-center gap-3 backdrop-blur">
                        <img src="{{ asset('storage/' . $popup->image) }}" alt="{{ $popup->title }}" class="w-16 h-16 rounded-2xl object-cover">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-amber-300 mb-1">{{ $popup->view === 'coffee' ? 'Café' : ucfirst($popup->view) }}</p>
                            <h3 class="text-lg font-semibold">{{ $popup->title }}</h3>
                            <p class="text-white/70 text-sm">{{ \Illuminate\Support\Str::limit(strip_tags($popup->description ?? ''), 80) }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </header>

    @if($settings->coffee_hero_image)
        <div class="max-w-5xl mx-auto px-4 mb-8">
            <img src="{{ asset('storage/' . $settings->coffee_hero_image) }}" alt="Destacado café" class="w-full rounded-3xl border border-white/10 shadow-2xl object-cover">
        </div>
    @endif

    <section class="max-w-6xl mx-auto px-4">
        <form method="GET" action="{{ route('coffee.index') }}"
              class="bg-white/10 border border-white/10 rounded-3xl p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 backdrop-blur">

            <div>
                <label class="text-xs uppercase tracking-[0.3em] text-white/70 mb-1 block">Origen</label>
                <select name="region" class="rounded-2xl px-3 py-2 text-slate-900 w-full">
                    <option value="">Todos</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}" {{ request('region') == $region->id ? 'selected' : '' }}>
                            {{ $region->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs uppercase tracking-[0.3em] text-white/70 mb-1 block">Método</label>
                <select name="type" class="rounded-2xl px-3 py-2 text-slate-900 w-full">
                    <option value="">Todos</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs uppercase tracking-[0.3em] text-white/70 mb-1 block">Perfil</label>
                <select name="grape" class="rounded-2xl px-3 py-2 text-slate-900 w-full">
                    <option value="">Todos</option>
                    @foreach($grapes as $grape)
                        <option value="{{ $grape->id }}" {{ request('grape') == $grape->id ? 'selected' : '' }}>
                            {{ $grape->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs uppercase tracking-[0.3em] text-white/70 mb-1 block">Precio máx.</label>
                <input type="number" name="max_price" class="rounded-2xl px-3 py-2 text-slate-900 w-full" value="{{ request('max_price') }}" placeholder="15" />
            </div>

            <div class="flex sm:flex-col items-stretch gap-3 mt-4 sm:mt-0">
                <button type="submit"
                        class="w-full rounded-2xl px-4 py-3 font-semibold text-white"
                        style="background-color: {{ $settings->button_color_wines ?? '#d97706' }};">
                    Filtrar
                </button>
                <a href="{{ route('coffee.index') }}"
                   class="w-full text-center rounded-2xl px-4 py-3 font-semibold text-white border border-white/30 hover:bg-white/10 transition">
                    Limpiar
                </a>
            </div>
        </form>
    </section>

    <main class="max-w-6xl mx-auto px-4 py-10 space-y-12">
        @if($filters && $wines->count())
            <div class="text-center space-y-2">
                <p class="text-xs uppercase tracking-[0.35em] text-amber-300">Selección personalizada</p>
                <h2 class="text-3xl font-semibold">Resultados para tu estado de ánimo cafetero</h2>
                <p class="text-white/70">{{ $wines->count() }} bebidas encontradas.</p>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($wines as $coffee)
                    <article id="coffee{{ $coffee->id }}" class="bg-white/10 border border-white/10 rounded-3xl p-5 backdrop-blur relative overflow-hidden">
                        <div class="absolute inset-0 opacity-10" style="background: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.9), transparent 60%);"></div>
                        <div class="relative space-y-3">
                            <div class="flex items-center gap-4">
                                @if($coffee->image)
                                    <img src="{{ asset('storage/' . $coffee->image) }}" class="h-16 w-16 rounded-2xl object-cover" alt="{{ $coffee->name }}">
                                @else
                                    <div class="h-16 w-16 rounded-2xl bg-white/20 flex items-center justify-center text-2xl">☕</div>
                                @endif
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-white/60">{{ $coffee->type->name ?? 'Método signature' }}</p>
                                    <h3 class="text-xl font-semibold">{{ $coffee->name }}</h3>
                                </div>
                                <span class="ml-auto text-amber-300 font-semibold text-lg">${{ number_format($coffee->price, 2) }}</span>
                            </div>
                            <p class="text-white/70 text-sm">{{ $coffee->description }}</p>
                            <div class="flex flex-wrap gap-2 text-xs text-white/80">
                                <span class="px-3 py-1 rounded-full border border-white/30">{{ $coffee->region->name ?? 'Origen mixto' }}</span>
                                @if($coffee->grapes->count())
                                    <span class="px-3 py-1 rounded-full border border-white/30">{{ $coffee->grapes->pluck('name')->implode(', ') }}</span>
                                @endif
                            </div>
                            @if($coffee->dishes->count())
                                <div class="pt-3 border-t border-white/10">
                                    <p class="text-xs uppercase tracking-[0.3em] text-amber-300 mb-2">Acompañantes sugeridos</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($coffee->dishes as $dish)
                                            <span class="px-3 py-1 rounded-full bg-white/15 text-xs">{{ $dish->name }}</span>
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
                <p class="text-white/70">Intenta ajustar el método, origen o rango de precio.</p>
            </div>
        @else
            @foreach($wineCategories as $category)
                <section id="category{{ $category->id }}" class="space-y-6">
                    <div class="bg-white/10 border border-white/10 rounded-3xl p-6 text-center">
                        <p class="text-xs uppercase tracking-[0.4em] text-white/60">Colección</p>
                        <h2 class="text-3xl font-semibold">{{ $category->name }}</h2>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($category->items as $coffee)
                            <article id="coffee{{ $coffee->id }}" class="bg-white/5 border border-white/10 rounded-3xl p-5 backdrop-blur flex flex-col gap-4">
                                <div class="flex items-start gap-4">
                                    @if($coffee->image)
                                        <img src="{{ asset('storage/' . $coffee->image) }}" class="h-16 w-16 rounded-2xl object-cover" alt="{{ $coffee->name }}">
                                    @else
                                        <div class="h-16 w-16 rounded-2xl bg-white/10 flex items-center justify-center text-2xl">☕</div>
                                    @endif
                                    <div>
                                        <h3 class="text-xl font-semibold">{{ $coffee->name }}</h3>
                                        <p class="text-white/60 text-sm">{{ $coffee->type->name ?? 'Método signature' }} · {{ $coffee->region->name ?? 'Origen mixto' }}</p>
                                    </div>
                                    <span class="ml-auto text-amber-300 font-semibold">${{ number_format($coffee->price, 2) }}</span>
                                </div>
                                <p class="text-white/70 text-sm flex-1">{{ $coffee->description }}</p>
                                @if($coffee->grapes->count())
                                    <div class="flex flex-wrap gap-2 text-xs text-white/70">
                                        @foreach($coffee->grapes as $note)
                                            <span class="px-3 py-1 rounded-full border border-white/20">{{ $note->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                @if($coffee->dishes->count())
                                    <div class="bg-black/20 border border-white/5 rounded-2xl p-3">
                                        <p class="text-xs uppercase tracking-[0.4em] text-white/60 mb-2">Brunch pairing</p>
                                        <div class="flex flex-wrap gap-2 text-xs">
                                            @foreach($coffee->dishes as $dish)
                                                <span class="px-3 py-1 rounded-full bg-white/10">{{ $dish->name }}</span>
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

    <section class="max-w-6xl mx-auto px-4">
        <div class="bg-white/5 border border-white/10 rounded-3xl p-6 flex flex-col lg:flex-row gap-6">
            <div class="flex-1 space-y-3">
                <p class="text-xs uppercase tracking-[0.35em] text-white/60">Brunch x Café</p>
                <h3 class="text-3xl font-semibold">Crea tu flight personal</h3>
                <p class="text-white/80">Combina un filtrado, bebida fría y un plato del brunch board. El barista guía las notas sensoriales y personaliza toppings.</p>
                <div class="flex flex-wrap gap-3 text-sm">
                    <span class="px-4 py-2 rounded-full border border-white/20 text-white/80">Flight 3 bebidas · $18</span>
                    <span class="px-4 py-2 rounded-full border border-white/20 text-white/80">Board salado/dulce · $14</span>
                </div>
            </div>
            <div class="flex flex-col gap-3">
                <a href="/menu" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-full font-semibold bg-white text-slate-900 shadow">
                    <i class="fas fa-utensils"></i> Ver cartas de brunch
                </a>
                <a href="{{ route('reservations.app') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-full font-semibold border border-white/30 text-white hover:bg-white/10 transition">
                    <i class="fas fa-calendar-check"></i> Reservar mesa
                </a>
            </div>
        </div>
    </section>

    <div class="fixed bottom-5 left-0 right-0 flex justify-center z-50">
        <div class="flex items-center gap-4 px-4 py-2 rounded-3xl backdrop-blur-lg border border-white/20 shadow-2xl"
             style="background-color: rgba(0,0,0,0.6);">
            <a href="/" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
               style="background-color: {{ $settings->button_color_wines ?? '#d97706' }};">
                <i class="fas fa-home text-lg"></i><span>Inicio</span>
            </a>
            <a href="/menu" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
               style="background-color: {{ $settings->button_color_wines ?? '#d97706' }};">
                <i class="fas fa-utensils text-lg"></i><span>Menú</span>
            </a>
            <a href="/cocktails" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
               style="background-color: {{ $settings->button_color_wines ?? '#d97706' }};">
                <i class="fas fa-champagne-glasses text-lg"></i><span>Brunch Bar</span>
            </a>
        </div>
    </div>

    <script src="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.js"></script>
</body>
</html>
