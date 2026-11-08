
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar tarea</title>
    <link rel="stylesheet" href="{{ asset('css/todo.css') }}">
</head>
<body>

    <div class="contenedor">
        <header>
            <h1>✏️ Editar tarea</h1>
            <p>Modifica los detalles de tu actividad.</p>
        </header>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section class="formulario">
            <form action="{{ route('tareas.update', $tarea) }}" method="POST">
                @csrf
                @method('PUT')

                <label for="titulo">Título</label>
                <input
                    id="titulo"
                    type="text"
                    name="titulo"
                    value="{{ old('titulo', $tarea->titulo) }}"
                    required
                >

                <label for="descripcion">Descripción</label>
                <textarea
                    id="descripcion"
                    name="descripcion"
                >{{ old('descripcion', $tarea->descripcion) }}</textarea>

                <button type="submit">Guardar cambios</button>
                <a class="btn-volver" href="{{ route('tareas.index') }}">
                    Volver
                </a>
            </form>
        </section>
    </div>

</body>
</html>
