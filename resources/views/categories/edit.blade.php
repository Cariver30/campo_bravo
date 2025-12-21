<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría - Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa; /* Fondo claro para mejor visibilidad */
            font-family: 'Arial', sans-serif; /* Fuente más legible */
        }
        .container {
            max-width: 960px; /* Limitar el ancho del contenedor para centrar el contenido */
            margin: auto; /* Auto margen para centrar el contenedor */
            padding-top: 50px; /* Espacio en la parte superior */
        }
        .card {
            margin-bottom: 20px; /* Espaciado entre tarjetas */
        }
        .btn {
            margin: 5px; /* Espaciado alrededor de botones */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Categoría</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Editar Categoría</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('categories.update', $category) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre de la categoría:</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
