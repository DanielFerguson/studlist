<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'StudList') }} - StudList</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Google Fonts - Rustic Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Source+Sans+3:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(139, 69, 19, 0.35);
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-secondary-light) 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(85, 107, 47, 0.25);
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
        }

        .btn-outline:hover {
            background: var(--color-primary);
            color: white;
        }

        /* Card Styles */
        .card-rustic {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(61, 41, 20, 0.08);
            border: 1px solid var(--color-border);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card-rustic:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(61, 41, 20, 0.15);
        }

        /* Texture Overlay */
        .texture-overlay {
            position: relative;
        }

        .texture-overlay::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            opacity: 0.03;
            pointer-events: none;
        }

        /* Warm Gradient Overlay for Heroes */
        .hero-overlay {
            background: linear-gradient(
                135deg,
                rgba(139, 69, 19, 0.7) 0%,
                rgba(160, 82, 45, 0.5) 50%,
                rgba(85, 107, 47, 0.4) 100%
            );
        }
    </style>
</head>
<body class="min-h-screen flex flex-col texture-overlay">
    <!-- Header -->
    @include('components.public.header')

    <!-- Main Content -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    @include('components.public.footer')

    @stack('scripts')
</body>
</html>

