<?php

namespace App\Http\Controllers;

use App\Models\Paralelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

class ParaleloController extends Controller
{
   
    public function index()
    {
        $paralelo = Paralelo::all();
        return $paralelo;
    }


    public function store(Request $request)
    {
        Log::info('Datos que vienen en la peticion:', $request->all());
        $request-> validate(([
            'nombre'=> 'required|String|max:100|unique:paralelos'
        ]));
        
        $paralelo= Paralelo::create([
            'nombre' => $request->nombre
        ]);
        Log::info('Paralelo creado con el ID:'. $paralelo->id);

        return response()->json([
            'message' => 'Paralelo creado exitosamente',
            'paralelo' => $paralelo
        ],201);
    }

/**buscar */
    public function show($id)
    {
        Log::info('Iniciando solicitud con el id:', ['id' => $id]);
        $paralelo = Paralelo::find($id);

        if(!$paralelo){
            Log::info('Datos no encontrado:', ['id' => $id]);
            return response()->json(['message'=> 'Paralelo no encontrado'], 420);
        }
        Log::info('Datos encontrado:', ['paralelo' => $paralelo]);
        return $paralelo;
    }

    /**actualizar */
    public function update(Request $request, $id)
    {
        Log::info('Iniciando solicitud con el id:', ['id' => $id]);
        $paralelo = Paralelo::find($id);
        if(!$paralelo){
            Log::info('Datos no encontrado:', ['id' => $id]);
            return response()->json(['message'=> 'Paralelo no encontrado'], 420);
        }
        $request->validate([
        'nombre' => 'required|string|max:100',
        ]);

        $paralelo->update($request->all());

        Log::info('Datos actualizado:', ['paralelo' => $paralelo]);
        return response()->json(['message' => 'Paralelo actualizado', 'paralelo' => $paralelo], 201);
    }

/**eliminar */
    public function destroy($id)
    {
        Log::info('Iniciando solicitud con el id:', ['id' => $id]);
        $paralelo = Paralelo::find($id);
        if(!$paralelo){
            Log::info('Datos no encontrado:', ['id' => $id]);
            return response()->json(['message'=> 'Paralelo no encontrado'], 420);
        }
        $paralelo->delete();
        Log::info('Datos eliminado:', ['paralelo' => $paralelo]);
        return response()->json(['message' => 'Paralelo eliminado', 'paralelo' => $paralelo], 201);
    }
}
