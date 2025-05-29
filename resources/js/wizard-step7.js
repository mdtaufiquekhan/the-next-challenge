// wizard-step7.js

import { showLoader, hideLoader } from './loader';

document.addEventListener('DOMContentLoaded', () => {
    let step7Loaded = false;

    const observer = new MutationObserver(() => {
        if (window.currentStep === 7 && !step7Loaded) {
            step7Loaded = true;

            showLoader(4); // 👈 Estimated wait time: 4 seconds

            fetch('/wizard/step7-generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                console.log('📦 Step 7 AI Options:', data);

                // Containers
                const formatContainer = document.getElementById('submission-format-buttons');
                const docsContainer = document.getElementById('required-docs-buttons');
                const deliverablesContainer = document.getElementById('deliverables-buttons');

                // Submission Format Options
                if (Array.isArray(data.submission_format_options) && formatContainer) {
                    formatContainer.innerHTML = '';
                    data.submission_format_options.forEach(format => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'btn custom-select-btn';
                        btn.dataset.format = format;
                        btn.textContent = format;
                        btn.onclick = () => selectSubmissionFormat(format);
                        formatContainer.appendChild(btn);
                    });
                }

                // Required Docs Options (multi)
                if (Array.isArray(data.required_docs) && docsContainer) {
                    docsContainer.innerHTML = '';
                    data.required_docs.forEach(doc => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'custom-select-btn';
                        btn.dataset.doc = doc;
                        btn.textContent = doc;
                        btn.onclick = function () {
                            toggleRequiredDoc(this);
                        };
                        docsContainer.appendChild(btn);
                    });
                }

                // Deliverables Options (multi)
                if (Array.isArray(data.deliverables) && deliverablesContainer) {
                    deliverablesContainer.innerHTML = '';
                    data.deliverables.forEach(deliv => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'btn custom-select-btn';
                        btn.dataset.deliverable = deliv;
                        btn.textContent = deliv;
                        btn.onclick = function () {
                            toggleDeliverable(this);
                        };
                        deliverablesContainer.appendChild(btn);
                    });
                }

                updateSummary?.();
            })
            .catch(err => {
                console.error('❌ Failed to fetch Step 7 options', err);
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
