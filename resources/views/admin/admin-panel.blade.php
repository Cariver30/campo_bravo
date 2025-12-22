@extends('layouts.admin')

@section('title', 'Panel maestro · ajustes globales')

@section('content')
@php
    $tabLabels = [
        'menu' => $settings->tab_label_menu ?? $settings->button_label_menu ?? 'Menú',
        'cocktails' => $settings->tab_label_cocktails ?? $settings->button_label_cocktails ?? 'Cócteles',
        'wines' => $settings->tab_label_wines ?? $settings->button_label_wines ?? 'Café & Brunch',
        'events' => $settings->tab_label_events ?? 'Eventos',
        'loyalty' => $settings->tab_label_loyalty ?? 'Fidelidad',
    ];
    $tabLabelsSingular = [
        'cocktails' => \Illuminate\Support\Str::singular($tabLabels['cocktails']) ?: $tabLabels['cocktails'],
    ];
@endphp
<div class="space-y-10">
    <section class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xl relative overflow-hidden">
        <div class="absolute inset-y-0 -right-10 opacity-20 pointer-events-none hidden md:block">
            <div class="w-64 h-64 rounded-full bg-amber-200 blur-3xl"></div>
        </div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <p class="uppercase tracking-[0.3em] text-xs text-amber-500 mb-3">Sistema creativo</p>
                <h1 class="text-3xl md:text-4xl font-semibold text-slate-900 mb-3">Panel de experiencias</h1>
                <p class="text-slate-600 max-w-2xl">Controla menú, estilos, cócteles, café y eventos desde una interfaz cohesionada.</p>
            </div>
            <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-amber-500/90 text-slate-900 font-semibold shadow-md hover:bg-amber-400/90 transition">
                <span>🎟️</span> Gestionar eventos
            </a>
        </div>
    </section>

    <section class="glass-grid">
        <article class="glass-card">
            <p class="text-xs uppercase tracking-widest text-slate-400 mb-1">Dinámica</p>
            <h3 class="text-xl font-semibold text-slate-900">Menú vivo</h3>
            <p class="text-sm text-slate-600 mt-2">Actualiza categorías, platos, estilos y orden.</p>
        </article>
        <article class="glass-card">
            <p class="text-xs uppercase tracking-widest text-slate-400 mb-1">Bebidas</p>
            <h3 class="text-xl font-semibold text-slate-900">Cócteles + Café</h3>
            <p class="text-sm text-slate-600 mt-2">Recetas calientes, frías y maridajes.</p>
        </article>
        <article class="glass-card">
            <p class="text-xs uppercase tracking-widest text-slate-400 mb-1">Difusión</p>
            <h3 class="text-xl font-semibold text-slate-900">Campañas SendGrid</h3>
            <p class="text-sm text-slate-600 mt-2">Envía newsletters y promos con adjuntos.</p>
            <a href="{{ route('admin.events.promotions.index') }}" class="mt-3 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 text-slate-900 text-sm font-semibold hover:bg-amber-200 transition">Ver campañas</a>
        </article>
    </section>

    <section class="glass-card">
        <div class="flex flex-wrap gap-3 mb-6" id="adminTabs">
            <button class="tab-button active" data-section="dashboard">Dashboard</button>
            <button class="tab-button" data-section="general">Configuraciones</button>
            @if($settings->show_tab_menu)
                <button class="tab-button" data-section="menu">{{ $tabLabels['menu'] }}</button>
            @endif
            @if($settings->show_tab_cocktails)
                <button class="tab-button" data-section="cocktails">{{ $tabLabels['cocktails'] }}</button>
            @endif
            <button class="tab-button" data-section="featured">Lo más vendido</button>
            @if($settings->show_tab_wines)
                <button class="tab-button" data-section="wines">{{ $tabLabels['wines'] }}</button>
            @endif
            @if($settings->show_tab_events)
                <button class="tab-button" data-section="events">{{ $tabLabels['events'] }}</button>
            @endif
            @if($settings->show_tab_campaigns)
                <button class="tab-button" data-section="campaigns">Campañas</button>
            @endif
            @if($settings->show_tab_popups)
                <button class="tab-button" data-section="popups">Pop-ups</button>
            @endif
            @if($settings->show_tab_loyalty)
                <button class="tab-button" data-section="loyalty-section">{{ $tabLabels['loyalty'] }}</button>
            @endif
        </div>

        <div class="space-y-8">
            <div id="dashboard" class="section-panel active">
                <div class="grid lg:grid-cols-3 gap-6">
                    <article class="module-card">
                        <div class="text-3xl mb-3">🍽️</div>
                        <h4 class="text-lg font-semibold text-slate-900">{{ $tabLabels['menu'] }}</h4>
                        <p class="text-sm text-slate-600 mb-4">Gestiona platos, categorías y estilos.</p>
                        <button class="ghost-button" data-section="menu">Ir a {{ $tabLabels['menu'] }}</button>
                    </article>
                    <article class="module-card">
                        <div class="text-3xl mb-3">🎟️</div>
                        <h4 class="text-lg font-semibold text-slate-900">{{ $tabLabels['events'] }}</h4>
                        <p class="text-sm text-slate-600 mb-4">Crea experiencias y secciones.</p>
                        <a href="{{ route('admin.events.index') }}" class="primary-button">Gestor de eventos</a>
                    </article>
                    <article class="module-card">
                        <div class="text-3xl mb-3">✉️</div>
                        <h4 class="text-lg font-semibold text-slate-900">Campañas</h4>
                        <p class="text-sm text-slate-600 mb-4">Construye correos con PDF, GIF o videos.</p>
                        <a href="{{ route('admin.events.promotions.index') }}" class="primary-button">Panel de campañas</a>
                    </article>
                </div>
            </div>

            <div id="general" class="section-panel">
                <div class="inner-panel">
                    <h3 class="inner-title">Configuración general</h3>
                    <p class="inner-text">Logo, redes, info fija y estilo del cover.</p>
                    @include('admin.partials.general-config')
                </div>
            </div>

            <div id="menu" class="section-panel">
                <div class="inner-panel space-y-4">
                    <h3 class="inner-title">{{ $tabLabels['menu'] }}</h3>
            <div class="subnav">
                <button class="subnav-button active" data-target="menu-create">Crear nuevo plato</button>
                <button class="subnav-button" data-target="menu-config">Configuración de Menú</button>
                <button class="subnav-button" data-target="menu-promos">Campañas</button>
            </div>
            <div id="menu-create" class="subnav-panel show">
                @include('admin.partials.manage-dishes')
            </div>
            <div id="menu-config" class="subnav-panel">
                @include('admin.partials.menu-config')
            </div>
            <div id="menu-promos" class="subnav-panel">
                <p class="text-sm text-slate-600 mb-3">Gestión rápida de promociones.</p>
                <a href="{{ route('admin.events.promotions.index') }}" class="primary-button inline-flex justify-center">Ver campañas</a>
            </div>
        </div>
            </div>

            <div id="cocktails" class="section-panel">
                <div class="inner-panel space-y-4">
                    <h3 class="inner-title">{{ $tabLabels['cocktails'] }}</h3>
                    <div class="subnav">
                        <button class="subnav-button active" data-target="cocktail-create">Crear {{ \Illuminate\Support\Str::lower($tabLabelsSingular['cocktails']) }}</button>
                        <button class="subnav-button" data-target="cocktail-config">Configuración de {{ $tabLabels['cocktails'] }}</button>
                    </div>
                    <div id="cocktail-create" class="subnav-panel show">
                        @include('admin.partials.manage-cocktails', ['cocktailLabel' => $tabLabels['cocktails']])
                    </div>
                    <div id="cocktail-config" class="subnav-panel">
                        @include('admin.partials.cocktails-config', ['cocktailLabel' => $tabLabels['cocktails']])
                    </div>
                </div>
            </div>

            <div id="featured" class="section-panel">
                @include('admin.partials.featured-tabs')
            </div>

            <div id="wines" class="section-panel">
                <div class="inner-panel space-y-4">
                    <h3 class="inner-title">{{ $tabLabels['wines'] }}</h3>
                    <div class="subnav">
                        <button class="subnav-button active" data-target="wine-create">Crear bebida</button>
                        <button class="subnav-button" data-target="wine-config">Configuración de Café</button>
                        <button class="subnav-button" data-target="wine-advanced">Gestión avanzada</button>
                    </div>
                    <div id="wine-create" class="subnav-panel show">
                        @include('admin.partials.manage-wines')
                    </div>
                    <div id="wine-config" class="subnav-panel">
                        @include('admin.partials.wines-config')
                    </div>
                    <div id="wine-advanced" class="subnav-panel">
                        @include('admin.partials.wine-advanced')
                    </div>
                </div>
            </div>

            <div id="events" class="section-panel">
                <div class="inner-panel">
                    <h3 class="inner-title">{{ $tabLabels['events'] }}</h3>
                    <p class="inner-text">Configura eventos, mapa de secciones y taquillas.</p>
                    <a href="{{ route('admin.events.index') }}" class="primary-button mt-4 inline-flex">Ir a gestor de eventos</a>
                </div>
            </div>

            <div id="campaigns" class="section-panel">
                <div class="inner-panel space-y-4">
                    <h3 class="inner-title">Campañas promocionales</h3>
                    <p class="inner-text">Envía boletines, cupones o lanzamientos a toda la lista de notificaciones usando la API de SendGrid.</p>
                    <ul class="text-sm text-slate-600 space-y-2">
                        <li>• Arrastra PDF/GIF/videos para adjuntarlos.</li>
                        <li>• Redacta el HTML en un editor simple.</li>
                        <li>• Envía en bulk o guarda como borrador.</li>
                    </ul>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.events.promotions.index') }}" class="primary-button inline-flex justify-center">Ver campañas</a>
                        <a href="{{ route('admin.events.promotions.create') }}" class="ghost-button inline-flex justify-center">Crear nueva</a>
                    </div>
                </div>
            </div>

            <div id="popups" class="section-panel">
                <div class="inner-panel">
                    <h3 class="inner-title">Pop-ups</h3>
                    @include('admin.partials.manage-popups')
                </div>
            </div>

            <div id="loyalty-section" class="section-panel">
                @include('admin.partials.loyalty')
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    .glass-card {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.5rem;
        padding: 1.75rem;
        box-shadow: 0 15px 30px rgba(15, 23, 42, 0.08);
    }
    .glass-grid {
        display: grid;
        gap: 1.25rem;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }
    .tab-button {
        padding: 0.6rem 1.5rem;
        border-radius: 9999px;
        border: 1px solid transparent;
        background: #f1f5f9;
        color: #475569;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all .2s;
    }
    .tab-button.active {
        background: #fcd34d;
        border-color: #fbbf24;
        color: #7c2d12;
    }
    .section-panel {
        display: none;
    }
    .section-panel.active {
        display: block;
    }
    .module-card {
        background: #ffffff;
        border-radius: 1.25rem;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px rgba(15,23,42,0.08);
    }
    .ghost-button {
        display: inline-flex;
        justify-content: center;
        width: 100%;
        padding: 0.75rem;
        border-radius: 9999px;
        border: 1px solid #cbd5f5;
        color: #0f172a;
    }
    .primary-button {
        display: inline-flex;
        justify-content: center;
        width: 100%;
        padding: 0.75rem;
        border-radius: 9999px;
        background: linear-gradient(120deg,#fcd34d,#f97316);
        color: #111827;
        font-weight: 600;
    }
    .inner-panel {
        background: #ffffff;
        border-radius: 1.5rem;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px rgba(15,23,42,0.05);
    }
    .feature-card {
        background: #ffffff;
        border-radius: 1.25rem;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 18px rgba(15,23,42,0.05);
    }
    .inner-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #0f172a;
    }
    .inner-text {
        font-size: 0.9rem;
        color: #475569;
        margin-bottom: 1.25rem;
    }
    .subnav {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .subnav-button {
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        font-size: 0.8rem;
    }
    .subnav-button.active {
        background: #fde68a;
        border-color: #f59e0b;
        color: #7c2d12;
    }
    .subnav-panel {
        display: none;
        border-radius: 1rem;
        padding: 1.25rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
    }
    .subnav-panel.show {
        display: block;
    }
    /* Bootstrap form compatibility */
    .form-control,
    .form-select {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        color: #0f172a;
        border-radius: 0.85rem;
    }
    .form-control:focus,
    .form-select:focus {
        border-color: rgba(251, 191, 36, 0.8);
        box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.15);
        background-color: #ffffff;
    }
    .btn {
        border-radius: 9999px;
        padding: 0.5rem 1.5rem;
        border: none;
    }
    .btn-primary {
        background: linear-gradient(90deg, #fbbf24, #f97316);
        color: #111827;
        font-weight: 600;
    }
    .btn-outline-light {
        border: 1px solid #cbd5f5;
        color: #0f172a;
    }
    .card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        color: #0f172a;
        padding: 1rem;
    }
</style>
@endpush

@push('scripts')
<script>
    const tabs = document.querySelectorAll('#adminTabs .tab-button');
    const panels = document.querySelectorAll('.section-panel');

    function openSection(target) {
        if (!target || !document.getElementById(target)) return;
        tabs.forEach(t => t.classList.remove('active'));
        const nav = document.querySelector(`#adminTabs .tab-button[data-section="${target}"]`);
        nav?.classList.add('active');
        panels.forEach(panel => panel.classList.remove('active'));
        document.getElementById(target).classList.add('active');
        window.history.replaceState({}, '', `?section=${target}`);
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', () => openSection(tab.dataset.section));
    });

    document.querySelectorAll('[data-section]').forEach(trigger => {
        if (trigger.closest('#adminTabs')) return;
        trigger.addEventListener('click', () => openSection(trigger.dataset.section));
    });

    window.toggleVisibility = function(sectionId) {
        const section = document.getElementById(sectionId);
        if (section) {
            section.classList.toggle('hidden');
        }
    };

    document.querySelectorAll('.subnav-button').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.dataset.target;
            const container = button.closest('.inner-panel');
            container.querySelectorAll('.subnav-button').forEach(b => b.classList.remove('active'));
            container.querySelectorAll('.subnav-panel').forEach(panel => panel.classList.remove('show'));
            button.classList.add('active');
            container.querySelector(`#${targetId}`).classList.add('show');
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(window.location.search);
        const section = params.get('section');
        if (section && document.getElementById(section)) {
            openSection(section);
        }
    });
</script>
@endpush
