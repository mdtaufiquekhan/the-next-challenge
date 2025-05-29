<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();

            /* ========= BASIC INFO ========= */
            /*
            |--------------------------------------------------------------------------
            | FIELD: language
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Getting Started
            | data-group (UI):          Getting Started
            | data-label (UI):          Language

            | Field Type (DB):          string
            | UI Input Type:            Single select dropdown

            | Purpose:
            |   - Specifies the **language** used for all AI-generated content such as titles, summaries, prompts.
            |   - It **does NOT control UI localization** — only controls the content creation language.
            |
            | Usage in UI:
            |   - Step 1 in the challenge wizard.
            |   - <select id="language" data-label="Language" data-group="Getting Started">
            |   - Example Options: "English", "Hindi", "Spanish"
            |
            | AI Integration:
            |   - This value is passed to the AI system to ensure responses are returned in the selected language.
            |
            | Storage Format:
            |   - Stores the **full readable name** (e.g., "English"), not ISO codes like "en" or "hi".
            |
            | Default:
            |   - "English"
            |
            | Example Stored Values:
            |   - "English"
            |   - "Hindi"
            |   - "Spanish"
            |
            | Notes:
            |   - Do not use short codes here. If language codes are required later (e.g., for locale fallback), store them separately.
            */
            $table->string('language')->default('English');

            /*
            |--------------------------------------------------------------------------
            | FIELD: tone
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Tone
            | data-group (UI):          Tone
            | data-label (UI):          Tone

            | Field Type (DB):          string
            | UI Input Type:            Single select dropdown

            | Purpose:
            |   - Defines the **communication style or personality** for AI-generated content.
            |   - Influences the tone of:
            |       → Prompts
            |       → Instructions
            |       → Descriptions and helper text
            |
            | Usage in UI:
            |   - Step 2 in the challenge wizard.
            |   - <select id="tone" data-label="Tone" data-group="Tone">
            |
            | AI Integration:
            |   - AI adapts generated language to match selected tone:
            |       → `friendly`: conversational and approachable
            |       → `professional`: formal and clear
            |       → `minimal`: concise and neutral
            |
            | Storage Format:
            |   - String value from predefined list
            |   - Common values: `friendly`, `professional`, `minimal`
            |
            | Validation Rules (Suggested):
            |   - Required
            |   - Must be one of: `friendly`, `professional`, `minimal`
            |
            | Example Values:
            |   - "friendly"
            |   - "professional"
            |   - "minimal"
            |
            | Notes:
            |   - Default value is `'friendly'` if none is selected.
            |   - UI presents these as user-friendly labels in a dropdown menu.
            */
            $table->string('tone')->default('friendly');

            /*
            |--------------------------------------------------------------------------
            | FIELD: initial_input
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Idea
            | data-group (UI):          Idea
            | data-label (UI):          Idea

            | Field Type (DB):          text
            | UI Input Type:            Textarea

            | Purpose:
            |   - Captures the user's **raw idea or challenge concept** during onboarding.
            |   - Provides **context for the AI** to generate structured fields such as:
            |       → Problem Statement
            |       → Why It Matters
            |       → Objectives
            |       → Suggested Tags and Titles
            |
            | Usage in UI:
            |   - Step 3 in the challenge wizard.
            |   - <textarea id="initial_input" data-label="Idea" data-group="Idea">
            |
            | AI Integration:
            |   - Forms the **seed input** for downstream content generation.
            |   - Directly passed into the AI prompt templates as user context.
            |
            | Storage Format:
            |   - Long text (free-form)
            |   - May include sentences, lists, or short paragraphs.
            |
            | Validation Rules (Suggested):
            |   - Required (if challenge is not system-generated)
            |   - Minimum 10 characters to ensure meaningful input
            |
            | Example Value:
            |   - "I want to create a hackathon that helps solve urban waste management using IoT and AI."
            |
            | Notes:
            |   - Users may edit or revise this text, but the initial input is key to guiding the AI.
            |   - This is not shown on public-facing views; only used internally for generation logic.
            */
            $table->text('initial_input')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FIELD: type
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Basic Info
            | data-group (UI):          Basic Info
            | data-label (UI):          Type

            | Field Type (DB):          string
            | UI Input Type:            <select>

            | Purpose:
            |   - Specifies the **challenge category**, which determines structure, judging, and sometimes UI logic.
            |   - Drives backend workflows (e.g., ideation vs. development may have different evaluation criteria).

            | Usage in UI:
            |   - Step 4 of the wizard.
            |   - <select id="type" data-label="Type" data-group="Basic Info">

            | Example Values:
            |   - "Ideation"
            |   - "Design"
            |   - "Development"
            |   - "Data Science"

            | Validation Rules (Suggested):
            |   - Required
            |   - Must be one of the allowed categories (enum validation)

            | Notes:
            |   - Ensure the selected value maps to pre-defined categories used across UI and backend.
            |   - May influence how deliverables or metrics are structured later in the form.
            */
            $table->string('type');



            /*
            |--------------------------------------------------------------------------
            | FIELD: tags
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Basic Info
            | data-group (UI):          Basic Info
            | data-label (UI):          Tags

            | Field Type (DB):          json
            | UI Input Type:            <input type="text"> (multiselect enabled)

            | Purpose:
            |   - Holds an array of **searchable tags** related to the challenge.
            |   - Tags are **suggested by AI** based on `initial_input`, but users can edit them.
            |   - Used for **frontend filtering**, discovery, and SEO.

            | Usage in UI:
            |   - Step 4 of the wizard.
            |   - <input id="tags" data-label="Tags" data-group="Basic Info">

            | AI Integration:
            |   - Tags are inferred from initial_input (e.g., keywords like “climate,” “blockchain,” “education”).
            |   - Can also be reused by AI for content classification or personalization.

            | Storage Format:
            |   - JSON array, e.g., `["AI", "IoT", "Sustainability"]`

            | Validation Rules (Suggested):
            |   - Optional, but recommended max 10 tags.
            |   - Must be strings; disallow empty or duplicate values.

            | Example Value:
            |   - `["AI", "Climate Action", "Recycling", "Innovation"]`

            | Notes:
            |   - Input accepts comma-separated values which are converted to JSON via frontend logic.
            |   - Useful for admin dashboards and analytics tagging as well.
            */
            $table->json('tags')->nullable();




            /* ========= PROBLEM DEFINITION ========= */
            /*
            |--------------------------------------------------------------------------
            | FIELD: problem_statement
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Problem & Objectives
            | data-group (UI):          Problem & Objectives
            | data-label (UI):          Problem

            | Field Type (DB):          text
            | UI Input Type:            Multiline textarea

            | Purpose:
            |   - Captures the **core problem** the challenge aims to solve.
            |   - Acts as the foundation for defining objectives, deliverables, and evaluation metrics.

            | Usage in UI:
            |   - Step 5 in the challenge wizard.
            |   - <textarea id="problem_statement" data-label="Problem" data-group="Problem & Objectives">

            | AI Integration:
            |   - AI uses this to understand the **context and pain point** of the challenge.
            |   - This field may be auto-generated by AI based on the user's initial input (Step 3).
            |   - Users can **review/edit/override** AI suggestions.

            | Storage Format:
            |   - Freeform plain text (no length restriction).
            |   - Newlines and simple formatting supported (if desired).

            | Validation Rules (Suggested):
            |   - Required (if objectives or evaluation metrics are needed)
            |   - Should not be empty if `objectives` or `evaluation_metrics` is filled
            |   - Min length: ~20 characters recommended for meaningful context

            | Example Values:
            |   - "Access to clean water remains a challenge in remote rural areas."
            |   - "Small businesses struggle with customer retention due to lack of analytics."

            | Notes:
            |   - This is one of the most **important AI input fields**.
            |   - Keep it clear and concise, ideally 2–4 sentences.
            */
            $table->text('problem_statement')->nullable();


            /*
            |--------------------------------------------------------------------------
            | FIELD: why_it_matters
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Problem & Objectives
            | data-group (UI):          Problem & Objectives
            | data-label (UI):          Importance

            | Field Type (DB):          text
            | UI Input Type:            Multiline textarea

            | Purpose:
            |   - Provides **context and justification** for solving the problem.
            |   - Explains **why** this challenge is important or impactful.
            |   - Helps reviewers, participants, and sponsors understand the **real-world relevance**.

            | Usage in UI:
            |   - Step 5 in the challenge wizard.
            |   - <textarea id="why_it_matters" data-label="Importance" data-group="Problem & Objectives">

            | AI Integration:
            |   - AI uses this to enrich prompt generation with meaningful background or societal relevance.
            |   - May be auto-generated from the `initial_input` or `problem_statement`.
            |   - Users can **review, refine, or personalize** the output.

            | Storage Format:
            |   - Plain text (supports newlines, no HTML).

            | Validation Rules (Suggested):
            |   - Required if `problem_statement` is filled.
            |   - Should be at least 1–2 meaningful sentences (~20–40 characters min).

            | Example Values:
            |   - "Water scarcity affects over 40% of the world’s population."
            |   - "Improving urban mobility reduces emissions and improves quality of life."

            | Notes:
            |   - Focus on **impact, significance, or urgency** of the problem.
            |   - Works best when combined with clear objectives.
            */
            $table->text('why_it_matters')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FIELD: objectives
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Problem & Objectives
            | data-group (UI):          Problem & Objectives
            | data-label (UI):          Objectives

            | Field Type (DB):          text
            | UI Input Type:            Multiline textarea

            | Purpose:
            |   - Defines **what success looks like** for the challenge.
            |   - Helps set **clear expectations** for participants.
            |   - Used to shape evaluation criteria, judging logic, and deliverables.

            | Usage in UI:
            |   - Step 5 in the challenge wizard.
            |   - <textarea id="objectives" data-label="Objectives" data-group="Problem & Objectives">

            | AI Integration:
            |   - Based on `initial_input`, `problem_statement`, and `why_it_matters`.
            |   - AI uses this to **generate evaluation metrics**, rubrics, or review guidelines.
            |   - Users can edit, expand, or reword AI suggestions.

            | Storage Format:
            |   - Plain text (optional newline-separated bullets or paragraph).

            | Validation Rules (Suggested):
            |   - Required if `problem_statement` is filled.
            |   - Minimum length recommended: 1–2 complete goals (~30 characters).

            | Example Values:
            |   - "Develop a working prototype of a traffic optimization algorithm."
            |   - "Increase awareness of mental health through a community campaign."
            |   - "Deliver a business plan for sustainable water management."

            | Notes:
            |   - Try to be **specific, measurable, and relevant**.
            |   - Objectives may also influence prize design and challenge evaluation later.
            */
            $table->text('objectives')->nullable();





            /* ========= PARTICIPANT REQUIREMENTS ========= */
            /*
            |--------------------------------------------------------------------------
            | FIELD: participants
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Participants & Skills
            | data-group (UI):          Participants & Skills
            | data-label (UI):          Participants

            | Field Type (DB):          json (array)
            | UI Input Type:            Multi-select dropdown

            | Purpose:
            |   - Represents eligible participant groups who can join the challenge.
            |   - Enables **filtering**, **eligibility logic**, and **analytics**.

            | Usage in UI:
            |   - Step 6 in the challenge wizard.
            |   - <select id="participants" multiple data-label="Participants" data-group="Participants & Skills">

            | AI Integration:
            |   - Used contextually to **adjust language** or **provide recommendations** for team-building.

            | Storage Format:
            |   - JSON array of strings (e.g., ["Students", "Developers"])

            | Validation Rules (Suggested):
            |   - Must have at least one participant type selected

            | Example Values:
            |   - ["Students", "NGOs"]
            |   - ["Startups", "Researchers"]

            | Notes:
            |   - Default options: Students, Developers, NGOs, Startups
            |   - Admins can customize the list as needed
            */
            $table->json('participants')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FIELD: teams_allowed
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Participants & Skills
            | data-group (UI):          Participants & Skills
            | data-label (UI):          Teams Allowed

            | Field Type (DB):          boolean
            | UI Input Type:            Checkbox

            | Purpose:
            |   - Specifies whether **team submissions** are permitted.
            |   - Drives logic for **team composition**, **validation**, and **submission flow**.

            | Usage in UI:
            |   - Step 6 in the challenge wizard.
            |   - <input type="checkbox" id="teams_allowed" data-label="Teams Allowed">

            | AI Integration:
            |   - May adjust language for prompt generation ("your team" vs "you").

            | Storage Format:
            |   - Boolean (true/false)

            | Validation Rules (Suggested):
            |   - Default: true (teams allowed)

            | Example Values:
            |   - true  → Teams are allowed
            |   - false → Only individual submissions

            | Notes:
            |   - Used in team size validation and dashboard logic
            */
            $table->boolean('teams_allowed')->default(true);


            /*
            |--------------------------------------------------------------------------
            | FIELD: languages
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Participants & Skills
            | data-group (UI):          Participants & Skills
            | data-label (UI):          Languages

            | Field Type (DB):          json (array)
            | UI Input Type:            Single-select dropdown (TomSelect)

            | Purpose:
            |   - Specifies which **submission languages** are acceptable.
            |   - Supports **judging**, **translation**, or **diversity goals**.

            | Usage in UI:
            |   - Step 6 in the challenge wizard.
            |   - <select id="languages" data-label="Languages">

            | AI Integration:
            |   - May adapt submission instructions or evaluations if language mismatch is detected.

            | Storage Format:
            |   - JSON array of strings (e.g., ["English"])

            | Validation Rules (Suggested):
            |   - Required

            | Example Values:
            |   - ["English"]
            |   - ["Hindi", "Spanish"]

            | Notes:
            |   - This is **not** the same as UI language (which is in `language`)
            |   - Limit to predefined ISO-compliant languages
            */
            $table->json('languages')->nullable();


            /*
            |--------------------------------------------------------------------------
            | FIELD: skills_required
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Participants & Skills
            | data-group (UI):          Participants & Skills
            | data-label (UI):          Skills

            | Field Type (DB):          json (array)
            | UI Input Type:            Multi-select dropdown

            | Purpose:
            |   - Optional list of skills participants are expected to have.
            |   - Helps in **team matchmaking**, **targeted promotion**, or **recommendations**.

            | Usage in UI:
            |   - Step 6 in the challenge wizard.
            |   - <select id="skills_required" multiple data-label="Skills">

            | AI Integration:
            |   - May use skills to **tune deliverables**, evaluation metrics, or match team members.

            | Storage Format:
            |   - JSON array of strings (e.g., ["Python", "UX Design"])

            | Validation Rules (Suggested):
            |   - Optional
            |   - Validate against allowed skills list

            | Example Values:
            |   - ["Python", "Project Management"]
            |   - ["Data Analysis", "Figma"]

            | Notes:
            |   - Can be mapped against internal skill taxonomies or user profiles
            */
            $table->json('skills_required')->nullable();



            

            /* ========= SUBMISSION DETAILS ========= */
            /*
            |--------------------------------------------------------------------------
            | FIELD: deliverables
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Deliverables
            | data-group (UI):          Deliverables
            | data-label (UI):          Deliverables

            | Field Type (DB):          json (array)
            | UI Input Type:            Textarea (editable list or plain text, AI generated)

            | Purpose:
            |   - Represents the expected **output or submission content** from participants.
            |   - Can include files, reports, visualizations, code, documentation, etc.

            | Usage in UI:
            |   - Step 7 in the challenge wizard.
            |   - <textarea id="deliverables" data-label="Deliverables" data-group="Deliverables">

            | AI Integration:
            |   - Generated based on the challenge type, objectives, and problem statement.
            |   - User can **edit or customize** the AI-suggested deliverables list.

            | Storage Format:
            |   - JSON array of strings (e.g., ["PDF", "Source Code", "Presentation Slides"])

            | Validation Rules (Suggested):
            |   - Optional
            |   - Should be a list of meaningful terms (no HTML)

            | Example Values:
            |   - ["PDF report", "GitHub repo", "Slide deck"]
            |   - ["Video demo", "Codebase", "Documentation"]

            | Notes:
            |   - Can be used for submission review UI and evaluation checklists
            */
            $table->json('deliverables')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FIELD: required_docs
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Deliverables
            | data-group (UI):          Deliverables
            | data-label (UI):          Required Docs

            | Field Type (DB):          json (array)
            | UI Input Type:            Multi-select dropdown (TomSelect)

            | Purpose:
            |   - Specifies **mandatory documentation or support files** needed for submission.
            |   - Ensures participants include proper references, licenses, or summaries.

            | Usage in UI:
            |   - Step 7 in the challenge wizard.
            |   - <select id="required_docs" multiple data-label="Required Docs" data-group="Deliverables">

            | AI Integration:
            |   - Suggested based on challenge type, evaluation needs, or organizer prompts.

            | Storage Format:
            |   - JSON array of strings (e.g., ["README", "License", "Pitch Deck"])

            | Validation Rules (Suggested):
            |   - Optional
            |   - Should validate against allowed doc types

            | Example Values:
            |   - ["README", "Submission Guide", "Pitch Deck"]
            |   - ["License", "Video Demo"]

            | Notes:
            |   - Helps judges and reviewers validate entries effectively
            */
            $table->json('required_docs')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FIELD: submission_format
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Deliverables
            | data-group (UI):          Deliverables
            | data-label (UI):          Submission Format

            | Field Type (DB):          string
            | UI Input Type:            Single-select (via button group)

            | Purpose:
            |   - Specifies the **preferred file format** or platform for challenge submissions.
            |   - Common examples include ZIP archives, GitHub repositories, or cloud drive links.

            | Usage in UI:
            |   - Step 7 in the challenge wizard.
            |   - Rendered as a set of **buttons** (not a <select> element).
            |   - Example:
            |       <button data-format="ZIP" onclick="selectSubmissionFormat('ZIP')">ZIP</button>

            | AI Integration:
            |   - ❌ No AI involvement.
            |   - This value is entirely selected by the user.

            | Storage Format:
            |   - String (plain text)
            |   - Must match one of the supported formats (validated via backend rules)

            | Validation Rules (Suggested):
            |   - Required for live submissions.
            |   - Must be one of the following:
            |       → "ZIP"
            |       → "GitHub Link"
            |       → "Google Drive"
            |       → "PDF"

            | Example Values:
            |   - "ZIP"
            |   - "GitHub Link"
            |   - "Google Drive"

            | Notes:
            |   - This is not an upload field. It guides how participants submit externally hosted materials.
            |   - Backend may use this field to tailor validation or post-submission processing.
            */
            $table->string('submission_format')->nullable();


            /* ========= EVALUATION ========= */
            /*
            |--------------------------------------------------------------------------
            | FIELD: evaluation_metrics
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Evaluation
            | data-group (UI):          Evaluation
            | data-label (UI):          Evaluation Metrics

            | Field Type (DB):          json (array of objects)
            | UI Input Type:            Repeater-style list (label + weight fields for each)

            | Purpose:
            |   - Defines **how submissions are scored**, using one or more custom evaluation criteria.
            |   - Each item includes a `label` (e.g., "Innovation") and a `weight` (e.g., 40).

            | Usage in UI:
            |   - Step 8 of the challenge wizard.
            |   - Displayed as a dynamic list of inputs where each row has:
            |       → Criterion label (text input)
            |       → Weight (%) (number input)
            |   - Includes "Add Criterion" and "Remove" actions.

            | AI Integration:
            |   - Suggested automatically based on problem, objectives, and challenge type.
            |   - Users can customize or replace suggestions.

            | Storage Format:
            |   - JSON array (e.g., [{"label":"Innovation","weight":40},{"label":"Impact","weight":30}])

            | Validation Rules (Suggested):
            |   - Must be valid JSON (array of objects)
            |   - Each object must include:
            |       → label (non-empty string)
            |       → weight (integer 0–100)
            |   - Recommended: Sum of all weights = 100

            | Example Values:
            |   - [{"label":"Innovation","weight":40},{"label":"Feasibility","weight":30},{"label":"Impact","weight":30}]
            */
            $table->json('evaluation_metrics')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FIELD: judging_method
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Evaluation
            | data-group (UI):          Evaluation
            | data-label (UI):          Judging Method

            | Field Type (DB):          string
            | UI Input Type:            Button group (single-select)

            | Purpose:
            |   - Defines **how winners are selected**: by judges, community votes, or automation.
            |   - Impacts review workflows, judge assignments, and scoring logic.

            | Usage in UI:
            |   - Step 8 in the challenge wizard.
            |   - Rendered as a button group with these options:
            |       → "Manual Review"
            |       → "Community Voting"
            |       → "Automated"
            |   - Example:
            |       <button data-method="Manual Review" onclick="selectJudgingMethod('Manual Review')">Manual Review</button>

            | AI Integration:
            |   - ❌ No AI suggestion or involvement.
            |   - The user must explicitly select the method.

            | Storage Format:
            |   - Plain string representing the selected judging method.

            | Validation Rules (Suggested):
            |   - Required for final submissions.
            |   - Must be one of:
            |       → "Manual Review"
            |       → "Community Voting"
            |       → "Automated"

            | Example Values:
            |   - "Manual Review"
            |   - "Community Voting"
            |   - "Automated"

            | Notes:
            |   - This field drives the post-submission logic (e.g., assign judges or open voting).
            |   - Ensure backend logic is conditionally triggered based on this value.
            */
            $table->string('judging_method')->nullable();


            /* ========= PRIZES & SPONSORS ========= */
            /*
            |--------------------------------------------------------------------------
            | FIELD: prize_structure
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Prizes & Rewards
            | data-group (UI):          Prizes & Rewards
            | data-label (UI):          Prize Structure

            | Field Type (DB):          json
            | UI Input Type:            Repeater-style component (rank, amount, optional notes)

            | Purpose:
            |   - Captures the list of prize tiers for a challenge.
            |   - Each entry includes a rank (e.g., "1st", "2nd") and optionally an amount or item.
            |   - Supports both monetary and non-monetary rewards.

            | Usage in UI:
            |   - Step 9 in the challenge wizard.
            |   - Interactive list of rows with inputs for:
            |       → Rank (e.g., "1st")
            |       → Prize amount (number or text, e.g., "₹10,000")
            |       → Optional description or item (e.g., "Certificate + Internship")

            | AI Integration:
            |   - Suggests an initial prize structure based on prize pool or common patterns.
            |   - Can parse simple formats like "1st: ₹10K, 2nd: ₹5K" into structured JSON.
            |   - Users can fully customize or override.

            | Storage Format:
            |   - JSON array of objects:
            |     [
            |       { "rank": "1st", "amount": "₹10000" },
            |       { "rank": "2nd", "amount": "₹5000" },
            |       { "rank": "Special", "description": "Internship at XYZ" }
            |     ]

            | Validation Rules (Suggested):
            |   - Optional
            |   - Must be valid JSON
            |   - Each object should include:
            |       → rank (required)
            |       → amount or description (at least one)

            | Example Values:
            |   - [{"rank": "1st", "amount": "₹10000"}, {"rank": "2nd", "amount": "₹5000"}]
            |   - [{"rank": "Top 3", "description": "Mentorship + Certification"}]
            */
            $table->json('prize_structure')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FIELD: non_monetary_rewards
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Prizes & Rewards
            | data-group (UI):          Prizes & Rewards
            | data-label (UI):          Non-Monetary Rewards

            | Field Type (DB):          text
            | UI Input Type:            Textarea

            | Purpose:
            |   - Lists intangible benefits or recognitions given to participants.
            |   - Includes things like certificates, visibility, internships, swag, etc.

            | Usage in UI:
            |   - Step 9 in the challenge wizard.
            |   - <textarea id="non_monetary_rewards" data-label="Non-Monetary Rewards" data-group="Prizes & Rewards">

            | AI Integration:
            |   - May suggest popular examples based on challenge type (e.g., design → portfolios, dev → internships).

            | Storage Format:
            |   - Free-form text

            | Validation Rules (Suggested):
            |   - Optional
            |   - Min 10 chars if present

            | Example Values:
            |   - "Certificate of Participation, Internship Offer"
            |   - "Featured on Organizer’s Website"

            | Notes:
            |   - Important for motivation in non-cash challenges.
            */
            $table->text('non_monetary_rewards')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FIELD: sponsor_info
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Prizes & Rewards
            | data-group (UI):          Prizes & Rewards
            | data-label (UI):          Sponsor Info

            | Field Type (DB):          text
            | UI Input Type:            Textarea

            | Purpose:
            |   - Describes the organizations or individuals supporting the challenge.
            |   - Useful for branding, credibility, and visibility.

            | Usage in UI:
            |   - Step 9 in the challenge wizard.
            |   - <textarea id="sponsor_info" data-label="Sponsor Info" data-group="Prizes & Rewards">

            | AI Integration:
            |   - May auto-insert descriptions from sponsor profiles or links.

            | Storage Format:
            |   - Free-form text

            | Validation Rules (Suggested):
            |   - Optional
            |   - Allow links or structured sponsor mentions

            | Example Values:
            |   - "Sponsored by TechStars & Google Developers"
            |   - "In collaboration with UNICEF Innovation Lab"

            | Notes:
            |   - Displayed on public pages and certificates.
            */
            $table->text('sponsor_info')->nullable();


            /* ========= TIMELINE ========= */
            $table->date('launch_date')->nullable(); // Challenge launch date
            $table->date('submission_deadline')->nullable(); // Submission deadline
            $table->date('judging_window_start')->nullable(); // Judging phase start
            $table->date('judging_window_end')->nullable(); // Judging phase end
            $table->date('announcement_date')->nullable(); // Date for announcing winners

            /* ========= RULES & LEGAL ========= */
            /*
            |--------------------------------------------------------------------------
            | FIELD: submission_limit
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Rules & Legal
            | data-group (UI):          Rules & Legal
            | data-label (UI):          Submission Limit

            | Field Type (DB):          integer
            | UI Input Type:            Number input

            | Purpose:
            |   - Sets the **maximum number of submissions** a participant or team is allowed to make.
            |   - Helps manage fairness and server load.

            | Usage in UI:
            |   - Step 11 in the challenge wizard.
            |   - <input type="number" id="submission_limit" data-label="Submission Limit" data-group="Rules & Legal">

            | AI Integration:
            |   - Not generated by AI; defaults can be suggested based on challenge type.

            | Storage Format:
            |   - Integer (nullable, treated as unlimited if null)

            | Validation Rules (Suggested):
            |   - Optional
            |   - Must be a positive integer
            |   - Min: 1

            | Example Values:
            |   - 1 (one-shot submission)
            |   - 3 (iterative refinement allowed)

            | Notes:
            |   - Used to enable/disable UI logic for additional submissions.
            */
            $table->integer('submission_limit')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FIELD: code_of_conduct
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Rules & Legal
            | data-group (UI):          Rules & Legal
            | data-label (UI):          Code of Conduct

            | Field Type (DB):          text
            | UI Input Type:            Textarea

            | Purpose:
            |   - Provides expected behavior guidelines and consequences.
            |   - Can be a custom entry or a predefined template.

            | Usage in UI:
            |   - Step 11 in the challenge wizard.
            |   - <textarea id="code_of_conduct" data-label="Code of Conduct" data-group="Rules & Legal">

            | AI Integration:
            |   - May propose boilerplate content based on standard ethical guidelines.

            | Storage Format:
            |   - Long text

            | Validation Rules (Suggested):
            |   - Optional
            |   - Minimum length for custom entries (e.g., 100 characters)

            | Example Values:
            |   - "All participants must treat each other respectfully..."
            |   - "Follows the Contributor Covenant 2.0"

            | Notes:
            |   - Should ideally link to an external or downloadable file if very long.
            */
            $table->text('code_of_conduct')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FIELD: ip_terms
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Rules & Legal
            | data-group (UI):          Rules & Legal
            | data-label (UI):          IP Terms

            | Field Type (DB):          string
            | UI Input Type:            Select dropdown

            | Purpose:
            |   - Specifies **who retains rights** to the work submitted:
            |       → "retain" → participants keep ownership
            |       → "assign" → organizer owns IP
            |       → "shared" → both parties co-own

            | Usage in UI:
            |   - Step 11 in the challenge wizard.
            |   - <select id="ip_terms" data-label="IP Terms" data-group="Rules & Legal">

            | AI Integration:
            |   - Not auto-filled, but AI could explain the implications of each term.

            | Storage Format:
            |   - String: 'retain', 'assign', 'shared'

            | Validation Rules (Suggested):
            |   - Required
            |   - Must be one of ['retain', 'assign', 'shared']

            | Example Values:
            |   - "retain"
            |   - "assign"
            |   - "shared"

            | Notes:
            |   - Legal clarity here is essential for both participants and organizers.
            */
            $table->string('ip_terms')->nullable();







            /* ========= Review & Submit ========= */
            /*
            |--------------------------------------------------------------------------
            | FIELD: title
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Basic Info
            | data-group (UI):          Basic Info
            | data-label (UI):          Title

            | Field Type (DB):          string
            | UI Input Type:            Text input

            | Purpose:
            |   - Acts as the **main title** of the challenge.
            |   - Displayed prominently on:
            |       → Challenge listings
            |       → Detail/view pages
            |       → Submission and review flows
            |
            | Usage in UI:
            |   - Step 4 in the challenge wizard.
            |   - <input id="title" data-label="Title" data-group="Basic Info">
            |
            | AI Integration:
            |   - Used as a fixed input and output by AI; the AI may suggest a title based on earlier inputs (e.g., idea).
            |   - Users can edit or override AI-generated suggestions.
            |
            | Storage Format:
            |   - Plain text (max ~255 characters, enforced by Laravel string column).
            |
            | Validation Rules (Suggested):
            |   - Required
            |   - Max length: 255 characters
            |   - Should be unique across public challenges (if needed)
            |
            | Example Values:
            |   - "Sustainable City Design Challenge"
            |   - "AI for Accessibility Hackathon"
            |
            | Notes:
            |   - Keep this concise and descriptive.
            |   - Do not store rich formatting or HTML.
            */
            $table->string('title');





            // Cover image for the challenge (optional). Stored as file path or URL.
            // Used in Basic Info step for visual branding.
            $table->string('cover_image')->nullable()->after('title');


            /*
            |--------------------------------------------------------------------------
            | FIELD: review_challenge
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Final Review
            | data-group (UI):          Final Review
            | data-label (UI):          The Challenge

            | Field Type (DB):          text
            | UI Input Type:            Textarea

            | Purpose:
            |   - Contains a plain-language summary of the overall challenge.
            |   - This section is **AI-generated** based on earlier inputs (problem, objectives, etc.).
            |   - Helps users review and refine the final narrative before publishing.

            | Usage in UI:
            |   - Step 12 (Final Review) of the challenge wizard.
            |   - <textarea id="review_challenge" name="review_challenge" data-label="The Challenge" data-group="Final Review">

            | AI Integration:
            |   - ✅ Yes — generated using `initial_input`, `problem_statement`, and other key fields.
            |   - Users can edit or fully rewrite the AI content before saving.

            | Storage Format:
            |   - Long text (no HTML formatting stored)

            | Validation Rules (Suggested):
            |   - Optional (can be skipped in draft)
            |   - If present, recommended min length: ~30 characters

            | Example Value:
            |   - "This challenge invites developers to propose innovative, tech-driven solutions for reducing food waste in urban centers."

            | Notes:
            |   - Displayed as a formatted preview in the summary panel.
            |   - Intended to give a compelling top-level description for public viewing.
            */
            $table->text('review_challenge')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FIELD: review_solution
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Final Review
            | data-group (UI):          Final Review
            | data-label (UI):          Solution Requirements

            | Field Type (DB):          text
            | UI Input Type:            Textarea

            | Purpose:
            |   - Describes what an ideal solution should include or achieve.
            |   - Helps guide participants on scope, expectations, and deliverable standards.

            | Usage in UI:
            |   - Step 12 (Final Review) of the challenge wizard.
            |   - <textarea id="review_solution" name="review_solution" data-label="Solution Requirements" data-group="Final Review">

            | AI Integration:
            |   - ✅ Yes — generated from `objectives`, `skills_required`, and `deliverables`.
            |   - Users can refine this summary or replace it with their own version.

            | Storage Format:
            |   - Plain text (may include line breaks, no HTML formatting stored)

            | Validation Rules (Suggested):
            |   - Optional (encouraged if evaluation depends on clear solution guidelines)
            |   - If present, minimum recommended length ~30 characters

            | Example Value:
            |   - "Solutions should demonstrate real-world impact, include a prototype, and describe scalability and feasibility."

            | Notes:
            |   - Often referenced by judges during review.
            |   - Forms the basis for aligning submissions to evaluation metrics.
            */
            $table->text('review_solution')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FIELD: review_submission
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Final Review
            | data-group (UI):          Final Review
            | data-label (UI):          Your Submission

            | Field Type (DB):          text
            | UI Input Type:            Textarea

            | Purpose:
            |   - Provides a summary of what participants are expected to submit.
            |   - Clarifies the required components or structure of the final submission.

            | Usage in UI:
            |   - Step 12 (Final Review) of the challenge wizard.
            |   - <textarea id="review_submission" name="review_submission" data-label="Your Submission" data-group="Final Review">

            | AI Integration:
            |   - ✅ Yes — generated based on `deliverables`, `submission_format`, and `required_docs`.
            |   - Users can customize the wording, add notes, or rearrange expectations.

            | Storage Format:
            |   - Free-form text (supports line breaks; no HTML)

            | Validation Rules (Suggested):
            |   - Optional
            |   - Should reflect submitted formats and expectations clearly

            | Example Value:
            |   - "Your submission should include a PDF report, source code (GitHub link), a README, and a short demo video."

            | Notes:
            |   - Helps reduce confusion and improve submission quality.
            |   - May be included in challenge overview or confirmation screens.
            */
            $table->text('review_submission')->nullable();


            /*
            |--------------------------------------------------------------------------
            | FIELD: review_evaluation
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Final Review
            | data-group (UI):          Final Review
            | data-label (UI):          Evaluation Criteria

            | Field Type (DB):          text
            | UI Input Type:            Textarea

            | Purpose:
            |   - Provides a narrative summary of how submissions will be judged.
            |   - Complements the structured `evaluation_metrics` field with human-readable explanation.

            | Usage in UI:
            |   - Step 12 (Final Review) of the challenge wizard.
            |   - <textarea id="review_evaluation" name="review_evaluation" data-label="Evaluation Criteria" data-group="Final Review">

            | AI Integration:
            |   - ✅ Yes — auto-generated using `evaluation_metrics`, `objectives`, and `judging_method`.
            |   - Meant to assist participants in understanding what matters most.

            | Storage Format:
            |   - Free-form text (supports multiline; no HTML)

            | Validation Rules (Suggested):
            |   - Optional
            |   - Should be consistent with the metrics defined earlier

            | Example Value:
            |   - "Submissions will be scored based on innovation (40%), feasibility (30%), and impact (30%)."

            | Notes:
            |   - Can be shown during challenge onboarding and submission phases.
            |   - Reinforces fairness and transparency for participants.
            */
            $table->text('review_evaluation')->nullable();


            /*
            |--------------------------------------------------------------------------
            | FIELD: review_participation
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Final Review
            | data-group (UI):          Final Review
            | data-label (UI):          Participation Guidance

            | Field Type (DB):          text
            | UI Input Type:            Textarea

            | Purpose:
            |   - Offers specific guidance or expectations for participants.
            |   - Clarifies rules, etiquette, team composition, or participation boundaries.

            | Usage in UI:
            |   - Step 12 (Final Review) of the challenge wizard.
            |   - <textarea id="review_participation" name="review_participation" data-label="Participation Guidance" data-group="Final Review">

            | AI Integration:
            |   - ✅ Yes — AI may suggest helpful instructions based on `participants`, `teams_allowed`, and `code_of_conduct`.

            | Storage Format:
            |   - Free-form text (supports multiline; no HTML)

            | Validation Rules (Suggested):
            |   - Optional
            |   - Should be actionable and friendly

            | Example Value:
            |   - "Participants must register as individuals or teams of up to 4 members. Communication must be respectful at all times."

            | Notes:
            |   - This field can improve user confidence and reduce confusion before submission.
            */
            $table->text('review_participation')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FIELD: review_awards
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Final Review
            | data-group (UI):          Final Review
            | data-label (UI):          Award / Prize

            | Field Type (DB):          text
            | UI Input Type:            Textarea

            | Purpose:
            |   - Summarizes the rewards participants can expect, both monetary and non-monetary.
            |   - Provides a narrative description separate from the structured prize JSON.

            | Usage in UI:
            |   - Step 12 (Final Review) of the challenge wizard.
            |   - <textarea id="review_awards" name="review_awards" data-label="Award / Prize" data-group="Final Review">

            | AI Integration:
            |   - ✅ Yes — AI suggests this content based on `prize_structure`, `currency`, and `non_monetary_rewards`.

            | Storage Format:
            |   - Plain text (free-form description)

            | Validation Rules (Suggested):
            |   - Optional
            |   - Should align with actual configured prize tiers

            | Example Value:
            |   - "Winners will receive cash prizes, certificates, and a chance for internship interviews with sponsors."

            | Notes:
            |   - This is used for display in preview panels and final confirmation messages.
            |   - Complements the structured prize JSON with human-readable detail.
            */
            $table->text('review_awards')->nullable();


            /*
            |--------------------------------------------------------------------------
            | FIELD: review_deadline
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Final Review
            | data-group (UI):          Final Review
            | data-label (UI):          Submission Deadline

            | Field Type (DB):          text
            | UI Input Type:            Textarea

            | Purpose:
            |   - Offers a human-readable explanation or highlight of the submission deadline.
            |   - Reinforces urgency and clarity for participants during final review.

            | Usage in UI:
            |   - Step 12 (Final Review) of the challenge wizard.
            |   - <textarea id="review_deadline" name="review_deadline" data-label="Submission Deadline" data-group="Final Review">

            | AI Integration:
            |   - ✅ Yes — AI generates this summary based on the `submission_deadline` date and `launch_date`.

            | Storage Format:
            |   - Plain text (free-form)

            | Validation Rules (Suggested):
            |   - Optional
            |   - Keep concise and clear

            | Example Value:
            |   - "All entries must be submitted before midnight on July 15, 2024."

            | Notes:
            |   - Used for clarity and redundancy during challenge submission.
            |   - Helps reduce misunderstandings about exact deadline time.
            */
            $table->text('review_deadline')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FIELD: review_resources
            |--------------------------------------------------------------------------
            | Group Name (UI Step):     Final Review
            | data-group (UI):          Final Review
            | data-label (UI):          Supporting Resources

            | Field Type (DB):          text
            | UI Input Type:            Textarea

            | Purpose:
            |   - Lists additional materials, tools, links, or documents participants should consult.
            |   - Enhances clarity and support during challenge execution.

            | Usage in UI:
            |   - Step 12 (Final Review) of the challenge wizard.
            |   - <textarea id="review_resources" name="review_resources" data-label="Supporting Resources" data-group="Final Review">

            | AI Integration:
            |   - ✅ Yes — AI can suggest links, open datasets, tutorials, APIs, or other relevant guides based on the problem statement or challenge type.

            | Storage Format:
            |   - Plain text (free-form). Can include URLs or markdown-like formatting if needed.

            | Validation Rules (Suggested):
            |   - Optional
            |   - May be limited to ~1000 characters for clarity

            | Example Value:
            |   - "Use the UN SDG Open Data platform. Check the GitHub repo template and read the example evaluation rubric."

            | Notes:
            |   - Valuable for new participants who need direction or additional reference points.
            */
            $table->text('review_resources')->nullable();



            /* ========= SYSTEM ========= */
            $table->string('status')->default('draft'); // Status: draft or live
            $table->timestamps(); // Laravel default created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};