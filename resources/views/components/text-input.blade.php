@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border border-graymuted placeholder:text-muted focus:border-primary focus:ring-primary rounded-md shadow-sm']) }}>
