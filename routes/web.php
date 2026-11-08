<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$estudiantes = [
    [
        'id' => 1,
        'nombre' => 'Ana Martínez',
        'grupo' => '4A',
        'materias' => [
            'Matemáticas' => 9.5,
            'Programación' => 10,
            'Inglés' => 9.2,
        ],
    ],
    [
        'id' => 2,
        'nombre' => 'Carlos López',
        'grupo' => '4A',
        'materias' => [
            'Matemáticas' => 7.8,
            'Programación' => 8.5,
            'Inglés' => 8.0,
        ],
    ],
    [
        'id' => 3,
        'nombre' => 'Sofía Hernández',
        'grupo' => '4B',
        'materias' => [
            'Matemáticas' => 10,
            'Programación' => 9.8,
            'Inglés' => 9.7,
        ],
    ],
    [
        'id' => 4,
        'nombre' => 'Diego Ramírez',
        'grupo' => '4B',
        'materias' => [
            'Matemáticas' => 6.5,
            'Programación' => 7.2,
            'Inglés' => 8.0,
        ],
    ],
    [
        'id' => 5,
        'nombre' => 'Valeria Torres',
        'grupo' => '4A',
        'materias' => [
            'Matemáticas' => 9.0,
            'Programación' => 9.4,
            'Inglés' => 9.6,
        ],
    ],
    [
        'id' => 6,
        'nombre' => 'Miguel Sánchez',
        'grupo' => '4B',
        'materias' => [
            'Matemáticas' => 8.2,
            'Programación' => 8.7,
            'Inglés' => 7.9,
        ],
    ],
];

Route::get('/', function (Request $request) use ($estudiantes) {
    $buscar = trim($request->query('buscar', ''));
    $grupo = $request->query('grupo', '');
    $orden = $request->query('orden', 'promedio_desc');
    $minimo = $request->query('minimo', '');

    $lista = collect($estudiantes)->map(function ($estudiante) {
        $estudiante['promedio'] = round(
            array_sum($estudiante['materias']) /
            count($estudiante['materias']),
            2
        );

        return $estudiante;
    });

    if ($buscar !== '') {
        $lista = $lista->filter(function ($estudiante) use ($buscar) {
            return stripos($estudiante['nombre'], $buscar) !== false;
        });
    }

    if (in_array($grupo, ['4A', '4B'], true)) {
        $lista = $lista->where('grupo', $grupo);
    }

    if ($minimo !== '' && is_numeric($minimo)
        && $minimo >= 0 && $minimo <= 10) {
        $lista = $lista->filter(function ($estudiante) use ($minimo) {
            return $estudiante['promedio'] >= (float) $minimo;
        });
    }

    if ($orden === 'promedio_asc') {
        $lista = $lista->sortBy('promedio');
    } elseif ($orden === 'nombre') {
        $lista = $lista->sortBy('nombre');
    } else {
        $lista = $lista->sortByDesc('promedio');
    }

    $promedioGeneral = round(
        collect($estudiantes)->avg(function ($estudiante) {
            return array_sum($estudiante['materias']) /
                count($estudiante['materias']);
        }),
        2
    );

    $mejores = collect($estudiantes)
        ->map(function ($estudiante) {
            $estudiante['promedio'] = round(
                array_sum($estudiante['materias']) /
                count($estudiante['materias']),
                2
            );

            return $estudiante;
        })
        ->sortByDesc('promedio')
        ->take(3);

    return view('welcome', [
        'estudiantes' => $lista,
        'buscar' => $buscar,
        'grupoSeleccionado' => $grupo,
        'orden' => $orden,
        'minimo' => $minimo,
        'promedioGeneral' => $promedioGeneral,
        'mejores' => $mejores,
    ]);
});

Route::post('/calificaciones', function (Request $request) {
    $datos = $request->validate([
        'estudiante' => ['required', 'string', 'max:100'],
        'materia' => [
            'required',
            'in:Matemáticas,Programación,Inglés',
        ],
        'calificacion' => [
            'required',
            'numeric',
            'min:0',
            'max:10',
            'regex:/^\d{1,2}(\.\d{1,2})?$/',
        ],
    ], [
        'estudiante.required' => 'Escribe el nombre del estudiante.',
        'materia.required' => 'Selecciona una materia.',
        'materia.in' => 'La materia seleccionada no es válida.',
        'calificacion.required' => 'Escribe una calificación.',
        'calificacion.numeric' => 'La calificación debe ser numérica.',
        'calificacion.min' => 'La calificación mínima es 0.',
        'calificacion.max' => 'La calificación máxima es 10.',
        'calificacion.regex' => 'Usa hasta dos decimales, por ejemplo 8.50.',
    ]);

    return redirect('/')
        ->with('success', 'Calificación validada correctamente para '
            . $datos['estudiante'] . ': '
            . number_format((float) $datos['calificacion'], 2)
            . ' en ' . $datos['materia'] . '.');
});
