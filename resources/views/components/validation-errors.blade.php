@props(['messages', 'type' => 'red'])

@if ($messages->any())
    <div class="bg-{{ $type }}-100 border-l-4 border-{{ $type }}-500 text-{{ $type }}-700 p-4 mx-8 my-2"
        role="alert">
        {{-- <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mx-8 my-2" role="alert"> --}}
        <p>
            <b>{{ count($messages) }}件のエラーがあります。</b>
        </p>
        <ul>
            @foreach ($messages->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif
