<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

class WizardGPTController extends Controller
{
    public function suggestStep4(Request $request)
    {
        $memory = Session::get('wizard.memory');

        if (!$memory || !isset($memory['initial_input'])) {
            return response()->json([
                'error' => 'Missing memory context. Please complete Step 3 first.'
            ], 400);
        }

        $prompt = <<<PROMPT
        Based on the following challenge idea, suggest:

        1. 4 to 6 appropriate challenge types (like Design, Development, Data Science, Ideation, etc.)
        2. 2 to 6 tags relevant to the theme (e.g., Sustainability, AI, Health, Education, etc.)

        Only return raw JSON in this format:
        {
        "types": [array of strings],
        "tags": [array of strings]
        }

        Challenge Idea:
        "{$memory['initial_input']}"
        PROMPT;

        try {
            $response = Http::withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'max_tokens' => 150,
                    'temperature' => 0.7,
                ]);

            $result = $response->json();
            $content = $result['choices'][0]['message']['content'] ?? '{}';

            $json = json_decode($content, true);

            if (!isset($json['types']) || !isset($json['tags'])) {
                return response()->json([
                    'error' => 'Invalid AI response structure.',
                    'raw' => $content
                ], 422);
            }

            return response()->json([
                'types' => array_slice($json['types'], 0, 6),
                'tags' => array_slice($json['tags'], 0, 6),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate Step 4 suggestions.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function suggestStep5(Request $request)
    {
        $memory = Session::get('wizard.memory');

        if (!$memory || empty($memory['initial_input'])) {
            return response()->json([
                'error' => 'Wizard memory is missing or incomplete.'
            ], 400);
        }

        $input = $memory['initial_input'];
        $context = $memory['context'] ?? '';

        $prompt = <<<PROMPT
        You are helping to write the foundation of an open innovation challenge.

        Based on the following context and input, return the following fields:

        - problem_statement: what issue this challenge solves
        - why_it_matters: why the issue is urgent or impactful
        - objectives: what success looks like

        Context:
        {$context}

        Challenge Idea:
        {$input}

        Return ONLY a valid JSON object in this exact format:

        {
        "problem_statement": "...",
        "why_it_matters": "...",
        "objectives": "..."
        }

        No commentary, markdown, or extra formatting.
        PROMPT;

        try {
            $response = Http::withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'max_tokens' => 400,
                    'temperature' => 0.6,
                ]);

            $text = $response->json()['choices'][0]['message']['content'] ?? '';

            // Decode directly from JSON
            $json = json_decode($text, true);

            if (is_array($json)) {
                return response()->json([
                    'problem_statement' => $json['problem_statement'] ?? '',
                    'why_it_matters' => $json['why_it_matters'] ?? '',
                    'objectives' => $json['objectives'] ?? '',
                ]);
            } else {
                \Log::warning('Step 5: AI returned invalid JSON', ['raw' => $text]);
                return response()->json([
                    'problem_statement' => '',
                    'why_it_matters' => '',
                    'objectives' => '',
                ]);
            }

        } catch (\Exception $e) {
            \Log::error("Step 5 AI generation failed", ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'AI request failed.'
            ], 500);
        }
    }

    public function suggestStep6(Request $request)
    {
        $memory = Session::get('wizard.memory');

        if (!$memory || empty($memory['initial_input'])) {
            return response()->json([
                'error' => 'Wizard memory is missing or incomplete.'
            ], 400);
        }

        $context = $memory['context'] ?? '';
        $input = $memory['initial_input'];

        $prompt = <<<PROMPT
        You are assisting with a public innovation challenge setup.

        Based on the challenge idea and context below, suggest:
        - 2 to 5 ideal participant groups (e.g., "Students", "NGOs", "Startups")
        - 3 to 6 relevant required skills (e.g., "Python", "UX Design")

        Return ONLY a valid JSON object in the following format:

        {
        "participants": ["...", "..."],
        "skills_required": ["...", "..."]
        }

        Do not include explanation, markdown, or any extra formatting.

        Context:
        {$context}

        Challenge Idea:
        {$input}
        PROMPT;

        try {
            $response = Http::withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'max_tokens' => 300,
                    'temperature' => 0.5,
                ]);

            $text = $response->json()['choices'][0]['message']['content'] ?? '';

            $json = json_decode($text, true);

            if (is_array($json)) {
                return response()->json([
                    'participants' => $json['participants'] ?? [],
                    'skills_required' => $json['skills_required'] ?? [],
                ]);
            } else {
                \Log::warning('Step 6: AI returned invalid JSON', ['raw' => $text]);
                return response()->json([
                    'participants' => [],
                    'skills_required' => [],
                ]);
            }

        } catch (\Exception $e) {
            \Log::error("Step 6 AI generation failed", ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'AI request failed.'
            ], 500);
        }
    }

    public function suggestStep7(Request $request)
    {
        $memory = Session::get('wizard.memory');

        if (!$memory || empty($memory['initial_input'])) {
            return response()->json([
                'error' => 'Wizard memory is missing or incomplete.'
            ], 400);
        }

        $idea = $memory['initial_input'];
        $type = $memory['type'] ?? 'General';
        $participants = implode(', ', $memory['participants'] ?? []);
        $skills = implode(', ', $memory['skills_required'] ?? []);
        $teams = !empty($memory['teams_allowed']) && $memory['teams_allowed'] === 'true' ? 'Yes' : 'No';
        $language = $memory['languages'] ?? 'English';

        $prompt = <<<PROMPT
        You are helping configure the submission process for a public innovation challenge.

        Challenge Overview:
        - Type: {$type}
        - Idea: {$idea}
        - Participants: {$participants}
        - Skills Required: {$skills}
        - Teams Allowed: {$teams}
        - Submission Language: {$language}

        Based on this context, suggest:

        1. The most appropriate submission format (e.g. "GitHub Link", "ZIP", "PDF", "Google Drive"). Choose based on the nature of the work.
        2. A set of 2 to 4 required documentation items relevant to the project (e.g. "README", "License", "Submission Guide", "Pitch Deck").
        3. A set of 2 to 5 deliverables that would demonstrate meaningful completion (e.g. "Source Code", "Video Demo", "Presentation", "Research Report").

        Return ONLY this valid JSON structure:

        {
        "submission_format": "GitHub Link",
        "submission_format_options": ["ZIP", "GitHub Link", "Google Drive", "PDF"],
        "required_docs": ["README", "License"],
        "deliverables": ["Source Code", "Video Demo"]
        }

        No extra explanation or markdown.
        PROMPT;

        try {
            $response = Http::withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'max_tokens' => 300,
                    'temperature' => 0.6,
                ]);

            $text = $response->json()['choices'][0]['message']['content'] ?? '';
            $json = json_decode($text, true);

            if (
                is_array($json) &&
                isset($json['submission_format']) &&
                isset($json['submission_format_options']) &&
                isset($json['required_docs']) &&
                isset($json['deliverables'])
            ) {
                return response()->json([
                    'submission_format' => $json['submission_format'],
                    'submission_format_options' => array_slice($json['submission_format_options'], 0, 6),
                    'required_docs' => array_slice($json['required_docs'], 0, 6),
                    'deliverables' => array_slice($json['deliverables'], 0, 6),
                ]);
            } else {
                \Log::warning('Step 7: AI returned invalid JSON', ['raw' => $text]);
                return response()->json([
                    'error' => 'Invalid AI structure',
                    'raw' => $text,
                ], 422);
            }

        } catch (\Exception $e) {
            \Log::error("Step 7 AI generation failed", ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'AI request failed.'
            ], 500);
        }
    }

    public function suggestStep8(Request $request)
    {
        $memory = Session::get('wizard.memory');

        if (!$memory || empty($memory['problem_statement']) || empty($memory['objectives'])) {
            return response()->json([
                'error' => 'Missing context. Please complete Step 5 first.'
            ], 400);
        }

        $idea = $memory['initial_input'];
        $type = $memory['type'] ?? 'General';
        $problem = $memory['problem_statement'];
        $objectives = $memory['objectives'];
        $context = $memory['context'] ?? '';

        $prompt = <<<PROMPT
        You are helping define fair and practical evaluation metrics for a public innovation challenge.

        Context:
        - Type: {$type}
        - Idea: {$idea}
        - Problem Statement: {$problem}
        - Objectives: {$objectives}
        - Additional Context: {$context}

        Based on this information and the likely complexity of the challenge, suggest **3 to 6 evaluation criteria** that are appropriate for judging submissions.

        Each metric must include:
        - A clear label (e.g. "Innovation", "Feasibility", "Impact")
        - A weight (%) based on its importance — total should add up to 100%

        Tailor the weights to match the difficulty, scale, and intended outcomes of the challenge.

        Return ONLY this valid JSON format:

        {
        "evaluation_metrics": [
            { "label": "Innovation", "weight": 30 },
            { "label": "Technical Execution", "weight": 25 },
            { "label": "Impact", "weight": 20 },
            ...
        ]
        }

        Do not include any commentary, markdown, headings, or extra text.
        PROMPT;

        try {
            $response = Http::withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'max_tokens' => 300,
                    'temperature' => 0.6,
                ]);

            $text = $response->json()['choices'][0]['message']['content'] ?? '';
            $json = json_decode($text, true);

            if (isset($json['evaluation_metrics']) && is_array($json['evaluation_metrics'])) {
                return response()->json([
                    'evaluation_metrics' => array_slice($json['evaluation_metrics'], 0, 6)
                ]);
            } else {
                \Log::warning('Step 8: Invalid AI structure', ['raw' => $text]);
                return response()->json([
                    'evaluation_metrics' => []
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Step 8 AI failed', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'AI request failed.'
            ], 500);
        }
    }


public function suggestStep9(Request $request)
{
    $memory = Session::get('wizard.memory');

    if (!$memory || empty($memory['initial_input'])) {
        return response()->json([
            'error' => 'Wizard memory is missing or incomplete.'
        ], 400);
    }

    $idea = $memory['initial_input'];
    $context = $memory['context'] ?? '';

    $idea = $memory['initial_input'];
    $type = $memory['type'] ?? 'General';
    $participants = implode(', ', $memory['participants'] ?? []);
    $skills = implode(', ', $memory['skills_required'] ?? []);
    $context = $memory['context'] ?? '';

    $prompt = <<<PROMPT
    You are designing a tiered prize structure for a public innovation challenge.

    Please consider the scope and complexity of this challenge:
    - Type: {$type}
    - Idea: {$idea}
    - Target Participants: {$participants}
    - Required Skills: {$skills}
    - Additional Context: {$context}

    Based on this, provide two things:

    1. A prize_structure consisting of 3 to 5 ranked rewards. Each entry should include:
        - rank (e.g. "1st", "2nd", "Honorable Mention")
        - description (mix of cash and non-cash rewards as appropriate)
        - Use USD as currency symbol, e.g. "$ "

    The structure should reflect the expected difficulty and impact — for example:
    - Higher cash for technical or high-effort challenges
    - More recognition-based for ideation or creative challenges

    2. A short sentence summarizing non_monetary_rewards — e.g. certificates, internships, publication, or public recognition.

    Return ONLY this valid JSON structure:

    {
    "prize_structure": [
        { "rank": "1st", "description": "$ " },
        { "rank": "2nd", "description": "$" },
        { "rank": "3rd", "description": "$" }
    ],
    "non_monetary_rewards": "Winners receive certificates and access to exclusive mentorship sessions."
    }

    No explanation, formatting, or commentary.
    PROMPT;

    try {
        $response = Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 300,
                'temperature' => 0.6,
            ]);

        $text = $response->json()['choices'][0]['message']['content'] ?? '';
        $json = json_decode($text, true);

        if (
            is_array($json['prize_structure']) &&
            isset($json['non_monetary_rewards'])
        ) {
            return response()->json([
                'prize_structure' => array_slice($json['prize_structure'], 0, 5),
                'non_monetary_rewards' => trim($json['non_monetary_rewards']),
            ]);
        } else {
            \Log::warning('Step 9: Invalid AI structure', ['raw' => $text]);
            return response()->json([
                'prize_structure' => [],
                'non_monetary_rewards' => ''
            ]);
        }

    } catch (\Exception $e) {
        \Log::error('Step 9 AI generation failed', ['error' => $e->getMessage()]);
        return response()->json([
            'error' => 'AI request failed.'
        ], 500);
    }
}


public function suggestStep10(Request $request)
{
    $memory = Session::get('wizard.memory');

    if (!$memory || empty($memory['initial_input'])) {
        return response()->json([
            'error' => 'Wizard memory is missing or incomplete.'
        ], 400);
    }

    $idea = $memory['initial_input'];
    $context = $memory['context'] ?? '';

    $today = now()->toDateString();

    $prompt = <<<PROMPT
    You are building a timeline for a public innovation challenge.

    Given the idea below and assuming today's date is {$today}, suggest practical timeline dates:

    1. launch_date: 1–3 days from today
    2. submission_deadline: realistic based on project complexity (usually 3–6 weeks, but flexible)
    3. judging_window_start: 1–2 days after submission deadline
    4. judging_window_end: 5–10 days after judging starts
    5. announcement_date: 1–3 days after judging ends

    Output only this valid JSON format:

    {
    "launch_date": "YYYY-MM-DD",
    "submission_deadline": "YYYY-MM-DD",
    "judging_window_start": "YYYY-MM-DD",
    "judging_window_end": "YYYY-MM-DD",
    "announcement_date": "YYYY-MM-DD"
    }

    Challenge Idea: {$idea}
    Context: {$context}

    No explanation, markdown, or formatting.
    PROMPT;

    try {
        $response = Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 250,
                'temperature' => 0.6,
            ]);

        $text = $response->json()['choices'][0]['message']['content'] ?? '';
        $json = json_decode($text, true);

        if (
            isset($json['launch_date']) &&
            isset($json['submission_deadline']) &&
            isset($json['judging_window_start']) &&
            isset($json['judging_window_end']) &&
            isset($json['announcement_date'])
        ) {
            return response()->json([
                'launch_date' => $json['launch_date'],
                'submission_deadline' => $json['submission_deadline'],
                'judging_window_start' => $json['judging_window_start'],
                'judging_window_end' => $json['judging_window_end'],
                'announcement_date' => $json['announcement_date'],
            ]);
        } else {
            \Log::warning('Step 10: AI returned invalid timeline JSON', ['raw' => $text]);
            return response()->json([], 422);
        }

    } catch (\Exception $e) {
        \Log::error('Step 10 AI timeline generation failed', ['error' => $e->getMessage()]);
        return response()->json([
            'error' => 'AI request failed.'
        ], 500);
    }
}


public function suggestStep11(Request $request)
{
    $memory = Session::get('wizard.memory');

    if (!$memory || empty($memory['initial_input'])) {
        return response()->json([
            'error' => 'Wizard memory is missing or incomplete.'
        ], 400);
    }

    $idea = $memory['initial_input'];
    $context = $memory['context'] ?? '';

    $prompt = <<<PROMPT
    You are writing a basic code of conduct for a public innovation challenge.

    Based on the challenge idea and context, suggest a 3 to 5 paragraph code of conduct covering:

    - respectful collaboration
    - anti-harassment
    - inclusive behavior
    - expectations for professionalism

    Return plain text (no markdown, no headings, no formatting). This will be directly inserted into a textarea field.

    Challenge Idea: {$idea}
    Context: {$context}
    PROMPT;

    try {
        $response = Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 400,
                'temperature' => 0.5,
            ]);

        $text = $response->json()['choices'][0]['message']['content'] ?? '';

        return response()->json([
            'code_of_conduct' => trim($text),
        ]);

    } catch (\Exception $e) {
        \Log::error('Step 11 AI code of conduct generation failed', ['error' => $e->getMessage()]);
        return response()->json([
            'error' => 'AI request failed.'
        ], 500);
    }
}





public function suggestStep12(Request $request)
{
    // Retrieve the wizard memory from the session
    $memory = Session::get('wizard.memory');

    // Validate required base input
    if (!$memory || empty($memory['initial_input'])) {
        return response()->json([
            'error' => 'Wizard memory is missing or incomplete.'
        ], 400);
    }

    // Compose context using all relevant fields
    $contextParts = [];

    $map = [
        'language' => 'Language',
        'tone' => 'Tone',
        'short_description' => 'Short Description',
        'type' => 'Challenge Type',
        'tags' => 'Tags',
        'problem_statement' => 'Problem Statement',
        'why_it_matters' => 'Why It Matters',
        'objectives' => 'Objectives',
        'participants' => 'Participants',
        'skills_required' => 'Skills Required',
        'teams_allowed' => 'Teams Allowed',
        'languages' => 'Submission Language',
        'submission_format' => 'Submission Format',
        'required_docs' => 'Required Documents',
        'deliverables' => 'Deliverables',
        'evaluation_metrics' => 'Evaluation Metrics',
        'judging_method' => 'Judging Method',
        'prize_structure' => 'Prize Structure',
        'non_monetary_rewards' => 'Non-Monetary Rewards',
        'sponsor_info' => 'Sponsor Info',
        'launch_date' => 'Launch Date',
        'submission_deadline' => 'Submission Deadline',
        'judging_window_start' => 'Judging Window Start',
        'judging_window_end' => 'Judging Window End',
        'announcement_date' => 'Announcement Date',
        'submission_limit' => 'Submission Limit',
        'code_of_conduct' => 'Code of Conduct',
        'ip_terms' => 'IP Terms'
    ];

    foreach ($map as $key => $label) {
        if (!isset($memory[$key])) continue;

        $value = $memory[$key];

        if (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        } elseif (is_bool($value)) {
            $value = $value ? 'Yes' : 'No';
        }

        $contextParts[] = "{$label}: {$value}";
    }

    $context = implode("\n", $contextParts);
    $idea = $memory['initial_input'];

    // Final AI prompt
    $prompt = <<<PROMPT
    You are helping finalize a public innovation challenge. Based on the challenge idea and full context below, generate rich, detailed content for each of the following fields.

    ---

    🔹 Guidelines:
    - Return only a valid JSON object with all values as properly escaped strings
    - Use basic HTML formatting: `<h2>`, `<h3>`, `<p>`, `<ul>`, `<li>`, `<strong>`
    - NO markdown, code blocks, commentary, or content outside the JSON
    - Ensure full completion — close all braces and tags

    ---

    🧠 Key Requirements:

    - **review_challenge**:
    - At least 500 words
    - Include: background, relevance, real-world applications, expected impact, who benefits, and why the challenge exists
    - Structure with multiple paragraphs and subheadings
    - Use examples or analogies if relevant

    - **review_solution**:
    - At least 200 words
    - Detail expected solution types, technical guidelines, creative freedom, constraints, and submission expectations
    - Use bullet points or lists for clarity

    - Other fields (titles, submission, evaluation, etc.) can be concise but complete and well-formatted.

    ---

    📥 Challenge Idea:
    {$idea}

    📚 Context:
    {$context}

    ---

    🎯 Expected Output Format:

    {
    "titles": ["...", "...", "...", "...", "..."],
    "review_challenge": "<h2>...</h2><p>...</p>",
    "review_solution": "<h2>...</h2><p>...</p><ul><li>...</li></ul>",
    "review_submission": "...",
    "review_evaluation": "...",
    "review_participation": "...",
    "review_awards": "...",
    "review_deadline": "...",
    "review_resources": "..."
    }
    PROMPT;

    try {
        $response = Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 6000,
                'temperature' => 0.5,
            ]);

        $text = $response->json()['choices'][0]['message']['content'] ?? '';

        \Log::debug('Step 12 raw AI output:', ['text' => $text]);

        $json = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($json)) {
            \Log::error('JSON decode error', [
                'error' => json_last_error_msg(),
                'raw' => $text
            ]);

            return response()->json([
                'error' => 'Invalid AI response structure.',
                'raw' => $text
            ], 422);
        }

        return response()->json([
            'titles' => $json['titles'] ?? [],
            'review_challenge' => $json['review_challenge'] ?? '',
            'review_solution' => $json['review_solution'] ?? '',
            'review_submission' => $json['review_submission'] ?? '',
            'review_evaluation' => $json['review_evaluation'] ?? '',
            'review_participation' => $json['review_participation'] ?? '',
            'review_awards' => $json['review_awards'] ?? '',
            'review_deadline' => $json['review_deadline'] ?? '',
            'review_resources' => $json['review_resources'] ?? '',
        ]);

    } catch (\Exception $e) {
        \Log::error('Step 12 AI generation failed', ['error' => $e->getMessage()]);
        return response()->json([
            'error' => 'AI request failed.'
        ], 500);
    }
}


}