<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear bebida de café</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h1>Crear bebida de café</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('wines.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Nombre -->
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <!-- Descripción -->
        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>

        <!-- Precio -->
        <div class="mb-3">
            <label for="price" class="form-label">Precio</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
        </div>

        <!-- Categoría -->
        <div class="mb-3">
            <label for="category_id" class="form-label">Categoría</label>
            <select class="form-select" id="category_id" name="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tipo de vino -->
        <div class="mb-3">
            <label for="type_id" class="form-label">Método o estilo</label>
            <select class="form-select" id="type_id" name="type_id">
                <option value="">Seleccionar</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Región -->
        <div class="mb-3">
            <label for="region_id" class="form-label">Región</label>
            <select class="form-select" id="region_id" name="region_id">
                <option value="">Seleccionar</option>
                @foreach($regions as $region)
                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Uvas -->
        <div class="mb-3">
            <label for="grapes" class="form-label">Uvas</label>
            <select class="form-select select2-multiple" id="grapes" name="grapes[]" multiple="multiple">
                @foreach($grapes as $grape)
                    <option value="{{ $grape->id }}">{{ $grape->name }}</option>
                @endforeach
            </select>
        </div>

        
        <!-- Platos -->
        <div class="mb-3">
            <label for="dishes" class="form-label">Platos que acompañan este vino</label>
            <select class="form-select select2-multiple" id="dishes" name="dishes[]" multiple="multiple">
                @foreach($dishes as $dish)
                    <option value="{{ $dish->id }}">{{ $dish->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Imagen -->
        <div class="mb-3">
            <label for="image" class="form-label">Imagen</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>

        <div class="form-check mb-4">
            <input type="checkbox" class="form-check-input" id="featured_on_cover" name="featured_on_cover" value="1" {{ old('featured_on_cover') ? 'checked' : '' }}>
            <label class="form-check-label" for="featured_on_cover">Destacar en la portada</label>
        </div>

        <!-- Botón -->
        <button type="submit" class="btn btn-primary">Crear</button>
    </form>
</div>

<!-- JS Bootstrap + Select2 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2-multiple').select2({
            placeholder: "Seleccionar",
            allowClear: true,
            width: '100%'
        });
    });
</script>

</body>
</html>
