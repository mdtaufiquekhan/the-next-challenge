let currentStep = 1;
let maxReachedStep = 1;

function showStep(step) {
  const steps = document.querySelectorAll('.wizard-step');
  steps.forEach((el, idx) => {
    el.classList.remove('active');
    if (idx + 1 === step) el.classList.add('active');
  });

  steps.forEach((stepEl, index) => {
    const i = index + 1;
    const group = document.getElementById(`step-group-${i}`);
    const connector = document.querySelector(`#step-group-${i} + .connector`);

    if (group) {
      group.classList.remove('active', 'completed');
      group.removeAttribute('aria-current');

      if (i < step) {
        group.classList.add('completed');
        if (connector) connector.classList.add('completed');
      } else if (i === step) {
        group.classList.add('active');
        group.setAttribute('aria-current', 'step');
        if (connector) connector.classList.remove('completed');
      } else {
        if (connector) connector.classList.remove('completed');
      }
    }
  });

  currentStep = step;
  window.currentStep = step;
  if (step > maxReachedStep) maxReachedStep = step;

  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  if (prevBtn) prevBtn.disabled = step === 1;
  if (nextBtn) nextBtn.innerText = step === steps.length ? 'Submit' : 'Next';
}

function changeStep(direction) {
  const targetStep = currentStep + direction;
  if (targetStep < 1) return;

  if (direction === 1) {
    const missingFields = validateStep(currentStep);
    if (missingFields.length > 0) {
      alert(`Please complete the following fields before continuing:\n• ${missingFields.join('\n• ')}`);
      return;
    }
  }

  showStep(targetStep);
  updateSummary?.();
}

// ✅ Dynamic step validator based on data-group and data-label
function validateStep(stepNumber) {
  const missing = [];

  const step = document.querySelector(`.wizard-step.step-${stepNumber}`);
  if (!step) return [];

  const fields = step.querySelectorAll('[data-label]');

  fields.forEach((el) => {
    const isOptional = el.dataset.require === 'optional' || el.dataset.require === 'false';
    const label = el.dataset.label || el.name || 'Unnamed Field';
    let isValid = true;

    if (isOptional) return; // skip validation

    if (el.tagName === 'SELECT') {
      if (el.multiple) {
        isValid = el.selectedOptions.length > 0;
      } else {
        isValid = el.value && el.value.trim() !== '';
      }
    } else if (el.type === 'checkbox') {
      isValid = el.checked;
    } else if (el.type === 'file') {
      isValid = el.files.length > 0;
    } else {
      isValid = el.value && el.value.trim() !== '';
    }

    el.classList.toggle('is-invalid', !isValid);
    if (!isValid) missing.push(label);
  });

  return missing;
}

// ⛔ Prevent PageUp/PageDown from skipping steps
document.addEventListener('keydown', (e) => {
  if (e.code === 'PageDown' || e.code === 'PageUp') {
    e.preventDefault();
    if (e.code === 'PageDown') changeStep(1);
    if (e.code === 'PageUp') changeStep(-1);
  }
}, { passive: false });

document.addEventListener('DOMContentLoaded', () => {
  showStep(currentStep);
  updateSummary?.();
});

window.changeStep = changeStep;
window.showStep = showStep;
window.currentStep = currentStep;
