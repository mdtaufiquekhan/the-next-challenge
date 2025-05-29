// quill-custom.js


import Quill from 'quill'; // ✅ This imports Quill from node_modules
import 'quill/dist/quill.snow.css'; // Optional: import Quill styles via Vite

// Store Quill instances
const quillEditors = {};

const fieldIds = [
  'review_challenge',
  'review_solution',
  'review_submission',
  'review_evaluation',
  'review_participation',
  'review_awards',
  'review_deadline',
  'review_resources'
];

export function initQuillEditors() {
  fieldIds.forEach(id => {
    const textarea = document.getElementById(id);
    if (!textarea) return;

    // Create editor container
    const quillDiv = document.createElement('div');
    quillDiv.id = `quill_${id}`;
    textarea.parentNode.insertBefore(quillDiv, textarea);
    textarea.style.display = 'none';

    // Initialize Quill
    const quill = new Quill(`#quill_${id}`, {
      theme: 'snow',
      placeholder: 'Write here...',
      modules: {
        toolbar: [
          ['bold', 'italic', 'underline'],
          [{ list: 'ordered' }, { list: 'bullet' }],
          ['link']
        ]
      }
    });

    // Store editor
    quillEditors[id] = quill;

    // Sync back to textarea on form submit
    textarea.closest('form')?.addEventListener('submit', () => {
      textarea.value = quill.root.innerHTML;
    });
  });
}

// Utility to programmatically set Quill editor content
export function setQuillContent(id, html) {
  if (quillEditors[id]) {
    quillEditors[id].clipboard.dangerouslyPasteHTML(html || '');
  }
}

// Optional: Get content from an editor
export function getQuillContent(id) {
  return quillEditors[id]?.root.innerHTML || '';
}
