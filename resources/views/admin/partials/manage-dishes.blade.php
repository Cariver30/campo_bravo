<!-- resources/views/admin/partials/manage-dishes.blade.php -->

<section class="mt-5">
    <h3>Platos</h3>
    <button class="btn btn-primary mb-3" onclick="toggleVisibility('dishes-list')">Gestionar Platos</button>
    <div id="dishes-list" class="hidden">
        <input type="text" id="searchDishesInput" onkeyup="filterDishes()" placeholder="Buscar platos..." class="form-control mb-3">
        <a href="{{ route('dishes.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Crear nuevo plato</a>
        <div id="dishesList">
            @foreach($dishes as $dish)
                <div class="card mb-3 dish-item" data-name="{{ $dish->name }}" data-category="{{ $dish->category->name }}">
                    <div class="card-body">
                        <h3 class="card-title">{{ $dish->name }}</h3>
                        <img src="{{ asset('storage/' . $dish->image) }}" alt="{{ $dish->name }}" class="img-thumbnail" style="max-width: 200px;">
                        <p class="card-text">{{ $dish->description }}</p>
                        <p class="card-text">${{ $dish->price }}</p>
                        <a href="{{ route('dishes.edit', $dish) }}" class="btn btn-outline-primary">Editar</a>
                        <button class="btn btn-outline-danger" form="delete-dish-{{ $dish->id }}">Eliminar</button>
                        <form id="delete-dish-{{ $dish->id }}" method="POST" action="{{ route('dishes.destroy', $dish) }}" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <form method="POST" action="{{ route('dishes.toggleVisibility', $dish) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn {{ $dish->visible ? 'btn-success' : 'btn-warning' }}">
                                {{ $dish->visible ? 'Visible' : 'Oculto' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="mt-5">
    <h3>Categorías de Platos</h3>
    <button class="btn btn-primary mb-3" onclick="toggleVisibility('dish-categories')">Categorías</button>
    <div id="dish-categories" class="hidden">
        <a href="{{ route('categories.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Crear nueva categoría</a>
        <div class="card my-3">
            <div class="card-body">
                <p class="text-muted small mb-2">Arrastra las categorías para reorganizar cómo aparecen en el menú.</p>
                <ul class="list-group category-sortable">
                    @foreach($categories->sortBy('order') as $category)
                        <li class="list-group-item d-flex align-items-center gap-2 sortable-item" data-id="{{ $category->id }}">
                            <span class="text-muted sortable-handle">&#x2630;</span>
                            <span class="flex-fill">{{ $category->name }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div id="categoriesList" class="category-card-sortable">
            @foreach($categories as $category)
                <div class="card mb-3 category-card sortable-item" data-id="{{ $category->id }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h3 class="card-title mb-0">{{ $category->name }}</h3>
                            <span class="text-muted small d-flex align-items-center gap-1 sortable-handle">
                                <i class="fas fa-grip-vertical"></i>
                                Arrastra
                            </span>
                        </div>
                        @if($category->dishes->count() > 0)
                            <ul class="list-group dish-sortable" data-category="{{ $category->id }}">
                                @foreach($category->dishes as $dish)
                                    <li class="list-group-item d-flex align-items-center gap-2 sortable-item" data-id="{{ $dish->id }}">
                                        <span class="text-muted">&#x2630;</span>
                                        <span class="flex-fill">{{ $dish->name }} - ${{ $dish->price }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <small class="text-muted">Arrastra para reordenar rápidamente.</small>
                        @else
                            <p>No dishes found for this category.</p>
                        @endif
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-primary">Editar</a>
                        <button class="btn btn-outline-danger" form="delete-category-{{ $category->id }}">Eliminar</button>
                        <form id="delete-category-{{ $category->id }}" method="POST" action="{{ route('categories.destroy', $category) }}" style="display:none;">
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
    function filterDishes() {
        let input = document.getElementById('searchDishesInput');
        let filter = input.value.toUpperCase();
        let dishesList = document.getElementById('dishesList');
        let dishItems = dishesList.getElementsByClassName('dish-item');

        for (let i = 0; i < dishItems.length; i++) {
            let name = dishItems[i].getAttribute('data-name');
            if (name.toUpperCase().indexOf(filter) > -1) {
                dishItems[i].style.display = "";
            } else {
                dishItems[i].style.display = "none";
            }
        }
    }
</script>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const saveCategoryOrder = (orderedIds) => {
            fetch('{{ route('categories.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: JSON.stringify({ order: orderedIds }),
            }).then(response => {
                if (!response.ok) throw new Error('HTTP '+response.status);
                return response.json();
            }).then(data => {
                if (!data.success) {
                    alert('No se pudo guardar el orden de categorías.');
                }
            }).catch(() => alert('Error al guardar el orden de categorías.'));
        };

        document.querySelectorAll('.category-sortable').forEach(list => {
            new Sortable(list, {
                animation: 150,
                handle: '.sortable-handle',
                ghostClass: 'bg-light',
                onEnd: evt => {
                    const order = Array.from(evt.to.querySelectorAll('.sortable-item')).map(item => item.dataset.id);
                    saveCategoryOrder(order);
                }
            });
        });

        document.querySelectorAll('.category-card-sortable').forEach(list => {
            new Sortable(list, {
                animation: 150,
                handle: '.sortable-handle',
                ghostClass: 'bg-light',
                onEnd: evt => {
                    const order = Array.from(evt.to.querySelectorAll('.category-card')).map(item => item.dataset.id);
                    saveCategoryOrder(order);
                }
            });
        });

        document.querySelectorAll('.dish-sortable').forEach(list => {
            new Sortable(list, {
                animation: 150,
                ghostClass: 'bg-light',
                onEnd: evt => {
                    const categoryId = evt.to.dataset.category;
                    const order = Array.from(evt.to.querySelectorAll('li')).map(item => item.dataset.id);

                    fetch('{{ route('dishes.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            category_id: categoryId,
                            order: order,
                        }),
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error('HTTP '+response.status);
                        }
                        return response.json();
                    }).then(data => {
                        if (!data.success) {
                            alert('No se pudo guardar el orden.');
                        }
                    }).catch(() => alert('Error al guardar el orden.'));
                }
            });
        });
    });
</script>
@endpush
