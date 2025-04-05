@props([
    'name' => '',
    'label' => null,
    'id' => null,
    'type' => '',
    'value' => '',
    'required' => null,
])

@php
$label ??= ucfirst(strtolower($name));
$id ??= str($name)->camel()->toString();
@endphp

<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>
    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        class="form-control"
        value="{{ $value }}"
        @if ($required) required @endif />
</div>
