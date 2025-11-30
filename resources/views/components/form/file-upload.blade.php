@props([
    'name',
    'label' => null,
    'multiple' => true,
    'maxFiles' => 10,
    'required' => false,
    'description' => null,
    'existingFiles' => [],
])

@php
    $inputId = 'filepond-' . Str::random(8);
@endphp

<div>
    @if($label)
        <label class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{-- Show existing files if any --}}
    @if(count($existingFiles) > 0)
        <div class="mb-4">
            <p class="text-sm text-[var(--color-text-light)] mb-2">Existing photos:</p>
            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                @foreach($existingFiles as $index => $file)
                    <div class="relative aspect-square rounded-lg overflow-hidden bg-[var(--color-cream)]">
                        <img 
                            src="{{ is_string($file) ? asset('storage/' . $file) : $file }}" 
                            alt="Existing photo {{ $index + 1 }}" 
                            class="w-full h-full object-cover"
                        >
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <input 
        type="file" 
        id="{{ $inputId }}"
        name="{{ $name }}"
        {{ $multiple ? 'multiple' : '' }}
        accept="image/*"
        class="filepond"
    >

    @if($description)
        <p class="form-description mt-2">{{ $description }}</p>
    @endif

    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
    @error($name . '.*')
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('{{ $inputId }}');
        if (input && typeof FilePond !== 'undefined') {
            FilePond.create(input, {
                allowMultiple: {{ $multiple ? 'true' : 'false' }},
                maxFiles: {{ $maxFiles }},
                labelIdle: 'Drag & Drop your photos or <span class="filepond--label-action">Browse</span>',
                acceptedFileTypes: ['image/*'],
                maxFileSize: '5MB',
                imagePreviewHeight: 170,
                credits: false,
                stylePanelLayout: null,
                // Required for standard form submission - stores files as File objects
                storeAsFile: true,
            });
        }
    });
</script>
@endpush
