// wizard-step4.js

import { showLoader, hideLoader } from './loader';

document.addEventListener('DOMContentLoaded', () => {
    let step4Loaded = false;

    const observer = new MutationObserver(() => {
        if (window.currentStep === 4 && !step4Loaded) {
            step4Loaded = true;

            showLoader(3); // 👈 Show loader with estimated wait of 3 seconds

            // POST to Laravel controller for Step 4 suggestions
            fetch('/wizard/step4-generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                console.log("🎯 Step 4 Suggestions:", data);

                const typeContainer = document.getElementById('challenge-type-buttons');
                const tagContainer = document.getElementById('tag-buttons');

                // Restore previously selected type
                const selectedType = document.getElementById('type')?.value;

                if (Array.isArray(data.types)) {
                    typeContainer.innerHTML = '';
                    data.types.forEach(type => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'btn custom-select-btn';
                        btn.dataset.type = type;
                        btn.setAttribute('onclick', `selectChallengeType('${type}')`);
                        btn.textContent = type;

                        if (type === selectedType) {
                            btn.classList.add('active');
                        }

                        typeContainer.appendChild(btn);
                    });
                }

                // Restore previously selected tags
                const selectedTagsRaw = document.getElementById('tags')?.value || '[]';
                let selectedTags = [];

                try {
                    selectedTags = JSON.parse(selectedTagsRaw);
                } catch (e) {
                    console.warn('Invalid JSON in tags input');
                }

                if (Array.isArray(data.tags)) {
                    tagContainer.innerHTML = '';
                    data.tags.forEach(tag => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'custom-select-btn';
                        btn.dataset.tag = tag;
                        btn.setAttribute('onclick', `toggleTag(this)`);
                        btn.textContent = tag;

                        if (selectedTags.includes(tag)) {
                            btn.classList.add('active');
                        }

                        tagContainer.appendChild(btn);
                    });
                }
            })
            .catch(err => {
                console.error("❌ Failed to load step 4 suggestions", err);
            })
            .finally(() => {
                hideLoader(); // 👈 Hide loader after completion
            });
        }
    });

    observer.observe(document.body, {
        attributes: true,
        childList: true,
        subtree: true
    });
});
