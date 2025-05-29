// wizard-step6.js

import { showLoader, hideLoader } from './loader';

document.addEventListener('DOMContentLoaded', () => {
    let step6Loaded = false;

    const observer = new MutationObserver(() => {
        if (window.currentStep === 6 && !step6Loaded) {
            step6Loaded = true;

            showLoader(4); // 👈 Show loader with 4-second estimate

            fetch('/wizard/step6-generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                console.log('🧠 Step 6 AI Suggestions:', data);

                const participants = data.participants || [];
                const skills = data.skills_required || [];

                const participantContainer = document.getElementById('participant-buttons');
                const skillContainer = document.getElementById('skills-required-buttons');

                participantContainer.innerHTML = '';
                skillContainer.innerHTML = '';

                participants.forEach(value => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'custom-select-btn';
                    btn.dataset.participant = value;
                    btn.textContent = value;
                    btn.onclick = function () {
                        toggleParticipant(this);
                    };
                    participantContainer.appendChild(btn);
                });

                skills.forEach(value => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'custom-select-btn';
                    btn.dataset.skill = value;
                    btn.textContent = value;
                    btn.onclick = function () {
                        toggleSkill(this);
                    };
                    skillContainer.appendChild(btn);
                });
            })
            .catch(err => {
                console.error('❌ Failed to fetch Step 6 suggestions', err);
            })
            .finally(() => {
                hideLoader(); // 👈 Hide loader after fetch
            });
        }
    });

    observer.observe(document.body, {
        attributes: true,
        childList: true,
        subtree: true
    });
});
