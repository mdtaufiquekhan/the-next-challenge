@extends('layouts.app')

@section('title', 'Create Challenge (Wizard)')

@section('content')

    <div class="main-body-section d-flex" id="challenge-container" role="main" aria-label="Challenge creation interface">
        <div id="onboarding-wizard"
            class="flex-grow-1 position-relative d-flex flex-column h-100"
            aria-live="polite"
            aria-atomic="true"
            role="region"
            aria-label="Step-by-step wizard for creating a challenge">

            <div id="left-nav-button"
                class="change-step-button change-step-button-left d-flex align-items-center justify-content-center position-absolute start-0 top-0 bottom-0"
                onclick="changeStep(-1)"
                role="button"
                tabindex="0"
                aria-label="Previous step">
                <span class="fs-4" aria-hidden="true">&#8592;</span>
            </div>

            <div class="main-form-container d-flex flex-column h-100">
               <form 
                    method="POST" 
                    action="{{ route('challenges.store') }}" 
                    enctype="multipart/form-data"
                    class="h-100 d-flex flex-column justify-content-between" 
                    aria-labelledby="wizard-title" 
                    role="form"
                >
                    @csrf

                    <div class="wizard-content flex-grow-1" role="group" aria-label="Challenge creation steps">

                        <!-- Step 1: Language -->
                        <div class="wizard-step step-1" role="tabpanel" aria-labelledby="step-label-1" id="step-panel-1" data-group="Getting Started">
                            <div class="text-center h-100 d-flex flex-column justify-content-center">
                                <h3 id="step-label-1">Select Your Language</h3>

                                <!-- Language buttons -->
                                <div class="btn-group mx-auto my-3" role="group" aria-label="Language selection">
                                    <button type="button" class="btn custom-select-btn" data-code="en" onclick="selectLanguage('en')">
                                        <img src="https://flagcdn.com/gb.svg" alt="English" style="width: 20px; margin-right: 0.5rem;"> English
                                    </button>
                                    {{-- <button type="button" class="btn custom-select-btn" data-code="hi" onclick="selectLanguage('hi')">
                                        <img src="https://flagcdn.com/in.svg" alt="Hindi" style="width: 20px; margin-right: 0.5rem;"> Hindi
                                    </button>
                                    <button type="button" class="btn custom-select-btn" data-code="bn" onclick="selectLanguage('bn')">
                                        <img src="https://flagcdn.com/bd.svg" alt="Bengali" style="width: 20px; margin-right: 0.5rem;"> Bengali
                                    </button>
                                    <button type="button" class="btn custom-select-btn" data-code="es" onclick="selectLanguage('es')">
                                        <img src="https://flagcdn.com/es.svg" alt="Spanish" style="width: 20px; margin-right: 0.5rem;"> Spanish
                                    </button>
                                    <button type="button" class="btn custom-select-btn" data-code="fr" onclick="selectLanguage('fr')">
                                        <img src="https://flagcdn.com/fr.svg" alt="French" style="width: 20px; margin-right: 0.5rem;"> French
                                    </button> --}}
                                </div>

                                <!-- Hidden input for language -->
                                <input type="hidden" id="language" name="language" data-label="Language" data-group="Getting Started">
                            </div>
                        </div>

                        <!-- Step 2: Tone -->
                        <div class="wizard-step step-2" role="tabpanel" aria-labelledby="step-label-2" id="step-panel-2" data-group="Tone">
                            <div class="text-center h-100 d-flex flex-column justify-content-center">
                                <h3 id="step-label-2">Choose Tone</h3>

                                <!-- Tone buttons -->
                                <div class="btn-group mx-auto my-3" role="group" aria-label="Tone selection">
                                    <button type="button"
                                            class="btn custom-select-btn"
                                            data-tone="friendly"
                                            onclick="selectTone('friendly')">
                                        😊 Friendly
                                    </button>
                                    <button type="button"
                                            class="btn custom-select-btn"
                                            data-tone="professional"
                                            onclick="selectTone('professional')">
                                        💼 Professional
                                    </button>
                                    <button type="button"
                                            class="btn custom-select-btn"
                                            data-tone="minimal"
                                            onclick="selectTone('minimal')">
                                        🧘 Minimal
                                    </button>
                                </div>

                                <!-- Hidden input for tone -->
                                <input type="hidden" id="tone" name="tone" data-label="Tone" data-group="Tone">

                            

                            </div>
                        </div>

                        <!-- Step 3: Initial Idea -->
                        <div class="wizard-step step-3" role="tabpanel" aria-labelledby="step-label-3" id="step-panel-3" data-group="Idea">
                            <div class="text-center h-100 d-flex flex-column justify-content-center">
                                <h3 id="step-label-3">What is your idea?</h3>

                                <label for="initial_input" class="form-label visually-hidden">Initial Idea</label>
                                <textarea class="form-control w-75 mx-auto"
                                        rows="4"
                                        id="initial_input"
                                        name="initial_input"
                                        placeholder="Describe your challenge..."
                                        oninput="updateSummary()"
                                        aria-required="true"
                                        data-label="Idea"
                                        data-group="Idea"></textarea>
                            </div>
                        </div>

                        <!-- Step 4: Basic Info -->
                        <div class="wizard-step step-4" role="tabpanel" aria-labelledby="step-label-4" id="step-panel-4" data-group="Basic Info">
                            <div class="container py-5">
                                <h3 id="step-label-4" class="text-center mb-3">Basic Challenge Info</h3>


                                <div class="mb-3 text-center">
                                    <label class="form-label d-block">Challenge Type</label>

                                    <!-- Button group -->
                                    <div class="btn-group" role="group" aria-label="Challenge Type Selection" id="challenge-type-buttons">
                                        
                                    </div>

                                    <!-- Hidden input to store selected value -->
                                    <input type="hidden"
                                        id="type"
                                        name="type"
                                        data-label="Type"
                                        data-group="Basic Info">

                                </div>

                                <div class="mb-3 text-center">
                                    <label class="form-label d-block">Tags</label>

                                    <!-- Tag button group -->
                                    <div class="btn-group flex-wrap d-flex justify-content-center gap-2" role="group" aria-label="Tag Selection" id="tag-buttons">

                                    </div>

                                    <!-- Hidden input for storing tags array -->
                                    <input type="hidden"
                                        id="tags"
                                        name="tags[]"
                                        data-label="Tags"
                                        data-group="Basic Info">
                                    
                                    <small class="d-block mt-2">
                                        <span class="info-icon" title="This helps categorize your challenge type.">i</span>
                                        Multiple tags can be selected to describe your challenge.
                                    </small>
                                </div>

                                

                            </div>
                        </div>

                        <!-- Step 5: Problem & Objectives -->
                        <div class="wizard-step step-5" role="tabpanel" aria-labelledby="step-label-5" id="step-panel-5" data-group="Problem & Objectives">
                            <div class="container py-5">
                                <h3 id="step-label-5" class="text-center mb-3">Problem & Objectives</h3>

                                <div class="mb-3">
                                    <label for="problem_statement" class="form-label">Core Problem</label>
                                    <textarea class="form-control"
                                            id="problem_statement"
                                            name="problem_statement"
                                            placeholder="Describe the core problem"
                                            aria-label="Problem Statement"
                                            data-group="Problem & Objectives"
                                            data-label="Problem Statement"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="why_it_matters" class="form-label">Why It Matters</label>
                                    <textarea class="form-control"
                                            id="why_it_matters"
                                            name="why_it_matters"
                                            placeholder="Why is this important?"
                                            aria-label="Why It Matters"
                                            data-group="Problem & Objectives"
                                            data-label="Why It Matters"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="objectives" class="form-label">Objectives / Success Criteria</label>
                                    <textarea class="form-control"
                                            id="objectives"
                                            name="objectives"
                                            placeholder="List your goals or success criteria"
                                            aria-label="Objectives"
                                            data-group="Problem & Objectives"
                                            data-label="Objectives"></textarea>
                                </div>
                            </div>
                        </div>



                        <!-- Step 6: Participants & Skills -->
                        <div class="wizard-step step-6" role="tabpanel" aria-labelledby="step-label-6" id="step-panel-6" data-group="Participants & Skills">
                            <div class="container py-5">
                                <h3 id="step-label-6" class="text-center mb-3">Participants & Skills</h3>

                                <!-- FIELD: participants (multi-select) -->
                                <div class="mb-3 text-center">
                                    <label class="form-label d-block">Target Participants</label>

                                    <div class="btn-group flex-wrap d-flex justify-content-center gap-2" role="group" aria-label="Participants" id="participant-buttons">
                                       
                                    </div>

                                    <small class="d-block mt-2">
                                        <span class="info-icon" title="This helps categorize your challenge type.">i</span>
                                        Multiple Target Participants can be selected to describe your challenge.
                                    </small>
                                    <input type="hidden"
                                        id="participants"
                                        name="participants"
                                        data-label="Participants"
                                        data-group="Participants & Skills">
                                        
                                </div>

                                <!-- FIELD: teams_allowed (single-toggle yes/no) -->
                                <div class="mb-3 text-center">
                                    <label class="form-label d-block">Allow Teams?</label>
                                    <div class="btn-group" role="group" aria-label="Allow Teams">
                                        <button type="button"
                                                class="btn custom-select-btn"
                                                data-team="yes"
                                                onclick="selectTeamsAllowed('yes')">
                                            Yes
                                        </button>
                                        <button type="button"
                                                class="btn custom-select-btn"
                                                data-team="no"
                                                onclick="selectTeamsAllowed('no')">
                                            No
                                        </button>
                                    </div>
                                    <input type="hidden"
                                        id="teams_allowed"
                                        name="teams_allowed"
                                        data-label="Teams Allowed"
                                        data-group="Participants & Skills">
                                </div>

                                <!-- FIELD: languages (single-select) -->
                                <div class="mb-3 text-center">
                                    <label class="form-label d-block">Submission Language</label>
                                    <div class="btn-group flex-wrap d-flex justify-content-center gap-2" role="group" aria-label="Languages" id="submission-language-buttons">
                                        <button type="button"
                                                class="btn custom-select-btn"
                                                data-lang="en"
                                                onclick="selectLanguageButton('en')">
                                            English
                                        </button>
                                        <button type="button"
                                                class="btn custom-select-btn"
                                                data-lang="es"
                                                onclick="selectLanguageButton('es')">
                                            Spanish
                                        </button>
                                        <button type="button"
                                                class="btn custom-select-btn"
                                                data-lang="hi"
                                                onclick="selectLanguageButton('hi')">
                                            Hindi
                                        </button>
                                        <button type="button"
                                                class="btn custom-select-btn"
                                                data-lang="fr"
                                                onclick="selectLanguageButton('fr')">
                                            French
                                        </button>
                                        <button type="button"
                                                class="btn custom-select-btn"
                                                data-lang="zh"
                                                onclick="selectLanguageButton('zh')">
                                            Chinese
                                        </button>
                                    </div>
                                    <input type="hidden"
                                        id="languages"
                                        name="languages"
                                        data-label="Submission Language"
                                        data-group="Participants & Skills">
                                </div>

                                <!-- FIELD: skills_required (multi-select) -->
                                <div class="mb-3 text-center">
                                    <label class="form-label d-block">Required Skills</label>
                                    <div class="btn-group flex-wrap d-flex justify-content-center gap-2" role="group" aria-label="Skills" id="skills-required-buttons">
                                       
                                    </div>
                                    <small class="d-block mt-2">
                                        <span class="info-icon" title="This helps categorize your challenge type.">i</span>
                                        Multiple Skills can be selected to describe your challenge.
                                    </small>
                                    <input type="hidden"
                                        id="skills_required"
                                        name="skills_required"
                                        data-label="Skills"
                                        data-group="Participants & Skills">
                                </div>
                            </div>
                        </div>




                    <!-- Step 7: Deliverables -->
                        <div class="wizard-step step-7" role="tabpanel" aria-labelledby="step-label-7" id="step-panel-7" data-group="Deliverables">
                            <div class="container py-5">
                                <h3 id="step-label-7" class="text-center mb-3">Deliverables</h3>

                                <!-- FIELD: submission_format (single-select buttons) -->
                                <div class="mb-3 text-center">
                                    <label class="form-label d-block">Preferred Submission Format</label>
                                    <div class="btn-group flex-wrap d-flex justify-content-center gap-2" role="group" aria-label="Submission Format" id="submission-format-buttons">
                                       
                                    </div>
                                    <input type="hidden"
                                        id="submission_format"
                                        name="submission_format"
                                        data-label="Submission Format"
                                        data-group="Deliverables">
                                </div>

                                <!-- FIELD: required_docs (multi-select buttons) -->
                                <div class="mb-3 text-center">
                                    <label class="form-label d-block">Required Documentation</label>
                                    <div class="btn-group flex-wrap d-flex justify-content-center gap-2" role="group" aria-label="Required Docs" id="required-docs-buttons">
                                        
                                    </div>
                                    <small class="d-block mt-2">
                                        <span class="info-icon" title="This helps categorize your challenge type.">i</span>
                                        Multiple Required Documentation Participants can be selected to describe your challenge.
                                    </small>
                                    <input type="hidden"
                                        id="required_docs"
                                        name="required_docs[]"
                                        data-label="Required Docs"
                                        data-group="Deliverables">
                                </div>

                                <!-- FIELD: deliverables -->
                                <div class="mb-3 text-center">
                                    <label class="form-label d-block">Expected Deliverables</label>

                                    <!-- Button Group for Deliverables -->
                                    <div id="deliverables-buttons"
                                        class="btn-group flex-wrap d-flex justify-content-center gap-2"
                                        role="group"
                                        aria-label="Deliverables">

                                       
                                    </div>

                                    <!-- Hidden input to store deliverables array as JSON -->
                                    <input type="hidden"
                                        id="deliverables"
                                        name="deliverables"
                                        data-label="Deliverables"
                                        data-group="Deliverables">
                                </div>
                            </div>
                        </div>




                        <!-- Step 8: Evaluation -->
                        <div class="wizard-step step-8" role="tabpanel" aria-labelledby="step-label-8" id="step-panel-8" data-group="Evaluation">
                            <div class="container py-5">
                                <h3 id="step-label-8" class="text-center mb-3">Evaluation Criteria</h3>

                                <!-- FIELD: evaluation_metrics -->
                                <!-- 
                                    FIELD: evaluation_metrics
                                    Group Name (UI Step):     Evaluation
                                    data-group (UI):          Evaluation
                                    data-label (UI):          Evaluation Metrics
                                    DB Field Type:            JSON
                                    UI Input Type:            Textarea (expects JSON array)

                                    Purpose:
                                    - Specifies how submissions are evaluated.
                                    - Each metric includes a label and a weight percentage.
                                    - AI may suggest default metrics based on problem/objectives.

                                    Notes:
                                    - Should be a valid JSON array.
                                    - Used during judging phase to ensure consistent scoring.
                                -->
                                <div class="mb-3" id="evaluation-metrics-wrapper">
                                    <label class="form-label">Evaluation Metrics <span>(Optional)</span></label>
                                    
                                    <div id="metrics-container">
                                        
                                    </div>

                                    {{-- <button type="button" class="btn custom-select-btn btn-sm" id="add-metric">+ Add Metric</button> --}}

                                    <!-- Hidden JSON field that will hold the final value -->
                                    <input type="hidden" id="evaluation_metrics" name="evaluation_metrics"
                                        aria-label="Evaluation Metrics"
                                        data-group="Evaluation"
                                        data-label="Evaluation Metrics" data-require="optional">
                                </div>

                                <!-- FIELD: judging_method -->
                                <!-- 
                                    FIELD: judging_method
                                    Group Name (UI Step):     Evaluation
                                    data-group (UI):          Evaluation
                                    data-label (UI):          Judging Method
                                    DB Field Type:            string
                                    UI Input Type:            Select

                                    Purpose:
                                    - Defines the mechanism for evaluating submissions.
                                    - Affects workflow automation and judge assignment logic.
                                    - Possible values: Manual Review, Community Voting, Automated

                                    Notes:
                                    - Required for configuring the review flow.
                                -->
                                <div class="mb-3 text-center">
                                    <label class="form-label d-block">Judging Method</label>
                                    <div class="btn-group flex-wrap d-flex justify-content-center gap-2" role="group" aria-label="Judging Method" id="judging-method-buttons">
                                        <button type="button"
                                                class="btn custom-select-btn"
                                                data-method="Manual Review"
                                                onclick="selectJudgingMethod('Manual Review')">
                                            Manual Review
                                        </button>
                                        <button type="button"
                                                class="btn custom-select-btn"
                                                data-method="Community Voting"
                                                onclick="selectJudgingMethod('Community Voting')">
                                            Community Voting
                                        </button>
                                        <button type="button"
                                                class="btn custom-select-btn"
                                                data-method="Automated"
                                                onclick="selectJudgingMethod('Automated')">
                                            Automated
                                        </button>
                                    </div>

                                    <input type="hidden"
                                        id="judging_method"
                                        name="judging_method"
                                        data-label="Judging Method"
                                        data-group="Evaluation">
                                </div>
                            </div>
                        </div>


                        <!-- Step 9: Prizes -->
                        <div class="wizard-step step-9" role="tabpanel" aria-labelledby="step-label-9" id="step-panel-9" data-group="Prizes & Rewards">
                            <div class="container py-5">
                                <h3 id="step-label-9" class="text-center mb-3">Prizes & Rewards</h3>

                                <!-- FIELD: prize_structure -->
                                <div class="mb-3" id="prize-structure-wrapper">
                                    <label class="form-label">Prize Structure</label>

                                    <div id="prize-entries-container">
                                        <!-- Prize row example -->
                                        <div class="row g-2 mb-2 prize-entry">
                                         
                                        </div>
                                    </div>

                                    {{-- <button type="button" class="btn custom-select-btn btn-sm" id="add-prize">+ Add Prize</button> --}}

                                    <!-- Hidden JSON field for backend submission -->
                                    <input type="hidden"
                                        id="prize_structure"
                                        name="prize_structure"
                                        aria-label="Prize Structure"
                                        data-group="Prizes & Rewards"
                                        data-label="Prize Structure">
                                </div>

                                <!-- FIELD: non_monetary_rewards -->
                                <div class="mb-3">
                                    <label for="non_monetary_rewards" class="form-label">Non-Monetary Rewards(Optional)</label>
                                    <textarea class="form-control"
                                            id="non_monetary_rewards"
                                            name="non_monetary_rewards"
                                            data-require="optional"
                                            placeholder="e.g. Certificates, Internships"
                                            aria-label="Non-monetary Rewards"
                                            data-group="Prizes & Rewards"
                                            data-label="Non-Monetary Rewards"></textarea>
                                </div>

                                <!-- FIELD: sponsor_info -->
                                <div class="mb-3">
                                    <label for="sponsor_info" class="form-label">Sponsor Information (Optional)</label>
                                    <textarea class="form-control"
                                            id="sponsor_info"
                                            name="sponsor_info"
                                            data-require="optional"
                                            placeholder="Sponsor Details"
                                            aria-label="Sponsor Info"
                                            data-group="Prizes & Rewards"
                                            data-require="optional"
                                            data-label="Sponsor Info"></textarea>
                                </div>
                            </div>
                        </div>



                        <!-- Step 10: Timeline -->
                        <div class="wizard-step step-10" role="tabpanel" aria-labelledby="step-label-10" id="step-panel-10" data-group="Timeline">
                            <div class="container py-5">
                                <h3 id="step-label-10" class="text-center mb-3">Timeline</h3>

                                <!-- FIELD: launch_date -->
                                <div class="mb-3">
                                    <label for="launch_date" class="form-label">Challenge Launch Date</label>
                                    <input type="date"
                                        class="form-control"
                                        id="launch_date"
                                        name="launch_date"
                                        aria-label="Challenge Launch Date"
                                        data-group="Timeline"
                                        data-label="Challenge Launch Date"
                                        value="">
                                </div>

                                <!-- FIELD: submission_deadline -->
                                <div class="mb-3">
                                    <label for="submission_deadline" class="form-label">Submission Deadline</label>
                                    <input type="date"
                                        class="form-control"
                                        id="submission_deadline"
                                        name="submission_deadline"
                                        aria-label="Submission Deadline"
                                        data-group="Timeline"
                                        data-label="Submission Deadline"
                                        value="">
                                </div>

                                <!-- FIELD: judging_window_start -->
                                <div class="mb-3">
                                    <label for="judging_window_start" class="form-label">Judging Window Start (Optional)</label>
                                    <input type="date"
                                        class="form-control"
                                        id="judging_window_start"
                                        name="judging_window_start"
                                        data-require="optional"
                                        aria-label="Judging Window Start"
                                        data-group="Timeline"
                                        data-label="Judging Window Start"
                                        value="">
                                </div>

                                <!-- FIELD: judging_window_end -->
                                <div class="mb-3">
                                    <label for="judging_window_end" class="form-label">Judging Window End (Optional)</label>
                                    <input type="date"
                                        class="form-control"
                                        id="judging_window_end"
                                        name="judging_window_end"
                                        data-require="optional"
                                        aria-label="Judging Window End"
                                        data-group="Timeline"
                                        data-label="Judging Window End"
                                        value="">
                                </div>

                                <!-- FIELD: announcement_date -->
                                <div class="mb-3">
                                    <label for="announcement_date" class="form-label">Winners Announcement Date</label>
                                    <input type="date"
                                        class="form-control"
                                        id="announcement_date"
                                        name="announcement_date"
                                        aria-label="Winners Announcement Date"
                                        data-group="Timeline"
                                        data-label="Winners Announcement Date"
                                        value="">
                                </div>
                            </div>
                        </div>



                        <!-- Step 11: Rules -->
                        <div class="wizard-step step-11" role="tabpanel" aria-labelledby="step-label-11" id="step-panel-11" data-group="Rules & Legal">
                            <div class="container py-5">
                                <h3 id="step-label-11" class="text-center mb-3">Rules & Legal</h3>

                                <!-- FIELD: submission_limit -->
                                <div class="mb-3">
                                    <label for="submission_limit" class="form-label">Submission Limit</label>
                                    <input type="number"
                                        class="form-control"
                                        id="submission_limit"
                                        name="submission_limit"
                                        placeholder="Maximum number of entries per participant"
                                        aria-label="Submission Limit"
                                        data-group="Rules & Legal"
                                        data-label="Submission Limit">
                                </div>

                                <!-- FIELD: code_of_conduct -->
                                <div class="mb-3">
                                    <label for="code_of_conduct" class="form-label">Code of Conduct</label>
                                    <textarea class="form-control"
                                            id="code_of_conduct"
                                            name="code_of_conduct"
                                            placeholder="Outline behavior expectations and consequences"
                                            aria-label="Code of Conduct"
                                            data-group="Rules & Legal"
                                            data-label="Code of Conduct"></textarea>
                                </div>

                                <!-- FIELD: ip_terms (single-select buttons) -->
                                <div class="mb-3 text-center">
                                    <label class="form-label d-block">Intellectual Property Terms</label>
                                    <div class="btn-group flex-wrap d-flex justify-content-center gap-2" role="group" aria-label="IP Terms" id="ip-terms-buttons">
                                        <button type="button"
                                                class="btn custom-select-btn"
                                                data-ip="retain"
                                                onclick="selectIPTerms('retain')">
                                            Participants retain rights
                                        </button>
                                        <button type="button"
                                                class="btn custom-select-btn"
                                                data-ip="assign"
                                                onclick="selectIPTerms('assign')">
                                            Organizer owns IP
                                        </button>
                                        <button type="button"
                                                class="btn custom-select-btn"
                                                data-ip="shared"
                                                onclick="selectIPTerms('shared')">
                                            Co-ownership
                                        </button>
                                    </div>

                                    <input type="hidden"
                                        id="ip_terms"
                                        name="ip_terms"
                                        data-label="IP Terms"
                                        data-group="Rules & Legal">
                                </div>
                            </div>
                        </div>




                        <!-- Step 12: Final Review -->
                        <div class="wizard-step step-12" role="tabpanel" aria-labelledby="step-label-12" id="step-panel-12" data-group="Final Review">
                            <div class="container py-5">
                                <h3 id="step-label-12" class="mb-3 text-center">Review & Submit</h3>
                                <p class="mb-4 text-center">You're all set. Fill in any final notes and submit the challenge or save it as a draft for later.</p>

                                <div class="mb-3">
                                    <label for="title" class="form-label">Challenge Title</label>
                                    <input type="text"
                                        class="form-control"
                                        id="title"
                                        name="title"
                                        placeholder="Enter the title of your challenge"
                                        aria-label="Challenge Title"
                                        data-group="Final Review"
                                        data-label="Title"
                                        value="">

                                    <!-- AI Title Suggestions -->
                                    <div id="title-suggestions" class="mt-2">
                                        <label class="form-label d-block">AI Suggestions:</label>
                                        <div id="suggestion-list" class="d-flex flex-wrap gap-2">
                                            <!-- JS will inject <button> tags here -->
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="mb-3">
                                    <label for="cover_image" class="form-label">Challenge Cover Image</label>
                                    <input type="file"
                                        class="form-control"
                                        id="cover_image"
                                        name="cover_image"
                                        accept="image/*"
                                        data-group="Final Review"
                                        data-label="Cover Image"
                                        aria-label="Challenge Cover Image">

                                    <small class="d d-block mb-2">Recommended size: 1200x600px</small>

                                    <!-- Image Preview Panel -->
                                    <div id="cover-image-preview" class="border rounded p-2" style="max-width: 100%; display: none;">
                                        <p class="fw-semibold mb-1">Preview:</p>
                                        <img src="#" id="cover-image-output" alt="Image Preview" class="img-fluid rounded shadow-sm" style="max-height: 300px;">
                                    </div>
                                </div> --}}

                                <!-- Custom Final Review Fields -->
                                <div class="mb-3">
                                    <label for="review_challenge" class="form-label">🧩 The Challenge</label>
                                    <textarea class="form-control" id="review_challenge" name="review_challenge" rows="3" data-group="Final Review" data-label="The Challenge"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="review_solution" class="form-label">💡 Solution Requirements</label>
                                    <textarea class="form-control" id="review_solution" name="review_solution" rows="3" data-group="Final Review" data-label="Solution Requirements"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="review_submission" class="form-label">📤 Your Submission</label>
                                    <textarea class="form-control" id="review_submission" name="review_submission" rows="3" data-group="Final Review" data-label="Your Submission"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="review_evaluation" class="form-label">📊 Evaluation Criteria</label>
                                    <textarea class="form-control" id="review_evaluation" name="review_evaluation" rows="3" data-group="Final Review" data-label="Evaluation Criteria"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="review_participation" class="form-label">👥 Participation Guidance</label>
                                    <textarea class="form-control" id="review_participation" name="review_participation" rows="3" data-group="Final Review" data-label="Participation Guidance"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="review_awards" class="form-label">🏆 Award / Prize</label>
                                    <textarea class="form-control" id="review_awards" name="review_awards" rows="3" data-group="Final Review" data-label="Award / Prize"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="review_deadline" class="form-label">⏰ Submission Deadline</label>
                                    <textarea class="form-control" id="review_deadline" name="review_deadline" rows="3" data-group="Final Review" data-label="Submission Deadline"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="review_resources" class="form-label">📚 Supporting Resources</label>
                                    <textarea class="form-control" id="review_resources" name="review_resources" rows="3" data-group="Final Review" data-label="Supporting Resources"></textarea>
                                </div>

                                <!-- Final Buttons -->
                                <div class="d-flex justify-content-center gap-3 mt-4">
                                    <button type="submit" class="btn btn-primary" id="submit-challenge" aria-label="Submit Challenge">
                                        Submit Challenge
                                    </button>
                                   
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            



            <div id="right-nav-button"
                class="change-step-button change-step-button-right d-flex align-items-center justify-content-center position-absolute end-0 top-0 bottom-0"
                onclick="changeStep(1)"
                role="button"
                tabindex="0"
                aria-label="Next step">
                <span class="fs-4" aria-hidden="true">&#8594;</span>
            </div>
        </div>

        <div id="dragbar"
            role="separator"
            aria-orientation="vertical"
            tabindex="0"
            aria-label="Resize panel divider">
        </div>

        <div id="summary-panel-wrapper"
            style="width: 400px;"
            role="complementary"
            aria-label="AI Assistant Tips and Summary Panel">
            <div id="summary-panel" class="d-flex flex-column h-100">
                <div class="panel-header fw-bold" id="summary-title">Tips</div>
                <div class="panel-body overflow-auto" role="region" aria-labelledby="summary-title" aria-live="polite">
                <div id="panel-content">
                    <ul class="list-unstyled small">
                    <li>✅ Start by choosing your preferred language.</li>
                    <li>💬 Then pick a tone that fits your challenge style.</li>
                    <li>📝 Finally, describe your idea or problem to solve.</li>
                    </ul>
                </div>
                </div>
            </div>
        </div>

</div>

<!-- Wizard Footer Step Indicator -->
<div class="wizard-footer-placeholder"></div>


@endsection

@section('footer-content')



    {{-- <script>
        // 📊 Evaluation Metrics JSON Sync
        // Dynamically builds evaluation criteria rows.
        // Syncs label + weight inputs into a hidden JSON field for backend submission.
        document.addEventListener('DOMContentLoaded', function () {
            const metricsContainer = document.getElementById('metrics-container');
            const addBtn = document.getElementById('add-metric');
            const hiddenInput = document.getElementById('evaluation_metrics');

            function updateJSON() {
                // Sync input fields to hidden JSON for backend submission
                const metrics = [];
                metricsContainer.querySelectorAll('.metric-item').forEach(row => {
                    const label = row.querySelector('.metric-label')?.value.trim();
                    const weight = row.querySelector('.metric-weight')?.value;
                    if (label && weight) {
                        metrics.push({ label, weight: parseInt(weight) });
                    }
                });
                hiddenInput.value = JSON.stringify(metrics);
            }

            function addMetricRow(label = '', weight = '') {
                const row = document.createElement('div');
                row.className = 'row g-2 mb-2 metric-item';
                row.innerHTML = `
                    <div class="col-md-6">
                        <input type="text" class="form-control metric-label" placeholder="Metric Label" value="${label}">
                    </div>
                    <div class="col-md-4">
                        <input type="number" class="form-control metric-weight" placeholder="Weight (%)" min="0" max="100" value="${weight}">
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove-metric" title="Remove">&times;</button>
                    </div>
                `;
                metricsContainer.appendChild(row);
                row.querySelectorAll('input').forEach(input => {
                    input.addEventListener('input', updateJSON);
                });
                row.querySelector('.remove-metric').addEventListener('click', () => {
                    row.remove();
                    updateJSON();
                });
                updateJSON();
            }

            addBtn.addEventListener('click', () => addMetricRow());

            // Preload existing JSON data if any
            const initialMetrics = hiddenInput.value;
            if (initialMetrics) {
                try {
                    JSON.parse(initialMetrics).forEach(metric => {
                        addMetricRow(metric.label, metric.weight);
                    });
                } catch (e) {
                    console.warn("Invalid initial metrics JSON.");
                }
            } else {
                addMetricRow(); // Start with one row
            }
        });
    </script> --}}

    {{-- <script>
        // 🏆 Prize Structure JSON Sync
        // Lets users add/remove prize rows (rank + description).
        // Stores final structure as JSON in a hidden input.

        document.addEventListener('DOMContentLoaded', function () {
            const prizeContainer = document.getElementById('prize-entries-container');
            const addPrizeBtn = document.getElementById('add-prize');
            const hiddenPrizeInput = document.getElementById('prize_structure');

            function updatePrizeJSON() {
                // Collect prize rows and serialize them to JSON
                const prizes = [];
                prizeContainer.querySelectorAll('.prize-entry').forEach(row => {
                    const rank = row.querySelector('.prize-rank')?.value.trim();
                    const description = row.querySelector('.prize-description')?.value.trim();
                    if (rank && description) {
                        prizes.push({ rank, description });
                    }
                });
                hiddenPrizeInput.value = JSON.stringify(prizes);
            }

            function addPrizeRow(rank = '', description = '') {
                const row = document.createElement('div');
                row.className = 'row g-2 mb-2 prize-entry';
                row.innerHTML = `
                    <div class="col-md-4">
                        <input type="text" class="form-control prize-rank" placeholder="Rank (e.g. 1st, 2nd)" value="${rank}">
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control prize-description" placeholder="Prize (e.g. ₹10K, Certificate)" value="${description}">
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove-prize" title="Remove">&times;</button>
                    </div>
                `;
                prizeContainer.appendChild(row);

                row.querySelectorAll('input').forEach(input => input.addEventListener('input', updatePrizeJSON));
                row.querySelector('.remove-prize').addEventListener('click', () => {
                    row.remove();
                    updatePrizeJSON();
                });

                updatePrizeJSON();
            }

            addPrizeBtn.addEventListener('click', () => addPrizeRow());

            // Load any existing prize JSON
            const initialData = hiddenPrizeInput.value;
            if (initialData) {
                try {
                    JSON.parse(initialData).forEach(item => addPrizeRow(item.rank, item.description));
                } catch (e) {
                    console.warn("Invalid prize structure JSON.");
                }
            } else {
                addPrizeRow(); // Start with one default row
            }
        });
    </script> --}}

    {{-- <script>
    // 🖼️ Cover Image Upload with Preview
    // Displays a live image preview when a user selects a challenge cover image file.
    // Hides the preview if the file is not an image.
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('cover_image');
        const previewWrapper = document.getElementById('cover-image-preview');
        const previewImg = document.getElementById('cover-image-output');

        // Live preview of uploaded image
        input.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    previewWrapper.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewWrapper.style.display = 'none';
                previewImg.src = '#';
            }
        });
    });
    </script> --}}


{{-- <script>
document.addEventListener('DOMContentLoaded', function () {
  tinymce.init({
    selector: '#review_challenge', // only this field
    menubar: false,
    height: 300,
    plugins: 'link lists code',
    toolbar: 'undo redo | bold italic | bullist numlist | link | code'
  });
});
</script> --}}
@endsection


