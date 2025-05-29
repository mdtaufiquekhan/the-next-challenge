import './bootstrap';
import './dark-mode';
import './wizard-navigation';
import { initQuillEditors } from './quill-custom'; // ✅ Quill setup
import './summary-panel';
import './dragbar';
import './wizard-footer-generation';
import { initWizardFieldBehaviors } from './wizard-field-behaviors'; // ✅ Import init function
import './wizard-memory';
import './wizard-step4';
import './wizard-step5';
import './wizard-step6';
import './wizard-step7';
import './wizard-step8';
import './wizard-step9';
import './wizard-step10';
import './wizard-step11';
import './wizard-step12';

// ✅ Initialize things after DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
  initQuillEditors();               // Load rich text editors
  initWizardFieldBehaviors();       // Restore button states & init interactivity
});
