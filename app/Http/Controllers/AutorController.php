<?php

namespace App\Http\Controllers;
use App\Models\Autor;
use Illuminate\Http\Request;
use App\Models\Libro;
use App\Http\Controllers\Controller;

class AutorController extends Controller
/*Metodos('index, store, update, destroy')*/
{
    public function index()
    {
       $autores = Autor::with('libros')->whereNull('deleted_at')->get();
return response()->json($autores);
    }


    public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'apellido' => 'required|string|max:255',
        'pais' => 'nullable|string|max:255',
        'autor_ids' => 'array',
    'autor_ids.*' => 'exists:autores,id',
    ]);

    $autor = Autor::create([
        'nombre' => $request->nombre,
        'apellido' => $request->apellido,
        'pais' => $request->pais,
    ]);
// Al crear o actualizar un libro
/* $libro->autores()->sync($request->autor_ids); */
    return response()->json($autor, 201);
}

    public function show($id)
{
    $autor = Autor::withCount('libros')->findOrFail($id);

    return response()->json($autor);
}

public function update(Request $request, $id)
{
    $autor = Autor::find($id);

    if (!$autor) {
        return response()->json(['message' => 'Autor no encontrado'], 404);
    }

    $autor->nombre = $request->nombre;
    $autor->apellido = $request->apellido;
    $autor->pais = $request->pais;
    $autor->save();

    return response()->json($autor);
}
public function destroy($id)
{
    $autor = Autor::find($id);

    if (!$autor) {
        return response()->json(['message' => 'Autor no encontrado'], 404);
    }

    // Si usas soft deletes
    $autor->delete();

    return response()->json(['message' => 'Autor eliminado correctamente']);
}
}
