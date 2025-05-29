// wizard-step11.js

import { showLoader, hideLoader } from './loader';

document.addEventListener('DOMContentLoaded', () => {
    let step11Loaded = false;

    const observer = new MutationObserver(() => {
        if (window.currentStep === 11 && !step11Loaded) {
            step11Loaded = true;

            showLoader(8); // 👈 Show loading overlay with 4s estimated time

            fetch('/wizard/step11-generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                console.log('📜 Step 11 AI Code of Conduct:', data);

                const textarea = document.getElementById('code_of_conduct');

                if (data.code_of_conduct && textarea && !textarea.value.trim()) {
                    textarea.value = data.code_of_conduct;
                }

                updateSummary?.();
            })
            .catch(err => {
                console.error('❌ Failed to fetch Step 11 code of conduct', err);
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
