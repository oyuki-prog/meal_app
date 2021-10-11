@props(['text', 'onclick' => null, 'type' => 'submit', 'color' => 'indigo'])

<button type="{{ $type }}"
    {{ $attributes->merge(['class' => 'bg-' . $color . '-500 hover:bg-' . $color . '-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2']) }}
    @if ($onclick) onclick="{{ $onclick }}" @endif> {{ $text }}</button>
