<section class="mt-5">
    <h3>Vinos</h3>
    <button class="btn btn-primary mb-3" onclick="toggleVisibility('wines-list')">Gestionar Vinos</button>
    <div id="wines-list" class="hidden">
        <input type="text" id="searchWinesInput" onkeyup="filterWines()" placeholder="Buscar vinos..." class="form-control mb-3">
        <a href="{{ route('wines.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Crear nuevo vino</a>
        <div id="winesList">
            @foreach($wines as $wine)
                <div class="card mb-3 wine-item" data-name="{{ $wine->name }}" data-category="{{ $wine->category->name }}">
                    <div class="card-body">
                        <h3 class="card-title">{{ $wine->name }}</h3>
                        <img src="{{ asset('storage/' . $wine->image) }}" alt="{{ $wine->name }}" class="img-thumbnail" style="max-width: 200px;">
                        <p class="card-text">{{ $wine->description }}</p>
                        <p class="card-text">${{ $wine->price }}</p>
                        <a href="{{ route('wines.edit', $wine) }}" class="btn btn-outline-primary">Editar</a>
                        <button class="btn btn-outline-danger" form="delete-wine-{{ $wine->id }}">Eliminar</button>
                        <form id="delete-wine-{{ $wine->id }}" method="POST" action="{{ route('wines.destroy', $wine) }}" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <form method="POST" action="{{ route('wines.toggleVisibility', $wine) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-secondary">
                                {{ $wine->visible ? 'Ocultar' : 'Mostrar' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Categorías de Vinos -->
<section class="mt-5">
    <h3>Categorías de Vinos</h3>
    <button class="btn btn-primary mb-3" onclick="toggleVisibility('wine-categories')">Categorías</button>
    <div id="wine-categories" class="hidden">
        <a href="{{ route('wine-categories.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Crear nueva categoría de vino</a>
        <div class="card my-3">
            <div class="card-body">
                <p class="text-muted small mb-2">Arrastra para definir el orden de las categorías en la carta de vinos.</p>
                <ul class="list-group wine-category-sortable">
                    @foreach($wineCategories->sortBy('order') as $category)
                        <li class="list-group-item d-flex align-items-center gap-2 sortable-item" data-id="{{ $category->id }}">
                            <span class="text-muted">&#x2630;</span>
                            <span class="flex-fill">{{ $category->name }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div id="wineCategoriesList">
            @foreach($wineCategories as $category)
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="card-title">{{ $category->name }}</h3>
                        @if($category->items->count() > 0)
                            <ul class="list-group wine-sortable" data-category="{{ $category->id }}">
                                @foreach($category->items as $item)
                                    <li class="list-group-item d-flex align-items-center gap-2 sortable-item" data-id="{{ $item->id }}">
                                        <span class="text-muted">&#x2630;</span>
                                        <span class="flex-fill">{{ $item->name }} - ${{ $item->price }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <small class="text-muted">Arrastra para reordenar esta categoría.</small>
                        @else
                            <p>No items found for this category.</p>
                        @endif
                        <a href="{{ route('wine-categories.edit', $category) }}" class="btn btn-outline-primary">Editar</a>
                        <button class="btn btn-outline-danger" form="delete-wine-category-{{ $category->id }}">Eliminar</button>
                        <form id="delete-wine-category-{{ $category->id }}" method="POST" action="{{ route('wine-categories.destroy', $category) }}" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    function filterWines() {
        let input = document.getElementById('searchWinesInput');
        let filter = input.value.toUpperCase();
        let winesList = document.getElementById('winesList');
        let wineItems = winesList.getElementsByClassName('wine-item');

        for (let i = 0; i < wineItems.length; i++) {
            let name = wineItems[i].getAttribute('data-name');
            if (name.toUpperCase().indexOf(filter) > -1) {
                wineItems[i].style.display = "";
            } else {
                wineItems[i].style.display = "none";
            }
        }
    }
</script>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.wine-category-sortable').forEach(list => {
            new Sortable(list, {
                animation: 150,
                ghostClass: 'bg-light',
                onEnd: evt => {
                    const order = Array.from(evt.to.querySelectorAll('li')).map(item => item.dataset.id);

                    fetch('{{ route('wine-categories.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ order }),
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('HTTP '+response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data.success) {
                            alert('No se pudo guardar el orden de categorías de vinos.');
                        }
                    })
                    .catch(() => alert('Error de red al guardar el orden de categorías.'));
                }
            });
        });

        document.querySelectorAll('.wine-sortable').forEach(list => {
            new Sortable(list, {
                animation: 150,
                ghostClass: 'bg-light',
                onEnd: evt => {
                    const categoryId = evt.to.dataset.category;
                    const order = Array.from(evt.to.querySelectorAll('li')).map(item => item.dataset.id);

                    fetch('{{ route('wines.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ category_id: categoryId, order }),
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('HTTP '+response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data.success) {
                            alert('No se pudo guardar el orden de vinos.');
                        }
                    })
                    .catch(() => alert('Error de red al guardar el orden.'));
                }
            });
        });
    });
</script>
@endpush
