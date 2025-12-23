<div id="cover-carousel-panel" class="border rounded-3 p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <div>
            <h5 class="mb-1">Carrusel destacado en portada</h5>
            <p class="text-muted small mb-0">Controla las tarjetas del slider que aparece entre las CTA y “Lo más vendido”. Cada tarjeta necesita un título e imagen.</p>
        </div>
        <button type="button" class="btn btn-outline-light btn-sm" data-section="dashboard">Cerrar</button>
    </div>

    <form action="{{ route('cover-carousel.store') }}" method="POST" enctype="multipart/form-data" class="row g-3 align-items-end mb-4">
        @csrf
        <div class="col-md-4">
            <label class="form-label">Título</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Subtítulo (opcional)</label>
            <input type="text" name="subtitle" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Imagen</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Label del botón</label>
            <input type="text" name="link_label" class="form-control">
        </div>
        <div class="col-md-5">
            <label class="form-label">URL del botón</label>
            <input type="url" name="link_url" class="form-control" placeholder="https://">
        </div>
        <div class="col-md-2">
            <label class="form-label">Posición</label>
            <input type="number" name="position" class="form-control" min="0" value="0">
        </div>
        <div class="col-md-2 text-end">
            <button class="btn btn-primary w-100">Añadir</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Preview</th>
                    <th>Título</th>
                    <th>Subtítulo</th>
                    <th>CTA</th>
                    <th>Posición</th>
                    <th>Visible</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($carouselItems as $item)
                    <tr>
                        <td style="width:120px;">
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="img-fluid rounded">
                        </td>
                        <td>
                            <form action="{{ route('cover-carousel.update', $item) }}" method="POST" enctype="multipart/form-data" class="row g-2">
                                @csrf
                                @method('PUT')
                                <div class="col-12">
                                    <input type="text" name="title" value="{{ $item->title }}" class="form-control form-control-sm" required>
                                </div>
                                <div class="col-12">
                                    <input type="text" name="subtitle" value="{{ $item->subtitle }}" class="form-control form-control-sm" placeholder="Subtítulo">
                                </div>
                                <div class="col-12">
                                    <input type="file" name="image" class="form-control form-control-sm">
                                    <small class="text-muted">Solo si deseas reemplazar la imagen.</small>
                                </div>
                        </td>
                        <td>
                                <input type="text" name="link_label" value="{{ $item->link_label }}" class="form-control form-control-sm mb-2" placeholder="Ej. Ver plato">
                                <input type="url" name="link_url" value="{{ $item->link_url }}" class="form-control form-control-sm" placeholder="https://">
                        </td>
                        <td class="text-center">
                                <input type="number" name="position" value="{{ $item->position }}" class="form-control form-control-sm" min="0">
                        </td>
                        <td class="text-center">
                                <div class="form-check d-flex justify-content-center">
                                    <input type="hidden" name="visible" value="0">
                                    <input class="form-check-input" type="checkbox" name="visible" value="1" {{ $item->visible ? 'checked' : '' }}>
                                </div>
                        </td>
                        <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary mb-2 w-100">Guardar</button>
                            </form>
                            <form action="{{ route('cover-carousel.destroy', $item) }}" method="POST" onsubmit="return confirm('¿Eliminar esta tarjeta del carrusel?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger w-100">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Aún no hay tarjetas en el carrusel.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
