// wizard-step10.js

import { showLoader, hideLoader } from './loader';

document.addEventListener('DOMContentLoaded', () => {
    let step10Loaded = false;

    const observer = new MutationObserver(() => {
        if (window.currentStep === 10 && !step10Loaded) {
            step10Loaded = true;

            showLoader(3); // 👈 Show loading overlay with 3s estimated time

            fetch('/wizard/step10-generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                console.log('🗓️ Step 10 AI Dates:', data);

                if (data.launch_date) {
                    document.getElementById('launch_date').value = data.launch_date;
                }
                if (data.submission_deadline) {
                    document.getElementById('submission_deadline').value = data.submission_deadline;
                }
                if (data.judging_window_start) {
                    document.getElementById('judging_window_start').value = data.judging_window_start;
                }
                if (data.judging_window_end) {
                    document.getElementById('judging_window_end').value = data.judging_window_end;
                }
                if (data.announcement_date) {
                    document.getElementById('announcement_date').value = data.announcement_date;
                }

                updateSummary?.();
            })
            .catch(err => {
                console.error('❌ Failed to fetch Step 10 AI dates', err);
            })
            .finally(() => {
                hideLoader(); // 👈 Always hide the loader after request finishes
            });
        }
    });

    observer.observe(document.body, {
        attributes: true,
        childList: true,
        subtree: true
    });
});
