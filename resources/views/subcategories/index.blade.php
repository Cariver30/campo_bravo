<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar subcategorías</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-white/90">
    <div class="max-w-5xl mx-auto py-10 px-4 lg:px-0 space-y-10">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <a href="{{ route('admin.new-panel', ['section' => 'menu']) }}" class="inline-flex items-center gap-2 text-sm text-white/70 hover:text-white transition">
                ← Volver al panel creativo
            </a>
            <a href="{{ route('subcategories.create') }}"
               class="inline-flex items-center gap-2 rounded-full bg-amber-400 px-5 py-2 text-slate-900 font-semibold shadow-lg shadow-amber-400/40 hover:bg-amber-300 transition">
                + Nueva subcategoría
            </a>
        </div>

        <div class="space-y-8">
            @forelse($categories as $category)
                <section class="border border-white/10 rounded-3xl bg-white/5 backdrop-blur p-6 space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-white/50">Categoría</p>
                            <h2 class="text-2xl font-semibold">{{ $category->name }}</h2>
                        </div>
                        <p class="text-sm text-white/50">{{ $category->subcategories->sum('dishes_count') }} platos conectados</p>
                    </div>
                    @if($category->subcategories->count())
                        <div class="divide-y divide-white/5 border border-white/10 rounded-2xl overflow-hidden">
                            @foreach($category->subcategories as $subcategory)
                                <article class="flex flex-wrap items-center gap-3 px-4 py-3 bg-slate-900/20">
                                    <div class="flex-1 min-w-[200px]">
                                        <p class="text-xs text-white/50 uppercase tracking-[0.3em] mb-1">Subcategoría</p>
                                        <h3 class="text-lg font-semibold">{{ $subcategory->name }}</h3>
                                    </div>
                                    <span class="text-sm text-white/60">Platos: {{ $subcategory->dishes_count }}</span>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('subcategories.edit', $subcategory) }}"
                                           class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl border border-white/20 hover:bg-white/10 transition text-sm">
                                            Editar
                                        </a>
                                        <form action="{{ route('subcategories.destroy', $subcategory) }}" method="POST" onsubmit="return confirm('¿Eliminar esta subcategoría?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="redirect_to" value="{{ route('subcategories.index') }}">
                                            <button type="submit"
                                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl border border-red-500/60 text-red-300 hover:bg-red-500/10 transition text-sm">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-white/60">Aún no hay subcategorías registradas para esta categoría.</p>
                    @endif
                </section>
            @empty
                <p class="text-center text-white/60">Todavía no hay categorías configuradas.</p>
            @endforelse
        </div>
    </div>
</body>
</html>
