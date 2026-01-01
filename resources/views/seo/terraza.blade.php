<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="La Terraza del campo de golf ofrece una experiencia gastronómica de temporada entre greens, con coctelería, platos a la parrilla y un ambiente abierto y luminoso.">
    <meta name="keywords" content="terraza, campo de golf, gastronomía, cocteles, ambiente abierto, La Terraza">
    <meta property="og:title" content="La Terraza en el Campo de Golf · Cocina creativa y vistas verdes">
    <meta property="og:description" content="Disfruta platos de estación, parrilla con aromas mediterráneos y cócteles artesanales en la terraza que abraza el hoyo 18.">
    <meta property="og:image" content="{{ asset('images/terraza-logo.svg') }}">
    <title>La Terraza · Ambiente y sabor en el campo de golf</title>
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            margin: 0;
            background: #f5f5f0;
            color: #1a1a1a;
        }
        .hero {
            min-height: 420px;
            background: linear-gradient(135deg, rgba(27, 94, 32, 0.85), rgba(15, 23, 42, 0.9)), url('{{ asset('images/terraza-ambiente.jpg') }}') no-repeat center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
        }
        .hero img {
            width: 140px;
            margin-bottom: 1rem;
        }
        .hero h1 {
            font-size: clamp(2.5rem, 4vw, 3.5rem);
            color: #fff;
            margin: 0;
        }
        .hero p {
            margin-top: 0.5rem;
            color: rgba(255,255,255,0.9);
            font-size: 1.1rem;
        }
        .content {
            max-width: 960px;
            margin: 0 auto;
            padding: 3rem 1.5rem 4rem;
            display: grid;
            gap: 1.5rem;
        }
        .content section {
            background: #fff;
            border-radius: 1.25rem;
            padding: 2rem;
            box-shadow: 0 25px 45px rgba(15,23,42,0.12);
            border: 1px solid rgba(15,23,42,0.08);
        }
        .content h2 {
            margin-top: 0;
            color: #0f172a;
        }
        .list-icon {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        .cta {
            text-align: center;
            padding: 1.5rem;
            background: #e8f1eb;
            border-radius: 1rem;
            border: 1px solid rgba(15,23,42,0.1);
        }
        .cta a {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.85rem 2rem;
            border-radius: 999px;
            background: #0f172a;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        }
        @media (max-width: 640px) {
            .hero {
                padding: 1.5rem;
            }
            .content section {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="hero">
        <div>
            <img src="{{ asset('images/terraza-logo.svg') }}" alt="Logotipo de La Terraza">
            <h1>La Terraza del Campo de Golf</h1>
            <p>Un refugio luminoso sobre los greens con cocina de autor, parrilla y coctelería fresca.</p>
        </div>
    </div>

    <div class="content">
        <section>
            <h2>Un ambiente que respira naturaleza</h2>
            <p>La terraza combina materiales minerales con madera cálida para acompañar la vegetación del campo. Ventanales de piso a techo, pérgolas de hierro negro y vegetación vertical crean zonas íntimas frente al hoyo 18. Al caer la tarde, luces doradas y música suave mantienen el ritmo tranquilo del golf mientras se sirven los primeros aperitivos.</p>
            <div class="list-icon">
                <span>•</span>
                <p>Vistas panorámicas al fairway y al lago central.</p>
            </div>
            <div class="list-icon">
                <span>•</span>
                <p>Sombras móviles, ventilación cruzada y calefactores discretos para todo el año.</p>
            </div>
            <div class="list-icon">
                <span>•</span>
                <p>Detalles artesanales en cerámica y textiles que reflejan la identidad del campo.</p>
            </div>
        </section>

        <section>
            <h2>Comida con alma de la tierra</h2>
            <p>La carta celebra productores locales: vegetales de estación, cortes de res madurada, mariscos en su punto y vegetales a la parrilla al carbón. Platos como la costilla glaseada con miel de la sierra, el risotto cítrico con espárragos verdes y las ensaladas templadas con queso de cabra en migas son el alma del menú.</p>
            <div class="list-icon">
                <span>•</span>
                <p>Menú degustación para grupos con maridaje sugerido por sommeliers.</p>
            </div>
            <div class="list-icon">
                <span>•</span>
                <p>Selección de cócteles herbales, vinos boutique y cervezas artesanales.</p>
            </div>
            <div class="list-icon">
                <span>•</span>
                <p>Opciones vegetarianas y sin gluten con creatividad y sabor.</p>
            </div>
        </section>

        <section class="cta">
            <p>“La Terraza” es el punto de encuentro perfecto para cerrar rondas de golf, celebrar momentos familiares o compartir un after-office con vistas verdes y servicio cálido.</p>
            <a href="{{ route('reservations.app') }}">Reserva tu mesa junto al green</a>
        </section>
    </div>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Restaurant",
            "name": "La Terraza",
            "url": "{{ route('terraza.seo') }}",
            "image": "{{ asset('images/terraza-logo.svg') }}",
            "description": "Cocina creativa, parrilla y coctelería fresca en un ambiente abierto dentro del campo de golf.",
            "address": {
                "@type": "PostalAddress",
                "addressLocality": "Campo de Golf",
                "addressRegion": "PR"
            },
            "servesCuisine": ["Mediterránea", "Grill"],
            "priceRange": "$$",
            "openingHours": "Mo-Su 11:00-23:00"
        }
    </script>
</body>
</html>
