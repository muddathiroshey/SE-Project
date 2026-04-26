
let currentStep = 1;
let selectedNiche = '';
let activeLanguagePairField = null;
let activeLanguagePairCard = null;

const languageOptions = [
  'Afrikaans', 'Albanian', 'Amharic', 'Arabic', 'Armenian', 'Assamese', 'Aymara', 'Azerbaijani',
  'Bambara', 'Basque', 'Belarusian', 'Bengali', 'Bhojpuri', 'Bosnian', 'Bulgarian', 'Burmese',
  'Catalan', 'Cebuano', 'Chinese (Simplified)', 'Chinese (Traditional)', 'Corsican', 'Croatian', 'Czech',
  'Danish', 'Dhivehi', 'Dogri', 'Dutch', 'English', 'Esperanto', 'Estonian', 'Ewe',
  'Filipino', 'Finnish', 'French', 'Frisian', 'Galician', 'Georgian', 'German', 'Greek', 'Guarani', 'Gujarati',
  'Haitian Creole', 'Hausa', 'Hawaiian', 'Hebrew', 'Hindi', 'Hmong', 'Hungarian',
  'Icelandic', 'Igbo', 'Ilocano', 'Indonesian', 'Irish', 'Italian', 'Japanese', 'Javanese',
  'Kannada', 'Kazakh', 'Khmer', 'Kinyarwanda', 'Konkani', 'Korean', 'Krio', 'Kurdish (Kurmanji)', 'Kurdish (Sorani)', 'Kyrgyz',
  'Lao', 'Latin', 'Latvian', 'Lingala', 'Lithuanian', 'Luganda', 'Luxembourgish',
  'Macedonian', 'Maithili', 'Malagasy', 'Malay', 'Malayalam', 'Maltese', 'Maori', 'Marathi', 'Meiteilon', 'Mizo', 'Mongolian',
  'Nepali', 'Norwegian', 'Nyanja', 'Odia', 'Oromo', 'Pashto', 'Persian', 'Polish', 'Portuguese', 'Punjabi',
  'Quechua', 'Romanian', 'Russian', 'Samoan', 'Sanskrit', 'Scots Gaelic', 'Sepedi', 'Serbian', 'Sesotho', 'Shona', 'Sindhi', 'Sinhala', 'Slovak', 'Slovenian', 'Somali', 'Spanish', 'Sundanese', 'Swahili', 'Swedish',
  'Tajik', 'Tamil', 'Tatar', 'Telugu', 'Thai', 'Tigrinya', 'Tsonga', 'Turkish', 'Turkmen', 'Twi',
  'Ukrainian', 'Urdu', 'Uyghur', 'Uzbek', 'Vietnamese', 'Welsh', 'Xhosa', 'Yiddish', 'Yoruba', 'Zulu'
];

const nicheQuestionSets = {
  'Data Science & ML': [
    {
      id: 'data_source_status',
      label: 'Data Sources & Access Status',
      type: 'cards',
      options: ['Clean labeled dataset', 'Raw data needs preparation', 'Data access pending', 'Need data plan defined']
    },
    {
      id: 'ml_target_metric',
      label: 'Primary ML Task',
      type: 'cards',
      options: ['Prediction / classification', 'Forecasting', 'Segmentation / clustering', 'NLP / text analysis', 'Recommendation / ranking']
    },
    {
      id: 'ml_model_constraints',
      label: 'Model Constraints',
      type: 'cards',
      options: ['Interpretability required', 'Accuracy prioritized', 'Low-latency inference', 'Regulated / audited model', 'Research prototype']
    },
    {
      id: 'ml_deployment_context',
      label: 'Deployment Context',
      type: 'cards',
      options: ['Notebook handoff', 'API-ready model', 'Dashboard / report', 'Production pipeline', 'Explainability audit']
    }
  ],
  'Legal Consulting': [
    {
      id: 'legal_matter_type',
      label: 'Matter Type',
      type: 'cards',
      options: ['Contract review', 'Regulatory compliance', 'Due diligence', 'Dispute / arbitration', 'Entity setup', 'Policy drafting']
    },
    {
      id: 'legal_jurisdictions',
      label: 'Jurisdictions & Governing Law',
      type: 'cards',
      options: ['Single local jurisdiction', 'Cross-border MENA', 'EU / UK involved', 'US involved', 'International arbitration']
    },
    {
      id: 'legal_documents',
      label: 'Document Stage',
      type: 'cards',
      options: ['First draft needed', 'Existing draft review', 'Negotiation markup', 'Clause risk summary', 'Full agreement package']
    },
    {
      id: 'legal_deadline_risk',
      label: 'Deadline & Risk Sensitivity',
      type: 'cards',
      options: ['Low urgency', 'Signing date soon', 'Filing deadline', 'High confidentiality', 'Material business risk']
    }
  ],
  'Technical Translation': [
    {
      id: 'translation_language_pair',
      label: 'Source & Target Languages',
      type: 'cards',
      options: ['Arabic to English', 'English to Arabic', 'English to French', 'English to German', 'Other pair']
    },
    {
      id: 'translation_volume_format',
      label: 'Volume & File Formats',
      type: 'cards',
      options: ['Under 5k words', '5k-20k words', '20k+ words', 'Slides / tables', 'Subtitles / timed text']
    },
    {
      id: 'translation_domain',
      label: 'Technical Domain',
      type: 'cards',
      options: ['Legal', 'Medical / clinical', 'Engineering', 'Software / API docs', 'Finance', 'Academic research']
    },
    {
      id: 'translation_quality_controls',
      label: 'Terminology & Review Requirements',
      type: 'cards',
      options: ['Use existing glossary', 'Create terminology list', 'Certified translation', 'Native review pass', 'CAT tool required']
    }
  ],
  'Financial Modelling': [
    {
      id: 'finance_model_purpose',
      label: 'Model Purpose',
      type: 'cards',
      options: ['Fundraising / pitch deck', 'Valuation', 'Budget forecast', 'Project finance', 'M&A / due diligence', 'Scenario planning']
    },
    {
      id: 'finance_inputs',
      label: 'Available Financial Inputs',
      type: 'cards',
      options: ['Historical financials ready', 'KPIs only', 'Assumptions need building', 'Messy spreadsheet cleanup', 'No data yet']
    },
    {
      id: 'finance_outputs',
      label: 'Required Outputs',
      type: 'cards',
      options: ['3-statement model', 'DCF valuation', 'Unit economics', 'Sensitivity tables', 'Investor-ready workbook']
    },
    {
      id: 'finance_scenario_depth',
      label: 'Scenario Complexity',
      type: 'cards',
      options: ['Base case only', 'Base / upside / downside', 'Probabilistic scenarios', 'Multi-currency', 'Multi-entity consolidation']
    }
  ],
  'Biomedical Research': [
    {
      id: 'biomed_study_type',
      label: 'Study Or Research Type',
      type: 'cards',
      options: ['Literature review', 'Protocol design', 'Data analysis', 'Regulatory writing', 'Grant / manuscript support', 'Clinical evidence summary']
    },
    {
      id: 'biomed_data_materials',
      label: 'Data, Samples, Or Materials',
      type: 'cards',
      options: ['Dataset available', 'Lab outputs available', 'Patient cohort data', 'Papers only', 'Materials still pending']
    },
    {
      id: 'biomed_ethics',
      label: 'Ethics, Privacy & Compliance',
      type: 'cards',
      options: ['IRB approved', 'IRB pending', 'De-identified data', 'HIPAA / GDPR sensitive', 'No human subject data']
    },
    {
      id: 'biomed_deliverable_standard',
      label: 'Deliverable Standard',
      type: 'cards',
      options: ['Academic manuscript', 'Regulatory-grade report', 'Internal scientific memo', 'Statistical analysis plan', 'Grant-ready package']
    }
  ],
  'Cybersecurity Audit': [
    {
      id: 'security_asset_scope',
      label: 'Assets In Scope',
      type: 'cards',
      options: ['Web app', 'API', 'Cloud account', 'Network', 'Code repository', 'Multiple assets']
    },
    {
      id: 'security_audit_type',
      label: 'Audit Type',
      type: 'cards',
      options: ['Web app pentest', 'Cloud security review', 'Network assessment', 'Code review', 'Compliance gap assessment', 'Incident readiness']
    },
    {
      id: 'security_access_level',
      label: 'Access Level',
      type: 'cards',
      options: ['Black-box', 'Grey-box', 'White-box', 'Credentialed review', 'Read-only cloud/repo access']
    },
    {
      id: 'security_reporting_needs',
      label: 'Reporting & Remediation Expectations',
      type: 'cards',
      options: ['Executive report', 'Technical PoC detail', 'Compliance mapping', 'Remediation plan', 'Retest included']
    }
  ]
};

function getStepPanel(stepNumber) {
  return document.getElementById('step' + stepNumber);
}

function getFieldLabel(field) {
  const group = field.closest('.form-group');
  const label = group?.querySelector('.form-label');
  if (!label) return 'this field';
  return label.textContent.replace(/\s+/g, ' ').trim();
}

function getOrCreateFieldError(field) {
  const targetId = field.getAttribute('data-error-target');
  if (targetId) return document.getElementById(targetId);

  const group = field.closest('.form-group') || field.parentElement;
  if (!group) return null;
  let error = group.querySelector('.field-error');
  if (!error) {
    error = document.createElement('div');
    error.className = 'field-error';
    group.appendChild(error);
  }
  return error;
}

function showFieldError(field, message) {
  const error = getOrCreateFieldError(field);
  if (error) {
    error.textContent = message;
    error.classList.add('show');
  }
  field.classList.add('input-invalid');
  const uploadZoneId = field.getAttribute('data-upload-zone');
  if (uploadZoneId) {
    document.getElementById(uploadZoneId)?.classList.add('error');
  }
  const cardGroupId = field.getAttribute('data-card-group');
  if (cardGroupId) {
    document.getElementById(cardGroupId)?.classList.add('input-invalid');
  }
}

function clearFieldError(field) {
  field.classList.remove('input-invalid');
  const uploadZoneId = field.getAttribute('data-upload-zone');
  if (uploadZoneId) {
    document.getElementById(uploadZoneId)?.classList.remove('error');
  }
  const cardGroupId = field.getAttribute('data-card-group');
  if (cardGroupId) {
    document.getElementById(cardGroupId)?.classList.remove('input-invalid');
  }
  const targetId = field.getAttribute('data-error-target');
  const error = targetId
    ? document.getElementById(targetId)
    : (field.closest('.form-group') || field.parentElement)?.querySelector('.field-error');
  if (error) {
    error.classList.remove('show');
    error.textContent = '';
  }
}

function clearStepErrors(stepNumber) {
  const panel = getStepPanel(stepNumber);
  if (!panel) return;
  panel.querySelectorAll('.input-invalid').forEach(field => field.classList.remove('input-invalid'));
  panel.querySelectorAll('.niche-answer-grid.input-invalid').forEach(group => group.classList.remove('input-invalid'));
  panel.querySelectorAll('.upload-zone.error').forEach(zone => zone.classList.remove('error'));
  panel.querySelectorAll('.field-error').forEach(error => {
    error.classList.remove('show');
    if (error.id !== 'niche-error') error.textContent = '';
  });
}

function isFieldValid(field) {
  if (field.id === 'project-budget') {
    const value = Number(field.value);
    return !Number.isNaN(value) && value > 0;
  }
  if (field.type === 'file') return (field.files?.length || 0) > 0;
  if (field.type === 'checkbox') return field.checked;
  if (field.type === 'number') {
    if (field.value.trim() === '') return false;
    const value = Number(field.value);
    if (Number.isNaN(value)) return false;
    if (field.min && value < Number(field.min)) return false;
    return true;
  }
  return field.value.trim() !== '';
}

function getCustomErrorMessage(field) {
  const label = getFieldLabel(field).toLowerCase();
  if (field.id === 'project-budget') {
    return 'Please add a project budget before continuing.';
  }
  if (field.type === 'checkbox' && field.id === 'agree-terms') {
    return 'You must agree to the Posting Guidelines & Terms before posting.';
  }
  if (field.type === 'checkbox') {
    return 'This checkbox is required.';
  }
  if (field.type === 'file') {
    return 'Please upload your custom NDA file.';
  }
  if (field.tagName === 'SELECT') {
    return `Please select ${label}.`;
  }
  if (field.type === 'number') {
    return `Please enter a valid ${label}.`;
  }
  return `Please fill ${label}.`;
}

function renderNicheQuestions(niche) {
  const container = document.getElementById('niche-question-container');
  if (!container) return;

  const questions = nicheQuestionSets[niche] || [];
  container.innerHTML = '';

  if (!questions.length) {
    container.style.display = 'none';
    return;
  }

  container.style.display = 'block';
  const heading = document.createElement('h3');
  heading.className = 'niche-question-heading';
  heading.textContent = `${niche} Questions`;
  container.appendChild(heading);

  questions.forEach(question => {
    const group = document.createElement('div');
    const label = document.createElement('label');
    let field;

    group.className = 'form-group';
    label.className = 'form-label';
    label.setAttribute('for', `niche-${question.id}`);
    label.textContent = question.label;

    if (question.type === 'cards') {
      const grid = document.createElement('div');
      field = document.createElement('input');

      field.type = 'hidden';
      field.className = 'niche-question-field';
      field.dataset.cardGroup = `niche-card-group-${question.id}`;
      grid.className = 'niche-answer-grid';
      grid.id = field.dataset.cardGroup;

      question.options.forEach(optionText => {
        const card = document.createElement('button');
        card.type = 'button';
        card.className = 'niche-answer-card';
        card.dataset.value = optionText;
        if (question.id === 'translation_language_pair' && optionText === 'Other pair') {
          card.dataset.languagePairPicker = 'true';
        }
        card.textContent = optionText;
        card.setAttribute('aria-pressed', 'false');
        grid.appendChild(card);
      });

      group.append(label, field, grid);
    } else if (question.type === 'select') {
      field = document.createElement('select');
      field.className = 'form-control niche-question-field';
      question.options.forEach(optionText => {
        const option = document.createElement('option');
        option.value = optionText;
        option.textContent = optionText;
        field.appendChild(option);
      });
    } else if (question.type === 'textarea') {
      field = document.createElement('textarea');
      field.className = 'form-control niche-question-field';
      field.rows = question.rows || 3;
      field.placeholder = question.placeholder || '';
    } else {
      field = document.createElement('input');
      field.type = question.type || 'text';
      field.className = 'form-control niche-question-field';
      field.placeholder = question.placeholder || '';
      if (question.type === 'number') {
        field.min = question.min || '1';
        field.step = question.step || '1';
      }
    }

    field.id = `niche-${question.id}`;
    field.name = `niche_answers[${question.id}]`;
    field.required = true;
    field.dataset.answerId = question.id;
    field.dataset.reviewLabel = question.label;
    field.dataset.nicheQuestion = 'true';

    if (question.type !== 'cards') {
      group.append(label, field);
    }
    container.appendChild(group);
  });
}

function getNicheAnswers() {
  return Array.from(document.querySelectorAll('.niche-question-field')).map(field => {
    const value = field.tagName === 'SELECT'
      ? field.selectedOptions?.[0]?.textContent.trim()
      : field.value.trim();
    return {
      id: field.dataset.answerId || field.name,
      label: field.dataset.reviewLabel || getFieldLabel(field),
      value: value || '-'
    };
  });
}

function renderNicheAnswers() {
  const container = document.getElementById('review-niche-answers');
  if (!container) return;

  const answers = getNicheAnswers();
  container.innerHTML = '';

  if (!answers.length) {
    const row = document.createElement('div');
    row.className = 'review-row';
    row.innerHTML = '<span class="label">Questions</span><span class="val">Choose a niche to generate questions</span>';
    container.appendChild(row);
    return;
  }

  answers.forEach(answer => {
    const row = document.createElement('div');
    const label = document.createElement('span');
    const value = document.createElement('span');

    row.className = 'review-row';
    label.className = 'label';
    value.className = 'val';
    label.textContent = answer.label;
    value.textContent = answer.value;
    row.append(label, value);
    container.appendChild(row);
  });
}

function syncNicheAnswersPayload() {
  const payload = getNicheAnswers().map(answer => ({
    id: answer.id,
    label: answer.label,
    value: answer.value === '-' ? '' : answer.value
  }));
  const field = document.getElementById('niche-answers-json');
  if (field) field.value = JSON.stringify(payload);
}

function populateLanguageSelect(select, placeholder) {
  if (!select || select.options.length) return;

  const emptyOption = document.createElement('option');
  emptyOption.value = '';
  emptyOption.textContent = placeholder;
  select.appendChild(emptyOption);

  languageOptions.forEach(language => {
    const option = document.createElement('option');
    option.value = language;
    option.textContent = language;
    select.appendChild(option);
  });
}

function parseLanguagePair(value) {
  const parts = value.split(' to ');
  return {
    source: parts[0] || '',
    target: parts[1] || ''
  };
}

function setLanguagePairError(message) {
  const error = document.getElementById('language-pair-error');
  if (!error) return;
  error.textContent = message;
  error.classList.add('show');
}

function clearLanguagePairError() {
  const error = document.getElementById('language-pair-error');
  if (!error) return;
  error.classList.remove('show');
}

function openLanguagePairModal(field, card) {
  const backdrop = document.getElementById('language-pair-backdrop');
  const sourceSelect = document.getElementById('source-language-select');
  const targetSelect = document.getElementById('target-language-select');
  if (!backdrop || !sourceSelect || !targetSelect) return;

  activeLanguagePairField = field;
  activeLanguagePairCard = card;
  populateLanguageSelect(sourceSelect, 'Choose source language');
  populateLanguageSelect(targetSelect, 'Choose target language');

  const existingPair = parseLanguagePair(field.value || '');
  sourceSelect.value = existingPair.source || '';
  targetSelect.value = existingPair.target || '';
  clearLanguagePairError();
  backdrop.style.display = 'flex';
  sourceSelect.focus();
}

function closeLanguagePairModal() {
  const backdrop = document.getElementById('language-pair-backdrop');
  if (backdrop) backdrop.style.display = 'none';
  clearLanguagePairError();
  activeLanguagePairField = null;
  activeLanguagePairCard = null;
}

function saveLanguagePair() {
  const sourceSelect = document.getElementById('source-language-select');
  const targetSelect = document.getElementById('target-language-select');
  const source = sourceSelect?.value || '';
  const target = targetSelect?.value || '';

  if (!source || !target) {
    setLanguagePairError('Choose both source and target languages.');
    return;
  }

  if (source === target) {
    setLanguagePairError('Choose two different languages.');
    return;
  }

  if (!activeLanguagePairField || !activeLanguagePairCard) return;

  const pair = `${source} to ${target}`;
  const group = activeLanguagePairCard.closest('.form-group');
  activeLanguagePairField.value = pair;
  group?.querySelectorAll('.niche-answer-card').forEach(option => {
    option.classList.toggle('selected', option === activeLanguagePairCard);
    option.setAttribute('aria-pressed', option === activeLanguagePairCard ? 'true' : 'false');
  });
  activeLanguagePairCard.textContent = pair;
  clearFieldError(activeLanguagePairField);
  updateReviewSummary();
  closeLanguagePairModal();
}

function flipLanguagePair() {
  const sourceSelect = document.getElementById('source-language-select');
  const targetSelect = document.getElementById('target-language-select');
  if (!sourceSelect || !targetSelect) return;
  const source = sourceSelect.value;
  sourceSelect.value = targetSelect.value;
  targetSelect.value = source;
  clearLanguagePairError();
}

function money(value) {
  return '$' + Math.round(value || 0).toLocaleString();
}

function getSelectedOptionText(id) {
  const select = document.getElementById(id);
  return select?.selectedOptions?.[0]?.textContent.trim() || '';
}

function getSelectedRadioLabel(name) {
  const radio = document.querySelector(`input[name="${name}"]:checked`);
  return radio?.closest('label')?.textContent.replace(/\s+/g, ' ').trim() || '';
}

function compactDuration(value) {
  const normalized = value.trim().replace(/\s*days?\b/i, 'd');
  return /^\d+(?:\.\d+)?$/.test(normalized) ? `${normalized}d` : normalized;
}

function getMilestoneFields(row) {
  return {
    name: row.querySelector('.milestone-name'),
    duration: row.querySelector('.milestone-duration'),
    amount: row.querySelector('.milestone-amount')
  };
}

function getMilestones() {
  return Array.from(document.querySelectorAll('#milestone-list .milestone-builder-row'))
    .map(row => {
      const fields = getMilestoneFields(row);
      return {
        name: fields.name?.value.trim() || '',
        duration: fields.duration?.value.trim() || '',
        amount: parseFloat(fields.amount?.value) || 0
      };
    })
    .filter(milestone => milestone.name || milestone.duration || milestone.amount);
}

function isPositiveNumberField(field) {
  if (!field || field.value.trim() === '') return false;
  const value = Number(field.value);
  return !Number.isNaN(value) && value > 0;
}

function isMilestoneRowComplete(row) {
  const fields = getMilestoneFields(row);
  return Boolean(fields.name?.value.trim()) && isPositiveNumberField(fields.duration) && isPositiveNumberField(fields.amount);
}

function clearMilestoneRowError(row) {
  if (!row) return;
  row.querySelectorAll('.input-invalid').forEach(field => field.classList.remove('input-invalid'));
  const error = row.querySelector('.milestone-row-error');
  if (error) {
    error.classList.remove('show');
    error.textContent = '';
  }
}

function validateMilestones() {
  const rows = Array.from(document.querySelectorAll('#milestone-list .milestone-builder-row'));
  let isValid = true;

  rows.forEach((row, index) => {
    const fields = getMilestoneFields(row);
    const invalidFields = [];

    if (!fields.name?.value.trim()) invalidFields.push(fields.name);
    if (!isPositiveNumberField(fields.duration)) invalidFields.push(fields.duration);
    if (!isPositiveNumberField(fields.amount)) invalidFields.push(fields.amount);

    if (!invalidFields.length) {
      clearMilestoneRowError(row);
      return;
    }

    isValid = false;
    invalidFields.forEach(field => field?.classList.add('input-invalid'));

    const error = row.querySelector('.milestone-row-error');
    if (error) {
      error.textContent = `Milestone ${index + 1}: enter a name, duration in days, and budget before continuing.`;
      error.classList.add('show');
    }
  });

  return isValid;
}

function parseDurationDays(value) {
  const match = value.match(/(\d+(?:\.\d+)?)/);
  return match ? Number(match[1]) : 0;
}

function formatTimeline(milestones) {
  if (!milestones.length) return '-';
  const totalDays = milestones.reduce((sum, milestone) => sum + parseDurationDays(milestone.duration), 0);
  return totalDays ? `${totalDays}d across ${milestones.length} milestones` : `${milestones.length} milestones`;
}

function renderReviewMilestones(milestones) {
  const container = document.getElementById('review-milestones');
  if (!container) return;
  container.innerHTML = '';

  if (!milestones.length) {
    const row = document.createElement('div');
    row.className = 'review-row';
    row.innerHTML = '<span class="label">Milestones</span><span class="val">-</span>';
    container.appendChild(row);
    return;
  }

  milestones.forEach((milestone, index) => {
    const row = document.createElement('div');
    const label = document.createElement('span');
    const value = document.createElement('span');
    const parts = [
      milestone.name || 'Untitled milestone',
      milestone.duration ? compactDuration(milestone.duration) : '',
      money(milestone.amount)
    ].filter(Boolean);

    row.className = 'review-row';
    label.className = 'label';
    value.className = 'val';
    label.textContent = 'Milestone ' + (index + 1);
    value.textContent = parts.join(' · ');
    row.append(label, value);
    container.appendChild(row);
  });
}

function getDamagesSummary() {
  if (document.getElementById('nda-type-custom')?.checked) return 'Defined in uploaded NDA';
  const damages = document.getElementById('nda-damages');
  if (damages?.value === 'custom') {
    const customAmount = Number(document.getElementById('nda-custom-amount')?.value || 0);
    return customAmount ? `${money(customAmount)} per breach` : 'Custom amount';
  }
  return getSelectedOptionText('nda-damages') || '-';
}

function updateReviewSummary() {
  const title = document.getElementById('project-title')?.value.trim() || '-';
  const brief = document.getElementById('project-brief')?.value.trim() || '-';
  const fullRequirements = document.getElementById('project-full-requirements')?.value.trim() || '-';
  const idealCandidate = document.getElementById('project-ideal-candidate')?.value.trim() || '-';
  const milestones = getMilestones();
  const totalBudget = milestones.reduce((sum, milestone) => sum + milestone.amount, 0);
  const firstEscrow = milestones[0]?.amount || 0;
  const isCustomNda = document.getElementById('nda-type-custom')?.checked;
  const ndaDuration = getSelectedOptionText('nda-duration') || '2 years';
  const ndaFile = document.getElementById('ndaFileSelected')?.value || '';

  document.getElementById('review-title').textContent = title;
  document.getElementById('review-niche').textContent = selectedNiche || '-';
  document.getElementById('review-budget').textContent = totalBudget ? money(totalBudget) : '-';
  document.getElementById('review-timeline').textContent = formatTimeline(milestones);
  document.getElementById('review-privacy').textContent = getSelectedRadioLabel('nda_visibility') || '-';
  document.getElementById('review-brief').textContent = brief;
  document.getElementById('review-full-requirements').textContent = fullRequirements;
  document.getElementById('review-ideal-candidate').textContent = idealCandidate;
  renderNicheAnswers();
  renderReviewMilestones(milestones);
  document.getElementById('review-total-budget').textContent = money(totalBudget);
  document.getElementById('review-first-escrow').textContent = firstEscrow ? `${money(firstEscrow)} (on contract signing)` : '$0';
  document.getElementById('review-nda-type').textContent = isCustomNda ? `Custom NDA · ${ndaFile || 'upload pending'}` : `Standard Nexus NDA · ${ndaDuration}`;
  document.getElementById('review-damages').textContent = getDamagesSummary();
  document.getElementById('review-profile-masking').textContent = document.getElementById('profile-masking')?.checked ? 'Client org name hidden' : 'Client org name visible';
  syncNicheAnswersPayload();
  syncMilestonesPayload();
}

function syncNdaPreviewDuration() {
  const ndaDuration = document.getElementById('nda-duration');
  const ndaTermValue = document.getElementById('nda-term-value');
  if (!ndaDuration || !ndaTermValue) return;
  ndaTermValue.textContent = ndaDuration.value || '2 years';
}

function toggleCustomDamagesField() {
  const damagesSelect = document.getElementById('nda-damages');
  const customWrap = document.getElementById('nda-custom-amount-wrap');
  const customInput = document.getElementById('nda-custom-amount');
  if (!damagesSelect || !customWrap || !customInput) return;

  if (document.getElementById('nda-type-custom')?.checked) {
    customWrap.style.display = 'none';
    customInput.required = false;
    customInput.value = '';
    clearFieldError(customInput);
    return;
  }

  const isCustom = damagesSelect.value === 'custom';
  customWrap.style.display = isCustom ? 'block' : 'none';
  customInput.required = isCustom;

  if (!isCustom) {
    customInput.value = '';
    clearFieldError(customInput);
  }
}

let msCount = 1;
function renumberMilestones() {
  document.querySelectorAll('#milestone-list .milestone-builder-row').forEach((row, index) => {
    const badge = row.querySelector('.milestone-num-badge');
    if (badge) badge.textContent = index + 1;
  });
}

function syncMilestoneFieldNames() {
  document.querySelectorAll('#milestone-list .milestone-builder-row').forEach((row, index) => {
    row.id = `ms-${index}`;
    const fields = getMilestoneFields(row);
    if (fields.name) fields.name.name = `milestones[${index}][name]`;
    if (fields.duration) fields.duration.name = `milestones[${index}][duration_days]`;
    if (fields.amount) fields.amount.name = `milestones[${index}][amount]`;
  });
}

function syncMilestonesPayload() {
  const payload = getMilestones().map((milestone, index) => ({
    index,
    name: milestone.name,
    duration_days: milestone.duration,
    amount: milestone.amount
  }));
  const field = document.getElementById('milestones-json');
  if (field) field.value = JSON.stringify(payload);
}

function syncBudgetFields(total, platformFee, specialistReceives, firstEscrowLock) {
  const totalField = document.getElementById('total-budget-input');
  const platformField = document.getElementById('platform-fee-input');
  const receivesField = document.getElementById('specialist-receives-input');
  const escrowField = document.getElementById('first-escrow-input');

  if (totalField) totalField.value = String(Math.round(total || 0));
  if (platformField) platformField.value = String(Math.round(platformFee || 0));
  if (receivesField) receivesField.value = String(Math.round(specialistReceives || 0));
  if (escrowField) escrowField.value = String(Math.round(firstEscrowLock || 0));
}

function syncSubmissionFields() {
  syncMilestoneFieldNames();
  syncNicheAnswersPayload();
  syncMilestonesPayload();
  recalcTotal();
}

function addMilestone() {
  const list = document.getElementById('milestone-list');
  const div = document.createElement('div');
  div.className = 'milestone-builder-row';
  div.id = 'ms-'+msCount;
  div.innerHTML = `<div style="display:flex;gap:10px;align-items:center;"><div class="milestone-num-badge">${list.children.length+1}</div><input type="text" class="form-control milestone-name" placeholder="Milestone name"></div><input type="number" class="form-control milestone-duration" min="1" step="1" inputmode="numeric" placeholder="Duration (days)"><div style="position:relative;"><span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--ink-faint);font-family:var(--font-mono);font-size:.875rem;">$</span><input type="number" class="form-control milestone-amount" min="1" step="1" style="padding-left:26px;" placeholder="0" oninput="recalcTotal()"></div><button type="button" class="btn btn-ghost btn-icon" onclick="removeMS(this)">🗑</button><div class="field-error milestone-row-error"></div>`;
  list.appendChild(div);
  msCount++;
  syncMilestoneFieldNames();
  updateReviewSummary();
}
function removeMS(btn) {
  btn.closest('.milestone-builder-row').remove();
  renumberMilestones();
  syncMilestoneFieldNames();
  recalcTotal();
}
function recalcTotal() {
  const vals = Array.from(document.querySelectorAll('#milestone-list .milestone-amount')).map(i => parseFloat(i.value)||0);
  const total = vals.reduce((a,b) => a+b, 0);
  const platformFee = total * 0.065;
  const specialistReceives = total - platformFee;
  const firstEscrowLock = vals[0] || 0;

  document.getElementById('ms-total').textContent = '$'+total.toLocaleString();
  document.getElementById('platform-fee').textContent = money(platformFee);
  document.getElementById('specialist-receives').textContent = money(specialistReceives);
  document.getElementById('first-escrow-lock').textContent = money(firstEscrowLock);
  syncBudgetFields(total, platformFee, specialistReceives, firstEscrowLock);
  syncMilestonesPayload();
  updateReviewSummary();
}

function addDragHover(e) {
  e.preventDefault();
  e.stopPropagation();
  e.currentTarget.classList.add('drag-over');
}

function removeDragHover(e) {
  e.currentTarget.classList.remove('drag-over');
}

function handleFilesDrop(e, type) {
  e.preventDefault();
  e.stopPropagation();
  e.currentTarget.classList.remove('drag-over');

  const files = e.dataTransfer.files;
  if (files.length > 0) {
    const fileInput = document.getElementById(type + 'File');
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(files[0]);
    fileInput.files = dataTransfer.files;

    const event = new Event('change', { bubbles: true });
    fileInput.dispatchEvent(event);
  }
}

function getFileIcon(filename) {
  const normalized = filename.toLowerCase();
  if (normalized.endsWith('.pdf')) return '📕';
  if (normalized.match(/\.(doc|docx)$/i)) return '📄';
  return '📎';
}

function formatFileSize(bytes) {
  if (!bytes) return '';
  if (bytes < 1024) return bytes + ' B';
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
  return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

function previewFile(input, type) {
  if (type !== 'nda') return;
  const file = input.files[0];
  if (!file) return;

  document.getElementById('ndaFileSelected').value = file.name;
  document.getElementById('ndaStatus').textContent = file.name;
  showFilePreview('nda', file);
  clearFieldError(input);
  updateReviewSummary();
}

function showFilePreview(type, file) {
  const uploadZone = document.getElementById(type + 'UploadZone');
  const previewDiv = document.getElementById(type + 'FilePreview');
  if (!uploadZone || !previewDiv) return;

  const icon = getFileIcon(file.name);
  const size = formatFileSize(file.size);

  previewDiv.innerHTML = `
    <div class="file-preview">
      <div class="file-preview-icon">${icon}</div>
      <div class="file-preview-info">
        <div class="file-preview-name">${file.name}</div>
        <div class="file-preview-size">${size}</div>
      </div>
      <button type="button" class="file-preview-remove" onclick="removeFilePreview('${type}')">✕ Remove</button>
    </div>
  `;

  uploadZone.style.display = 'none';
  previewDiv.style.display = 'block';
}

function removeFilePreview(type) {
  const uploadZone = document.getElementById(type + 'UploadZone');
  const previewDiv = document.getElementById(type + 'FilePreview');
  const fileInput = document.getElementById(type + 'File');
  const selectedField = document.getElementById(type + 'FileSelected');
  const status = document.getElementById(type + 'Status');

  if (fileInput) fileInput.value = '';
  if (selectedField) selectedField.value = '';
  if (status) status.textContent = 'Not uploaded';
  if (previewDiv) previewDiv.style.display = 'none';
  if (uploadZone) uploadZone.style.display = 'block';
  updateReviewSummary();
}

function resetNdaUpload() {
  removeFilePreview('nda');
  const uploadFile = document.getElementById('ndaFile');
  if (uploadFile) clearFieldError(uploadFile);
}

function toggleNdaMode() {
  const standardRadio = document.getElementById('nda-type-standard');
  const customRadio = document.getElementById('nda-type-custom');
  const standardFields = document.getElementById('nda-standard-fields');
  const uploadFields = document.getElementById('nda-upload-fields');
  const uploadFile = document.getElementById('ndaFile');
  const ndaDuration = document.getElementById('nda-duration');
  const ndaDamages = document.getElementById('nda-damages');
  const ndaCustomAmount = document.getElementById('nda-custom-amount');

  if (!standardRadio || !customRadio || !standardFields || !uploadFields || !uploadFile || !ndaDuration || !ndaDamages || !ndaCustomAmount) return;

  const isCustomMode = customRadio.checked;

  standardFields.style.display = isCustomMode ? 'none' : 'block';
  uploadFields.style.display = isCustomMode ? 'block' : 'none';

  ndaDuration.required = !isCustomMode;
  ndaDamages.required = !isCustomMode;
  ndaCustomAmount.required = !isCustomMode && ndaDamages.value === 'custom';
  uploadFile.required = isCustomMode;

  if (isCustomMode) {
    clearFieldError(ndaDuration);
    clearFieldError(ndaDamages);
    clearFieldError(ndaCustomAmount);
    toggleCustomDamagesField();
  } else {
    resetNdaUpload();
    toggleCustomDamagesField();
  }
  updateReviewSummary();
}

function validateStep(stepNumber) {
  const panel = getStepPanel(stepNumber);
  if (!panel) return true;

  clearStepErrors(stepNumber);

  if (stepNumber === 1 && !selectedNiche) {
    document.getElementById('niche-error')?.classList.add('show');
    return false;
  }

  const requiredFields = panel.querySelectorAll('input[required], select[required], textarea[required]');
  let isValid = true;
  for (const field of requiredFields) {
    if (!isFieldValid(field)) {
      showFieldError(field, getCustomErrorMessage(field));
      isValid = false;
    }
  }

  if (stepNumber === 3 && !validateMilestones()) {
    isValid = false;
  }

  return isValid;
}

function updateStepUI() {
  // Hide all panels
  document.querySelectorAll('.wizard-step-panel').forEach(p => p.classList.remove('active'));
  // Show current step
  document.getElementById('step' + currentStep)?.classList.add('active');
  
  // Update progress dots and titles
  for (let i = 1; i <= 5; i++) {
    const dot = document.getElementById('dot' + i);
    const title = document.getElementById('t' + i);
    dot.classList.remove('done', 'active');
    title.classList.remove('done', 'active');
    
    if (i < currentStep) {
      dot.classList.add('done');
      dot.textContent = '✓';
      title.classList.add('done');
    } else if (i === currentStep) {
      dot.classList.add('active');
      dot.textContent = i;
      title.classList.add('active');
    } else {
      dot.textContent = i;
    }
  }
  updateReviewSummary();
}

function goStep(n) {
  currentStep = n;
  updateStepUI();
  document.querySelector('.wizard-right').scrollTop = 0;
}

function nextStep() {
  if (!validateStep(currentStep)) return;
  if (currentStep < 5) currentStep++;
  updateStepUI();
  document.querySelector('.wizard-right').scrollTop = 0;
}

function prevStep() {
  if (currentStep > 1) currentStep--;
  updateStepUI();
  document.querySelector('.wizard-right').scrollTop = 0;
}

function validateAllStepsForSubmit() {
  for (let stepNumber = 1; stepNumber <= 5; stepNumber++) {
    if (!validateStep(stepNumber)) {
      currentStep = stepNumber;
      updateStepUI();
      document.querySelector('.wizard-right').scrollTop = 0;
      return false;
    }
  }
  return true;
}

// Niche selection
document.querySelectorAll('.niche-select-card').forEach(card => {
  card.addEventListener('click', function() {
    document.querySelectorAll('.niche-select-card').forEach(c => c.classList.remove('selected'));
    this.classList.add('selected');
    selectedNiche = this.querySelector('.niche-card-name').textContent;
    document.getElementById('selected-niche').value = selectedNiche;
    document.getElementById('niche-error')?.classList.remove('show');
    renderNicheQuestions(selectedNiche);
    updateReviewSummary();
  });
});

document.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
  const eventName = (field.type === 'checkbox' || field.tagName === 'SELECT') ? 'change' : 'input';
  field.addEventListener(eventName, function() {
    if (isFieldValid(this)) {
      clearFieldError(this);
    }
    updateReviewSummary();
  });
});

document.querySelectorAll('#project-title, #project-brief, #project-full-requirements, #project-ideal-candidate, #project-budget').forEach(field => {
  field?.addEventListener('input', updateReviewSummary);
});

document.getElementById('niche-question-container')?.addEventListener('input', function(e) {
  if (!e.target.matches('.niche-question-field')) return;
  if (isFieldValid(e.target)) clearFieldError(e.target);
  updateReviewSummary();
});
document.getElementById('niche-question-container')?.addEventListener('click', function(e) {
  const card = e.target.closest('.niche-answer-card');
  if (!card) return;

  const group = card.closest('.form-group');
  const field = group?.querySelector('.niche-question-field');
  if (!field) return;

  if (card.dataset.languagePairPicker === 'true') {
    openLanguagePairModal(field, card);
    return;
  }

  group.querySelectorAll('.niche-answer-card').forEach(option => {
    option.classList.toggle('selected', option === card);
    option.setAttribute('aria-pressed', option === card ? 'true' : 'false');
  });

  field.value = card.dataset.value || card.textContent.trim();
  clearFieldError(field);
  updateReviewSummary();
});
document.getElementById('niche-question-container')?.addEventListener('change', function(e) {
  if (!e.target.matches('.niche-question-field')) return;
  if (isFieldValid(e.target)) clearFieldError(e.target);
  updateReviewSummary();
});

document.querySelectorAll('#project-timeline, #privacy-level').forEach(field => {
  field?.addEventListener('change', updateReviewSummary);
});

document.getElementById('milestone-list')?.addEventListener('input', function(e) {
  if (!e.target.matches('input')) return;
  const row = e.target.closest('.milestone-builder-row');
  e.target.classList.remove('input-invalid');
  if (isMilestoneRowComplete(row)) clearMilestoneRowError(row);
  if (e.target.matches('.milestone-amount')) recalcTotal();
  else updateReviewSummary();
});
document.getElementById('milestone-list')?.addEventListener('keydown', function(e) {
  if (e.target.matches('.milestone-duration') && ['e', 'E', '+', '-', '.'].includes(e.key)) {
    e.preventDefault();
  }
});
document.querySelectorAll('input[name="nda_visibility"], #profile-masking').forEach(field => {
  field?.addEventListener('change', updateReviewSummary);
});
document.getElementById('nda-duration')?.addEventListener('change', function() {
  syncNdaPreviewDuration();
  updateReviewSummary();
});
document.getElementById('nda-damages')?.addEventListener('change', function() {
  toggleCustomDamagesField();
  updateReviewSummary();
});
document.getElementById('nda-custom-amount')?.addEventListener('input', function() {
  if (isFieldValid(this)) clearFieldError(this);
  updateReviewSummary();
});
document.getElementById('ndaFile')?.addEventListener('change', function() {
  if (isFieldValid(this)) clearFieldError(this);
  updateReviewSummary();
});
document.getElementById('nda-type-standard')?.addEventListener('change', toggleNdaMode);
document.getElementById('nda-type-custom')?.addEventListener('change', toggleNdaMode);

const cancelBtn = document.getElementById('cancel-btn');
const exitBackdrop = document.getElementById('exit-confirm-backdrop');
const exitStayBtn = document.getElementById('exit-stay-btn');
const exitConfirmBtn = document.getElementById('exit-confirm-btn');
const languagePairBackdrop = document.getElementById('language-pair-backdrop');
const languagePairCancel = document.getElementById('language-pair-cancel');
const languagePairSave = document.getElementById('language-pair-save');
const languageSwapBtn = document.getElementById('language-swap-btn');
const projectPostForm = document.getElementById('project-post-form');

function openExitConfirm() {
  if (!exitBackdrop) return;
  exitBackdrop.style.display = 'flex';
}

function closeExitConfirm() {
  if (!exitBackdrop) return;
  exitBackdrop.style.display = 'none';
}

cancelBtn?.addEventListener('click', function(e) {
  e.preventDefault();
  openExitConfirm();
});

exitStayBtn?.addEventListener('click', closeExitConfirm);

exitConfirmBtn?.addEventListener('click', function() {
  const targetUrl = cancelBtn?.getAttribute('href') || 'dashboard-client.html';
  window.location.href = targetUrl;
});

exitBackdrop?.addEventListener('click', function(e) {
  if (e.target === exitBackdrop) {
    closeExitConfirm();
  }
});

languagePairCancel?.addEventListener('click', closeLanguagePairModal);
languagePairSave?.addEventListener('click', saveLanguagePair);
languageSwapBtn?.addEventListener('click', flipLanguagePair);
languagePairBackdrop?.addEventListener('click', function(e) {
  if (e.target === languagePairBackdrop) {
    closeLanguagePairModal();
  }
});
document.getElementById('source-language-select')?.addEventListener('change', clearLanguagePairError);
document.getElementById('target-language-select')?.addEventListener('change', clearLanguagePairError);

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape' && languagePairBackdrop?.style.display === 'flex') {
    closeLanguagePairModal();
    return;
  }
  if (e.key === 'Escape' && exitBackdrop?.style.display === 'flex') {
    closeExitConfirm();
  }
});

// Next/Back buttons
document.querySelectorAll('.step-nav .btn').forEach(btn => {
  btn.addEventListener('click', function(e) {
    if (this.hasAttribute('onclick')) return;

    if (this.textContent.includes('Back') || this.textContent.includes('←')) {
      prevStep();
    } else if (this.textContent.includes('Continue') || this.textContent.includes('→')) {
      nextStep();
    } else if (this.textContent.includes('Post')) {
      if (!validateStep(5)) {
        e.preventDefault();
        return;
      }
      syncSubmissionFields();
    }
  });
});

projectPostForm?.addEventListener('submit', function(e) {
  if (!validateAllStepsForSubmit()) {
    e.preventDefault();
    return;
  }
  syncSubmissionFields();
});

// Clear terms validation state when user checks the box
document.getElementById('agree-terms')?.addEventListener('change', function() {
  if (this.checked) {
    clearFieldError(this);
  }
});

// Left nav step clicks
document.querySelectorAll('.wizard-left-step').forEach((step, idx) => {
  step.addEventListener('click', function() {
    const targetStep = idx + 1;
    if (targetStep > currentStep) {
      for (let stepToCheck = currentStep; stepToCheck < targetStep; stepToCheck++) {
        if (!validateStep(stepToCheck)) return;
      }
    }
    currentStep = targetStep;
    updateStepUI();
    document.querySelector('.wizard-right').scrollTop = 0;
  });
});

// Initialize UI
syncMilestoneFieldNames();
updateStepUI();
recalcTotal();
updateReviewSummary();
syncNdaPreviewDuration();
toggleCustomDamagesField();
toggleNdaMode();
syncSubmissionFields();