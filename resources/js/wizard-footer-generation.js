// wizard-footer-generation.js

window.generateWizardFooter = function () {
  const steps = document.querySelectorAll('.wizard-step');
  const footer = document.createElement('div');

  footer.className = 'wizard-footer text-center';
  footer.setAttribute('role', 'navigation');
  footer.setAttribute('aria-label', 'Challenge Wizard Step Progress');

  const progress = document.createElement('div');
  progress.className = 'wizard-progress d-flex justify-content-center align-items-center flex-wrap';
  progress.setAttribute('role', 'list');

  steps.forEach((step, index) => {
    const stepIndex = index + 1;
    const groupName = step.getAttribute('data-group') || `Step ${stepIndex}`;

    const groupDiv = document.createElement('div');
    groupDiv.className = 'step-circle-group';
    groupDiv.id = `step-group-${stepIndex}`;
    groupDiv.setAttribute('role', 'listitem');

    const circle = document.createElement('div');
    circle.className = 'circle';
    circle.setAttribute('aria-hidden', 'true');
    circle.textContent = stepIndex;

    const label = document.createElement('div');
    label.className = 'label';
    label.textContent = groupName;

    groupDiv.appendChild(circle);
    groupDiv.appendChild(label);
    progress.appendChild(groupDiv);

    if (stepIndex < steps.length) {
      const connector = document.createElement('div');
      connector.className = 'connector';
      connector.setAttribute('aria-hidden', 'true');
      progress.appendChild(connector);
    }
  });

  footer.appendChild(progress);

  const existingFooter = document.querySelector('.wizard-footer');
  if (existingFooter) {
    existingFooter.replaceWith(footer);
  } else {
    document.body.appendChild(footer);
  }
};

// Auto-generate on DOM load
document.addEventListener('DOMContentLoaded', () => {
  window.generateWizardFooter();
});
