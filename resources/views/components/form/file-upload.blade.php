@props([
    'name',
    'label' => null,
    'multiple' => true,
    'maxFiles' => 10,
    'required' => false,
    'description' => null,
    'existingFiles' => [],
])

<div 
    x-data="{ 
        pond: null,
        existingFiles: @js($existingFiles),
        init() {
            this.pond = FilePond.create(this.$refs.input, {
                allowMultiple: {{ $multiple ? 'true' : 'false' }},
                maxFiles: {{ $maxFiles }},
                name: '{{ $name }}',
                labelIdle: 'Drag & Drop your photos or <span class=\"filepond--label-action\">Browse</span>',
                acceptedFileTypes: ['image/*'],
                maxFileSize: '5MB',
                imagePreviewHeight: 170,
                credits: false,
            });
        }
    }"
>
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
        x-ref="input"
        {{ $multiple ? 'multiple' : '' }}
        accept="image/*"
        class="hidden"
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


