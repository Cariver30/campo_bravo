<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar plato · Panel creativo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" />
</head>
<body class="min-h-screen bg-slate-950 text-white">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-0 space-y-8">
        <a href="{{ route('admin.new-panel', ['section' => 'menu']) }}" class="inline-flex items-center gap-2 text-sm text-white/70 hover:text-white transition">
            <span class="text-lg">←</span> Volver al panel
        </a>

        <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur p-8 shadow-2xl">
            <div class="space-y-2 mb-8">
                <p class="text-xs uppercase tracking-[0.35em] text-white/60">Menú creativo</p>
                <h1 class="text-3xl font-semibold">Editar plato</h1>
                <p class="text-white/60 text-sm">Actualiza los datos para sincronizar el menú público y la portada.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-500/40 bg-rose-500/10 p-4 text-sm text-rose-100">
                    <p class="font-semibold mb-2">Hay campos que revisar:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('dishes.update', $dish) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-semibold text-white/80 mb-2">Nombre del plato</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $dish->name) }}" required
                           class="block w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-white/40 focus:border-emerald-400 focus:ring-emerald-400" />
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-white/80 mb-2">Descripción</label>
                    <textarea id="description" name="description" rows="4"
                              class="block w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-white/40 focus:border-emerald-400 focus:ring-emerald-400">{{ old('description', $dish->description) }}</textarea>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-semibold text-white/80 mb-2">Precio</label>
                        <input type="number" id="price" name="price" step="0.01" value="{{ old('price', $dish->price) }}" required
                               class="block w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-white/40 focus:border-emerald-400 focus:ring-emerald-400" />
                    </div>
                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-white/80 mb-2">Categoría</label>
                        <select id="category_id" name="category_id" required
                                class="block w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white focus:border-emerald-400 focus:ring-emerald-400">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $dish->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="recommended_dishes" class="block text-sm font-semibold text-white/80 mb-2">Combínalo con otros platos</label>
                    <p class="text-xs text-white/50 mb-2">Selecciona los platos que se mostrarán como recomendaciones dentro de este ítem.</p>
                    @php
                        $selectedRecommendations = collect(old('recommended_dishes', $dish->recommendedDishes->pluck('id')->all()))
                            ->map(fn($value) => (int) $value);
                    @endphp
                    <select id="recommended_dishes" name="recommended_dishes[]" multiple size="6"
                            class="block w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white focus:border-emerald-400 focus:ring-emerald-400 max-h-56">
                        @foreach($allDishes as $availableDish)
                            @continue($availableDish->id === $dish->id)
                            <option value="{{ $availableDish->id }}" {{ $selectedRecommendations->contains($availableDish->id) ? 'selected' : '' }}>
                                {{ $availableDish->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="image" class="block text-sm font-semibold text-white/80 mb-2">Imagen (opcional)</label>
                    <div class="flex flex-col gap-3">
                        <input type="file" id="image" name="image"
                               class="block w-full rounded-2xl border border-dashed border-white/20 bg-white/5 px-4 py-3 text-white focus:border-emerald-400 focus:ring-emerald-400" />
                        @if($dish->image)
                            <img src="{{ asset('storage/' . $dish->image) }}" alt="{{ $dish->name }}"
                                 class="w-full rounded-2xl border border-white/10 object-cover max-h-60">
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    <input type="hidden" name="visible" value="0">
                    <input type="checkbox" id="visible" name="visible" value="1" {{ old('visible', $dish->visible) ? 'checked' : '' }}
                           class="w-5 h-5 rounded border-white/30 bg-transparent text-emerald-400 focus:ring-emerald-400" />
                    <label for="visible" class="text-sm text-white/80">
                        Mostrar este plato en el listado público
                    </label>
                </div>

                <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    <input type="hidden" name="featured_on_cover" value="0">
                    <input type="checkbox" id="featured_on_cover" name="featured_on_cover" value="1" {{ old('featured_on_cover', $dish->featured_on_cover) ? 'checked' : '' }}
                           class="w-5 h-5 rounded border-white/30 bg-transparent text-emerald-400 focus:ring-emerald-400" />
                    <label for="featured_on_cover" class="text-sm text-white/80">
                        Destacar en la portada (usa el bloque de su categoría)
                    </label>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4 pt-4">
                    <p class="text-xs text-white/50">Los cambios quedan guardados automáticamente al guardar.</p>
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-full bg-emerald-400 px-6 py-3 font-semibold text-slate-900 shadow-lg shadow-emerald-400/30 hover:bg-emerald-300 transition">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>
