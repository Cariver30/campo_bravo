<form action="{{ route('admin.updateBackground') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf
    @php
        $inputBase = 'block w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-slate-900 focus:border-amber-300 focus:ring-2 focus:ring-amber-200';
        $textareaBase = $inputBase . ' min-h-[60px]';
    @endphp

    <section class="bg-white/95 border border-slate-200 rounded-3xl p-6 shadow-sm space-y-6">
        <header class="flex flex-col gap-1">
            <p class="text-xs uppercase tracking-[0.35em] text-amber-500">Identidad</p>
            <h3 class="text-xl font-semibold text-slate-900">Logo y colores base</h3>
            <p class="text-sm text-slate-500">Estos controles alimentan el hero y los textos globales de la portada.</p>
        </header>
        <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <label for="logo" class="text-sm font-semibold text-slate-700">Logo principal</label>
                <input type="file" id="logo" name="logo" class="block w-full text-sm text-slate-900 border border-slate-200 rounded-2xl cursor-pointer focus:outline-none">
                @if($settings && $settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo actual" class="mt-3 rounded-2xl border border-slate-100 max-h-32 object-contain bg-slate-50 p-3">
                @endif
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-1">
                    <label for="font_family_cover" class="text-sm font-medium text-slate-700">Fuente de la portada</label>
                    <input type="text" id="font_family_cover" name="font_family_cover" value="{{ $settings->font_family_cover ?? 'Inter, sans-serif' }}" class="{{ $inputBase }}">
                </div>
                <div class="space-y-1">
                    <label for="text_color_cover" class="text-sm font-medium text-slate-700">Color títulos</label>
                    <input type="color" id="text_color_cover" name="text_color_cover" value="{{ $settings->text_color_cover ?? '#000000' }}" class="w-16 h-12 rounded-xl border border-slate-200">
                </div>
                <div class="space-y-1">
                    <label for="text_color_cover_secondary" class="text-sm font-medium text-slate-700">Color textos largos</label>
                    <input type="color" id="text_color_cover_secondary" name="text_color_cover_secondary" value="{{ $settings->text_color_cover_secondary ?? '#6b7280' }}" class="w-16 h-12 rounded-xl border border-slate-200">
                </div>
                <div class="space-y-1">
                    <label for="button_color_cover" class="text-sm font-medium text-slate-700">Color de botones</label>
                    <input type="color" id="button_color_cover" name="button_color_cover" value="{{ $settings->button_color_cover ?? '#ffb723' }}" class="w-16 h-12 rounded-xl border border-slate-200">
                </div>
            </div>
        </div>
        <div class="grid gap-6 md:grid-cols-3">
            <div class="space-y-1">
                <label for="button_font_size_cover" class="text-sm font-medium text-slate-700">Tamaño de texto en botones (px)</label>
                <input type="number" id="button_font_size_cover" name="button_font_size_cover" value="{{ $settings->button_font_size_cover ?? 18 }}" class="{{ $inputBase }}">
            </div>
            <div class="space-y-1">
                <label for="card_bg_color_cover" class="text-sm font-medium text-slate-700">Fondo de tarjetas</label>
                <input type="color" id="card_bg_color_cover" name="card_bg_color_cover" value="{{ $settings->card_bg_color_cover ?? '#397db5' }}" class="w-16 h-12 rounded-xl border border-slate-200">
                <p class="text-xs text-slate-400">Se mezcla con la opacidad para los bloques principales.</p>
            </div>
            <div class="space-y-1">
                <label for="card_opacity_cover" class="text-sm font-medium text-slate-700">Opacidad de tarjetas</label>
                <input type="number" step="0.1" id="card_opacity_cover" name="card_opacity_cover" value="{{ $settings->card_opacity_cover ?? 0.85 }}" class="{{ $inputBase }}">
            </div>
        </div>
    </section>

    <section class="bg-white/95 border border-slate-200 rounded-3xl p-6 shadow-sm space-y-6">
        <header>
            <p class="text-xs uppercase tracking-[0.35em] text-amber-500">Hero + contacto</p>
            <h3 class="text-xl font-semibold text-slate-900">Narrativa de bienvenida</h3>
        </header>
        <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-1">
                <label for="background_image_cover" class="text-sm font-medium text-slate-700">Imagen de fondo</label>
                <input type="file" id="background_image_cover" name="background_image_cover" class="block w-full text-sm text-slate-900 border border-slate-200 rounded-2xl cursor-pointer focus:outline-none">
                @if($settings->background_image_cover)
                    <img src="{{ asset('storage/' . $settings->background_image_cover) }}" class="mt-3 rounded-2xl border border-slate-100 max-h-48 object-cover" alt="Fondo actual">
                @endif
            </div>
            <div class="grid gap-4">
                <div class="space-y-1">
                    <label for="business_hours" class="text-sm font-medium text-slate-700">Horarios</label>
                    <textarea id="business_hours" name="business_hours" rows="3" class="{{ $textareaBase }}">{{ $settings->business_hours ?? '' }}</textarea>
                </div>
                <div class="space-y-1">
                    <label for="phone_number" class="text-sm font-medium text-slate-700">Teléfono</label>
                    <input type="text" id="phone_number" name="phone_number" value="{{ $settings->phone_number ?? '' }}" class="{{ $inputBase }}">
                </div>
                <div class="space-y-1">
                    <label for="cover_location_text" class="text-sm font-medium text-slate-700">Ubicación corta</label>
                    <input type="text" id="cover_location_text" name="cover_location_text" value="{{ $settings->cover_location_text ?? '' }}" class="{{ $inputBase }}" placeholder="Prosecco · Bayamón">
                </div>
            </div>
        </div>
        <div class="grid gap-4">
            <input type="text" id="cover_hero_kicker" name="cover_hero_kicker" value="{{ $settings->cover_hero_kicker ?? '' }}" class="{{ $inputBase }}" placeholder="Etiqueta superior (ej. cocina creativa)">
            <input type="text" id="cover_hero_title" name="cover_hero_title" value="{{ $settings->cover_hero_title ?? '' }}" class="{{ $inputBase }} rounded-3xl text-lg font-semibold" placeholder="Título principal">
            <textarea id="cover_hero_paragraph" name="cover_hero_paragraph" rows="3" class="{{ $textareaBase }}" placeholder="Descripción breve del concepto">{{ $settings->cover_hero_paragraph ?? '' }}</textarea>
        </div>
        <div class="grid gap-4 md:grid-cols-3">
            <div class="space-y-1">
                <label for="facebook_url" class="text-sm font-medium text-slate-700">Facebook</label>
                <input type="url" id="facebook_url" name="facebook_url" value="{{ $settings->facebook_url ?? '' }}" class="{{ $inputBase }}">
            </div>
            <div class="space-y-1">
                <label for="twitter_url" class="text-sm font-medium text-slate-700">Twitter/X</label>
                <input type="url" id="twitter_url" name="twitter_url" value="{{ $settings->twitter_url ?? '' }}" class="{{ $inputBase }}">
            </div>
            <div class="space-y-1">
                <label for="instagram_url" class="text-sm font-medium text-slate-700">Instagram</label>
                <input type="url" id="instagram_url" name="instagram_url" value="{{ $settings->instagram_url ?? '' }}" class="{{ $inputBase }}">
            </div>
            <div class="space-y-1">
                <label for="social_icon_bg_color" class="text-sm font-medium text-slate-700">Color fondo íconos</label>
                <input type="color" id="social_icon_bg_color" name="social_icon_bg_color" value="{{ $settings->social_icon_bg_color ?? '#ffb723' }}" class="w-16 h-12 rounded-xl border border-slate-200">
            </div>
            <div class="space-y-1">
                <label for="social_icon_icon_color" class="text-sm font-medium text-slate-700">Color íconos</label>
                <input type="color" id="social_icon_icon_color" name="social_icon_icon_color" value="{{ $settings->social_icon_icon_color ?? '#762d79' }}" class="w-16 h-12 rounded-xl border border-slate-200">
            </div>
        </div>
    </section>

    @php
        $ctaImages = [
            'menu' => 'Menú',
            'cafe' => 'Cava de vinos',
            'cocktails' => 'Bebidas',
            'events' => 'Eventos',
            'reservations' => 'Reservas',
        ];
        $ctaVisibility = [
            'show_cta_menu' => 'Mostrar Menú',
            'show_cta_cafe' => 'Mostrar Cava',
            'show_cta_cocktails' => 'Mostrar Bebidas',
            'show_cta_events' => 'Mostrar Eventos',
            'show_cta_reservations' => 'Mostrar Reservas',
        ];
        $ctaLabels = [
            'menu' => 'Menú',
            'cafe' => 'Cava de vinos',
            'cocktails' => 'Bebidas',
            'events' => 'Eventos',
            'reservations' => 'Reservas',
            'vip' => 'Lista VIP',
        ];
    @endphp

    <section class="bg-white/95 border border-slate-200 rounded-3xl p-6 shadow-sm space-y-6">
        <header>
            <p class="text-xs uppercase tracking-[0.35em] text-amber-500">CTA grid</p>
            <h3 class="text-xl font-semibold text-slate-900">Imagen, colores y textos por tarjeta</h3>
        </header>
        <div class="grid gap-6 md:grid-cols-3">
            @foreach($ctaImages as $key => $label)
                <div class="space-y-2 border border-slate-100 rounded-2xl p-4">
                    <p class="text-sm font-semibold text-slate-700">{{ $label }}</p>
                    <input type="file" name="cta_image_{{ $key }}" class="block w-full text-sm text-slate-900 border border-slate-200 rounded-2xl cursor-pointer focus:outline-none">
                    @if($settings->{'cta_image_'.$key})
                        <img src="{{ asset('storage/' . $settings->{'cta_image_'.$key}) }}" class="mt-2 rounded-2xl object-cover max-h-32 w-full" alt="CTA {{ $label }}">
                    @endif
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-center">
                        <div>
                            <label class="text-xs text-slate-500 block">Fondo</label>
                            <input type="color" name="cover_cta_{{ $key }}_bg_color" value="{{ $settings->{'cover_cta_'.$key.'_bg_color'} ?? '#397db5' }}" class="w-14 h-10 rounded-xl border border-slate-200">
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 block">Texto</label>
                            <input type="color" name="cover_cta_{{ $key }}_text_color" value="{{ $settings->{'cover_cta_'.$key.'_text_color'} ?? '#fff2b3' }}" class="w-14 h-10 rounded-xl border border-slate-200">
                        </div>
                        <div class="md:col-span-1 col-span-2">
                            <label class="text-xs text-slate-500 block">Botón</label>
                            <input type="color" name="cover_cta_{{ $key }}_button_color" value="{{ $settings->{'cover_cta_'.$key.'_button_color'} ?? '#ffb723' }}" class="w-14 h-10 rounded-xl border border-slate-200">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($ctaVisibility as $field => $label)
                <label class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700">
                    <span>{{ $label }}</span>
                    <span class="flex items-center gap-2">
                        <span class="text-xs text-slate-400">{{ ($settings->{$field} ?? true) ? 'On' : 'Off' }}</span>
                        <input type="hidden" name="{{ $field }}" value="0">
                        <input type="checkbox" name="{{ $field }}" value="1" class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-400" {{ ($settings->{$field} ?? true) ? 'checked' : '' }}>
                    </span>
                </label>
            @endforeach
        </div>
        <div class="space-y-4">
            @foreach($ctaLabels as $key => $label)
                <div class="border border-slate-100 rounded-3xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-900">{{ $label }}</p>
                        @if($key === 'vip')
                            <label class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                                <input type="hidden" name="show_cta_vip" value="0">
                                <input type="checkbox" name="show_cta_vip" value="1" class="rounded text-amber-500 border-slate-300 focus:ring-amber-400" {{ ($settings->show_cta_vip ?? true) ? 'checked' : '' }}>
                                Mostrar tarjeta VIP
                            </label>
                        @endif
                    </div>
                    <div class="grid gap-4 md:grid-cols-3">
                        <input type="text" name="cover_cta_{{ $key }}_subtitle" value="{{ $settings->{'cover_cta_'.$key.'_subtitle'} ?? '' }}" class="{{ $inputBase }}" placeholder="Subtítulo">
                        <input type="text" name="cover_cta_{{ $key }}_button_text" value="{{ $settings->{'cover_cta_'.$key.'_button_text'} ?? '' }}" class="{{ $inputBase }}" placeholder="Texto del botón">
                        @if(in_array($key, ['menu','cafe','cocktails','events','reservations']))
                            <input type="text" name="button_label_{{ $key === 'cafe' ? 'wines' : $key }}" value="{{ $settings->{'button_label_'.($key === 'cafe' ? 'wines' : $key)} ?? '' }}" class="{{ $inputBase }}" placeholder="Etiqueta del botón">
                        @elseif($key === 'vip')
                            <input type="text" name="button_label_vip" value="{{ $settings->button_label_vip ?? '' }}" class="{{ $inputBase }}" placeholder="Etiqueta del botón">
                        @endif
                    </div>
                    <textarea name="cover_cta_{{ $key }}_copy" rows="2" class="{{ $textareaBase }}" placeholder="Descripción corta">{{ $settings->{'cover_cta_'.$key.'_copy'} ?? '' }}</textarea>
                    @if($key === 'vip')
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-xs text-slate-500 block mb-1">Fondo VIP</label>
                                <input type="color" name="cover_cta_vip_bg_color" value="{{ $settings->cover_cta_vip_bg_color ?? '#762d79' }}" class="w-16 h-10 rounded-xl border border-slate-200">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 block mb-1">Texto VIP</label>
                                <input type="color" name="cover_cta_vip_text_color" value="{{ $settings->cover_cta_vip_text_color ?? '#fff2b3' }}" class="w-16 h-10 rounded-xl border border-slate-200">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 block mb-1">Botón VIP</label>
                                <input type="color" name="cover_cta_vip_button_color" value="{{ $settings->cover_cta_vip_button_color ?? '#ffb723' }}" class="w-16 h-10 rounded-xl border border-slate-200">
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    <section class="bg-white/95 border border-slate-200 rounded-3xl p-6 shadow-sm space-y-6">
        <header>
            <p class="text-xs uppercase tracking-[0.35em] text-amber-500">Tabs visibles</p>
            <h3 class="text-xl font-semibold text-slate-900">Nombres y visibilidad</h3>
        </header>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-1">
                <label class="text-sm font-medium text-slate-700">Nombre Menú</label>
                <input type="text" name="tab_label_menu" value="{{ $settings->tab_label_menu ?? 'Menú' }}" class="{{ $inputBase }}">
            </div>
            <div class="space-y-1">
                <label class="text-sm font-medium text-slate-700">Nombre {{ $settings->tab_label_cocktails ?? 'Cócteles' }}</label>
                <input type="text" name="tab_label_cocktails" value="{{ $settings->tab_label_cocktails ?? $settings->button_label_cocktails ?? 'Cócteles' }}" class="{{ $inputBase }}">
            </div>
            <div class="space-y-1">
                <label class="text-sm font-medium text-slate-700">Nombre Cava</label>
                <input type="text" name="tab_label_wines" value="{{ $settings->tab_label_wines ?? 'Cava de vinos' }}" class="{{ $inputBase }}">
            </div>
            <div class="space-y-1">
                <label class="text-sm font-medium text-slate-700">Nombre Eventos</label>
                <input type="text" name="tab_label_events" value="{{ $settings->tab_label_events ?? 'Eventos' }}" class="{{ $inputBase }}">
            </div>
            <div class="space-y-1">
                <label class="text-sm font-medium text-slate-700">Nombre Fidelidad</label>
                <input type="text" name="tab_label_loyalty" value="{{ $settings->tab_label_loyalty ?? 'Fidelidad' }}" class="{{ $inputBase }}">
            </div>
        </div>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach([
                'show_tab_menu' => 'Menú',
                'show_tab_cocktails' => 'Bebidas',
                'show_tab_wines' => 'Cava',
                'show_tab_events' => 'Eventos',
                'show_tab_campaigns' => 'Campañas',
                'show_tab_popups' => 'Pop-ups',
                'show_tab_loyalty' => 'Fidelidad',
            ] as $field => $label)
                <label class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700">
                    <span>{{ $label }}</span>
                    <span class="flex items-center gap-2 text-xs text-slate-400">
                        <input type="hidden" name="{{ $field }}" value="0">
                        <input type="checkbox" name="{{ $field }}" value="1" class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-400" {{ ($settings->{$field} ?? false) ? 'checked' : '' }}>
                    </span>
                </label>
            @endforeach
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label for="fixed_bottom_font_size" class="text-sm font-medium text-slate-700">Tamaño texto fijo</label>
                <input type="number" id="fixed_bottom_font_size" name="fixed_bottom_font_size" value="{{ $settings->fixed_bottom_font_size ?? 14 }}" class="{{ $inputBase }}">
            </div>
            <div>
                <label for="fixed_bottom_font_color" class="text-sm font-medium text-slate-700">Color texto fijo</label>
                <input type="color" id="fixed_bottom_font_color" name="fixed_bottom_font_color" value="{{ $settings->fixed_bottom_font_color ?? '#ffffff' }}" class="w-16 h-12 rounded-xl border border-slate-200">
            </div>
        </div>
    </section>

    <section class="bg-white/95 border border-slate-200 rounded-3xl p-6 shadow-sm space-y-6">
        <header>
            <p class="text-xs uppercase tracking-[0.35em] text-amber-500">Fidelidad + bloques destacados</p>
            <h3 class="text-xl font-semibold text-slate-900">Tarjetas con copy editable</h3>
        </header>
        <div class="space-y-3 border border-slate-100 rounded-3xl p-4">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold text-slate-900">Tarjeta de fidelidad</p>
                <label class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                    <input type="hidden" name="show_cover_loyalty_card" value="0">
                    <input type="checkbox" name="show_cover_loyalty_card" value="1" class="rounded text-amber-500 border-slate-300 focus:ring-amber-400" {{ ($settings->show_cover_loyalty_card ?? true) ? 'checked' : '' }}>
                    Mostrar
                </label>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <input type="text" name="cover_loyalty_label" value="{{ $settings->cover_loyalty_label ?? '' }}" class="{{ $inputBase }}" placeholder="Etiqueta">
                <input type="text" name="cover_loyalty_title" value="{{ $settings->cover_loyalty_title ?? '' }}" class="{{ $inputBase }}" placeholder="Título">
            </div>
            <textarea name="cover_loyalty_description" rows="3" class="{{ $textareaBase }}" placeholder="Descripción">{{ $settings->cover_loyalty_description ?? '' }}</textarea>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-1">
                <label for="featured_card_bg_color" class="text-sm font-medium text-slate-700">Fondo “Lo más vendido”</label>
                <input type="color" id="featured_card_bg_color" name="featured_card_bg_color" value="{{ $settings->featured_card_bg_color ?? '#762d79' }}" class="w-16 h-12 rounded-xl border border-slate-200">
            </div>
            <div class="space-y-1">
                <label for="featured_card_text_color" class="text-sm font-medium text-slate-700">Texto “Lo más vendido”</label>
                <input type="color" id="featured_card_text_color" name="featured_card_text_color" value="{{ $settings->featured_card_text_color ?? '#fff2b3' }}" class="w-16 h-12 rounded-xl border border-slate-200">
            </div>
            <div class="space-y-1">
                <label for="featured_tab_bg_color" class="text-sm font-medium text-slate-700">Fondo pestaña activa</label>
                <input type="color" id="featured_tab_bg_color" name="featured_tab_bg_color" value="{{ $settings->featured_tab_bg_color ?? '#ffb723' }}" class="w-16 h-12 rounded-xl border border-slate-200">
            </div>
            <div class="space-y-1">
                <label for="featured_tab_text_color" class="text-sm font-medium text-slate-700">Texto pestañas</label>
                <input type="color" id="featured_tab_text_color" name="featured_tab_text_color" value="{{ $settings->featured_tab_text_color ?? '#0f172a' }}" class="w-16 h-12 rounded-xl border border-slate-200">
            </div>
            <div class="space-y-1">
                <label for="featured_price_color" class="text-sm font-medium text-slate-700">Color de precios</label>
                <input type="color" id="featured_price_color" name="featured_price_color" value="{{ $settings->featured_price_color ?? '#ffb723' }}" class="w-16 h-12 rounded-xl border border-slate-200">
            </div>
        </div>
    </section>

    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-amber-500 px-6 py-3 font-semibold text-slate-900 shadow hover:bg-amber-400 transition">
        Guardar cambios
    </button>
</form>

@if(isset($managers))
    <section class="mt-8 bg-white/95 border border-slate-200 rounded-3xl p-6 shadow-sm space-y-6">
        <header>
            <p class="text-xs uppercase tracking-[0.35em] text-amber-500">Accesos de gerentes</p>
            <h3 class="text-xl font-semibold text-slate-900">Invita y controla lo que pueden ver</h3>
            <p class="text-sm text-slate-500">Usa este bloque para enviar enlaces privados y pausar accesos. Los tabs que ocultes arriba tampoco estarán disponibles para ellos.</p>
        </header>

        <div class="grid gap-6 lg:grid-cols-2">
            <form action="{{ route('admin.managers.store') }}" method="POST" class="space-y-4 rounded-2xl border border-slate-200 p-5 shadow-sm">
                @csrf
                <div>
                    <label class="text-sm font-semibold text-slate-700 block mb-1">Nombre completo</label>
                    <input type="text" name="name" required class="{{ $inputBase }}" placeholder="Ej. Laura Gerente">
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700 block mb-1">Correo</label>
                    <input type="email" name="email" required class="{{ $inputBase }}" placeholder="gerente@ejemplo.com">
                </div>
                <p class="text-xs text-slate-500">El sistema envía un correo con enlace para establecer contraseña y activar su sesión.</p>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-amber-500 px-5 py-2.5 font-semibold text-slate-900 shadow hover:bg-amber-400 transition">
                    Invitar gerente
                </button>
            </form>

            <article class="rounded-2xl border border-slate-200 p-5 bg-slate-50/80 text-slate-700 text-sm space-y-2 shadow-inner">
                <p>• Los gerentes solo pueden entrar a los tabs habilitados y no pueden modificar configuraciones críticas.</p>
                <p>• Si deseas restringir una vista para todos excepto admin, desactiva el tab correspondiente en el bloque anterior.</p>
                <p>• Usa “Bloquear” para pausar el acceso sin borrar el historial.</p>
            </article>
        </div>

        <div class="space-y-3">
            @forelse($managers as $manager)
                <div class="rounded-2xl border border-slate-200 p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white">
                    <div>
                        <p class="text-base font-semibold text-slate-900">{{ $manager->name }}</p>
                        <p class="text-sm text-slate-500">{{ $manager->email }}</p>
                        <div class="flex flex-wrap gap-3 text-xs mt-2 text-slate-500">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full {{ $manager->active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $manager->active ? 'Activo' : 'Bloqueado' }}
                            </span>
                            <span>Invitado: {{ optional($manager->invitation_sent_at)->format('d/m/Y H:i') ?? '—' }}</span>
                            <span>{{ $manager->invitation_accepted_at ? 'Acceso confirmado' : 'Pendiente de aceptar' }}</span>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <form action="{{ route('admin.managers.toggle', $manager) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold {{ $manager->active ? 'text-rose-600 hover:border-rose-300' : 'text-emerald-600 hover:border-emerald-300' }}">
                                {{ $manager->active ? 'Bloquear' : 'Activar' }}
                            </button>
                        </form>
                        @if(! $manager->invitation_accepted_at)
                            <form action="{{ route('admin.managers.resend', $manager) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-amber-300">
                                    Reenviar enlace
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.managers.destroy', $manager) }}" method="POST" onsubmit="return confirm('¿Eliminar este gerente? Esta acción no se puede deshacer.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-full border border-rose-200 px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-500">Aún no has invitado gerentes. Usa el formulario para enviar el primer acceso.</p>
            @endforelse
        </div>
    </section>
@endif
