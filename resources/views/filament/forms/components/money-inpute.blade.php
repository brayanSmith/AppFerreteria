@php
    $id = $getId();
    $name = $getName();
    $value = $getState();
    $disabled = $isDisabled();
    $formatted = $value !== null ? ('$ ' . number_format((float) $value, 0, ',', '.')) : '';
    $realId = $id . '_real';
@endphp

<div {{ $attributes->merge($getExtraAttributeBag()->getAttributes()) }}>
    <input
        id="{{ $id }}_visual"
        type="text"
        value="{{ $formatted }}"
        {{-- @if($disabled) disabled @endif --}}
        readonly
        class="filament-money-inpute__visual"
    />
</div>
