<section class="mt-5">
    <h3>Cocktails</h3>
    <button class="btn btn-primary mb-3" onclick="toggleVisibility('cocktails-list')">Gestionar Cocktails</button>
    <div id="cocktails-list" class="hidden">
        <input type="text" id="searchCocktailsInput" onkeyup="filterCocktails()" placeholder="Buscar cocktails..." class="form-control mb-3">
        <a href="{{ route('cocktails.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Crear nuevo cocktail</a>
        <div id="cocktailsList">
            @foreach($cocktails as $cocktail)
                <div class="card mb-3 cocktail-item" data-name="{{ $cocktail->name }}" data-category="{{ $cocktail->category->name }}">
                    <div class="card-body">
                        <h3 class="card-title">{{ $cocktail->name }}</h3>
                        <img src="{{ asset('storage/' . $cocktail->image) }}" alt="{{ $cocktail->name }}" class="img-thumbnail" style="max-width: 200px;">
                        <p class="card-text">{{ $cocktail->description }}</p>
                        <p class="card-text">${{ $cocktail->price }}</p>
                        <a href="{{ route('cocktails.edit', $cocktail) }}" class="btn btn-outline-primary">Editar</a>
                        <button class="btn btn-outline-danger" form="delete-cocktail-{{ $cocktail->id }}">Eliminar</button>
                        <form id="delete-cocktail-{{ $cocktail->id }}" method="POST" action="{{ route('cocktails.destroy', $cocktail) }}" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <form method="POST" action="{{ route('cocktails.toggleVisibility', $cocktail) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn {{ $cocktail->visible ? 'btn-success' : 'btn-warning' }}">
                                {{ $cocktail->visible ? 'Visible' : 'Oculto' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Categorías de Cocktails -->
<section class="mt-5">
    <h3>Categorías de Cocktails</h3>
    <button class="btn btn-primary mb-3" onclick="toggleVisibility('cocktail-categories')">Categorías</button>
    <div id="cocktail-categories" class="hidden">
        <a href="{{ route('cocktail-categories.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Crear nueva categoría de cocktail</a>
        <div class="card my-3">
            <div class="card-body">
                <p class="text-muted small mb-2">Arrastra para definir el orden en el listado de cócteles.</p>
                <ul class="list-group cocktail-category-sortable">
                    @foreach($cocktailCategories->sortBy('order') as $category)
                        <li class="list-group-item d-flex align-items-center gap-2 sortable-item" data-id="{{ $category->id }}">
                            <span class="text-muted">&#x2630;</span>
                            <span class="flex-fill">{{ $category->name }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div id="cocktailCategoriesList">
            @foreach($cocktailCategories as $category)
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="card-title">{{ $category->name }}</h3>
                        @if($category->items->count() > 0)
                            <ul class="list-group cocktail-sortable" data-category="{{ $category->id }}">
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
                        <a href="{{ route('cocktail-categories.edit', $category) }}" class="btn btn-outline-primary">Editar</a>
                        <button class="btn btn-outline-danger" form="delete-cocktail-category-{{ $category->id }}">Eliminar</button>
                        <form id="delete-cocktail-category-{{ $category->id }}" method="POST" action="{{ route('cocktail-categories.destroy', $category) }}" style="display:none;">
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
    function filterCocktails() {
        let input = document.getElementById('searchCocktailsInput');
        let filter = input.value.toUpperCase();
        let cocktailsList = document.getElementById('cocktailsList');
        let cocktailItems = cocktailsList.getElementsByClassName('cocktail-item');

        for (let i = 0; i < cocktailItems.length; i++) {
            let name = cocktailItems[i].getAttribute('data-name');
            if (name.toUpperCase().indexOf(filter) > -1) {
                cocktailItems[i].style.display = "";
            } else {
                cocktailItems[i].style.display = "none";
            }
        }
    }
</script>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.cocktail-category-sortable').forEach(list => {
            new Sortable(list, {
                animation: 150,
                ghostClass: 'bg-light',
                onEnd: evt => {
                    const order = Array.from(evt.to.querySelectorAll('li')).map(item => item.dataset.id);
                    fetch('{{ route('cocktail-categories.reorder') }}', {
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
                            alert('No se pudo guardar el orden de categorías de cócteles.');
                        }
                    })
                    .catch(() => alert('Error de red al guardar el orden de categorías.'));
                }
            });
        });

        document.querySelectorAll('.cocktail-sortable').forEach(list => {
            new Sortable(list, {
                animation: 150,
                ghostClass: 'bg-light',
                onEnd: evt => {
                    const categoryId = evt.to.dataset.category;
                    const order = Array.from(evt.to.querySelectorAll('li')).map(item => item.dataset.id);

                    fetch('{{ route('cocktails.reorder') }}', {
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
                            alert('No se pudo guardar el orden de cócteles.');
                        }
                    })
                    .catch(() => alert('Error de red al guardar el orden.'));
                }
            });
        });
    });
</script>
@endpush
