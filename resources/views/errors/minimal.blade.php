<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title') - MasterCut</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('img/mastercut-mark.svg') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|dm-serif-display:400&display=swap" rel="stylesheet" />
        <style>
            * { box-sizing: border-box; margin: 0; padding: 0; }
            body { background: #171513; color: #FFFDF8; font-family: 'DM Sans', system-ui, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; text-align: center; padding: 1rem; }
            .eyebrow { color: #D99A4E; font-size: .75rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; }
            h1 { font-family: 'DM Serif Display', Georgia, serif; font-size: clamp(2.5rem, 8vw, 5rem); margin-top: 1rem; }
            p { color: rgba(255,253,248,.7); margin-top: 1rem; font-size: 1.1rem; }
            a { display: inline-flex; margin-top: 2rem; background: #D99A4E; color: #171513; font-weight: 700; padding: 1rem 1.5rem; text-decoration: none; }
            a:hover { background: #FFFDF8; }
        </style>
    </head>
    <body>
        <main>
            <p class="eyebrow">Error @yield('code')</p>
            <h1>@yield('title')</h1>
            <p>@yield('message')</p>
            <a href="{{ url('/') }}">Volver al inicio →</a>
        </main>
    </body>
</html>
