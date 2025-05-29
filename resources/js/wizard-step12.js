// wizard-step12.js

import { setQuillContent } from './quill-custom';
import { showLoader, hideLoader } from './loader';

document.addEventListener('DOMContentLoaded', () => {
  let step12Loaded = false;

  const observer = new MutationObserver(() => {
    if (window.currentStep === 12 && !step12Loaded) {
      step12Loaded = true;

      showLoader(30); // 👈 Estimated wait: 20 seconds

      fetch('/wizard/step12-generate', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
        .then(res => res.json())
        .then(data => {
          console.log('📜 Step 12 AI Suggestions:', data);

          // Populate title suggestions
          const suggestionList = document.getElementById('suggestion-list');
          suggestionList.innerHTML = '';

          if (Array.isArray(data.titles)) {
            data.titles.forEach(title => {
              const titleButton = document.createElement('button');
              titleButton.type = 'button';
              titleButton.className = 'btn btn-outline-primary';
              titleButton.textContent = title;
              titleButton.onclick = () => {
                document.getElementById('title').value = title;
              };
              suggestionList.appendChild(titleButton);
            });
          }

          // ✅ Populate Quill editor fields (instead of textarea.value)
          setQuillContent('review_challenge', data.review_challenge);
          setQuillContent('review_solution', data.review_solution);
          setQuillContent('review_submission', data.review_submission);
          setQuillContent('review_evaluation', data.review_evaluation);
          setQuillContent('review_participation', data.review_participation);
          setQuillContent('review_awards', data.review_awards);
          setQuillContent('review_deadline', data.review_deadline);
          setQuillContent('review_resources', data.review_resources);

          updateSummary?.(); // Optional
        })
        .catch(err => {
          console.error('❌ Failed to fetch Step 12 suggestions', err);
        })
        .finally(() => {
          hideLoader(); // 👈 Always hide loader afterward
        });
    }
  });

  observer.observe(document.body, {
    attributes: true,
    childList: true,
    subtree: true
  });
});
