<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Status;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::with('status')->get();
        $statuses = Status::all();

        $statusCounts = Lead::select('status_id', DB::raw('count(*) as total'))
                            ->groupBy('status_id')
                            ->get();

        return response()->json([
            'leads' => $leads,
            'statuses' => $statuses,
            'statusCounts' => $statusCounts
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        Lead::create($validatedData);

        return response()->json('Stored successfully!');
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $lead->status_id = $request->status_id;
        $lead->save();

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Lead $lead)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $lead->update($validatedData);

        return response()->json([
            'success' => true,
            'lead' => $lead->fresh()->load('status')
        ]);
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return response()->json(['success' => true]);
    }
}
