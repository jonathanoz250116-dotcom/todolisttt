
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi TodoList</title>
    <link rel="stylesheet" href="{{ asset('css/todo.css') }}">
</head>
<body>

    <div class="contenedor">
        <header>
            <h1>📝 Mi TodoList</h1>
            <p>Organiza tus tareas y cumple tus objetivos.</p>
        </header>

        @if (session('mensaje'))
            <div class="mensaje">
                {{ session('mensaje') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section class="formulario">
            <h2>Agregar nueva tarea</h2>

            <form action="{{ route('tareas.store') }}" method="POST">
                @csrf

                <input
                    type="text"
                    name="titulo"
                    placeholder="¿Qué tienes que hacer?"
                    value="{{ old('titulo') }}"
                    required
                >

                <textarea
                    name="descripcion"
                    placeholder="Descripción de la tarea (opcional)"
                >{{ old('descripcion') }}</textarea>

                <button type="submit">+ Agregar tarea</button>
            </form>
        </section>

        <section class="lista">
            <h2>Mis tareas ({{ $tareas->count() }})</h2>

            @forelse ($tareas as $tarea)
                <article class="tarea {{ $tarea->completada ? 'terminada' : '' }}">
                    <div class="contenido">
                        <h3>{{ $tarea->titulo }}</h3>

                        @if ($tarea->descripcion)
                            <p>{{ $tarea->descripcion }}</p>
                        @endif

                        <span class="estado">
                            {{ $tarea->completada ? '✅ Completada' : '⏳ Pendiente' }}
                        </span>
                    </div>

                    <div class="acciones">
                        <form action="{{ route('tareas.toggle', $tarea) }}"
                              method="POST">
                            @csrf
                            @method('PATCH')

                            <button class="btn-completar" type="submit">
                                {{ $tarea->completada ? 'Reabrir' : 'Completar' }}
                            </button>
                        </form>

                        <a class="btn-editar"
                           href="{{ route('tareas.edit', $tarea) }}">
                            Editar
                        </a>

                        <form action="{{ route('tareas.destroy', $tarea) }}"
                              method="POST"
                              onsubmit="return confirm('¿Quieres eliminar esta tarea?')">
                            @csrf
                            @method('DELETE')

                            <button class="btn-eliminar" type="submit">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="vacio">
                    <p>🌱 Todavía no tienes tareas.</p>
                    <p>¡Agrega tu primera tarea para comenzar!</p>
                </div>
            @endforelse
        </section>

        <footer>
            Hecho con Laravel 💚
        </footer>
    </div>

</body>
</html>
