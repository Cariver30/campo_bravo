<form action="{{ route('admin.updateBackground') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="logo" class="form-label">Logo</label>
        <input type="file" class="form-control" id="logo" name="logo">
        @if($settings && $settings->logo)
            <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo" class="img-fluid mt-2">
        @endif
    </div>

    <div class="mb-3">
        <label for="facebook_url" class="form-label">Facebook URL</label>
        <input type="url" class="form-control" id="facebook_url" name="facebook_url" value="{{ $settings->facebook_url ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="twitter_url" class="form-label">Twitter URL</label>
        <input type="url" class="form-control" id="twitter_url" name="twitter_url" value="{{ $settings->twitter_url ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="instagram_url" class="form-label">Instagram URL</label>
        <input type="url" class="form-control" id="instagram_url" name="instagram_url" value="{{ $settings->instagram_url ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="phone_number" class="form-label">Número de Teléfono</label>
        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $settings->phone_number ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="business_hours" class="form-label">Horarios de Atención</label>
        <textarea class="form-control" id="business_hours" name="business_hours">{{ $settings->business_hours ?? '' }}</textarea>
    </div>

    <div class="mb-3">
        <label for="font_family_cover" class="form-label">Familia de Fuente de Cover</label>
        <input type="text" class="form-control" id="font_family_cover" name="font_family_cover" value="{{ $settings->font_family_cover ?? 'Arial' }}">
    </div>
    <div class="mb-3">
        <label for="text_color_cover" class="form-label">Color del Texto de Cover</label>
        <input type="color" class="form-control" id="text_color_cover" name="text_color_cover" value="{{ $settings->text_color_cover ?? '#000000' }}">
    </div>
    <div class="mb-3">
        <label for="background_image_cover" class="form-label">Imagen de Fondo Cover</label>
        <input type="file" class="form-control" id="background_image_cover" name="background_image_cover">
    </div>
    <div class="mb-3">
        <label for="card_opacity_cover" class="form-label">Opacidad de las Tarjetas de Cover</label>
        <input type="number" step="0.1" class="form-control" id="card_opacity_cover" name="card_opacity_cover" value="{{ $settings->card_opacity_cover ?? 1 }}">
    </div>
    <div class="mb-3">
        <label for="button_color_cover" class="form-label">Color del Botón de Cover</label>
        <input type="color" class="form-control" id="button_color_cover" name="button_color_cover" value="{{ $settings->button_color_cover ?? '#000000' }}">
    </div>
    <div class="mb-3">
        <label for="button_font_size_cover" class="form-label">Tamaño de la Fuente del Botón de Cover</label>
        <input type="number" class="form-control" id="button_font_size_cover" name="button_font_size_cover" value="{{ $settings->button_font_size_cover ?? 18 }}">
    </div>
    <div class="mb-3">
        <label for="fixed_bottom_font_size" class="form-label">Tamaño de la Fuente de la Información Fija</label>
        <input type="number" class="form-control" id="fixed_bottom_font_size" name="fixed_bottom_font_size" value="{{ $settings->fixed_bottom_font_size ?? 14 }}">
    </div>
    <div class="mb-3">
        <label for="fixed_bottom_font_color" class="form-label">Color de la Fuente de la Información Fija</label>
        <input type="color" class="form-control" id="fixed_bottom_font_color" name="fixed_bottom_font_color" value="{{ $settings->fixed_bottom_font_color ?? '#000000' }}">
    </div>


    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
</form>
