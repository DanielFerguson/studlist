<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark'=> ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Inline script to detect system dark mode preference and apply it immediately --}}
    <script>
        (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
    </script>

    {{-- Inline style to set the HTML background color based on our theme in app.css --}}
    <style>
        html {
            background-color: oklch(1 0 0);
        }

        html.dark {
            background-color: oklch(0.145 0 0);
        }
    </style>

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Google Fonts - Rustic Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Source+Sans+3:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

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
            transition: all 0.3s ease;
        }

        .card-rustic:hover {
            box-shadow: 0 8px 30px rgba(61, 41, 20, 0.15);
        }
    </style>

    @routes
    @viteReactRefresh
    @vite(['resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>