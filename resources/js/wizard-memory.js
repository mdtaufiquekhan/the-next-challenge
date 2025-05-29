//wizard-memory.js

document.addEventListener('DOMContentLoaded', () => {
    let lastTriggeredStep = null;

    const observer = new MutationObserver(() => {
        const currentStep = window.currentStep;
        if (!currentStep || currentStep === lastTriggeredStep) return;

        if ([4, 5, 6, 7, 8, 9, 10, 11, 12].includes(currentStep)) {
            lastTriggeredStep = currentStep;

            // Core required memory base
            const payload = {
                language: document.getElementById('language')?.value,
                tone: document.getElementById('tone')?.value,
                initial_input: document.getElementById('initial_input')?.value,
                current_step: currentStep,
            };

            if (!payload.language || !payload.tone || !payload.initial_input) {
                console.warn('⚠️ Skipping memory update — required fields missing.');
                return;
            }

            // 🧩 Step 5 stores Step 4 fields
            if (currentStep === 5) {
                const type = document.getElementById('type')?.value;
                const tags = document.getElementById('tags')?.value;

                if (type) payload.type = type;
                if (tags) {
                    try {
                        payload.tags = JSON.parse(tags);
                    } catch {
                        console.warn('⚠️ Invalid tags JSON, skipping.');
                    }
                }
            }

            // 🧠 Step 6 stores Step 5 fields
            if (currentStep === 6) {
                const problem = document.getElementById('problem_statement')?.value;
                const why = document.getElementById('why_it_matters')?.value;
                const objectives = document.getElementById('objectives')?.value;

                if (problem) payload.problem_statement = problem;
                if (why) payload.why_it_matters = why;
                if (objectives) payload.objectives = objectives;
            }

            if (currentStep === 7) {
                const participants = document.getElementById('participants')?.value;
                const skills = document.getElementById('skills_required')?.value;
                const teamsAllowed = document.getElementById('teams_allowed')?.value;
                const languages = document.getElementById('languages')?.value;

                if (participants) {
                    try {
                        payload.participants = JSON.parse(participants);
                    } catch {
                        console.warn('⚠️ Invalid participants JSON, skipping.');
                    }
                }

                if (skills) {
                    try {
                        payload.skills_required = JSON.parse(skills);
                    } catch {
                        console.warn('⚠️ Invalid skills_required JSON, skipping.');
                    }
                }

                if (teamsAllowed !== undefined) payload.teams_allowed = teamsAllowed;
                if (languages) payload.languages = languages;
            }


            // 🧪 Step 8 stores Step 7 fields
            if (currentStep === 8) {
                const submissionFormat = document.getElementById('submission_format')?.value;
                const requiredDocs = document.getElementById('required_docs')?.value;
                const deliverables = document.getElementById('deliverables')?.value;

                if (submissionFormat) payload.submission_format = submissionFormat;

                if (requiredDocs) {
                    try {
                        payload.required_docs = JSON.parse(requiredDocs);
                    } catch {
                        console.warn('⚠️ Invalid required_docs JSON, skipping.');
                    }
                }

                if (deliverables) {
                    try {
                        payload.deliverables = JSON.parse(deliverables);
                    } catch {
                        console.warn('⚠️ Invalid deliverables JSON, skipping.');
                    }
                }
            }


            // 🧑‍⚖️ Step 9 stores Step 8 fields
            if (currentStep === 9) {
                const metrics = document.getElementById('evaluation_metrics')?.value;
                const judgingMethod = document.getElementById('judging_method')?.value;

                if (metrics) {
                    try {
                        payload.evaluation_metrics = JSON.parse(metrics);
                    } catch {
                        console.warn('⚠️ Invalid evaluation_metrics JSON, skipping.');
                    }
                }

                if (judgingMethod) payload.judging_method = judgingMethod;
            }

            // 🏆 Step 10 stores Step 9 fields
            if (currentStep === 10) {
                const prizeStructure = document.getElementById('prize_structure')?.value;
                const currency = document.getElementById('currency')?.value;
                const rewards = document.getElementById('non_monetary_rewards')?.value;
                const sponsorInfo = document.getElementById('sponsor_info')?.value;

                if (prizeStructure) {
                    try {
                        payload.prize_structure = JSON.parse(prizeStructure);
                    } catch {
                        console.warn('⚠️ Invalid prize_structure JSON, skipping.');
                    }
                }

                if (currency) payload.currency = currency;
                if (rewards) payload.non_monetary_rewards = rewards;
                if (sponsorInfo) payload.sponsor_info = sponsorInfo;
            }

            // 🗓️ Step 11 stores Step 10 fields
            if (currentStep === 11) {
                const launchDate = document.getElementById('launch_date')?.value;
                const deadline = document.getElementById('submission_deadline')?.value;
                const judgingStart = document.getElementById('judging_window_start')?.value;
                const judgingEnd = document.getElementById('judging_window_end')?.value;
                const announcement = document.getElementById('announcement_date')?.value;

                if (launchDate) payload.launch_date = launchDate;
                if (deadline) payload.submission_deadline = deadline;
                if (judgingStart) payload.judging_window_start = judgingStart;
                if (judgingEnd) payload.judging_window_end = judgingEnd;
                if (announcement) payload.announcement_date = announcement;
            }

            // 📜 Step 12 stores Step 11 fields
            if (currentStep === 12) {
                const payload = {
                    submission_limit: parseInt(document.getElementById('submission_limit')?.value || 0),
                    code_of_conduct: document.getElementById('code_of_conduct')?.value,
                    ip_terms: document.getElementById('ip_terms')?.value,

                    // Preserve essentials from earlier steps
                    language: window.memory?.language || document.getElementById('language')?.value,
                    tone: window.memory?.tone || document.getElementById('tone')?.value,
                    initial_input: window.memory?.initial_input || document.getElementById('initial_input')?.value,
                };

                payload.current_step = currentStep;

                fetch('/wizard/store-memory', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(payload)
                });
            }



            fetch('/wizard/store-memory', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                console.log(`✅ Step ${currentStep} memory stored`);
                console.log('🧠 Updated memory:', data.memory);
            })
            .catch(err => {
                console.error('❌ Memory store failed:', err);
            });
        }
    });

    observer.observe(document.body, {
        attributes: true,
        childList: true,
        subtree: true
    });
});
