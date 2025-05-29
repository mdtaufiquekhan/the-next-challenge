//wizard-field-behaviors.js

    // ========================================
    // 🌐 Language Selection (Step 1)
    // ========================================
    function selectLanguage(code) {
        const input = document.getElementById('language');
        if (!input) return;

        input.value = code;

        document.querySelectorAll('[onclick^="selectLanguage"]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.code === code);
        });

        updateSummary?.();
        changeStep(1); // auto-advance to tone
    }

    // ========================================
    // 🗣 Tone Selection (Step 2)
    // ========================================
    function selectTone(tone) {
        const input = document.getElementById('tone');
        if (!input) return;

        input.value = tone;

        document.querySelectorAll('[onclick^="selectTone"]').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.tone === tone) {
                btn.classList.add('active');
            }
        });

        updateSummary?.();
        changeStep(1); // auto-advance to next step
    }

    // ========================================
    // 📦 Challenge Type (Single Select)
    // ========================================
    function selectChallengeType(type) {
        const input = document.getElementById('type');
        if (!input) return;

        input.value = type;

        document.querySelectorAll('[onclick^="selectChallengeType"]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.type === type);
        });

        updateSummary?.();
    }

    // ========================================
    // 🏷 Tags (Multi-Select)
    // ========================================
    function toggleTag(button) {
        const tag = button.dataset.tag;
        const input = document.getElementById('tags');
        if (!tag || !input) return;

        let tags = input.value ? JSON.parse(input.value) : [];

        if (tags.includes(tag)) {
            tags = tags.filter(t => t !== tag);
            button.classList.remove('active');
        } else {
            tags.push(tag);
            button.classList.add('active');
        }

        input.value = JSON.stringify(tags);
        updateSummary?.();
    }

    // ========================================
    // 👥 Participants (Multi-Select)
    // ========================================
    function toggleParticipant(button) {
        const value = button.dataset.participant;
        const input = document.getElementById('participants');
        if (!value || !input) return;

        let selected = input.value ? JSON.parse(input.value) : [];

        if (selected.includes(value)) {
            selected = selected.filter(item => item !== value);
            button.classList.remove('active');
        } else {
            selected.push(value);
            button.classList.add('active');
        }

        input.value = JSON.stringify(selected);
        updateSummary?.();
    }

    // ✅ Teams Allowed (Yes/No Toggle)
    function selectTeamsAllowed(choice) {
        const input = document.getElementById('teams_allowed');
        if (!input) return;

        input.value = choice === 'yes' ? 'true' : 'false';

        document.querySelectorAll('[data-team]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.team === choice);
        });

        updateSummary?.();
    }

    // ✅ Submission Language (Single Select)
    function selectLanguageButton(code) {
        const input = document.getElementById('languages');
        if (!input) return;

        input.value = code;

        document.querySelectorAll('[data-lang]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.lang === code);
        });

        updateSummary?.();
    }

    // ✅ Skills Required (Multi-Select)
    function toggleSkill(button) {
        const value = button.dataset.skill;
        const input = document.getElementById('skills_required');
        if (!value || !input) return;

        let selected = input.value ? JSON.parse(input.value) : [];

        if (selected.includes(value)) {
            selected = selected.filter(item => item !== value);
            button.classList.remove('active');
        } else {
            selected.push(value);
            button.classList.add('active');
        }

        input.value = JSON.stringify(selected);
        updateSummary?.();
    }

    // ========================================
    // 📁 Submission Format (Single Select)
    // ========================================
    function selectSubmissionFormat(format) {
        const input = document.getElementById('submission_format');
        if (!input) return;

        input.value = format;

        document.querySelectorAll('[data-format]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.format === format);
        });

        updateSummary?.();
    }

    // ✅ Required Docs (Multi-Select)
    function toggleRequiredDoc(button) {
        const value = button.dataset.doc;
        const input = document.getElementById('required_docs');
        if (!value || !input) return;

        let docs = input.value ? JSON.parse(input.value) : [];

        if (docs.includes(value)) {
            docs = docs.filter(doc => doc !== value);
            button.classList.remove('active');
        } else {
            docs.push(value);
            button.classList.add('active');
        }

        input.value = JSON.stringify(docs);
        updateSummary?.();
    }

    // ========================================
    // 📦 Deliverables (Multi-Select)
    // ========================================
    function toggleDeliverable(button) {
        const value = button.dataset.deliverable;
        const input = document.getElementById('deliverables');
        if (!value || !input) return;

        let selected = input.value ? JSON.parse(input.value) : [];

        if (selected.includes(value)) {
            selected = selected.filter(item => item !== value);
            button.classList.remove('active');
        } else {
            selected.push(value);
            button.classList.add('active');
        }

        input.value = JSON.stringify(selected);
        updateSummary?.();
    }


    // ========================================
    // 🧑‍⚖️ Judging Method (Single Select)
    // ========================================
    function selectJudgingMethod(method) {
        const input = document.getElementById('judging_method');
        if (!input) return;

        input.value = method;

        document.querySelectorAll('[data-method]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.method === method);
        });

        updateSummary?.();
    }

    // ========================================
    // 💰 Currency Selection (Single Select)
    // ========================================
    function selectCurrency(code) {
        const input = document.getElementById('currency');
        if (!input) return;

        input.value = code;

        document.querySelectorAll('[data-currency]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.currency === code);
        });

        updateSummary?.();
    }

    // ========================================
    // 🧠 IP Terms Selection (Single Select)
    // ========================================
    function selectIPTerms(value) {
        const input = document.getElementById('ip_terms');
        if (!input) return;

        input.value = value;

        document.querySelectorAll('[data-ip]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.ip === value);
        });

        updateSummary?.();
    }

    // ========================================
    // 🔁 Restore Button States on Page Load
    // ========================================
    function initWizardFieldBehaviors() {
        const restoreGroup = (inputId, selector, attr, isSingle = false) => {
            const input = document.getElementById(inputId);
            if (!input?.value) return;

            try {
                const values = isSingle ? [input.value] : JSON.parse(input.value);
                document.querySelectorAll(selector).forEach(btn => {
                    if (values.includes(btn.dataset[attr])) {
                        btn.classList.add('active');
                    }
                });
            } catch (e) {
                console.warn(`Could not restore "${inputId}":`, e);
            }
        };

        restoreGroup('participants', '[data-participant]', 'participant');
        restoreGroup('skills_required', '[data-skill]', 'skill');
        restoreGroup('languages', '[data-lang]', 'lang', true);
        restoreGroup('submission_format', '[data-format]', 'format', true);
        restoreGroup('required_docs', '[data-doc]', 'doc');
        restoreGroup('deliverables', '[data-deliverable]', 'deliverable');
        restoreGroup('judging_method', '[data-method]', 'method', true);
        restoreGroup('currency', '[data-currency]', 'currency', true);
        restoreGroup('ip_terms', '[data-ip]', 'ip', true);

        const teamsAllowed = document.getElementById('teams_allowed')?.value;
        if (teamsAllowed === 'true' || teamsAllowed === 'false') {
            document.querySelectorAll('[data-team]').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.team === (teamsAllowed === 'true' ? 'yes' : 'no'));
            });
        }
    }

    // ✅ Export for Vite
    export { initWizardFieldBehaviors };

    // 🌐 Attach to window for inline handlers
    window.selectLanguage = selectLanguage;
    window.selectTone = selectTone;
    window.selectChallengeType = selectChallengeType;
    window.toggleTag = toggleTag;
    window.toggleParticipant = toggleParticipant;
    window.selectTeamsAllowed = selectTeamsAllowed;
    window.selectLanguageButton = selectLanguageButton;
    window.toggleSkill = toggleSkill;
    window.selectSubmissionFormat = selectSubmissionFormat;
    window.toggleRequiredDoc = toggleRequiredDoc;
    window.toggleDeliverable = toggleDeliverable;
    window.selectJudgingMethod = selectJudgingMethod;
    window.selectCurrency = selectCurrency;
    window.selectIPTerms = selectIPTerms;