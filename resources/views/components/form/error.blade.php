@props([
    'field',
    'class' => ''
])

@php
    $errorClasses = 'text-danger text-sm mt-1 block ' . $class;
@endphp

@error($field)
    <span class="{{ $errorClasses }}" {{ $attributes->except(['field', 'class']) }}>
        {{ $message }}
    </span>
@enderror