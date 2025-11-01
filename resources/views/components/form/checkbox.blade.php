@props([
    'name',
    'label' => null,
    'value' => '1',
    'checked' => false,
    'class' => '',
    'id' => null
])

@php
    $checkboxId = $id ?? $name . '_' . $value;
    $checkboxClasses = 'h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary focus:ring-2 ' . $class;
    $isChecked = old($name) ? in_array($value, (array) old($name)) : $checked;
@endphp

<div class="flex items-center mb-2">
    <input 
        type="checkbox"
        name="{{ $name }}"
        id="{{ $checkboxId }}"
        value="{{ $value }}"
        class="{{ $checkboxClasses }}"
        @if($isChecked) checked @endif
        {{ $attributes->except(['name', 'label', 'value', 'checked', 'class', 'id']) }}
    />
    
    @if($label)
        <label for="{{ $checkboxId }}" class="ml-2 text-sm text-secondary cursor-pointer">
            {{ $label }}
        </label>
    @endif
</div>

<x-form.error :field="$name" />