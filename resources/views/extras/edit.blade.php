@extends('layouts.admin')

@section('title', 'Editar extra')

@section('content')
    <div class="max-w-3xl space-y-6">
        <a href="{{ request('redirect_to', route('extras.index')) }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800">
            ← Volver
        </a>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl space-y-6">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-amber-500 mb-2">Extras</p>
                <h1 class="text-3xl font-semibold text-slate-900">Editar extra</h1>
                <p class="text-slate-600">Actualiza el nombre, precio o disponibilidad.</p>
            </div>

            @if ($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <p class="font-semibold mb-1">Revisa la información:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('extras.update', $extra) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_to" value="{{ request('redirect_to', route('extras.index')) }}">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $extra->name) }}" required class="w-full rounded-2xl border border-slate-200 px-4 py-2.5">
                </div>
                <div class="grid sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Precio</label>
                        <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $extra->price) }}" required class="w-full rounded-2xl border border-slate-200 px-4 py-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Vista</label>
                        <select name="view_scope" class="w-full rounded-2xl border border-slate-200 px-4 py-2.5">
                            @foreach($viewScopes as $scope)
                                <option value="{{ $scope }}" @selected(old('view_scope', $extra->view_scope) === $scope)>
                                    {{ ucfirst($scope) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
                    <textarea name="description" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-2.5">{{ old('description', $extra->description) }}</textarea>
                </div>
                <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="active" value="1" @checked(old('active', $extra->active)) class="rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                    Disponible
                </label>

                <div class="flex flex-wrap items-center gap-3 justify-between pt-4">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-amber-400 px-6 py-2.5 text-slate-900 font-semibold">
                        Guardar cambios
                    </button>
                </div>
            </form>

            <form action="{{ route('extras.destroy', $extra) }}" method="POST" onsubmit="return confirm('¿Eliminar este extra?');" class="text-right">
                @csrf
                @method('DELETE')
                <input type="hidden" name="redirect_to" value="{{ request('redirect_to', route('extras.index')) }}">
                <button type="submit" class="text-rose-600 text-sm font-semibold">Eliminar este extra</button>
            </form>
        </div>
    </div>
@endsection
