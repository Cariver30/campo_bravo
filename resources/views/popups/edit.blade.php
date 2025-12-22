<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pop-up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Editar Pop-up</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.popups.update', $popup) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Título</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $popup->title }}" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Imagen</label>
            <input type="file" class="form-control" id="image" name="image">
            <img src="{{ asset('storage/' . $popup->image) }}" alt="{{ $popup->title }}" class="img-fluid mt-2" width="200">
        </div>
        <div class="mb-3">
            <label for="view" class="form-label">Vista</label>
            <select class="form-control" id="view" name="view" required>
                <option value="menu" {{ $popup->view == 'menu' ? 'selected' : '' }}>Menú</option>
                <option value="cocktails" {{ $popup->view == 'cocktails' ? 'selected' : '' }}>Cocktails</option>
                <option value="coffee" {{ $popup->view == 'coffee' ? 'selected' : '' }}>Café</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Fecha de inicio</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $popup->start_date }}" required>
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">Fecha de fin</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $popup->end_date }}" required>
        </div>
        <!-- Añadir este código en create.blade.php y edit.blade.php -->
<div class="mb-3">
    <label for="repeat_days" class="form-label">Días de la semana</label>
    <select class="form-control" id="repeat_days" name="repeat_days[]" multiple>
        <option value="0">Domingo</option>
        <option value="1">Lunes</option>
        <option value="2">Martes</option>
        <option value="3">Miércoles</option>
        <option value="4">Jueves</option>
        <option value="5">Viernes</option>
        <option value="6">Sábado</option>
    </select>
</div>

        <div class="mb-3">
            <label for="active" class="form-label">Activo</label>
            <select class="form-control" id="active" name="active" required>
                <option value="1" {{ $popup->active ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ !$popup->active ? 'selected' : '' }}>No</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
