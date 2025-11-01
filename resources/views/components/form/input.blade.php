@props([
    'name',
    'type' => 'text',
    'label' => null,
    'required' => false,
    'placeholder' => null,
    'value' => null,
    'class' => '',
    'id' => null
])

@php
    $inputId = $id ?? $name;
    $inputClasses = 'w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 ' . $class;
    $hasError = $errors->has($name);
    
    if ($hasError) {
        $inputClasses .= ' border-danger focus:ring-danger focus:border-danger';
    }
@endphp

<div class="mb-4">
    @if($label)
        <x-form.label :for="$inputId" :required="$required">
            {{ $label }}
        </x-form.label>
    @endif
    
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $inputId }}"
        class="{{ $inputClasses }}"
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($value !== null) value="{{ old($name, $value) }}" @else value="{{ old($name) }}" @endif
        @if($required) required @endif
        {{ $attributes->except(['name', 'type', 'label', 'required', 'placeholder', 'value', 'class', 'id']) }}
    />
    
    <x-form.error :field="$name" />
</div>