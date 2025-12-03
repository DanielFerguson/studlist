<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Authentication' }} - StudList</title>

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
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
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

        /* Auth Card */
        .auth-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(61, 41, 20, 0.08);
            border: 1px solid var(--color-border);
            overflow: hidden;
        }

        /* Checkbox */
        .form-checkbox {
            width: 1rem;
            height: 1rem;
            border: 1px solid var(--color-border);
            border-radius: 0.25rem;
            appearance: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .form-checkbox:checked {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
        }

        .form-checkbox:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        }

        /* Link Styles */
        .text-link {
            color: var(--color-primary);
            transition: color 0.2s ease;
        }

        .text-link:hover {
            color: var(--color-primary-light);
            text-decoration: underline;
        }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="min-h-screen bg-[var(--color-warm-white)] flex flex-col">
    <!-- Header -->
    <header class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2 w-fit">
                <img src="/sl-logo.svg" alt="StudList" class="h-10 w-10">
                <span class="font-heading font-bold text-2xl text-[var(--color-primary)]">StudList</span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="auth-card">
                <div class="p-8">
                    <!-- Title -->
                    @if(isset($title))
                    <div class="text-center mb-8">
                        <h1 class="font-heading text-2xl font-bold text-[var(--color-text)]">
                            {{ $heading ?? $title }}
                        </h1>
                        @if(isset($description))
                        <p class="mt-2 text-[var(--color-text-light)]">
                            {{ $description }}
                        </p>
                        @endif
                    </div>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center text-sm text-[var(--color-text-light)]">
        <p>&copy; {{ date('Y') }} StudList. All rights reserved.</p>
    </footer>
</body>

</html>

