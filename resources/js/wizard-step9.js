// wizard-step9.js

import { showLoader, hideLoader } from './loader';

document.addEventListener('DOMContentLoaded', () => {
    let step9Loaded = false;

    const observer = new MutationObserver(() => {
        if (window.currentStep === 9 && !step9Loaded) {
            step9Loaded = true;

            showLoader(3); // 👈 Estimated wait: 3 seconds

            fetch('/wizard/step9-generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                console.log('🏆 Step 9 AI Suggestions:', data);

                const prizeContainer = document.getElementById('prize-entries-container');
                const prizeHidden = document.getElementById('prize_structure');
                const rewardsTextarea = document.getElementById('non_monetary_rewards');

                // --------------------------
                // Prize Structure Injection
                // --------------------------
                if (Array.isArray(data.prize_structure)) {
                    prizeContainer.innerHTML = '';

                    data.prize_structure.forEach(prize => {
                        const row = document.createElement('div');
                        row.className = 'row g-2 mb-2 prize-entry';

                        row.innerHTML = `
                            <div class="col-md-4">
                                <input type="text" class="form-control prize-rank" placeholder="Rank (e.g. 1st, 2nd)" value="${prize.rank}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control prize-description" placeholder="Prize (e.g. ₹10K, Certificate)" value="${prize.description}">
                            </div>
                            <div class="col-md-2 d-flex align-items-center">
                                <button type="button" class="btn btn-danger btn-sm remove-prize" title="Remove">&times;</button>
                            </div>
                        `;

                        row.querySelectorAll('input').forEach(input => input.addEventListener('input', updateJSON));
                        row.querySelector('.remove-prize').addEventListener('click', () => {
                            row.remove();
                            updateJSON();
                        });

                        prizeContainer.appendChild(row);
                    });

                    updateJSON();
                }

                function updateJSON() {
                    const prizes = [];
                    prizeContainer.querySelectorAll('.prize-entry').forEach(row => {
                        const rank = row.querySelector('.prize-rank')?.value.trim();
                        const desc = row.querySelector('.prize-description')?.value.trim();
                        if (rank && desc) {
                            prizes.push({ rank, description: desc });
                        }
                    });
                    prizeHidden.value = JSON.stringify(prizes);
                }

                // --------------------------
                // Non-Monetary Rewards (Optional)
                // --------------------------
                if (data.non_monetary_rewards && rewardsTextarea && !rewardsTextarea.value.trim()) {
                    rewardsTextarea.value = data.non_monetary_rewards;
                }

                updateSummary?.();
            })
            .catch(err => {
                console.error('❌ Failed to fetch Step 9 AI suggestions', err);
            })
            .finally(() => {
                hideLoader(); // 👈 Hide loader when fetch is done
            });
        }
    });

    observer.observe(document.body, {
        attributes: true,
        childList: true,
        subtree: true
    });
});
