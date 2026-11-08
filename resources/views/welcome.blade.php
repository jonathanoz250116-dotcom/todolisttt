
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel escolar | Laravel</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        header {
            background: #173b65;
            color: white;
            padding: 28px 6%;
        }

        header h1 {
            margin: 0 0 8px;
        }

        header p {
            margin: 0;
            color: #dbeafe;
        }

        main {
            max-width: 1250px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .tarjetas {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }

        .tarjeta, .panel {
            background: white;
            padding: 22px;
            border-radius: 14px;
            box-shadow: 0 4px 15px #0f172a0a;
        }

        .tarjeta p {
            color: #64748b;
            margin: 0 0 10px;
        }

        .tarjeta h2 {
            margin: 0;
            font-size: 30px;
            color: #173b65;
        }

        .panel {
            margin-bottom: 24px;
        }

        .panel h2 {
            margin-top: 0;
        }

        .filtros {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(165px, 1fr));
            gap: 15px;
            align-items: end;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: white;
            font-size: 14px;
        }

        button, .boton {
            display: inline-block;
            border: none;
            border-radius: 8px;
            padding: 12px 18px;
            background: #2563eb;
            color: white;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }

        button:hover, .boton:hover {
            background: #1d4ed8;
        }

        .secundario {
            background: #e2e8f0;
            color: #1e293b;
        }

        .secundario:hover {
            background: #cbd5e1;
        }

        .ranking {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 15px;
        }

        .puesto {
            padding: 18px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .puesto h3 {
            margin: 8px 0;
        }

        .medalla {
            font-size: 25px;
        }

        .promedio {
            font-weight: bold;
            color: #15803d;
            font-size: 20px;
        }

        .tabla-contenedor {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 750px;
        }

        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background: #f8fafc;
            color: #475569;
            font-size: 13px;
        }

        .calificacion {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 7px;
            background: #dcfce7;
            color: #166534;
            font-weight: bold;
        }

        .baja {
            background: #fee2e2;
            color: #991b1b;
        }

        .grupo {
            background: #dbeafe;
            color: #1d4ed8;
            padding: 5px 9px;
            border-radius: 15px;
            font-size: 12px;
        }

        .mensaje {
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .correcto {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        .form-calificacion {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            align-items: end;
        }

        footer {
            text-align: center;
            padding: 25px;
            color: #64748b;
        }

        @media (max-width: 600px) {
            header {
                padding: 25px 20px;
            }

            main {
                margin-top: 20px;
                padding: 0 12px;
            }

            .panel {
                padding: 16px;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>🎓 Panel de rendimiento escolar</h1>
    <p>Control de estudiantes, materias y calificaciones</p>
</header>

<main>

    @if (session('success'))
        <div class="mensaje correcto">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mensaje error">
            <strong>Revisa los siguientes errores:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="tarjetas">
        <div class="tarjeta">
            <p>Estudiantes registrados</p>
            <h2>{{ count($estudiantes) }}</h2>
        </div>

        <div class="tarjeta">
            <p>Promedio general</p>
            <h2>{{ number_format($promedioGeneral, 2) }}</h2>
        </div>

        <div class="tarjeta">
            <p>Mejor promedio</p>
            <h2>
                {{ $mejores->isNotEmpty()
                    ? number_format($mejores->first()['promedio'], 2)
                    : '0.00' }}
            </h2>
        </div>
    </section>

    <section class="panel">
        <h2>🔎 Buscar y filtrar estudiantes</h2>

        <form action="{{ url('/') }}" method="GET" class="filtros">

            <div>
                <label for="buscar">Nombre del estudiante</label>
                <input
                    type="text"
                    id="buscar"
                    name="buscar"
                    value="{{ $buscar }}"
                    maxlength="100"
                    placeholder="Ej. Ana Martínez">
            </div>

            <div>
                <label for="grupo">Grupo</label>
                <select name="grupo" id="grupo">
                    <option value="">Todos los grupos</option>
                    <option value="4A"
                        {{ $grupoSeleccionado === '4A' ? 'selected' : '' }}>
                        4A
                    </option>
                    <option value="4B"
                        {{ $grupoSeleccionado === '4B' ? 'selected' : '' }}>
                        4B
                    </option>
                </select>
            </div>

            <div>
                <label for="minimo">Promedio mínimo</label>
                <input
                    type="number"
                    id="minimo"
                    name="minimo"
                    min="0"
                    max="10"
                    step="0.01"
                    value="{{ $minimo }}"
                    placeholder="Ej. 8.00">
            </div>

            <div>
                <label for="orden">Ordenar por</label>
                <select name="orden" id="orden">
                    <option value="promedio_desc"
                        {{ $orden === 'promedio_desc' ? 'selected' : '' }}>
                        Mejor promedio
                    </option>
                    <option value="promedio_asc"
                        {{ $orden === 'promedio_asc' ? 'selected' : '' }}>
                        Menor promedio
                    </option>
                    <option value="nombre"
                        {{ $orden === 'nombre' ? 'selected' : '' }}>
                        Nombre A-Z
                    </option>
                </select>
            </div>

            <div>
                <button type="submit">Aplicar filtros</button>
                <a href="{{ url('/') }}" class="boton secundario">
                    Limpiar
                </a>
            </div>

        </form>
    </section>

    <section class="panel">
        <h2>🏆 Mejores estudiantes</h2>
        <p>Los tres promedios más altos de la lista completa.</p>

        <div class="ranking">
            @foreach ($mejores as $i => $alumno)
                <div class="puesto">
                    <div class="medalla">
                        @if ($i === 0)
                            🥇
                        @elseif ($i === 1)
                            🥈
                        @else
                            🥉
                        @endif
                    </div>

                    <h3>{{ $alumno['nombre'] }}</h3>
                    <p>Grupo {{ $alumno['grupo'] }}</p>

                    <div class="promedio">
                        {{ number_format($alumno['promedio'], 2) }}/10
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="panel">
        <h2>📚 Calificaciones por estudiante</h2>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Grupo</th>
                        <th>Matemáticas</th>
                        <th>Programación</th>
                        <th>Inglés</th>
                        <th>Promedio</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($estudiantes as $alumno)
                        <tr>
                            <td>{{ $alumno['nombre'] }}</td>

                            <td>
                                <span class="grupo">
                                    {{ $alumno['grupo'] }}
                                </span>
                            </td>

                            @foreach ($alumno['materias'] as $nota)
                                <td>
                                    <span class="calificacion
                                        {{ $nota < 6 ? 'baja' : '' }}">
                                        {{ number_format($nota, 2) }}
                                    </span>
                                </td>
                            @endforeach

                            <td>
                                <strong>
                                    {{ number_format($alumno['promedio'], 2) }}
                                </strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                No se encontraron estudiantes con esos filtros.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel">
        <h2>✏️ Validar una calificación</h2>
        <p>
            Este formulario comprueba los datos, pero todavía no los guarda
            en la lista de estudiantes.
        </p>

        <form action="{{ url('/calificaciones') }}"
              method="POST"
              class="form-calificacion">

            @csrf

            <div>
                <label for="estudiante">Nombre del estudiante</label>
                <input
                    type="text"
                    id="estudiante"
                    name="estudiante"
                    maxlength="100"
                    value="{{ old('estudiante') }}"
                    required>
            </div>

            <div>
                <label for="materia">Materia</label>
                <select id="materia" name="materia" required>
                    <option value="">Selecciona una materia</option>

                    <option value="Matemáticas"
                        {{ old('materia') === 'Matemáticas' ? 'selected' : '' }}>
                        Matemáticas
                    </option>

                    <option value="Programación"
                        {{ old('materia') === 'Programación' ? 'selected' : '' }}>
                        Programación
                    </option>

                    <option value="Inglés"
                        {{ old('materia') === 'Inglés' ? 'selected' : '' }}>
                        Inglés
                    </option>
                </select>
            </div>

            <div>
                <label for="calificacion">Calificación (0 a 10)</label>
                <input
                    type="number"
                    id="calificacion"
                    name="calificacion"
                    min="0"
                    max="10"
                    step="0.01"
                    value="{{ old('calificacion') }}"
                    placeholder="Ej. 8.50"
                    required>
            </div>

            <div>
                <button type="submit">Validar calificación</button>
            </div>

        </form>
    </section>

</main>

<footer>
    Sistema escolar de práctica desarrollado con Laravel.
</footer>

</body>
</html>
