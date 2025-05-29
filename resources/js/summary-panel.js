window.updateSummary = function () {
  const currentStep = window.currentStep || 1;
  const summaryTitle = document.getElementById('summary-title');
  const panelContent = document.getElementById('panel-content');

  if (!summaryTitle || !panelContent) return;

  if (currentStep <= 3) {
    summaryTitle.textContent = 'Tips';
    panelContent.innerHTML = `
      <ul class="list-unstyled small">
        <li>✅ Start by choosing your preferred language.</li>
        <li>💬 Then pick a tone that fits your challenge style.</li>
        <li>📝 Finally, describe your idea or problem to solve.</li>
      </ul>`;
    return;
  }

  summaryTitle.textContent = 'Summary';

  const groupedData = {};

  document.querySelectorAll('.wizard-step').forEach((step) => {
    const group = step.dataset.group;
    if (!group) return;

    const fields = step.querySelectorAll('[data-label]');
    fields.forEach((field) => {
      const label = field.dataset.label;
      let value = '';

      if (field.tagName === 'SELECT' && field.multiple) {
        value = Array.from(field.selectedOptions).map(opt => opt.value).join(', ');
      } else if (field.type === 'checkbox') {
        value = field.checked ? 'Yes' : 'No';
      } else {
        value = field.value?.trim();
      }

      if (value) {
        if (!groupedData[group]) groupedData[group] = [];
        groupedData[group].push(`<li><strong>${label}:</strong> ${value}</li>`);
      }
    });
  });

  let html = `<h6 class="fw-bold" id="summary-data-label">Your Challenge Summary</h6>`;
  for (const [group, items] of Object.entries(groupedData)) {
    html += `<div class="mb-2"><strong>${group}</strong><ul class="list-unstyled small">${items.join('')}</ul></div>`;
  }

  panelContent.innerHTML = html;
};
