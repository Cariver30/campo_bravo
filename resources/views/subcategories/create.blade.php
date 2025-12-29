<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva subcategoría</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-white/90">
    <div class="max-w-3xl mx-auto py-12 px-4 space-y-8">
        <a href="{{ route('subcategories.index') }}" class="inline-flex items-center gap-2 text-sm text-white/70 hover:text-white transition">
            ← Volver a subcategorías
        </a>

        <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur p-8 shadow-2xl space-y-6">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-white/50">Menú creativo</p>
                <h1 class="text-3xl font-semibold">Crear subcategoría</h1>
                <p class="text-white/60 text-sm">Organiza tus platos dentro de bloques más específicos.</p>
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

            <form method="POST" action="{{ route('subcategories.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ route('subcategories.index') }}">
                <div>
                    <label class="block text-sm font-semibold text-white/80 mb-2">Nombre público</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-white/40 focus:border-amber-400 focus:ring-amber-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white/80 mb-2">Categoría madre</label>
                    <select name="category_id" required
                           class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white focus:border-amber-400 focus:ring-amber-400">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-white/80 mb-2">Color de fondo</label>
                        <input type="color" name="background_color" value="{{ old('background_color', '#ffffff') }}"
                               class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-2 h-12 text-white focus:border-amber-400 focus:ring-amber-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white/80 mb-2">Color de texto</label>
                        <input type="color" name="text_color" value="{{ old('text_color', '#000000') }}"
                               class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-2 h-12 text-white focus:border-amber-400 focus:ring-amber-400">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <p class="text-xs text-white/50">Se añadirá al final de la categoría seleccionada.</p>
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-full bg-amber-400 px-6 py-3 font-semibold text-slate-900 shadow-lg shadow-amber-400/40 hover:bg-amber-300 transition">
                        Guardar subcategoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
