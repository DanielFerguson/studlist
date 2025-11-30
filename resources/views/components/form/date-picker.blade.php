@props([
    'name',
    'label' => null,
    'value' => '',
    'placeholder' => 'Select a date',
    'required' => false,
    'disabled' => false,
    'description' => null,
    'minDate' => null,
    'maxDate' => null,
])

<div 
    x-data="{ 
        picker: null,
        init() {
            this.picker = new Pikaday({
                field: this.$refs.input,
                format: 'YYYY-MM-DD',
                toString(date, format) {
                    return moment(date).format(format);
                },
                parse(dateString, format) {
                    return moment(dateString, format).toDate();
                },
                @if($minDate)
                minDate: new Date('{{ $minDate }}'),
                @endif
                @if($maxDate)
                maxDate: new Date('{{ $maxDate }}'),
                @endif
                onSelect: function() {
                    this._o.field.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }
    }"
>
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <input 
            type="text" 
            id="{{ $name }}" 
            name="{{ $name }}" 
            x-ref="input"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            readonly
            {{ $attributes->merge(['class' => 'form-input cursor-pointer' . ($errors->has($name) ? ' border-red-500' : '')]) }}
        >
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <svg class="w-5 h-5 text-[var(--color-text-light)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
    </div>

    @if($description)
        <p class="form-description">{{ $description }}</p>
    @endif

    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>

