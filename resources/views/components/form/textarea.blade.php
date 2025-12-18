@props([
    'name',
    'label' => null,
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'description' => null,
    'rows' => 4,
    'maxlength' => null,
])

<div x-data="{ count: {{ strlen(old($name, $value)) }} }">
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <textarea 
        id="{{ $name }}" 
        name="{{ $name }}" 
        placeholder="{{ $placeholder }}"
        rows="{{ $rows }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $maxlength ? "maxlength=$maxlength" : '' }}
        @if($maxlength) x-on:input="count = $el.value.length" @endif
        {{ $attributes->merge(['class' => 'form-input resize-y' . ($errors->has($name) ? ' border-red-500' : '')]) }}
    >{{ old($name, $value) }}</textarea>

    <div class="flex justify-between mt-1">
        @if($description)
            <p class="form-description">{{ $description }}</p>
        @else
            <span></span>
        @endif
        
        @if($maxlength)
            <p class="text-xs text-[var(--color-text-light)]">
                <span x-text="count"></span>/{{ $maxlength }}
            </p>
        @endif
    </div>

    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>












