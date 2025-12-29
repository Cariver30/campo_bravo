<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar subcategoría</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-white/90">
    <div class="max-w-3xl mx-auto py-12 px-4 space-y-8">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <a href="{{ route('subcategories.index') }}" class="inline-flex items-center gap-2 text-sm text-white/70 hover:text-white transition">
                ← Volver a subcategorías
            </a>
            <form action="{{ route('subcategories.destroy', $subcategory) }}" method="POST" onsubmit="return confirm('¿Eliminar esta subcategoría?');">
                @csrf
                @method('DELETE')
                <input type="hidden" name="redirect_to" value="{{ route('subcategories.index') }}">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-full border border-rose-500 text-rose-300 px-4 py-2 text-sm hover:bg-rose-500/10 transition">
                    Eliminar
                </button>
            </form>
        </div>

        <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur p-8 shadow-2xl space-y-6">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-white/50">Menú creativo</p>
                <h1 class="text-3xl font-semibold">Editar subcategoría</h1>
                <p class="text-white/60 text-sm">Actualiza el nombre o mueve esta subcategoría a otra categoría.</p>
            </div>

            @if ($errors->any())
                <div class="rounded-2xl border border-rose-500/40 bg-rose-500/10 p-4 text-sm text-rose-100">
                    <p class="font-semibold mb-2">Revisa la información:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('subcategories.update', $subcategory) }}" class="space-y-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_to" value="{{ route('subcategories.index') }}">
                <div>
                    <label class="block text-sm font-semibold text-white/80 mb-2">Nombre público</label>
                    <input type="text" name="name" value="{{ old('name', $subcategory->name) }}" required
                           class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-white/40 focus:border-emerald-400 focus:ring-emerald-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white/80 mb-2">Categoría madre</label>
                    <select name="category_id" required
                            class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white focus:border-emerald-400 focus:ring-emerald-400">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $subcategory->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <p class="text-xs text-white/50">Si cambias de categoría, se moverá al final automáticamente.</p>
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-full bg-emerald-400 px-6 py-3 font-semibold text-slate-900 shadow-lg shadow-emerald-400/40 hover:bg-emerald-300 transition">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
