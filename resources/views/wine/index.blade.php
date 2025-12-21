<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vinos</title>

    <!-- Tailwind + Flowbite -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: {{ $settings->font_family_wines ?? 'ui-sans-serif' }};
            @if($settings && $settings->background_image_wines)
                background: url('{{ asset("storage/" . $settings->background_image_wines) }}') no-repeat center center fixed;
            @endif
            background-size: cover;
        }
    </style>
</head>
<body class="text-white">

    <!-- LOGO -->
    <div class="text-center py-6">
        <img src="{{ asset('storage/' . ($settings->logo ?? 'default-logo.png')) }}"
             class="mx-auto h-28" alt="Logo del Restaurante">
    </div>

    <!-- 🔎 FILTRO -->
    <div class="max-w-6xl mx-auto px-4">
        <form method="GET" action="{{ route('wines.index') }}"
              class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-black bg-opacity-40 backdrop-blur-md p-4 rounded-lg shadow-lg mb-8">

            <select name="region" class="rounded-lg p-2 text-black">
                <option value="">Regiones</option>
                @foreach($regions as $region)
                    <option value="{{ $region->id }}" {{ request('region') == $region->id ? 'selected' : '' }}>
                        {{ $region->name }}
                    </option>
                @endforeach
            </select>

            <select name="type" class="rounded-lg p-2 text-black">
                <option value="">Tipos</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>

            <select name="grape" class="rounded-lg p-2 text-black">
                <option value="">Uvas</option>
                @foreach($grapes as $grape)
                    <option value="{{ $grape->id }}" {{ request('grape') == $grape->id ? 'selected' : '' }}>
                        {{ $grape->name }}
                    </option>
                @endforeach
            </select>

            <input type="number" name="max_price" placeholder="Precio máx." class="rounded-lg p-2 text-black" value="{{ request('max_price') }}" />

            <div class="md:col-span-4 flex justify-end mt-2">
                <button type="submit"
                        class="px-4 py-2 rounded-lg text-white font-semibold"
                        style="background-color: {{ $settings->button_color_wines ?? '#b91c1c' }}">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- ✅ CONTENIDO DE VINOS -->
    <div class="max-w-6xl mx-auto px-4">
        @if(request()->hasAny(['region', 'type', 'grape', 'max_price']) && $wines->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($wines as $wine)
                    <div 
                        id="wine{{ $wine->id }}"
                        class="bg-white/10 backdrop-blur-md p-4 rounded-lg shadow-lg cursor-pointer transition hover:scale-105 relative"
                        data-modal-target="wineModal{{ $wine->id }}" 
                        data-modal-toggle="wineModal{{ $wine->id }}"
                    >
                        <span class="absolute top-2 right-2 text-xs bg-gray-700 text-white px-2 py-1 rounded">Ver más</span>

                        <div class="flex items-center space-x-4">
                            <img src="{{ asset('storage/' . $wine->image) }}" class="h-20 w-20 object-cover rounded-full" alt="{{ $wine->name }}">
                            <div>
                                <h3 class="text-xl font-semibold">{{ $wine->name }}</h3>
                                <p class="text-sm text-gray-300">${{ number_format($wine->price, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal individual -->
                    <div id="wineModal{{ $wine->id }}" tabindex="-1" aria-hidden="true"
                        class="hidden fixed inset-0 z-50 justify-center items-center w-full p-4 overflow-x-hidden overflow-y-auto">
                        <div class="relative w-full max-w-2xl">
                            <div class="relative rounded-lg shadow bg-gray-900 text-gray-100">
                                <div class="flex justify-between items-center p-4 rounded-t border-b border-gray-700">
                                    <h3 class="text-xl font-bold">{{ $wine->name }}</h3>
                                    <button data-modal-hide="wineModal{{ $wine->id }}" class="text-gray-200 hover:text-red-400">
                                        ✕
                                    </button>
                                </div>
                                <div class="p-6 space-y-4">
                                    <img src="{{ asset('storage/' . $wine->image) }}"
                                         class="w-full h-60 object-cover rounded-lg">
                                    <p>{{ $wine->description }}</p>
                                    <p><strong>Precio:</strong> ${{ number_format($wine->price, 2) }}</p>
                                    <p><strong>Tipo:</strong> {{ $wine->type->name ?? 'Sin tipo' }}</p>
                                    <p><strong>Región:</strong> {{ $wine->region->name ?? 'Sin región' }}</p>
                                    <p><strong>Uvas:</strong> {{ $wine->grapes->pluck('name')->join(', ') ?: 'No especificado' }}</p>
                                    @if($wine->dishes->count())
                                            <p><strong>Platos sugeridos:</strong> {{ $wine->dishes->pluck('name')->join(', ') }}</p>
                                            @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @elseif(request()->hasAny(['region', 'type', 'grape', 'max_price']) && $wines->isEmpty())
            <p class="text-center text-gray-300 text-lg">No se encontraron vinos con los filtros seleccionados.</p>

        @else
            @foreach($wineCategories as $category)
                <section id="category{{ $category->id }}" class="mb-12">
                    <div class="bg-white bg-opacity-20 rounded-xl px-6 py-4 shadow-lg mb-6 text-center">
                        <h2 class="text-3xl font-bold text-white tracking-widest uppercase">{{ $category->name }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($category->items as $wine)
                            <div 
                                id="wine{{ $wine->id }}"
                                class="bg-white/10 backdrop-blur-md p-4 rounded-lg shadow-lg cursor-pointer transition hover:scale-105 relative"
                                data-modal-target="wineModal{{ $wine->id }}" 
                                data-modal-toggle="wineModal{{ $wine->id }}"
                            >
                                <span class="absolute top-2 right-2 text-xs bg-gray-700 text-white px-2 py-1 rounded">Ver más</span>

                                <div class="flex items-center space-x-4">
                                    <img src="{{ asset('storage/' . $wine->image) }}" class="h-20 w-20 object-cover rounded-full" alt="{{ $wine->name }}">
                                    <div>
                                        <h3 class="text-xl font-semibold">{{ $wine->name }}</h3>
                                        <p class="text-sm text-gray-300">${{ number_format($wine->price, 2) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal individual -->
                            <div id="wineModal{{ $wine->id }}" tabindex="-1" aria-hidden="true"
                                class="hidden fixed inset-0 z-50 justify-center items-center w-full p-4 overflow-x-hidden overflow-y-auto">
                                <div class="relative w-full max-w-2xl">
                                    <div class="relative rounded-lg shadow bg-gray-900 text-gray-100">
                                        <div class="flex justify-between items-center p-4 rounded-t border-b border-gray-700">
                                            <h3 class="text-xl font-bold">{{ $wine->name }}</h3>
                                            <button data-modal-hide="wineModal{{ $wine->id }}" class="text-gray-200 hover:text-red-400">
                                                ✕
                                            </button>
                                        </div>
                                        <div class="p-6 space-y-4">
                                            <img src="{{ asset('storage/' . $wine->image) }}"
                                                 class="w-full h-60 object-cover rounded-lg">
                                            <p>{{ $wine->description }}</p>
                                            <p><strong>Precio:</strong> ${{ number_format($wine->price, 2) }}</p>
                                            <p><strong>Tipo:</strong> {{ $wine->type->name ?? 'Sin tipo' }}</p>
                                            <p><strong>Región:</strong> {{ $wine->region->name ?? 'Sin región' }}</p>
                                            <p><strong>Uvas:</strong> {{ $wine->grapes->pluck('name')->join(', ') ?: 'No especificado' }}</p>
                                            @if($wine->dishes->count())
                                            <p><strong>Platos sugeridos:</strong> {{ $wine->dishes->pluck('name')->join(', ') }}</p>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        @endif
    </div>

    <!-- BOTONES FLOTANTES -->
    <div class="fixed bottom-5 left-0 right-0 z-50 flex justify-center">
        <div class="flex items-center gap-4 px-4 py-2 rounded-3xl backdrop-blur-lg border border-white/20 shadow-2xl"
             style="background-color: rgba(0,0,0,0.55);">
            <a href="/" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
               style="background-color: {{ $settings->button_color_wines ?? '#6b021d' }};">
                <i class="fas fa-home text-lg"></i><span>Inicio</span>
            </a>
            <a href="/menu" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
               style="background-color: {{ $settings->button_color_wines ?? '#6b021d' }};">
                <i class="fas fa-utensils text-lg"></i><span>Menú</span>
            </a>
            <a href="/cocktails" class="flex items-center gap-2 px-3 py-2 rounded-full text-sm font-semibold text-white transition hover:scale-105"
               style="background-color: {{ $settings->button_color_wines ?? '#6b021d' }};">
                <i class="fas fa-cocktail text-lg"></i><span>Cócteles</span>
            </a>
        </div>
    </div>
<!-- Tailwind CSS desde CDN -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Flowbite (incluye Alpine.js internamente) -->
<script src="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('🌐 DOM cargado correctamente');

    // 🔁 Activar toggle del menú lateral (si existe)
    const toggleMenuButton = document.getElementById('toggleMenu');
    const categoryMenu = document.getElementById('categoryMenu');

    if (toggleMenuButton && categoryMenu) {
        toggleMenuButton.addEventListener('click', () => {
            const isOpen = categoryMenu.classList.contains('left-0');
            categoryMenu.classList.toggle('-left-[300px]', isOpen);
            categoryMenu.classList.toggle('left-0', !isOpen);
        });
    }

    // 🔁 Enlaces de categorías con scroll suave
    const categoryLinks = document.querySelectorAll('#categoryMenu a');
    categoryLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const target = document.querySelector(targetId);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
            categoryMenu?.classList.add('-left-[300px]');
            categoryMenu?.classList.remove('left-0');
        });
    });

    // 🔁 Pop-ups (si tienes alguno activo)
    const popups = @json($popups);
    const now = new Date();
    const currentDay = now.getDay();

    popups.forEach(popup => {
        const start = new Date(popup.start_date);
        const end = new Date(popup.end_date);
        const repeatDays = popup.repeat_days ? popup.repeat_days.split(',').map(Number) : [];

        if (popup.active && now >= start && now <= end &&
            popup.view === 'wines' &&
            (repeatDays.length === 0 || repeatDays.includes(currentDay))) {
            showPopup(`{{ asset('storage') }}/${popup.image}`);
        }
    });

    function showPopup(imageUrl) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75';
        modal.innerHTML = `
            <div class="bg-white p-6 rounded shadow max-w-md">
                <img src="${imageUrl}" class="w-full h-auto rounded mb-4" alt="Pop-up">
                <button class="mt-2 w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="this.closest('div').parentElement.remove()">Cerrar</button>
            </div>
        `;
        document.body.appendChild(modal);
    }
</scrip>

</body>
</html>
