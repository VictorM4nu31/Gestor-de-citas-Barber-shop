<?php

/**
 * Collects duplicate array keys from a PHP file that only returns an array.
 *
 * PHP silently keeps the last value for a repeated key, so a duplicated entry
 * makes every earlier sibling unreachable. Bracket depth is tracked from the
 * token stream rather than the raw text, because this project stores regular
 * expressions such as '/^[a-z]+$/' whose character classes would otherwise
 * corrupt a line-based count.
 *
 * @return array<int, string>
 */
function clavesDuplicadas(string $ruta): array
{
    $tokens = token_get_all(file_get_contents($ruta) ?: '');
    $pila = [[]];
    $duplicadas = [];
    $total = count($tokens);

    for ($i = 0; $i < $total; $i++) {
        $token = $tokens[$i];

        if (is_string($token) && $token === '[') {
            $pila[] = [];

            continue;
        }

        if (is_string($token) && $token === ']') {
            if (count($pila) > 1) {
                array_pop($pila);
            }

            continue;
        }

        if (! is_array($token) || $token[0] !== T_CONSTANT_ENCAPSED_STRING) {
            continue;
        }

        $clave = trim($token[1], "'\"");
        $esClave = false;

        for ($siguiente = $i + 1; $siguiente < $total; $siguiente++) {
            $candidato = $tokens[$siguiente];

            if (is_array($candidato) && in_array($candidato[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
                continue;
            }

            $esClave = is_array($candidato) && $candidato[0] === T_DOUBLE_ARROW;
            break;
        }

        if (! $esClave) {
            continue;
        }

        $actual = end($pila);

        if (in_array($clave, $actual, true)) {
            $duplicadas[] = "'{$clave}' (linea {$token[2]})";
        } else {
            $pila[count($pila) - 1][] = $clave;
        }
    }

    return $duplicadas;
}

it('has no duplicate array keys in the language files', function () {
    // Pre-existing: the barber and appointment forms validate identically named
    // fields, so validation.php declares custom.nombre_completo, custom.email and
    // friends twice. The second block silently wins. Resolving it means deciding
    // which wording applies, so it is recorded here instead of silently changed.
    $conocidas = [
        'validation.php' => ['nombre_completo', 'email', 'password', 'telefono', 'especialidad', 'experiencia', 'foto'],
    ];

    $nuevas = [];

    foreach (glob(base_path('resources/lang/es/*.php')) as $archivo) {
        $nombre = basename($archivo);
        $permitidas = $conocidas[$nombre] ?? [];

        foreach (clavesDuplicadas($archivo) as $duplicada) {
            $clave = trim(explode(' ', $duplicada)[0], "'");

            if (! in_array($clave, $permitidas, true)) {
                $nuevas[] = $nombre.' -> '.$duplicada;
            }
        }
    }

    expect($nuevas)->toBe([]);
});

it('parses every language file', function () {
    $rotos = [];

    foreach (glob(base_path('resources/lang/es/*.php')) as $archivo) {
        exec('php -l '.escapeshellarg($archivo).' 2>&1', $salida, $codigo);

        if ($codigo !== 0) {
            $rotos[] = basename($archivo).': '.implode(' ', $salida);
        }

        $salida = [];
    }

    expect($rotos)->toBe([]);
});

it('resolves the gallery upload labels instead of echoing the key', function () {
    expect(__('gallery.admin.upload.title'))->not->toBe('gallery.admin.upload.title')
        ->and(__('gallery.admin.upload.uploading'))->not->toBe('gallery.admin.upload.uploading')
        ->and(__('gallery.admin.upload.file_too_large'))->toContain(':max_size')
        ->and(__('gallery.admin.upload.validation.file_size'))->toContain(':max');
});

it('keeps the services section copy separate from the field label', function () {
    expect(__('services.description'))->toBe('Descubre los servicios que ofrecemos para ti.')
        ->and(__('services.description_label'))->toBe('Descripción');
});
