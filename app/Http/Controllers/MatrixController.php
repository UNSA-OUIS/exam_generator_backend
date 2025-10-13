<?php

namespace App\Http\Controllers;

use App\Models\Matrix;
use Illuminate\Http\Request;

class MatrixController extends Controller
{
    /**
     * @OA\Get(
     *     path="/matrices",
     *     summary="Listar matrices",
     *     description="Obtiene la lista completa de matrices",
     *     tags={"Matrices"},
     *     security={{"sanctum_token": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de matrices",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Matrix"))
     *     ),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function index()
    {
        $matrices = Matrix::with('modality')->get();
        return response()->json($matrices);
    }

    /**
     * @OA\Post(
     *     path="/matrices",
     *     summary="Crear nueva matriz",
     *     description="Crea una nueva matriz y la devuelve",
     *     tags={"Matrices"},
     *     security={{"sanctum_token": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"year", "process_id", "total_alternatives"},
     *             @OA\Property(property="year", type="string", example="2025"),
     *             @OA\Property(property="process_id", type="integer", example=1),
     *             @OA\Property(property="total_alternatives", type="integer", example=100)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Matriz creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Matrix")
     *     ),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|digits:4',
            'process_id' => 'required|exists:processes,id',
            'total_alternatives' => 'required|integer',
        ]);

        $matrix = Matrix::create($validated);

        return response()->json($matrix, 201);
    }

    /**
     * @OA\Get(
     *     path="/matrices/{id}",
     *     summary="Obtener una matriz",
     *     description="Devuelve los detalles de una matriz específica",
     *     tags={"Matrices"},
     *     security={{"sanctum_token": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la matriz",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos de la matriz",
     *         @OA\JsonContent(ref="#/components/schemas/Matrix")
     *     ),
     *     @OA\Response(response=404, description="Matriz no encontrada")
     * )
     */
    public function show(Matrix $matrix)
    {
        $matrix->load('modality', 'details');
        return response()->json($matrix);
    }

    /**
     * @OA\Patch(
     *     path="/matrices/{id}",
     *     summary="Actualizar una matriz",
     *     description="Actualiza los datos de una matriz existente",
     *     tags={"Matrices"},
     *     security={{"sanctum_token": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la matriz",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="year", type="string", example="2025"),
     *             @OA\Property(property="process_id", type="integer", example=1),
     *             @OA\Property(property="total_alternatives", type="integer", example=150)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Matriz actualizada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Matrix")
     *     ),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=404, description="Matriz no encontrada")
     * )
     */
    public function update(Request $request, Matrix $matrix)
    {
        $validated = $request->validate([
            'year' => 'sometimes|required|digits:4',
            'process_id' => 'sometimes|required|exists:processes,id',
            'total_alternatives' => 'sometimes|required|integer',
        ]);

        $matrix->update($validated);

        return response()->json($matrix);
    }

    /**
     * @OA\Delete(
     *     path="/matrices/{id}",
     *     summary="Eliminar una matriz",
     *     description="Elimina una matriz por su ID",
     *     tags={"Matrices"},
     *     security={{"sanctum_token": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la matriz",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Matriz eliminada correctamente"
     *     ),
     *     @OA\Response(response=404, description="Matriz no encontrada")
     * )
     */
    public function destroy(Matrix $matrix)
    {
        $matrix->delete();
        return response()->json(null, 204);
    }
}
