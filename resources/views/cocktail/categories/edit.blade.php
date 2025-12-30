<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar categoría · Barra creativa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" />
</head>
<body class="min-h-screen bg-slate-950 text-white">
    <div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-0 space-y-8">
        <a href="{{ route('admin.new-panel', ['section' => 'cocktails', 'expand' => 'cocktail-categories']) }}"
           class="inline-flex items-center gap-2 text-sm text-white/70 hover:text-white transition">
            <span class="text-lg">←</span> Volver al panel
        </a>

        <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur p-8 shadow-2xl space-y-8">
            <div class="space-y-2">
                <p class="text-xs uppercase tracking-[0.35em] text-white/60">Mixología</p>
                <h1 class="text-3xl font-semibold">Editar categoría</h1>
                <p class="text-white/60 text-sm">Actualiza el nombre interno y lo que ven los clientes en la portada.</p>
            </div>

            @if (session('success'))
                <div class="rounded-2xl border border-emerald-500/40 bg-emerald-500/10 p-4 text-sm text-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-2xl border border-rose-500/40 bg-rose-500/10 p-4 text-sm text-rose-100">
                    <p class="font-semibold.mb-2">Corrige los siguientes campos:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('cocktail-categories.update', $cocktailCategory) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-semibold text-white/80 mb-2">Nombre interno</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $cocktailCategory->name) }}" required
                           class="block w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-white/40 focus:border-pink-400 focus:ring-pink-400" />
                    <p class="text-xs text-white/50 mt-2">Se usa dentro del panel y como respaldo del nombre público.</p>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="cover_title" class="block text-sm font-semibold text-white/80 mb-2">Nombre público</label>
                        <input type="text" id="cover_title" name="cover_title" value="{{ old('cover_title', $cocktailCategory->cover_title) }}"
                               class="block w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-white/40 focus:border-pink-400 focus:ring-pink-400" />
                        <p class="text-xs text-white/50 mt-2">Aparece en tabs, tarjetas y bloques del cover.</p>
                    </div>
                    <div>
                        <label for="cover_subtitle" class="block text-sm font-semibold text-white/80 mb-2">Descripción breve</label>
                        <input type="text" id="cover_subtitle" name="cover_subtitle" value="{{ old('cover_subtitle', $cocktailCategory->cover_subtitle) }}"
                               class="block w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-white/40 focus:border-pink-400 focus:ring-pink-400" />
                        <p class="text-xs text-white/50 mt-2">Se muestra debajo del título en la portada.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                    <input type="hidden" name="show_on_cover" value="0">
                    <input type="checkbox" id="show_on_cover" name="show_on_cover" value="1"
                           {{ old('show_on_cover', $cocktailCategory->show_on_cover) ? 'checked' : '' }}
                           class="w-5 h-5 rounded border-white/30 bg-transparent text-pink-400 focus:ring-pink-400" />
                    <label for="show_on_cover" class="text-sm text-white/80">Mostrar como pestaña en la portada</label>
                </div>

                <div class="flex items-center justify-between gap-4 pt-4">
                    <p class="text-xs text-white/50">Los cambios impactan inmediatamente la navegación del cliente.</p>
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-full bg-pink-400 px-6 py-3 font-semibold text-slate-900 shadow-lg shadow-pink-400/30 hover:bg-pink-300 transition">
                        Actualizar categoría
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>
