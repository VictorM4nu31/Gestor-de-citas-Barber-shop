@props([
    'name',
    'label' => null,
    'required' => false,
    'placeholder' => null,
    'value' => null,
    'rows' => 4,
    'class' => '',
    'id' => null
])

@php
    $textareaId = $id ?? $name;
    $textareaClasses = 'w-full px-3 py-2 border border-accent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 resize-vertical ' . $class;
    $hasError = $errors->has($name);
    
    if ($hasError) {
        $textareaClasses .= ' border-danger focus:ring-danger focus:border-danger';
    }
@endphp

<div class="mb-4">
    @if($label)
        <x-form.label :for="$textareaId" :required="$required">
            {{ $label }}
        </x-form.label>
    @endif
    
    <textarea 
        name="{{ $name }}"
        id="{{ $textareaId }}"
        rows="{{ $rows }}"
        class="{{ $textareaClasses }}"
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($required) required @endif
        {{ $attributes->except(['name', 'label', 'required', 'placeholder', 'value', 'rows', 'class', 'id']) }}
    >{{ old($name, $value) }}</textarea>
    
    <x-form.error :field="$name" />
</div>