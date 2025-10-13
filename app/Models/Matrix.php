<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Matrix",
 *     type="object",
 *     title="Matrix",
 *     required={"id", "year", "process_id", "total_alternatives"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="year", type="string", example="2025"),
 *     @OA\Property(property="process_id", type="integer", example=2),
 *     @OA\Property(property="total_alternatives", type="integer", example=120),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-27T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-28T12:30:00Z"),

 *     @OA\Property(
 *         property="process",
 *         type="object",
 *         description="Proceso relacionado",
 *         @OA\Property(property="id", type="integer", example=2),
 *         @OA\Property(property="name", type="string", example="Proceso 2025")
 *     ),

 *     @OA\Property(
 *         property="details",
 *         type="array",
 *         description="Detalles de la matriz",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=10),
 *             @OA\Property(property="matrix_id", type="integer", example=1),
 *             @OA\Property(property="label", type="string", example="Matemática"),
 *             @OA\Property(property="value", type="number", format="float", example=5.25)
 *         )
 *     )
 * )
 */
class Matrix extends Model
{
    protected $fillable = [
        'year',
        'modality_id',
        'total_alternatives',
    ];

    public function modality()
    {
        return $this->belongsTo(Modality::class);
    }

    public function requirements()
    {
        return $this->hasMany(MatrixRequirement::class);
    }
}
