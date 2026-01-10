@props(['size' => 'h-8 sm:h-10 md:h-12 w-auto object-contain flex-shrink-0'])

<img
    src="{{ asset('images/Logo.png') }}"
    alt="{{ config('app.name', 'Logo') }}"
    {{ $attributes->merge(['class' => $size]) }}
/>
