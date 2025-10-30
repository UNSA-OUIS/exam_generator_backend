<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamRequirement;
use App\Services\Requirements\ExamRequirementService;
use Illuminate\Support\Facades\Log;

class ExamRequirementController extends Controller
{
    public function __construct(protected ExamRequirementService $service) {}

    public function index()
    {
        $requirements = ExamRequirement::with(['block'])->get();
        return response()->json($requirements);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|uuid|exists:exams,id',
            'parent_id' => 'required|integer|exists:exam_requirements,id',
            'area' => 'required|string',
            'block_id' => 'required|integer|exists:blocks,id',
            'difficulty' => 'required|string', // Assuming enum validation
            'n_questions' => 'required|integer|min:0',
        ]);

        try {
            $created = $this->service->store($validated);
            return response()->json($created, 201);
        } catch (\Throwable $e) {
            Log::error('Error creating ExamRequirement: ' . $e->getMessage());
            $status = $e->getCode() ?: 422;
            return response()->json(['error' => $e->getMessage()], $status);
        }
    }

    public function update(Request $request, ExamRequirement $examRequirement)
    {
        $validated = $request->validate([
            'n_questions' => 'sometimes|integer|min:0',
        ]);

        try {
            $updated = $this->service->update($examRequirement, $validated);
            return response()->json($updated);
        } catch (\Throwable $e) {
            $status = $e->getCode() ?: 422;
            return response()->json(['error' => $e->getMessage()], $status);
        }
    }

    public function destroy(ExamRequirement $examRequirement)
    {
        $examRequirement->delete();
        return response()->noContent();
    }

    public function byExam(Request $request, $examId)
    {
        $requirements = ExamRequirement::with(['block'])
            ->where('exam_id', $examId)
            ->when($request->has('area'), function ($query) use ($request) {
                $query->where('area', $request->input('area'));
            })
            ->orderBy('parent_id')
            ->get();
        return response()->json($requirements);
    }
}
