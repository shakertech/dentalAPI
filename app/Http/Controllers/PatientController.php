<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->has('trashed')) {
            $query->withTrashed();
        }

        return response()->json($query->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'age' => 'nullable|integer|min:0|max:150',
            'weight' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'medical_history' => 'nullable|string',

            // Offline fields usually handled by sync, but if creating online:
            'device_id' => 'nullable|string',
            'created_by' => 'nullable|string',
        ]);

        // tenant_id is auto-assigned by trait
        // If created online via API by a user
        $validated['created_by'] = $validated['created_by'] ?? $request->user()->id;
        $validated['synced_at'] = now(); // It's already on server

        $patient = Patient::create($validated);

        return response()->json([
            'message' => 'Patient created successfully',
            'patient' => $patient
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        // Global scope handles tenant check
        $patient = Patient::withTrashed()->findOrFail($id);

        return response()->json($patient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'age' => 'nullable|integer|min:0|max:150',
            'weight' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        $validated['synced_at'] = now();

        $patient->update($validated);

        return response()->json([
            'message' => 'Patient updated successfully',
            'patient' => $patient
        ]);
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(Request $request, string $id)
    {
        $patient = Patient::findOrFail($id);

        $patient->update([
            'is_deleted' => true, // Offline sync flag
            'synced_at' => now(),
        ]);

        $patient->delete(); // Laravel Soft Delete

        return response()->json(['message' => 'Patient deleted successfully']);
    }

    /**
     * Permanently remove the specified resource from storage.
     */
    public function forceDelete(Request $request, string $id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);

        $patient->forceDelete();

        return response()->json(['message' => 'Patient permanently deleted']);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request, string $id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);

        $patient->restore();
        $patient->update([
            'is_deleted' => false,
            'synced_at' => now(),
        ]);

        return response()->json(['message' => 'Patient restored successfully', 'patient' => $patient]);
    }
}
