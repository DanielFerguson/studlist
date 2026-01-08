@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => '',
    'placeholder' => 'Select an option',
    'required' => false,
    'disabled' => false,
    'description' => null,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <select 
        id="{{ $name }}" 
        name="{{ $name }}" 
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'form-input' . ($errors->has($name) ? ' border-red-500' : '')]) }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $optionValue => $optionLabel)
            <option 
                value="{{ $optionValue }}" 
                {{ old($name, $value) == $optionValue ? 'selected' : '' }}
            >
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @if($description)
        <p class="form-description">{{ $description }}</p>
    @endif

    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>













