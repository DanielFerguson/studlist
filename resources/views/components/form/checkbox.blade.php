@props([
    'name',
    'label' => null,
    'checked' => false,
    'disabled' => false,
    'description' => null,
])

<div class="flex flex-row items-start space-x-3 rounded-lg border border-[var(--color-border)] p-4 bg-white">
    <input 
        type="checkbox" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        value="1"
        {{ old($name, $checked) ? 'checked' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'form-checkbox mt-0.5']) }}
    >
    <div class="space-y-1 leading-none">
        @if($label)
            <label for="{{ $name }}" class="text-sm font-medium text-[var(--color-text)] cursor-pointer">
                {{ $label }}
            </label>
        @endif
        @if($description)
            <p class="text-sm text-[var(--color-text-light)]">{{ $description }}</p>
        @endif
    </div>
    
    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>








