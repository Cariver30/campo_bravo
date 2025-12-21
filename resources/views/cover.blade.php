<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Página de Inicio</title>

    <!-- Tailwind + Flowbite -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        :root {
            --accent-color: {{ $settings->button_color_cover ?? '#FF5722' }};
        }
        body {
            font-family: {{ $settings->font_family_cover ?? 'ui-sans-serif' }};
            @if($settings && $settings->background_image_cover)
                background: url('{{ asset('storage/' . $settings->background_image_cover) }}') no-repeat center center fixed;
            @endif
            background-size: cover;
        }
        .vip-button {
            position: relative;
            width: 12rem;
            height: 3rem;
            border-radius: 9999px;
            font-weight: 600;
            color: #fff;
            background: var(--accent-color);
            transition: transform .2s ease, box-shadow .2s ease;
            animation: vip-glow 1.5s infinite;
            overflow: hidden;
        }
        .vip-button::after {
            content: '';
            position: absolute;
            inset: 4px;
            border-radius: 9999px;
            border: 2px dashed rgba(255,255,255,0.65);
            animation: vip-blink 2s linear infinite;
            pointer-events: none;
        }
        .vip-button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 18px rgba(255,255,255,0.35);
        }
        @keyframes vip-glow {
            0%, 100% { box-shadow: 0 0 12px rgba(255,255,255,0.15); }
            50% { box-shadow: 0 0 20px rgba(255,255,255,0.45); }
        }
        @keyframes vip-blink {
            0% { opacity: 0.25; }
            50% { opacity: 1; }
            100% { opacity: 0.25; }
        }
    </style>
</head>
<body class="relative min-h-screen bg-black/50 text-white flex flex-col items-center justify-center">

    <!-- Logo centrado arriba -->
    <div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50">
        <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo del Restaurante" class="w-52 max-w-xs mx-auto">
    </div>

    <!-- Contenedor central -->
    <main class="text-center z-20 w-full px-4">
        @if(session('notification_success'))
            <div id="subscriptionStatus" class="max-w-md mx-auto mb-6 bg-emerald-500/20 border border-emerald-400/30 rounded-2xl px-4 py-3 text-sm text-emerald-100">
                {{ session('notification_success') }}
            </div>
        @endif

        <div class="space-y-4 mb-8">
            <button onclick="window.location.href='/menu'" 
                    class="w-48 h-12 bg-[{{ $settings->button_color_cover ?? '#FF5722' }}] text-white rounded-full font-semibold text-lg transition transform hover:scale-105 animate-pulse">
                Menú
            </button>
            <button onclick="window.location.href='/cocktails'" 
                    class="w-48 h-12 bg-[{{ $settings->button_color_cover ?? '#FF5722' }}] text-white rounded-full font-semibold text-lg transition transform hover:scale-105 animate-pulse">
                Cócteles
            </button>
            <button onclick="window.location.href='/wines'" 
                    class="w-48 h-12 bg-[{{ $settings->button_color_cover ?? '#FF5722' }}] text-white rounded-full font-semibold text-lg transition transform hover:scale-105 animate-pulse">
                Vinos
            </button>
            <button onclick="window.location.href='{{ route('experiences.index') }}'" 
                    class="w-48 h-12 bg-[{{ $settings->button_color_cover ?? '#FF5722' }}] text-white rounded-full font-semibold text-lg transition transform hover:scale-105 animate-pulse">
                Eventos especiales
            </button>
            <button id="openNotifyModal"
                    class="vip-button text-lg">
                Lista VIP
            </button>
            <p id="notifyStatus" class="text-xs text-white/70 hidden">Ya estás suscrito a las alertas ✉️</p>
            <button onclick="window.location.href='{{ route('reservations.app') }}'" 
                    class="w-48 h-12 bg-[{{ $settings->button_color_cover ?? '#FF5722' }}] text-white rounded-full font-semibold text-lg transition transform hover:scale-105 animate-pulse">
                Reservas
            </button>
        </div>

        <!-- Información adicional -->
        <div class="text-sm text-white/90 space-y-4">
            <p>{{ $settings->business_hours ?? 'Horario no configurado' }}</p>

            <div class="flex items-center justify-center gap-2">
                <span>Pet Friendly</span>
                <i class="fas fa-paw text-2xl text-white"></i>
            </div>
        </div>
    </main>

    <!-- Redes sociales abajo -->
    <footer class="fixed bottom-6 left-0 right-0 z-40">
        <div class="flex justify-center gap-6">
            <a href="{{ $settings->facebook_url ?? '#' }}" target="_blank" 
               class="w-12 h-12 bg-[{{ $settings->button_color_cover ?? '#000' }}] text-white flex items-center justify-center rounded-full transition hover:scale-110 hover:bg-white hover:text-black">
                <i class="fab fa-facebook-f"></i>
            </a>
            
            <a href="{{ $settings->instagram_url ?? '#' }}" target="_blank" 
               class="w-12 h-12 bg-[{{ $settings->button_color_cover ?? '#000' }}] text-white flex items-center justify-center rounded-full transition hover:scale-110 hover:bg-white hover:text-black">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="tel:{{ $settings->phone_number ?? '#' }}" 
               class="w-12 h-12 bg-[{{ $settings->button_color_cover ?? '#000' }}] text-white flex items-center justify-center rounded-full transition hover:scale-110 hover:bg-white hover:text-black">
                <i class="fas fa-phone"></i>
            </a>
        </div>
    </footer>

    <!-- Modal de notificación -->
    <div id="notifyModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center px-4 {{ ($errors->has('name') || $errors->has('email')) ? '' : 'hidden' }} z-50">
        <div class="bg-white text-slate-900 rounded-3xl w-full max-w-md p-6 relative">
            <button id="closeNotifyModal" class="absolute top-4 right-4 text-2xl text-slate-500 hover:text-slate-800">&times;</button>
            <p class="text-xs uppercase tracking-[0.35em] text-amber-500 mb-2">Experiencias</p>
            <h2 class="text-2xl font-semibold mb-2">Recibe las alertas VIP</h2>
            <p class="text-sm text-slate-500 mb-4">Entérate primero de nuevas experiencias, cenas especiales y eventos privados.</p>
            <form action="{{ route('experiences.notify.cover') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <input type="text" name="name" placeholder="Tu nombre" value="{{ old('name') }}"
                           class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <input type="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}"
                           class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-slate-900 text-white py-3 rounded-2xl font-semibold hover:bg-slate-800 transition">
                    Quiero recibir noticias
                </button>
                <p class="text-xs text-slate-400 text-center">Prometemos solo enviar experiencias relevantes.</p>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('notifyModal');
            const openBtn = document.getElementById('openNotifyModal');
            const closeBtn = document.getElementById('closeNotifyModal');
            const statusBadge = document.getElementById('notifyStatus');
            const flash = document.getElementById('subscriptionStatus');
            const isRegistered = localStorage.getItem('eventNotifyRegistered') === '1';

            const openModal = () => {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };
            const closeModal = () => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            openBtn?.addEventListener('click', openModal);
            closeBtn?.addEventListener('click', closeModal);
            modal?.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });

            if (flash) {
                localStorage.setItem('eventNotifyRegistered', '1');
            }

            if (isRegistered && statusBadge) {
                statusBadge.classList.remove('hidden');
                openBtn.textContent = 'Actualizar datos';
            } else if (window.innerWidth < 768 && !modal.classList.contains('hidden')) {
                // already open due to errors
            } else if (window.innerWidth < 768 && !isRegistered) {
                setTimeout(() => {
                    if (modal.classList.contains('hidden')) {
                        openModal();
                    }
                }, 2000);
            }

            // If validation errors opened the modal, highlight status
            if (!modal.classList.contains('hidden')) {
                document.body.classList.add('overflow-hidden');
            }
        });
    </script>

</body>
</html>
