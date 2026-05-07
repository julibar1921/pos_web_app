@php
    $logo = \App\Models\Setting::get('logo');
@endphp

@if($logo)
    <img src="{{ Storage::url($logo) }}" {{ $attributes->merge(['class' => 'h-9 w-auto']) }}>
@else
    <div {{ $attributes->merge(['class' => 'h-9 w-9 bg-indigo-600 rounded-xl flex items-center justify-center text-white']) }}>
        <i class="fas fa-shopping-basket"></i>
    </div>
@endif
