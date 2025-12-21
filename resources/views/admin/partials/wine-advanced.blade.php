<!-- Tipos de Vino -->
<section class="mt-5">
    <h3>Tipos de Vino</h3>
    <button class="btn btn-primary mb-3" onclick="toggleVisibility('wine-types-list')">Gestionar Tipos</button>
    <div id="wine-types-list" class="hidden">
        <a href="{{ route('wine-types.create') }}" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Crear nuevo tipo</a>
        @foreach($wineTypes as $type)
            <div class="card mb-2">
                <div class="card-body">
                    <h5>{{ $type->name }}</h5>
                    <a href="{{ route('wine-types.edit', $type) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                    <form action="{{ route('wine-types.destroy', $type) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">Eliminar</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- Regiones -->
<section class="mt-5">
    <h3>Regiones</h3>
    <button class="btn btn-primary mb-3" onclick="toggleVisibility('regions-list')">Gestionar Regiones</button>
    <div id="regions-list" class="hidden">
        <a href="{{ route('regions.create') }}" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Crear nueva región</a>
        @foreach($regions as $region)
            <div class="card mb-2">
                <div class="card-body">
                    <h5>{{ $region->name }}</h5>
                    <a href="{{ route('regions.edit', $region) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                    <form action="{{ route('regions.destroy', $region) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">Eliminar</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- Uvas -->
<section class="mt-5">
    <h3>Uvas</h3>
    <button class="btn btn-primary mb-3" onclick="toggleVisibility('grapes-list')">Gestionar Uvas</button>
    <div id="grapes-list" class="hidden">
        <a href="{{ route('grapes.create') }}" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Crear nueva uva</a>
        @foreach($grapes as $grape)
            <div class="card mb-2">
                <div class="card-body">
                    <h5>{{ $grape->name }}</h5>
                    <a href="{{ route('grapes.edit', $grape) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                    <form action="{{ route('grapes.destroy', $grape) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">Eliminar</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- Maridajes -->
<section class="mt-5">
    <h3>Maridajes</h3>
    <button class="btn btn-primary mb-3" onclick="toggleVisibility('food-pairings-list')">Gestionar Maridajes</button>
    <div id="food-pairings-list" class="hidden">
        <a href="{{ route('food-pairings.create') }}" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Crear nuevo maridaje</a>
        @foreach($foodPairings as $pairing)
            <div class="card mb-2">
                <div class="card-body">
                    <h5>{{ $pairing->name }}</h5>
                    <a href="{{ route('food-pairings.edit', $pairing) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                    <form action="{{ route('food-pairings.destroy', $pairing) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">Eliminar</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</section>
