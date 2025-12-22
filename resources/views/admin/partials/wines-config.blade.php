<!-- resources/views/admin/partials/wines-config.blade.php -->
<form method="POST" action="{{ route('admin.updateBackground') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <h3 class="text-2xl font-bold text-gray-800">Configuración de Café &amp; bebidas</h3>

    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900" for="background_image_wines">Imagen de fondo (barra de café)</label>
        <input class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300" type="file" name="background_image_wines" id="background_image_wines">
    </div>
    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900" for="coffee_hero_image">Imagen destacada (hero)</label>
        <input class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300" type="file" name="coffee_hero_image" id="coffee_hero_image">
        @if($settings->coffee_hero_image)
            <img src="{{ asset('storage/' . $settings->coffee_hero_image) }}" class="mt-2 rounded-lg shadow" alt="Hero café">
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-900">Color del texto</label>
            <input type="color" name="text_color_wines" class="w-full h-10 border border-gray-300 rounded" value="{{ $settings->text_color_wines ?? '#000000' }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-900">Color del botón</label>
            <input type="color" name="button_color_wines" class="w-full h-10 border border-gray-300 rounded" value="{{ $settings->button_color_wines ?? '#000000' }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-900">Opacidad de tarjeta</label>
            <input type="number" step="0.1" name="card_opacity_wines" class="w-full border border-gray-300 rounded" value="{{ $settings->card_opacity_wines ?? 0.9 }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-900">Tamaño fuente categoría</label>
            <input type="number" name="category_name_font_size_wines" class="w-full border border-gray-300 rounded" value="{{ $settings->category_name_font_size_wines ?? 20 }}">
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-900">Fondo categoría</label>
            <input type="color" name="category_name_bg_color_wines" class="w-full h-10" value="{{ $settings->category_name_bg_color_wines ?? '#ffffff' }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-900">Texto categoría</label>
            <input type="color" name="category_name_text_color_wines" class="w-full h-10" value="{{ $settings->category_name_text_color_wines ?? '#000000' }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-900">Fondo tarjeta</label>
            <input type="color" name="card_bg_color_wines" class="w-full h-10" value="{{ $settings->card_bg_color_wines ?? '#ffffff' }}">
        </div>
    </div>

    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Guardar Cambios</button>
</form>
