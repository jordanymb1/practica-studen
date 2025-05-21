<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Paralelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $student = Estudiante::with('paralelo')->get();
        $resultado = $student->map(function ($est){
            return [
                'id' => $est->id,
                'name' => $est->name,
                'cedula' => $est->cedula,
                'correo' => $est->correo,
                'paralelo' => $est->paralelo->nombre ?? null,
            ];
        });
        return response()->json(['resultado'=> $resultado],201);
    }

    public function store(Request $request)
    {
        Log::info('Intentando crear estudiantes:', $request->all());
        $request->validate([
        'name' => 'required|string',
        'cedula' => 'required|string|unique:estudiantes,cedula',
        'correo' => 'required|email|unique:estudiantes,correo',
        'paralelos_id' => 'required|exists:paralelos,id',
        ]);

        $student= Estudiante::create($request->all());
        Log::info('Estudiante creado con el ID:'. $student->id);
        
        return response()->json([
            'message' => 'Estudiante creado exitosamente',
            'student' => $student
        ],201);
    }

    public function show($id){

        Log::info('Iniciando solicitud con el id:', ['id' => $id]);
        $estudiante = Estudiante::with('paralelo')->find($id);
        if(!$estudiante){
            Log::info('Datos no encontrado:', ['id' => $id]);
            return response()->json([
                'message' => 'Estudiante no encontrado'
            ],420);
        }
        Log::info('Estudiante encontrado con el ID:', ['estudiante' => $estudiante]);
        return response()->json([
            'id' => $estudiante->id,
            'name' => $estudiante->name,
            'cedula' => $estudiante->cedula,
            'correo' => $estudiante->correo,
            'paralelo' => $estudiante->paralelo->nombre ?? null,
        ]);
    }
   
    public function update(Request $request, $id)
    {
        Log::info('Iniciando solicitud con el id:', ['id' => $id]);
        $student = Estudiante::find($id);

        if(!$student){
            Log::info('Datos no encontrado:', ['id' => $id]);
            return response()->json(['message'=> 'Estudiante no encontrado'], 420);
        }

        $request->validate([
        'name' => 'sometimes|required|string|max:100',
        'cedula' => 'sometimes|required|string|max:10|unique:estudiantes,cedula,' . $id,
        'correo' => 'sometimes|required|email',
        'paralelos_id' => 'sometimes|required|exists:paralelos,id',
        ]);

        $student->update($request->all());
        Log::info('Datos actualizado:', ['student' => $student]);
        return response()->json([
            'message' => 'Estudiante actualizado correctamente', 
            'student' => $student
        ], 201);
    }

   
    public function destroy($id)
    {
        Log::info('Iniciando solicitud con el id:', ['id' => $id]);
        $student = Estudiante::find($id);

        if(!$student){
            Log::info('Datos no encontrado:', ['id' => $id]);
            return response()->json(['message'=> 'Estudiante no encontrado'],420);
        }

        $student->delete();
        Log::info('Datos eliminado:', ['student' => $student]);

        return response()->json(['message' => 'Estudiante eliminado correctamente'], 201);
    }
    // Método para manejar el servicio SOAP
    public function soapServer()
{
    header("Content-Type: text/xml; charset=utf-8");

    $options = [
        'uri' => url('/soap'),
    ];

    $server = new \SoapServer(null, $options);
    $server->setClass(self::class); 
    $server->handle();
}



    // Función SOAP para obtener todos los estudiantes
    public function getEstudiantes()
    {
        return Estudiante::all()->toArray();
    }

    // Función SOAP para obtener un estudiante por ID
    public function getEstudianteById($id)
    {
        $estudiante = Estudiante::find($id);
        return $estudiante ? $estudiante->toArray() : null;
    }
    // Crear un estudiante vía SOAP
public function createEstudiante($name, $cedula, $correo, $paralelos_id)
{
    $existsCedula = Estudiante::where('cedula', $cedula)->exists();
    $existsCorreo = Estudiante::where('correo', $correo)->exists();

    if ($existsCedula || $existsCorreo) {
        throw new \SoapFault("Server", "El estudiante con esa cédula o correo ya existe.");
    }

    $estudiante = Estudiante::create([
        'name' => $name,
        'cedula' => $cedula,
        'correo' => $correo,
        'paralelos_id' => $paralelos_id
    ]);

    return $estudiante->toArray();
}

// Actualizar un estudiante vía SOAP
public function updateEstudiante($id, $name = null, $cedula = null, $correo = null, $paralelos_id = null)
{
    $estudiante = Estudiante::find($id);
    if (!$estudiante) {
        throw new \SoapFault("Server", "Estudiante no encontrado con ID $id");
    }

    // Validaciones básicas
    if ($cedula && Estudiante::where('cedula', $cedula)->where('id', '!=', $id)->exists()) {
        throw new \SoapFault("Server", "La cédula ya está en uso.");
    }
    if ($correo && Estudiante::where('correo', $correo)->where('id', '!=', $id)->exists()) {
        throw new \SoapFault("Server", "El correo ya está en uso.");
    }

    // Actualizar solo si vienen parámetros
    $estudiante->name = $name ?? $estudiante->name;
    $estudiante->cedula = $cedula ?? $estudiante->cedula;
    $estudiante->correo = $correo ?? $estudiante->correo;
    $estudiante->paralelos_id = $paralelos_id ?? $estudiante->paralelos_id;

    $estudiante->save();

    return $estudiante->toArray();
}

// Eliminar un estudiante vía SOAP
public function deleteEstudiante($id)
{
    $estudiante = Estudiante::find($id);
    if (!$estudiante) {
        throw new \SoapFault("Server", "Estudiante no encontrado con ID $id");
    }
    $estudiante->delete();

    return ['message' => "Estudiante con ID $id eliminado correctamente."];
}


}
