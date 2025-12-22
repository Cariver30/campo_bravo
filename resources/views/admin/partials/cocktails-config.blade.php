<!-- resources/views/admin/partials/cocktails-config.blade.php -->
@php $cocktailLabel = $cocktailLabel ?? 'Cócteles'; @endphp
<form action="{{ route('admin.updateBackground') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <h3>Configuraciones de {{ $cocktailLabel }}</h3>
    <div class="mb-3">
        <label for="background_image_cocktails" class="form-label">Imagen de Fondo de {{ $cocktailLabel }}</label>
        <input type="file" class="form-control" id="background_image_cocktails" name="background_image_cocktails">
    </div>
    <div class="mb-3">
        <label for="cocktail_hero_image" class="form-label">Imagen destacada (hero)</label>
        <input type="file" class="form-control" id="cocktail_hero_image" name="cocktail_hero_image">
        @if($settings->cocktail_hero_image)
            <img src="{{ asset('storage/' . $settings->cocktail_hero_image) }}" class="img-fluid rounded mt-2" alt="Hero cócteles">
        @endif
    </div>
    <div class="mb-3">
        <label for="text_color_cocktails" class="form-label">Color del Texto de {{ $cocktailLabel }}</label>
        <input type="color" class="form-control" id="text_color_cocktails" name="text_color_cocktails" value="{{ $settings->text_color_cocktails ?? '#000000' }}">
    </div>
    <div class="mb-3">
        <label for="card_opacity_cocktails" class="form-label">Opacidad de las Tarjetas de {{ $cocktailLabel }}</label>
        <input type="number" step="0.1" class="form-control" id="card_opacity_cocktails" name="card_opacity_cocktails" value="{{ $settings->card_opacity_cocktails ?? 1 }}">
    </div>
    <div class="mb-3">
        <label for="button_color_cocktails" class="form-label">Color del Botón de {{ $cocktailLabel }}</label>
        <input type="color" class="form-control" id="button_color_cocktails" name="button_color_cocktails" value="{{ $settings->button_color_cocktails ?? '#000000' }}">
    </div>
    <div class="form-group">
        <label for="category_name_bg_color_cocktails">Color de fondo de la categoría ({{ $cocktailLabel }}):</label>
        <input type="color" class="form-control" id="category_name_bg_color_cocktails" name="category_name_bg_color_cocktails" value="{{ $settings->category_name_bg_color_cocktails ?? '#ffffff' }}">
    </div>
    <div class="form-group">
        <label for="category_name_text_color_cocktails">Color de texto de la categoría ({{ $cocktailLabel }}):</label>
        <input type="color" class="form-control" id="category_name_text_color_cocktails" name="category_name_text_color_cocktails" value="{{ $settings->category_name_text_color_cocktails ?? '#000000' }}">
    </div>
    <div class="form-group">
        <label for="category_name_font_size_cocktails">Tamaño de fuente de la categoría ({{ $cocktailLabel }}):</label>
        <input type="number" class="form-control" id="category_name_font_size_cocktails" name="category_name_font_size_cocktails" value="{{ $settings->category_name_font_size_cocktails ?? 16 }}">
    </div>
    <div class="form-group">
        <label for="card_bg_color_cocktails">Color de fondo de la tarjeta ({{ $cocktailLabel }}):</label>
        <input type="color" class="form-control" id="card_bg_color_cocktails" name="card_bg_color_cocktails" value="{{ $settings->card_bg_color_cocktails ?? '#ffffff' }}">
    </div>
    <button type="submit" class="btn btn-primary">Actualizar {{ $cocktailLabel }}</button>
</form>
