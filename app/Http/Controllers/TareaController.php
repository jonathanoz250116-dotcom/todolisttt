<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    public function index()
    {
        $tareas = Tarea::latest()->get();

        return view('tareas.index', compact('tareas'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        Tarea::create($datos);

        return redirect()->route('tareas.index')
            ->with('mensaje', '¡Tarea agregada correctamente!');
    }

    public function edit(Tarea $tarea)
    {
        return view('tareas.edit', compact('tarea'));
    }

    public function update(Request $request, Tarea $tarea)
    {
        $datos = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $tarea->update($datos);

        return redirect()->route('tareas.index')
            ->with('mensaje', '¡Tarea actualizada!');
    }

    public function toggle(Tarea $tarea)
    {
        $tarea->update([
            'completada' => !$tarea->completada
        ]);

        return redirect()->route('tareas.index');
    }

    public function destroy(Tarea $tarea)
    {
        $tarea->delete();

        return redirect()->route('tareas.index')
            ->with('mensaje', 'Tarea eliminada.');
    }
}