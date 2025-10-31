@props([
    'for' => null,
    'required' => false,
    'class' => ''
])

@php
    $labelClasses = 'block text-sm font-medium text-secondary mb-1 ' . $class;
@endphp

<label 
    @if($for) for="{{ $for }}" @endif
    class="{{ $labelClasses }}"
    {{ $attributes->except(['for', 'required', 'class']) }}
>
    {{ $slot }}
    @if($required)
        <span class="text-danger ml-1">*</span>
    @endif
</label>