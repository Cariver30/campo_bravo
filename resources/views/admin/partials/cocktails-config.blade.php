<!-- resources/views/admin/partials/cocktails-config.blade.php -->
<form action="{{ route('admin.updateBackground') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <h3>Configuraciones de Cocktails</h3>
    <div class="mb-3">
        <label for="background_image_cocktails" class="form-label">Imagen de Fondo de Cocktails</label>
        <input type="file" class="form-control" id="background_image_cocktails" name="background_image_cocktails">
    </div>
    <div class="mb-3">
        <label for="text_color_cocktails" class="form-label">Color del Texto de Cocktails</label>
        <input type="color" class="form-control" id="text_color_cocktails" name="text_color_cocktails" value="{{ $settings->text_color_cocktails ?? '#000000' }}">
    </div>
    <div class="mb-3">
        <label for="card_opacity_cocktails" class="form-label">Opacidad de las Tarjetas de Cocktails</label>
        <input type="number" step="0.1" class="form-control" id="card_opacity_cocktails" name="card_opacity_cocktails" value="{{ $settings->card_opacity_cocktails ?? 1 }}">
    </div>
    <div class="mb-3">
        <label for="button_color_cocktails" class="form-label">Color del Botón de Cocktails</label>
        <input type="color" class="form-control" id="button_color_cocktails" name="button_color_cocktails" value="{{ $settings->button_color_cocktails ?? '#000000' }}">
    </div>
    <div class="form-group">
        <label for="category_name_bg_color_cocktails">Color de fondo de la categoría (Cocktails):</label>
        <input type="color" class="form-control" id="category_name_bg_color_cocktails" name="category_name_bg_color_cocktails" value="{{ $settings->category_name_bg_color_cocktails ?? '#ffffff' }}">
    </div>
    <div class="form-group">
        <label for="category_name_text_color_cocktails">Color de texto de la categoría (Cocktails):</label>
        <input type="color" class="form-control" id="category_name_text_color_cocktails" name="category_name_text_color_cocktails" value="{{ $settings->category_name_text_color_cocktails ?? '#000000' }}">
    </div>
    <div class="form-group">
        <label for="category_name_font_size_cocktails">Tamaño de fuente de la categoría (Cocktails):</label>
        <input type="number" class="form-control" id="category_name_font_size_cocktails" name="category_name_font_size_cocktails" value="{{ $settings->category_name_font_size_cocktails ?? 16 }}">
    </div>
    <div class="form-group">
        <label for="card_bg_color_cocktails">Color de fondo de la tarjeta (Cocktails):</label>
        <input type="color" class="form-control" id="card_bg_color_cocktails" name="card_bg_color_cocktails" value="{{ $settings->card_bg_color_cocktails ?? '#ffffff' }}">
    </div>
    <button type="submit" class="btn btn-primary">Actualizar Cocktails</button>
</form>