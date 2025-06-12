<?php

namespace App\Http\Controllers;

use App\Models\Matrix;
use Illuminate\Http\Request;

class MatrixController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matrices = Matrix::all();
        return response()->json($matrices);
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(Matrix $matrix)
    {
        $matrix->load('process', 'details');
        return response()->json($matrix);
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(Matrix $matrix)
    {
        $matrix->delete();
        return response()->json(null, 204);
    }
}
