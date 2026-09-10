@props([
    'id',
    'title' => 'Confirmar acción',
    'message' => '¿Quieres continuar?',
    'variant' => 'danger',
    'show' => false,
])

@php
    $buttonClasses = $variant === 'danger'
        ? 'bg-danger text-light hover:bg-secondary focus:ring-danger'
        : 'bg-primary text-light hover:bg-secondary focus:ring-primary';
@endphp

<div
    x-data="{ open: @json((bool) $show), trigger: null }"
    x-on:open-modal-{{ $id }}.window="trigger = $event.detail?.trigger || $event.target; open = true; $nextTick(() => $refs.cancel.focus())"
    x-on:keydown.escape.window="if (open) { open = false; trigger?.focus() }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[60] flex items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
    aria-describedby="{{ $id }}-message"
>
    <div x-show="open" x-transition.opacity class="absolute inset-0 bg-secondary/70" @click="open = false; trigger?.focus()"></div>
    <div x-show="open" x-transition class="relative w-full max-w-md border border-accent bg-light p-6 shadow-2xl">
        <h2 id="{{ $id }}-title" class="display-title text-2xl text-secondary">{{ $title }}</h2>
        <p id="{{ $id }}-message" class="mt-3 text-sm leading-relaxed text-muted">{{ $message }}</p>

        {{ $slot }}

        <div class="mt-6 flex justify-end gap-3">
            <button
                x-ref="cancel"
                type="button"
                class="border border-accent px-4 py-2 text-sm font-bold text-secondary transition hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary"
                @click="open = false; trigger?.focus()"
            >
                Cancelar
            </button>
            <button
                type="button"
                class="px-4 py-2 text-sm font-bold transition focus:outline-none focus:ring-2 {{ $buttonClasses }}"
                @click="$dispatch('confirmed-{{ $id }}'); open = false; trigger?.focus()"
            >
                Confirmar
            </button>
        </div>
    </div>
</div>
