<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Models\Libro;
use App\Http\Controllers\Controller;


class LibroController extends Controller
{
    public function index()
    {
         $libros = Libro::with('autores')->get();
    return response()->json($libros);
    }

    public function show($id)
{
    $libro = Libro::with('autores')->findOrFail($id);
    return response()->json($libro);
}

 public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'anio_publicacion' => 'required|integer',
            'editorial' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'autor_ids' => 'required|array|min:1',
            'autor_ids.*' => 'exists:autores,id',
        ]);

        $libro = Libro::create([
            'titulo' => $validated['titulo'],
            'anio_publicacion' => $validated['anio_publicacion'],
            'editorial' => $validated['editorial'] ?? null,
            'descripcion' => $validated['descripcion'] ?? null,
        ]);

        $libro->autores()->attach($validated['autor_ids']);

        return response()->json($libro->load('autores'), 201);
    } catch (\Throwable $e) {
        \Log::error("Error in store LibroController: {$e->getMessage()}");
        return response()->json(['error' => 'Error interno'], 500);
    }
}

public function update(Request $request, $id)
{
    $libro = Libro::findOrFail($id);

    $validated = $request->validate([
        'titulo' => 'required|string',
        'autor_ids' => 'required|array|min:1',
        'autor_ids.*' => 'exists:autores,id',
        'anio_publicacion' => 'required|integer',
        'editorial' => 'nullable|string',
        'descripcion' => 'nullable|string',
    ]);

    $libro->update($validated);
    $libro->autores()->sync($validated['autor_ids']);

    return response()->json($libro->load('autores'));
}
}
