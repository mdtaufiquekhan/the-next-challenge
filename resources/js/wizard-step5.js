// wizard-step5.js

import { showLoader, hideLoader } from './loader';

document.addEventListener('DOMContentLoaded', () => {
    let step5Loaded = false;

    const observer = new MutationObserver(() => {
        if (window.currentStep === 5 && !step5Loaded) {
            step5Loaded = true;

            showLoader(5); // 👈 Show loader with estimated wait time of 3 seconds

            fetch('/wizard/step5-generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                console.log('🧠 Step 5 AI Suggestions:', data);

                if (data.problem_statement) {
                    document.getElementById('problem_statement').value = data.problem_statement;
                }

                if (data.why_it_matters) {
                    document.getElementById('why_it_matters').value = data.why_it_matters;
                }

                if (data.objectives) {
                    document.getElementById('objectives').value = data.objectives;
                }
            })
            .catch(err => {
                console.error('❌ Failed to fetch Step 5 suggestions', err);
            })
            .finally(() => {
                hideLoader(); // 👈 Hide loader after fetch completes
            });
        }
    });

    observer.observe(document.body, {
        attributes: true,
        childList: true,
        subtree: true
    });
});
