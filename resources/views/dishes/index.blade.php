<!-- resources/views/dishes/index.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Dishes</title>
</head>
<body>
    <h1>Dishes</h1>

    @foreach($dishes as $dish)
        <div>
            <h2>{{ $dish->name }}</h2>
            <p>{{ $dish->description }}</p>
            @if((float) ($dish->price ?? 0) > 0)
                <p>${{ number_format((float) $dish->price, 2) }}</p>
            @endif
            <!-- Add more fields as necessary -->
        </div>
    @endforeach
</body>
</html>
