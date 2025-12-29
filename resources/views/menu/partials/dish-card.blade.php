@php
    $dishExtras = $dish->extras->where('active', true);
    $dishExtrasPayload = $dishExtras->map(function ($extra) {
        return [
            'name' => $extra->name,
            'price' => number_format($extra->price, 2, '.', ''),
            'description' => $extra->description,
        ];
    });
@endphp
<div id="dish{{ $dish->id }}" onclick="openDishModal(this)"
    class="dish-card rounded-lg p-4 shadow-lg relative flex items-center cursor-pointer hover:scale-105 transition"
    style="background-color: {{ $menuCardBg }};
           opacity: {{ $settings->card_opacity_menu ?? 0.9 }};"
    data-name="{{ $dish->name }}"
    data-description="{{ $dish->description }}"
    data-price="${{ number_format($dish->price, 2) }}"
    data-image="{{ $resolveMedia($dish->image) }}"
    data-wines="{{ e($dish->wines->map(fn($wine) => $wine->id.'::'.$wine->name)->implode('|')) }}"
    data-recommended="{{ e($dish->recommendedDishes->map(fn($recommended) => $recommended->id.'::'.$recommended->name)->implode('|')) }}"
    data-extras='@json($dishExtrasPayload)'>

    <span class="absolute top-2 right-2 text-xs px-2 py-1 rounded"
          style="background-color: {{ $settings->button_color_menu ?? $palette['violet'] }};
                 color: {{ $settings->text_color_menu ?? $palette['cream'] }};">Ver más</span>


    <img src="{{ $resolveMedia($dish->image) }}"
         alt="{{ $dish->name }}"
         class="h-24 w-24 rounded-full object-cover mr-4 border"
         style="border-color: rgba(118, 45, 121, 0.2);">

    <div class="flex-1">
        <h3 class="text-xl font-bold">{{ $dish->name }}</h3>
        <p class="text-sm mb-2">${{ number_format($dish->price, 2) }}</p>


        @if ($dish->wines && $dish->wines->count())
            <div class="mt-3">
                <p class="text-xs uppercase tracking-[0.2em] mb-2" style="color: {{ $settings->text_color_menu ?? $palette['violet'] }};">Maridajes sugeridos</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($dish->wines as $wine)
                        <a href="{{ $cavaRouteUrl }}#wine{{ $wine->id }}"
                           class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold border transition hover:scale-105"
                           style="background-color: {{ $settings->category_name_bg_color_menu ?? 'rgba(57, 125, 181, 0.18)' }}; border-color: {{ $settings->button_color_menu ?? $palette['blue'] }}; color: {{ $settings->text_color_menu ?? $palette['blue'] }};">
                            <i class="fas fa-wine-glass-alt" style="color: {{ $settings->button_color_menu ?? $palette['amber'] }};"></i>
                            {{ $wine->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($dish->recommendedDishes && $dish->recommendedDishes->count())
            <div class="mt-3 border-t pt-3" style="border-color: rgba(118, 45, 121, 0.2);">
                <p class="text-xs uppercase tracking-[0.2em] mb-1" style="color: {{ $settings->text_color_menu ?? $palette['violet'] }};">Combínalo con</p>
                <p class="text-xs" style="color: {{ $settings->text_color_menu ?? $palette['blue'] }};">
                    Variedad de platos recomendados · abre la tarjeta para verlos todos.
                </p>
            </div>
        @endif
    </div>
</div>
