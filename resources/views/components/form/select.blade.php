@props([
    'name',
    'label' => null,
    'required' => false,
    'placeholder' => null,
    'options' => [],
    'selected' => null,
    'class' => '',
    'id' => null
])

@php
    $selectId = $id ?? $name;
    $selectClasses = 'w-full px-3 py-2 border border-accent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 bg-background ' . $class;
    $hasError = $errors->has($name);
    
    if ($hasError) {
        $selectClasses .= ' border-danger focus:ring-danger focus:border-danger';
    }
    
    $selectedValue = old($name, $selected);
@endphp

<div class="mb-4">
    @if($label)
        <x-form.label :for="$selectId" :required="$required">
            {{ $label }}
        </x-form.label>
    @endif
    
    <select 
        name="{{ $name }}"
        id="{{ $selectId }}"
        class="{{ $selectClasses }}"
        @if($required) required @endif
        {{ $attributes->except(['name', 'label', 'required', 'placeholder', 'options', 'selected', 'class', 'id']) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $value => $text)
            <option 
                value="{{ $value }}" 
                @if($selectedValue == $value) selected @endif
            >
                {{ $text }}
            </option>
        @endforeach
    </select>
    
    <x-form.error :field="$name" />
</div>