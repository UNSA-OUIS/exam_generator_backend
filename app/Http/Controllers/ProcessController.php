<?php

namespace App\Http\Controllers;

use App\Models\Process;
use Illuminate\Http\Request;

// php artisan make:controller ProcessController --api --model=Process
/**
 * @OA\Info(
 *      version="1.0.0",
 *      x={
 *          "logo": {
 *              "url": "https://via.placeholder.com/190x90.png?text=L5-Swagger"
 *          }
 *      },
 *      title="Exam generator API",
 *      description="API for exam generator project at UNSA",
 *      @OA\Contact(
 *          email="rhualla@unsa.edu.pe"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */
class ProcessController extends Controller
{
    /**
     * @OA\Get(
     *     path="/processes",
     *     summary="Obtener lista de procesos",
     *     description="Retorna una lista de procesos",
     *     tags={"Procesos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de procesos"
     *     ),
     *     security={
     *         {"sanctum_token": {}}
     *     }
     * )
     * 
     * Returns list of processes
     */
    public function index()
    {
        $processes = Process::all();
        return response()->json($processes);
    }

    /**
     * @OA\Post(
     *     path="/processes",
     *     summary="Crear un nuevo proceso",
     *     description="Crea un nuevo proceso y lo devuelve",
     *     tags={"Procesos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="Proceso de admisión")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Proceso creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Proceso de admisión"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-27T10:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-27T10:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="array",
     *                     @OA\Items(type="string", example="The name field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     security={
     *         {"sanctum_token": {}}
     *     }
     * )
     *
     * Crea un nuevo proceso
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $process = Process::create($validated);

        return response()->json($process, 201);
    }

    /**
     * @OA\Get(
     *      path="/processes/{id}",
     *      operationId="show",
     *      tags={"Procesos"},
     *      summary="Obtiene informacion de un proceso",
     *      description="Retorna datos de un proceso",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id del proceso",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Process data"
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      security={
     *         {"sanctum_token": {}}
     *     },
     * )
     * 
     * Return data from a specific process
     */
    public function show(Process $process)
    {
        return response()->json($process);
    }

    /**
     * @OA\Patch(
     *     path="/processes/{id}",
     *     summary="Actualizar un proceso",
     *     description="Actualiza los datos de un proceso existente",
     *     tags={"Procesos"},
     *     security={{"sanctum_token": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del proceso",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, example="Proceso actualizado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Proceso actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Proceso actualizado"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Unauthenticated."))
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="array",
     *                     @OA\Items(type="string", example="The name must not be greater than 255 characters.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Proceso no encontrado"
     *     )
     * )
     */
    public function update(Request $request, Process $process)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $process->update($validated);

        return response()->json($process);
    }

    /**
     * @OA\Delete(
     *     path="/processes/{id}",
     *     summary="Eliminar un proceso",
     *     description="Elimina un proceso por su ID",
     *     tags={"Procesos"},
     *     security={{"sanctum_token": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del proceso",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Proceso eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Unauthenticated."))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Proceso no encontrado"
     *     )
     * )
     */
    public function destroy(Process $process)
    {
        $process->delete();
        return response()->json(null, 204);
    }
}
