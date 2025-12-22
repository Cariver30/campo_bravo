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
        <label for="card_bg_color_cover" class="form-label">Color de fondo de tarjetas Cover</label>
        <input type="color" class="form-control" id="card_bg_color_cover" name="card_bg_color_cover" value="{{ $settings->card_bg_color_cover ?? '#000000' }}">
        <small class="text-muted">Se mezcla con la opacidad configurada arriba.</small>
    </div>
    <div class="mb-3">
        <label for="button_color_cover" class="form-label">Color del Botón de Cover</label>
        <input type="color" class="form-control" id="button_color_cover" name="button_color_cover" value="{{ $settings->button_color_cover ?? '#000000' }}">
    </div>
    <div class="mb-3">
        <label for="button_font_size_cover" class="form-label">Tamaño de la Fuente del Botón de Cover</label>
        <input type="number" class="form-control" id="button_font_size_cover" name="button_font_size_cover" value="{{ $settings->button_font_size_cover ?? 18 }}">
    </div>

    <div class="border rounded-3 p-3 mb-4">
        <h5 class="mb-3">Etiquetas de botones en la portada</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="button_label_menu" class="form-label">Botón principal 1</label>
                <input type="text" class="form-control" id="button_label_menu" name="button_label_menu" value="{{ $settings->button_label_menu ?? 'Menú' }}">
            </div>
            <div class="col-md-6">
                <label for="button_label_cocktails" class="form-label">Botón principal 2</label>
                <input type="text" class="form-control" id="button_label_cocktails" name="button_label_cocktails" value="{{ $settings->button_label_cocktails ?? 'Cócteles' }}">
            </div>
            <div class="col-md-6">
                <label for="button_label_wines" class="form-label">Botón principal 3</label>
                <input type="text" class="form-control" id="button_label_wines" name="button_label_wines" value="{{ $settings->button_label_wines ?? 'Cafe' }}">
            </div>
            <div class="col-md-6">
                <label for="button_label_events" class="form-label">Botón principal 4</label>
                <input type="text" class="form-control" id="button_label_events" name="button_label_events" value="{{ $settings->button_label_events ?? 'Eventos especiales' }}">
            </div>
            <div class="col-md-6">
                <label for="button_label_vip" class="form-label">Botón lista VIP</label>
                <input type="text" class="form-control" id="button_label_vip" name="button_label_vip" value="{{ $settings->button_label_vip ?? 'Lista VIP' }}">
            </div>
            <div class="col-md-6">
                <label for="button_label_reservations" class="form-label">Botón de reservas</label>
                <input type="text" class="form-control" id="button_label_reservations" name="button_label_reservations" value="{{ $settings->button_label_reservations ?? 'Reservas' }}">
            </div>
        </div>
    </div>

    <div class="border rounded-3 p-3 mb-4">
        <h5 class="mb-3">Nombres de secciones (tabs)</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="tab_label_menu" class="form-label">Nombre para “Menú”</label>
                <input type="text" class="form-control" id="tab_label_menu" name="tab_label_menu" value="{{ $settings->tab_label_menu ?? 'Menú' }}">
            </div>
            <div class="col-md-6">
                <label for="tab_label_cocktails" class="form-label">Nombre para “{{ $settings->button_label_cocktails ?? 'Cócteles' }}”</label>
                <input type="text" class="form-control" id="tab_label_cocktails" name="tab_label_cocktails" value="{{ $settings->tab_label_cocktails ?? $settings->button_label_cocktails ?? 'Cócteles' }}">
            </div>
            <div class="col-md-6">
                <label for="tab_label_wines" class="form-label">Nombre para “Café &amp; Brunch”</label>
                <input type="text" class="form-control" id="tab_label_wines" name="tab_label_wines" value="{{ $settings->tab_label_wines ?? 'Café & Brunch' }}">
            </div>
            <div class="col-md-6">
                <label for="tab_label_events" class="form-label">Nombre para “Eventos”</label>
                <input type="text" class="form-control" id="tab_label_events" name="tab_label_events" value="{{ $settings->tab_label_events ?? 'Eventos' }}">
            </div>
            <div class="col-md-6">
                <label for="tab_label_loyalty" class="form-label">Nombre para “Fidelidad”</label>
                <input type="text" class="form-control" id="tab_label_loyalty" name="tab_label_loyalty" value="{{ $settings->tab_label_loyalty ?? 'Fidelidad' }}">
            </div>
        </div>
        <div class="row g-3 mt-3">
            <div class="col-md-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="show_tab_menu" name="show_tab_menu" {{ $settings->show_tab_menu ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_tab_menu">Mostrar Menú</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="show_tab_cocktails" name="show_tab_cocktails" {{ $settings->show_tab_cocktails ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_tab_cocktails">Mostrar Bebidas</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="show_tab_wines" name="show_tab_wines" {{ $settings->show_tab_wines ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_tab_wines">Mostrar Café &amp; Brunch</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" id="show_tab_events" name="show_tab_events" {{ $settings->show_tab_events ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_tab_events">Mostrar Eventos</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" id="show_tab_campaigns" name="show_tab_campaigns" {{ $settings->show_tab_campaigns ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_tab_campaigns">Mostrar Campañas</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" id="show_tab_popups" name="show_tab_popups" {{ $settings->show_tab_popups ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_tab_popups">Mostrar Pop-ups</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" id="show_tab_loyalty" name="show_tab_loyalty" {{ $settings->show_tab_loyalty ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_tab_loyalty">Mostrar Fidelidad</label>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label for="fixed_bottom_font_size" class="form-label">Tamaño de la Fuente de la Información Fija</label>
        <input type="number" class="form-control" id="fixed_bottom_font_size" name="fixed_bottom_font_size" value="{{ $settings->fixed_bottom_font_size ?? 14 }}">
    </div>
    <div class="mb-3">
        <label for="fixed_bottom_font_color" class="form-label">Color de la Fuente de la Información Fija</label>
        <input type="color" class="form-control" id="fixed_bottom_font_color" name="fixed_bottom_font_color" value="{{ $settings->fixed_bottom_font_color ?? '#000000' }}">
    </div>

    <div class="border rounded-3 p-3 mb-4">
        <h5 class="mb-3">Galería visual del cover</h5>
        <p class="text-muted small mb-3">Estas imágenes se muestran en la tarjeta hero (café, brunch y mimosas). Sube fotos horizontales en alta resolución.</p>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Imagen 1</label>
                <input type="file" class="form-control" name="cover_gallery_image_1">
                @if($settings->cover_gallery_image_1)
                    <img src="{{ asset('storage/' . $settings->cover_gallery_image_1) }}" class="img-fluid rounded mt-2" alt="Cover 1">
                @endif
            </div>
            <div class="col-md-4">
                <label class="form-label">Imagen 2</label>
                <input type="file" class="form-control" name="cover_gallery_image_2">
                @if($settings->cover_gallery_image_2)
                    <img src="{{ asset('storage/' . $settings->cover_gallery_image_2) }}" class="img-fluid rounded mt-2" alt="Cover 2">
                @endif
            </div>
            <div class="col-md-4">
                <label class="form-label">Imagen 3</label>
                <input type="file" class="form-control" name="cover_gallery_image_3">
                @if($settings->cover_gallery_image_3)
                    <img src="{{ asset('storage/' . $settings->cover_gallery_image_3) }}" class="img-fluid rounded mt-2" alt="Cover 3">
                @endif
            </div>
        </div>
    </div>

    <div class="border rounded-3 p-3 mb-4">
        <h5 class="mb-3">Imágenes para los CTA de portada</h5>
        <p class="text-muted small mb-3">Opcional: cada botón puede mostrar una miniatura. Deja el campo vacío para usar solo texto.</p>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">CTA Menú</label>
                <input type="file" class="form-control" name="cta_image_menu">
                @if($settings->cta_image_menu)
                    <img src="{{ asset('storage/' . $settings->cta_image_menu) }}" class="img-fluid rounded mt-2" alt="CTA Menú">
                @endif
            </div>
            <div class="col-md-4">
                <label class="form-label">CTA Café</label>
                <input type="file" class="form-control" name="cta_image_cafe">
                @if($settings->cta_image_cafe)
                    <img src="{{ asset('storage/' . $settings->cta_image_cafe) }}" class="img-fluid rounded mt-2" alt="CTA Café">
                @endif
            </div>
            <div class="col-md-4">
                <label class="form-label">CTA Cócteles</label>
                <input type="file" class="form-control" name="cta_image_cocktails">
                @if($settings->cta_image_cocktails)
                    <img src="{{ asset('storage/' . $settings->cta_image_cocktails) }}" class="img-fluid rounded mt-2" alt="CTA Cócteles">
                @endif
            </div>
            <div class="col-md-4">
                <label class="form-label">CTA Eventos</label>
                <input type="file" class="form-control" name="cta_image_events">
                @if($settings->cta_image_events)
                    <img src="{{ asset('storage/' . $settings->cta_image_events) }}" class="img-fluid rounded mt-2" alt="CTA Eventos">
                @endif
            </div>
            <div class="col-md-4">
                <label class="form-label">CTA Reservas</label>
                <input type="file" class="form-control" name="cta_image_reservations">
                @if($settings->cta_image_reservations)
                    <img src="{{ asset('storage/' . $settings->cta_image_reservations) }}" class="img-fluid rounded mt-2" alt="CTA Reservas">
                @endif
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
</form>
