// wizard-step8.js

import { showLoader, hideLoader } from './loader';

document.addEventListener('DOMContentLoaded', () => {
    let step8Loaded = false;

    const observer = new MutationObserver(() => {
        if (window.currentStep === 8 && !step8Loaded) {
            step8Loaded = true;

            showLoader(4); // 👈 Estimated wait: 4 seconds

            fetch('/wizard/step8-generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                console.log('📊 Step 8 AI Suggestions:', data);

                const container = document.getElementById('metrics-container');
                const hiddenInput = document.getElementById('evaluation_metrics');

                if (!Array.isArray(data.evaluation_metrics)) return;

                container.innerHTML = ''; // Clear previous

                const metrics = data.evaluation_metrics;

                metrics.forEach(metric => {
                    const row = document.createElement('div');
                    row.className = 'row g-2 mb-2 metric-item';

                    row.innerHTML = `
                        <div class="col-md-6">
                            <input type="text" class="form-control metric-label" placeholder="Metric Label" value="${metric.label}">
                        </div>
                        <div class="col-md-4">
                            <input type="number" class="form-control metric-weight" placeholder="Weight (%)" min="0" max="100" value="${metric.weight}">
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <button type="button" class="btn btn-danger btn-sm remove-metric" title="Remove">&times;</button>
                        </div>
                    `;

                    // Attach behaviors
                    row.querySelectorAll('input').forEach(input => input.addEventListener('input', updateJSON));
                    row.querySelector('.remove-metric').addEventListener('click', () => {
                        row.remove();
                        updateJSON();
                    });

                    container.appendChild(row);
                });

                // Sync to hidden input
                function updateJSON() {
                    const values = [];
                    container.querySelectorAll('.metric-item').forEach(row => {
                        const label = row.querySelector('.metric-label')?.value.trim();
                        const weight = row.querySelector('.metric-weight')?.value;
                        if (label && weight) {
                            values.push({ label, weight: parseInt(weight) });
                        }
                    });
                    hiddenInput.value = JSON.stringify(values);
                }

                updateJSON();
                updateSummary?.();
            })
            .catch(err => {
                console.error('❌ Failed to fetch Step 8 metrics', err);
            })
            .finally(() => {
                hideLoader(); // 👈 Hide loader when done
            });
        }
    });

    observer.observe(document.body, {
        attributes: true,
        childList: true,
        subtree: true
    });
});
