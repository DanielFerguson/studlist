<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - StudList</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Google Fonts - Rustic Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Source+Sans+3:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- FilePond CSS -->
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
        rel="stylesheet">

    <!-- Pikaday CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">

    @vite(['resources/css/app.css'])

    <style>
        :root {
            /* Rustic Color Palette */
            --color-primary: #8B4513;
            --color-primary-light: #A0522D;
            --color-secondary: #556B2F;
            --color-secondary-light: #6B8E23;
            --color-accent: #CC5500;
            --color-cream: #FDF5E6;
            --color-text: #3D2914;
            --color-text-light: #5D4E37;
            --color-warm-white: #FAF7F2;
            --color-border: #D4C4A8;
        }

        body {
            font-family: 'Source Sans 3', sans-serif;
            color: var(--color-text);
            background-color: var(--color-warm-white);
        }

        .font-heading {
            font-family: 'Lora', serif;
        }

        /* Rustic Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(139, 69, 19, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(139, 69, 19, 0.35);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-secondary-light) 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(85, 107, 47, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(85, 107, 47, 0.35);
        }

        .btn-outline {
            border: 2px solid var(--color-primary);
            color: var(--color-primary);
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-outline:hover {
            background: var(--color-primary);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(220, 38, 38, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(220, 38, 38, 0.35);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        /* Card Styles */
        .card-rustic {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(61, 41, 20, 0.08);
            border: 1px solid var(--color-border);
            overflow: hidden;
        }

        /* Form Styles */
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--color-border);
            border-radius: 0.5rem;
            background: white;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        }

        .form-input:disabled {
            background: var(--color-cream);
            cursor: not-allowed;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--color-text);
        }

        .form-error {
            color: #DC2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .form-description {
            color: var(--color-text-light);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Sidebar Styles */
        .sidebar {
            background: white;
            border-right: 1px solid var(--color-border);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            color: var(--color-text-light);
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background: var(--color-cream);
            color: var(--color-primary);
        }

        .sidebar-link.active {
            background: var(--color-primary);
            color: white;
        }

        /* Table Styles */
        .table-rustic {
            width: 100%;
            border-collapse: collapse;
        }

        .table-rustic th {
            text-align: left;
            padding: 1rem;
            font-weight: 600;
            color: var(--color-text-light);
            border-bottom: 2px solid var(--color-border);
            background: var(--color-cream);
        }

        .table-rustic td {
            padding: 1rem;
            border-bottom: 1px solid var(--color-border);
        }

        .table-rustic tr:hover td {
            background: var(--color-cream);
        }

        /* Badge Styles */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background: #DEF7EC;
            color: #03543F;
        }

        .badge-warning {
            background: #FEF3C7;
            color: #92400E;
        }

        .badge-danger {
            background: #FEE2E2;
            color: #991B1B;
        }

        .badge-secondary {
            background: var(--color-cream);
            color: var(--color-text-light);
        }

        /* Modal Overlay */
        .modal-overlay {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        /* FilePond Customization */
        .filepond--root {
            font-family: 'Source Sans 3', sans-serif;
        }

        .filepond--panel-root {
            background-color: var(--color-cream);
            border: 2px dashed var(--color-border);
        }

        .filepond--drop-label {
            color: var(--color-text-light);
        }

        /* Pikaday Customization */
        .pika-single {
            font-family: 'Source Sans 3', sans-serif;
            border-color: var(--color-border);
            border-radius: 0.5rem;
        }

        .pika-button:hover {
            background: var(--color-primary) !important;
            color: white !important;
        }

        .is-selected .pika-button {
            background: var(--color-primary);
        }
    </style>

</head>

<body class="min-h-screen bg-[var(--color-warm-white)]">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 modal-overlay lg:hidden"
            @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        @include('components.app.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen lg:ml-64">
            <!-- Header -->
            @include('components.app.header')

            <!-- Page Content -->
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- FilePond JS - Main library must load first, then plugins -->
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js">
    </script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js">
    </script>

    <!-- Pikaday JS -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>

    <script>
        // Register FilePond plugins
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize
        );

        // FilePond defaults
        FilePond.setOptions({
            acceptedFileTypes: ['image/*'],
            maxFileSize: '5MB',
            imagePreviewHeight: 170,
            styleLoadIndicatorPosition: 'center bottom',
            styleProgressIndicatorPosition: 'right bottom',
            styleButtonRemoveItemPosition: 'left bottom',
            styleButtonProcessItemPosition: 'right bottom',
        });

    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')
</body>

</html>