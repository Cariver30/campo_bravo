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
                    <th>Contenido</th>
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
                            <strong>{{ $item->title }}</strong>
                            <p class="text-muted small mb-1">{{ $item->subtitle }}</p>
                            <small class="text-muted">ID: {{ $item->id }}</small>
                        </td>
                        <td>
                            <p class="mb-1">
                                <span class="badge bg-secondary">{{ $item->link_label ?? 'Sin label' }}</span>
                            </p>
                            <a href="{{ $item->link_url }}" target="_blank" class="small text-decoration-underline">
                                {{ $item->link_url ?: 'Sin URL' }}
                            </a>
                        </td>
                        <td class="text-center">{{ $item->position }}</td>
                        <td class="text-center">
                            <span class="badge {{ $item->visible ? 'bg-success' : 'bg-secondary' }}">
                                {{ $item->visible ? 'Activo' : 'Oculto' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex flex-column gap-2">
                                <button type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        data-carousel-edit="item-{{ $item->id }}">
                                    Editar
                                </button>
                                <form action="{{ route('cover-carousel.destroy', $item) }}" method="POST" onsubmit="return confirm('¿Eliminar esta tarjeta del carrusel?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger w-100">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr id="carousel-edit-item-{{ $item->id }}" class="d-none bg-light">
                        <td colspan="6">
                            <form action="{{ route('cover-carousel.update', $item) }}" method="POST" enctype="multipart/form-data" class="row g-3">
                                @csrf
                                @method('PUT')
                                <div class="col-md-6">
                                    <label class="form-label">Título</label>
                                    <input type="text" name="title" value="{{ $item->title }}" class="form-control form-control-sm" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Subtítulo</label>
                                    <input type="text" name="subtitle" value="{{ $item->subtitle }}" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Label del botón</label>
                                    <input type="text" name="link_label" value="{{ $item->link_label }}" class="form-control form-control-sm" placeholder="Ej. Ver plato">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">URL</label>
                                    <input type="url" name="link_url" value="{{ $item->link_url }}" class="form-control form-control-sm" placeholder="https://">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Posición</label>
                                    <input type="number" name="position" value="{{ $item->position }}" class="form-control form-control-sm" min="0">
                                </div>
                                <div class="col-md-2 d-flex align-items-center">
                                    <div class="form-check">
                                        <input type="hidden" name="visible" value="0">
                                        <input class="form-check-input" type="checkbox" name="visible" value="1" {{ $item->visible ? 'checked' : '' }} id="visible-item-{{ $item->id }}">
                                        <label class="form-check-label small" for="visible-item-{{ $item->id }}">Visible</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Imagen</label>
                                    <input type="file" name="image" class="form-control form-control-sm">
                                    <small class="text-muted">Sube una nueva imagen para reemplazar la actual.</small>
                                </div>
                                <div class="col-md-6 d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-carousel-edit="item-{{ $item->id }}">Cerrar</button>
                                    <button class="btn btn-primary btn-sm">Guardar cambios</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Aún no hay tarjetas en el carrusel.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('[data-carousel-edit]').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-carousel-edit');
            const row = document.getElementById(`carousel-edit-${targetId}`);
            if (row) {
                row.classList.toggle('d-none');
            }
        });
    });
</script>
@endpush
