<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\Language;
use Illuminate\Support\Facades\Session;

class ChallengeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $challenges = Challenge::all();
        return view('challenges-landing-page.index', compact('challenges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Session::forget('wizard.memory');
        $languages = Language::where('is_active', true)->get();

        return view('challenges-landing-page.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'review_challenge' => 'nullable|string',
            'review_solution' => 'nullable|string',
            'review_submission' => 'nullable|string',
            'review_evaluation' => 'nullable|string',
            'review_participation' => 'nullable|string',
            'review_awards' => 'nullable|string',
            'review_deadline' => 'nullable|string',
            'review_resources' => 'nullable|string',
        ]);

        // Handle file upload if exists
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Challenge::create($validated);

        return redirect()->route('challenges.index')->with('success', 'Challenge created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $challenge = Challenge::findOrFail($id);
        return view('challenges-landing-page.show', compact('challenge'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}