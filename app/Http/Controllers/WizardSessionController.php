<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class WizardSessionController extends Controller
{


 public function memory(Request $request)
    {
        $currentStep = (int) $request->input('current_step', 0);
        $existingMemory = Session::get('wizard.memory', []);

        // Base memory fields used across all steps
        $baseFields = $request->only(['language', 'tone', 'initial_input', 'current_step']);

        // Step-specific fields
        $allowed = $this->allowedFieldsByStep()[$currentStep] ?? [];
        $stepFields = $request->only($allowed);

        // Merge new fields into memory
        $memory = array_merge($existingMemory, $baseFields, $stepFields);

        // From step 4 onward, generate short description
        if ($currentStep >= 4) {
            $memory['short_description'] = $this->generateShortDescription($memory);
        }

        // Save to session
        Session::put('wizard.memory', $memory);

        return response()->json([
            'status' => 'ok',
            'step' => $currentStep,
            'updated' => array_keys($stepFields),
            'memory' => $memory,
        ]);
    }

    private function allowedFieldsByStep(): array
    {
        return [
            1 => ['language'],
            2 => ['tone'],
            3 => ['initial_input'],
            4 => ['type', 'tags'],
            5 => ['problem_statement', 'why_it_matters', 'objectives', 'type', 'tags'],
            6 => [
                // ✅ Step 6 stores Step 5 fields
                'problem_statement', 'why_it_matters', 'objectives',
                'participants', 'skills_required', 'teams_allowed', 'languages',
            ],
            7 => [
                // ✅ Step 7 stores Step 6 fields
                'participants', 'skills_required', 'teams_allowed', 'languages',
                'submission_format', 'required_docs', 'deliverables',
            ],
            8 => [
                // ✅ Step 8 stores Step 7 fields
                'submission_format', 'required_docs', 'deliverables',
                'evaluation_metrics', 'judging_method',
            ],
            9 => [
                // ✅ Step 9 stores Step 8 fields
                'evaluation_metrics', 'judging_method',
                'prize_structure', 'currency', 'non_monetary_rewards', 'sponsor_info',
            ],
            10 => [
                // ✅ Step 10 stores Step 9 fields
                'prize_structure', 'currency', 'non_monetary_rewards', 'sponsor_info',
                'launch_date', 'submission_deadline', 'judging_window_start', 'judging_window_end', 'announcement_date',
            ],
            11 => [
                // ✅ Step 11 stores Step 10 fields
                'launch_date', 'submission_deadline', 'judging_window_start', 'judging_window_end', 'announcement_date',
                'submission_limit', 'code_of_conduct', 'ip_terms',
            ],
            12 => [
                // ✅ Step 12 stores Step 11 fields (final legal step)
                'submission_limit', 'code_of_conduct', 'ip_terms',
            ],
        ];
    }


    private function generateShortDescription(array $memory): string
    {
        $tone = $memory['tone'] ?? '';
        $type = $memory['type'] ?? '';
        $idea = $memory['initial_input'] ?? '';
        $tags = is_array($memory['tags'] ?? null) ? implode(', ', $memory['tags']) : '';
        $participants = is_array($memory['participants'] ?? null) ? implode(' and ', $memory['participants']) : '';
        $objectives = $memory['objectives'] ?? '';
        $format = $memory['submission_format'] ?? '';
        $docs = is_array($memory['required_docs'] ?? null) ? implode(', ', $memory['required_docs']) : '';

        $desc = "A {$tone}";
        $desc .= $type ? " {$type}" : " challenge";
        $desc .= $idea ? " on {$idea}" : '';
        $desc .= $participants ? " for {$participants}" : '';
        $desc .= $tags ? " — tagged with {$tags}" : '';
        $desc .= $objectives ? " — focused on {$objectives}" : '';
        $desc .= $format ? " — submit via {$format}" : '';
        $desc .= $docs ? " with {$docs}" : '';

        return Str::limit(trim($desc), 250);
    }

}