// ── UNSAVED STATE ──
let unsaved = false;
function markUnsaved() {
  unsaved = true;
  const banner = document.getElementById('unsaved-banner');
  if (banner) banner.classList.add('visible');
}
function discardChanges() {
  unsaved = false;
  const banner = document.getElementById('unsaved-banner');
  if (banner) banner.classList.remove('visible');
  showToast('Changes discarded.', 'info');
}
function saveProfile() {
  unsaved = false;
  const banner = document.getElementById('unsaved-banner');
  if (banner) banner.classList.remove('visible');
  showToast('Profile saved successfully. Changes are now live on your public profile.');
}

// ── TOAST ──
function showToast(msg, type) {
  const s = document.getElementById('toast-stack');
  const icon = type === 'info' ? 'ℹ' : '✓';
  const cls = type === 'info' ? '' : 'success';
  s.innerHTML = `<div class="toast ${cls}"><span class="toast-icon">${icon}</span><div><div class="toast-title">${type==='info'?'Notice':'Saved'}</div><div class="toast-body">${msg}</div></div></div>`;
  setTimeout(()=>s.innerHTML='', 4000);
}

// ── NAV ACTIVE ──
function setActive(el) {
  document.querySelectorAll('.edit-nav-link').forEach(a => a.classList.remove('active'));
  el.classList.add('active');
}

// ── CREDENTIAL TOGGLE ──
function toggleCred(i) {
  const item = document.getElementById('cred-'+i);
  if(!item) return;
  const isOpen = item.classList.toggle('open');
  item.querySelector('.credential-edit-body').style.display = isOpen ? 'block' : 'none';
  item.querySelector('.credential-chevron').style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0)';
}
function addCredential() { document.getElementById('add-cred-modal').classList.remove('hidden'); }

function submitNewCredential() {
  const modal = document.getElementById('add-cred-modal');
  const typeSelect = document.getElementById('new-cred-type-select');
  const otherTypeInput = document.getElementById('new-cred-type-other');
  const nameInput = document.getElementById('new-cred-name');
  const orgInput = document.getElementById('new-cred-org');
  const issueDateInput = document.getElementById('new-cred-issue-date');
  const expiryDateInput = document.getElementById('new-cred-expiry-date');
  const verificationInput = document.getElementById('new-cred-verification');
  const fileInput = document.getElementById('new-cred-file');

  if (!typeSelect || !nameInput || !orgInput || !issueDateInput || !verificationInput) return;

  const typeValue = typeSelect.value;
  const type = typeValue === 'Other' ? (otherTypeInput?.value.trim() || '') : typeValue;
  if (!type) { showToast('Please select or enter a credential type.', 'info'); return; }
  const name = nameInput.value.trim();
  if (!name) { showToast('Please enter the credential name.', 'info'); return; }

  const list = document.getElementById('credential-list');
  if (!list) return;

  const issueDate = issueDateInput.value;
  const expiryDate = expiryDateInput.value;
  const org = orgInput.value.trim() || 'Unknown issuer';
  const verification = verificationInput.value.trim();
  const file = fileInput?.files?.[0] || null;
  const footerText = expiryDate ? ` · Expires ${formatMonthYear(expiryDate)}` : '';
  const fileRow = file ? `<div class="form-group"><label class="form-label">Certificate Document</label><div class="uploaded-file-row"><div class="uploaded-file-icon">📄</div><span class="uploaded-file-name">${file.name}</span><span class="uploaded-file-size">${formatFileSize(file.size)}</span><span class="badge badge-pending" style="font-size:.625rem;flex-shrink:0;">Under Review</span></div></div>` : '';
  const verificationRow = verification ? `<div class="form-group"><label class="form-label">Verification URL / Credential ID</label><input type="text" class="form-control" value="${verification}" readonly></div>` : '';

  const index = list.querySelectorAll('.credential-edit-item').length;
  const item = document.createElement('div');
  item.className = 'credential-edit-item open';
  item.id = 'cred-' + index;
  item.innerHTML = `
      <div class="credential-edit-header" onclick="toggleCred(${index})">
        <span class="credential-drag-handle">⠿</span>
        <div class="credential-type-icon">🏅</div>
        <div class="credential-edit-name">
          <div style="font-weight:700;">${name}</div>
          <div style="font-size:.8125rem;color:var(--ink-muted);font-weight:400;">${type} · ${org}${footerText}</div>
        </div>
        <span class="badge badge-pending badge-dot" style="flex-shrink:0;">Under Review</span>
        <span class="credential-chevron">▾</span>
      </div>
      <div class="credential-edit-body" style="display:block;">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Credential Type</label>
            <input type="text" class="form-control" value="${type}" readonly>
          </div>
          <div class="form-group">
            <label class="form-label">Issuing Organization</label>
            <input type="text" class="form-control" value="${org}" readonly>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Issue Date</label>
            <input type="month" class="form-control" value="${issueDate}" readonly>
          </div>
          <div class="form-group">
            <label class="form-label">Expiry Date</label>
            <input type="month" class="form-control" value="${expiryDate}" readonly>
          </div>
        </div>
        ${verificationRow}
        ${fileRow}
      </div>
  `;

  list.appendChild(item);
  if (modal) modal.classList.add('hidden');
  resetAddCredentialModal();
  showToast('Credential submitted for verification. Expected review: 2–5 business days.');
}

function formatMonthYear(value) {
  if (!value) return '';
  const [year, month] = value.split('-');
  return `${new Date(year, month - 1).toLocaleString('default', { month: 'long' })} ${year}`;
}

function formatFileSize(bytes) {
  if (bytes < 1024) return bytes + ' B';
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
  return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

function resetAddCredentialModal() {
  document.getElementById('new-cred-type-select').selectedIndex = 0;
  const otherTypeInput = document.getElementById('new-cred-type-other');
  if (otherTypeInput) otherTypeInput.value = '';
  document.getElementById('new-cred-name').value = '';
  document.getElementById('new-cred-org').value = '';
  document.getElementById('new-cred-issue-date').value = '';
  document.getElementById('new-cred-expiry-date').value = '';
  document.getElementById('new-cred-verification').value = '';
  const fileInput = document.getElementById('new-cred-file');
  if (fileInput) fileInput.value = '';
  const preview = document.getElementById('new-cred-file-preview');
  if (preview) {
    preview.style.display = 'none';
    preview.innerHTML = '';
  }
  const uploadZone = document.getElementById('new-cred-upload-zone');
  if (uploadZone) uploadZone.style.display = 'flex';
  const otherGroup = document.getElementById('new-cred-type-other-group');
  if (otherGroup) otherGroup.style.display = 'none';
}

function toggleOtherCredentialType(select) {
  let otherGroup = select.closest('.form-row')?.querySelector('.other-credential-type-group');
  if (!otherGroup) {
    otherGroup = document.getElementById('new-cred-type-other-group');
  }
  if (!otherGroup) return;
  if (select.value === 'Other') {
    otherGroup.style.display = 'block';
    const input = otherGroup.querySelector('input');
    if (input) input.focus();
  } else {
    otherGroup.style.display = 'none';
  }
}

// ── NAME CHANGE REQUEST ──
function openNameChangeModal() {
  const modal = document.getElementById('name-change-modal');
  const sourceField = document.getElementById('full-name-field');
  const input = document.getElementById('name-change-full-name');
  if(sourceField && input) input.value = sourceField.value;
  if(modal) modal.classList.remove('hidden');
}

function closeNameChangeModal() {
  const modal = document.getElementById('name-change-modal');
  const input = document.getElementById('name-change-full-name');
  const fileInput = document.getElementById('name-change-id-input');
  const preview = document.getElementById('name-change-id-preview');
  if(modal) modal.classList.add('hidden');
  if(input) input.value = '';
  if(fileInput) fileInput.value = '';
  if(preview) preview.innerHTML = '';
}

function handleNameChangeIdUpload(input) {
  const preview = document.getElementById('name-change-id-preview');
  if(!preview || !input.files || !input.files[0]) return;

  const f = input.files[0];
  if(!f.type.startsWith('image/')) {
    showToast('Please upload an image file for your ID.', 'info');
    input.value = '';
    preview.innerHTML = '';
    return;
  }

  const size = f.size > 1048576 ? (f.size/1048576).toFixed(1)+' MB' : (f.size/1024).toFixed(0)+' KB';
  preview.innerHTML = `<div class="uploaded-file-row"><div class="uploaded-file-icon">🪪</div><span class="uploaded-file-name">${f.name}</span><span class="uploaded-file-size">${size}</span></div>`;
}

function submitNameChangeRequest() {
  const fullNameInput = document.getElementById('name-change-full-name');
  const fileInput = document.getElementById('name-change-id-input');
  const name = fullNameInput ? fullNameInput.value.trim() : '';

  if(name.length < 3) {
    showToast('Please enter your full legal name.', 'info');
    if(fullNameInput) fullNameInput.focus();
    return;
  }

  if(!fileInput || !fileInput.files || !fileInput.files[0]) {
    showToast('Please upload an image of your ID.', 'info');
    return;
  }

  closeNameChangeModal();
  showToast('Name change request submitted. Verification is usually completed in 1–2 business days.');
}

// ── CHAR COUNTER ──
function countChars(el, max, id) {
  const n = el.value.length;
  const counter = document.getElementById(id);
  if(!counter) return;
  counter.textContent = `${n} / ${max}`;
  counter.className = 'char-counter' + (n>max?' over':n>max*.9?' warn':'');
}

// ── COUNTRY / TIMEZONE ──
const COUNTRY_TIMEZONES = {
  'Algeria': ['Africa/Algiers'],
  'Argentina': ['America/Argentina/Buenos_Aires'],
  'Australia': ['Australia/Sydney', 'Australia/Adelaide', 'Australia/Perth'],
  'Austria': ['Europe/Vienna'],
  'Bahrain': ['Asia/Bahrain'],
  'Bangladesh': ['Asia/Dhaka'],
  'Belgium': ['Europe/Brussels'],
  'Brazil': ['America/Sao_Paulo', 'America/Manaus', 'America/Rio_Branco', 'America/Noronha'],
  'Bulgaria': ['Europe/Sofia'],
  'Canada': ['America/Toronto', 'America/Winnipeg', 'America/Edmonton', 'America/Vancouver', 'America/Halifax', 'America/St_Johns'],
  'Chile': ['America/Santiago', 'Pacific/Easter'],
  'China': ['Asia/Shanghai'],
  'Colombia': ['America/Bogota'],
  'Croatia': ['Europe/Zagreb'],
  'Czechia': ['Europe/Prague'],
  'Denmark': ['Europe/Copenhagen'],
  'Egypt': ['Africa/Cairo'],
  'Estonia': ['Europe/Tallinn'],
  'Ethiopia': ['Africa/Addis_Ababa'],
  'Finland': ['Europe/Helsinki'],
  'France': ['Europe/Paris'],
  'Germany': ['Europe/Berlin'],
  'Ghana': ['Africa/Accra'],
  'Greece': ['Europe/Athens'],
  'Hong Kong': ['Asia/Hong_Kong'],
  'Hungary': ['Europe/Budapest'],
  'India': ['Asia/Kolkata'],
  'Indonesia': ['Asia/Jakarta', 'Asia/Makassar', 'Asia/Jayapura'],
  'Ireland': ['Europe/Dublin'],
  'Israel': ['Asia/Jerusalem'],
  'Italy': ['Europe/Rome'],
  'Japan': ['Asia/Tokyo'],
  'Jordan': ['Asia/Amman'],
  'Kazakhstan': ['Asia/Almaty', 'Asia/Aqtobe'],
  'Kenya': ['Africa/Nairobi'],
  'Kuwait': ['Asia/Kuwait'],
  'Lebanon': ['Asia/Beirut'],
  'Malaysia': ['Asia/Kuala_Lumpur'],
  'Mexico': ['America/Mexico_City', 'America/Cancun', 'America/Chihuahua', 'America/Tijuana'],
  'Morocco': ['Africa/Casablanca'],
  'Netherlands': ['Europe/Amsterdam'],
  'New Zealand': ['Pacific/Auckland', 'Pacific/Chatham'],
  'Nigeria': ['Africa/Lagos'],
  'Norway': ['Europe/Oslo'],
  'Oman': ['Asia/Muscat'],
  'Pakistan': ['Asia/Karachi'],
  'Peru': ['America/Lima'],
  'Philippines': ['Asia/Manila'],
  'Poland': ['Europe/Warsaw'],
  'Portugal': ['Europe/Lisbon', 'Atlantic/Azores', 'Atlantic/Madeira'],
  'Qatar': ['Asia/Qatar'],
  'Romania': ['Europe/Bucharest'],
  'Russia': ['Europe/Kaliningrad', 'Europe/Moscow', 'Europe/Samara', 'Asia/Yekaterinburg', 'Asia/Omsk', 'Asia/Krasnoyarsk', 'Asia/Irkutsk', 'Asia/Yakutsk', 'Asia/Vladivostok', 'Asia/Magadan', 'Asia/Kamchatka'],
  'Saudi Arabia': ['Asia/Riyadh'],
  'Serbia': ['Europe/Belgrade'],
  'Singapore': ['Asia/Singapore'],
  'South Africa': ['Africa/Johannesburg'],
  'South Korea': ['Asia/Seoul'],
  'Spain': ['Europe/Madrid', 'Atlantic/Canary'],
  'Sri Lanka': ['Asia/Colombo'],
  'Sweden': ['Europe/Stockholm'],
  'Switzerland': ['Europe/Zurich'],
  'Taiwan': ['Asia/Taipei'],
  'Thailand': ['Asia/Bangkok'],
  'Tunisia': ['Africa/Tunis'],
  'Turkey': ['Europe/Istanbul'],
  'Ukraine': ['Europe/Kyiv'],
  'United Arab Emirates': ['Asia/Dubai'],
  'United Kingdom': ['Europe/London'],
  'United States': ['America/New_York', 'America/Chicago', 'America/Denver', 'America/Phoenix', 'America/Los_Angeles', 'America/Anchorage', 'Pacific/Honolulu'],
  'Uruguay': ['America/Montevideo'],
  'Vietnam': ['Asia/Ho_Chi_Minh']
};

function formatTimezoneLabel(zone) {
  try {
    const formatter = new Intl.DateTimeFormat('en-US', { timeZone: zone, timeZoneName: 'shortOffset' });
    const offsetPart = formatter.formatToParts(new Date()).find(part => part.type === 'timeZoneName');
    const offset = offsetPart ? offsetPart.value : '';
    return offset ? `${offset} - ${zone}` : zone;
  } catch(e) {
    return zone;
  }
}

function populateCountrySelect() {
  const countrySelect = document.getElementById('country-select');
  if(!countrySelect) return;

  const current = COUNTRY_TIMEZONES[countrySelect.value] ? countrySelect.value : 'Egypt';
  countrySelect.innerHTML = '';

  Object.keys(COUNTRY_TIMEZONES).sort().forEach(country => {
    const option = document.createElement('option');
    option.value = country;
    option.textContent = country;
    option.selected = country === current;
    countrySelect.appendChild(option);
  });

  syncTimezoneForCountry(false);
}

function syncTimezoneForCountry(shouldMark) {
  const countrySelect = document.getElementById('country-select');
  const timezoneSelect = document.getElementById('timezone-select');
  if(!countrySelect || !timezoneSelect) return;

  const zones = COUNTRY_TIMEZONES[countrySelect.value] || COUNTRY_TIMEZONES.Egypt;
  timezoneSelect.innerHTML = '';

  zones.forEach(zone => {
    const option = document.createElement('option');
    option.value = zone;
    option.textContent = formatTimezoneLabel(zone);
    timezoneSelect.appendChild(option);
  });

  timezoneSelect.disabled = zones.length === 1;
  timezoneSelect.title = zones.length === 1 ? 'Timezone is set by country' : '';

  const previewCountry = document.getElementById('preview-country');
  if(previewCountry) previewCountry.textContent = countrySelect.value;

  if(shouldMark) markUnsaved();
}

function handleTimezoneChange() {
  markUnsaved();
}

// ── ADD ROWS ──
function addSkillRow() {
  const wrap = document.getElementById('skill-rows');
  const d = document.createElement('div');
  d.className = 'skill-edit-row';
  d.innerHTML = `<input type="text" class="form-control" placeholder="e.g. Kubernetes" oninput="markUnsaved()"><select class="form-control skill-level-select" onchange="markUnsaved()"><option>Beginner</option><option>Intermediate</option><option>Advanced</option><option>Expert</option></select><button class="btn btn-ghost btn-icon" style="color:var(--rust);" onclick="this.closest('.skill-edit-row').remove();markUnsaved()">🗑</button>`;
  wrap.appendChild(d);
  d.querySelector('input').focus();
  markUnsaved();
}
function addLangRow() {
  const wrap = document.getElementById('lang-rows');
  const d = document.createElement('div');
  d.className = 'language-row';
  d.innerHTML = `<input type="text" class="form-control" placeholder="Language" oninput="markUnsaved()"><select class="form-control" onchange="markUnsaved()"><option>Native</option><option selected>Fluent</option><option>Conversational</option><option>Basic</option></select><button class="btn btn-ghost btn-icon" style="color:var(--rust);" onclick="this.closest('.language-row').remove();markUnsaved()">🗑</button>`;
  wrap.appendChild(d);
  d.querySelector('input').focus();
  markUnsaved();
}
function addSpecCard() {
  const list = document.getElementById('spec-cards');
  if(list.children.length >= 4) { showToast('Maximum 4 specialization cards allowed.','info'); return; }
  const d = document.createElement('div');
  d.className = 'spec-edit-card';
  d.innerHTML = `<div class="form-group" style="margin:0;"><label class="form-label">Title</label><input type="text" class="form-control" placeholder="e.g. Time Series Forecasting" oninput="markUnsaved()"></div><div class="form-group" style="margin:0;"><label class="form-label">Description</label><input type="text" class="form-control" placeholder="Brief one-liner description" oninput="markUnsaved()"></div><button class="btn btn-ghost btn-icon" style="color:var(--rust);margin-top:22px;" onclick="this.closest('.spec-edit-card').remove();markUnsaved()">🗑</button>`;
  list.appendChild(d);
  markUnsaved();
}

function savePreviewState() {
  const state = {
    name: document.getElementById('full-name-field')?.value || '',
    title: document.getElementById('professional-title-field')?.value || '',
    country: document.getElementById('country-select')?.value || '',
    timezone: document.getElementById('timezone-select')?.value || '',
    experience: document.getElementById('experience-years-field')?.value || '',
    niche: document.getElementById('primary-niche-field')?.value || '',
    bio: document.getElementById('bio-field')?.value || '',
    specs: Array.from(document.querySelectorAll('#spec-cards .spec-edit-card')).map(card => ({
      title: card.querySelector('input[type="text"]')?.value.trim() || '',
      description: card.querySelectorAll('input[type="text"]')[1]?.value.trim() || ''
    })).filter(item => item.title || item.description),
    skills: Array.from(document.querySelectorAll('#skill-rows .skill-edit-row')).map(row => ({
      name: row.querySelector('input[type="text"]')?.value.trim() || '',
      level: row.querySelector('select')?.value || 'Beginner'
    })).filter(item => item.name),
    languages: Array.from(document.querySelectorAll('#lang-rows .language-row')).map(row => ({
      language: row.querySelector('input[type="text"]')?.value.trim() || '',
      level: row.querySelector('select')?.value || 'Fluent'
    })).filter(item => item.language)
  };
  localStorage.setItem('nexusProfilePreview', JSON.stringify(state));
}

function savePreviewStateAndGo(event) {
  if (event) event.preventDefault();
  savePreviewState();
  window.location.href = 'expert-profile.php';
}

// ── PORTFOLIO ──
function togglePortfolioEdit(btn) {
  const body = btn.closest('.portfolio-edit-item').querySelector('.portfolio-edit-body');
  const open = body.style.display === 'block';
  body.style.display = open ? 'none' : 'block';
  btn.textContent = open ? 'Edit' : 'Done';
}
function addPortfolioItem() {
  const modal = document.getElementById('add-portfolio-modal');
  if (modal) modal.classList.remove('hidden');
  resetAddPortfolioModal();
}

function closeAddPortfolioModal() {
  const modal = document.getElementById('add-portfolio-modal');
  if (modal) modal.classList.add('hidden');
}

function getPortfolioFileMeta(fileName) {
  const ext = fileName.split('.').pop().toLowerCase();
  switch (ext) {
    case 'ipynb': return { icon: '📓', label: 'Jupyter Notebook' };
    case 'pdf': return { icon: '📄', label: 'PDF Document' };
    case 'py': return { icon: '🐍', label: 'Python Script' };
    case 'zip': return { icon: '📦', label: 'ZIP Archive' };
    case 'jpg':
    case 'jpeg':
    case 'png': return { icon: '🖼', label: 'Image File' };
    case 'md': return { icon: '📝', label: 'Markdown File' };
    default: return { icon: '📁', label: 'File Upload' };
  }
}

function submitNewPortfolioItem() {
  const titleInput = document.getElementById('new-pf-title');
  const descInput = document.getElementById('new-pf-description');
  const clientInput = document.getElementById('new-pf-client');
  const fileInput = document.getElementById('new-pf-file');
  const title = titleInput ? titleInput.value.trim() : '';
  const description = descInput ? descInput.value.trim() : '';
  const client = clientInput ? clientInput.value.trim() : '';
  const file = fileInput?.files?.[0] || null;
  if (!title) { showToast('Please enter the item title.', 'info'); if (titleInput) titleInput.focus(); return; }
  if (!file) { showToast('Please upload a file for this portfolio item.', 'info'); return; }

  const list = document.getElementById('portfolio-list');
  if (!list) return;
  const item = document.createElement('div');
  item.className = 'portfolio-edit-item';
  const fileSize = formatFileSize(file.size);
  const clientLine = client ? ` · ${client}` : '';
  const meta = getPortfolioFileMeta(file.name);
  const now = new Date();
  const dateLabel = formatMonthYear(`${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`);
  item.innerHTML = `
    <div class="portfolio-edit-header">
      <div class="portfolio-type">${meta.icon}</div>
      <div style="flex:1;min-width:0;">
        <div style="font-weight:700;font-size:.9rem;">${title}</div>
        <div class="text-xs text-muted font-mono">${meta.label} · ${fileSize} · ${dateLabel}${clientLine}</div>
      </div>
      <div style="display:flex;gap:6px;flex-shrink:0;">
        <span class="badge badge-gold" style="font-size:.625rem;">Public</span>
        <button class="btn btn-ghost btn-sm" onclick="togglePortfolioEdit(this)">Edit</button>
        <button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="this.closest('.portfolio-edit-item').remove();markUnsaved()">🗑</button>
      </div>
    </div>
    <div class="portfolio-edit-body" style="display:none;">
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Title</label>
          <input type="text" class="form-control" value="${title}" oninput="markUnsaved()">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Description</label>
        <textarea class="form-control" rows="2" oninput="markUnsaved()">${description}</textarea>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Visibility</label>
          <select class="form-control" onchange="markUnsaved()">
            <option selected>Public (visible to all)</option>
            <option>Hidden (not displayed)</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Client Name</label>
          <input type="text" class="form-control" value="${client}" oninput="markUnsaved()" placeholder="Shown only if public · leave blank to hide">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Replace File</label>
        <div class="uploaded-file-row">
          <div class="uploaded-file-icon">${meta.icon}</div>
          <span class="uploaded-file-name">${file.name}</span>
          <span class="uploaded-file-size">${fileSize}</span>
          <button class="btn btn-ghost btn-sm" style="margin-left:auto;">Replace</button>
        </div>
      </div>
    </div>
  `;
  list.prepend(item);
  markUnsaved();
  closeAddPortfolioModal();
  showToast('Portfolio item added. It is now visible in your section.');
}

function resetAddPortfolioModal() {
  const titleInput = document.getElementById('new-pf-title');
  if (titleInput) titleInput.value = '';
  const descInput = document.getElementById('new-pf-description');
  if (descInput) descInput.value = '';
  const clientInput = document.getElementById('new-pf-client');
  if (clientInput) clientInput.value = '';
  const fileInput = document.getElementById('new-pf-file');
  if (fileInput) fileInput.value = '';
  const preview = document.getElementById('new-pf-file-preview');
  if (preview) {
    preview.innerHTML = '';
    preview.style.display = 'none';
  }
  const uploadZone = document.getElementById('new-pf-upload-zone');
  if (uploadZone) uploadZone.style.display = 'flex';
}

function handlePortfolioFileUpload(input) {
  const preview = document.getElementById('new-pf-file-preview');
  const uploadZone = document.getElementById('new-pf-upload-zone');
  if (!preview || !uploadZone || !input.files || !input.files[0]) return;

  const file = input.files[0];
  const size = file.size > 1048576 ? (file.size / 1048576).toFixed(1) + ' MB' : (file.size / 1024).toFixed(0) + ' KB';
  const ext = file.name.split('.').pop().toLowerCase();
  const icons = { pdf: '📄', ipynb: '📓', py: '🐍', zip: '📦', jpg: '🖼', jpeg: '🖼', png: '🖼' };
  const icon = icons[ext] || '📁';

  uploadZone.style.display = 'none';
  preview.style.display = 'block';
  preview.innerHTML = `<div class="uploaded-file-row"><div class="uploaded-file-icon">${icon}</div><span class="uploaded-file-name">${file.name}</span><span class="uploaded-file-size">${size}</span><button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="removePortfolioFile(this)">✕</button></div>`;
  markUnsaved();
}

function handlePortfolioDrop(event) {
  event.preventDefault();
  event.stopPropagation();
  const zone = event.currentTarget;
  if (zone) zone.classList.remove('drag-over');
  const files = event.dataTransfer?.files;
  if (!files || !files.length) return;
  const input = document.getElementById('new-pf-file');
  if (!input) return;
  const dataTransfer = new DataTransfer();
  dataTransfer.items.add(files[0]);
  input.files = dataTransfer.files;
  input.dispatchEvent(new Event('change', { bubbles: true }));
}

function removePortfolioFile(button) {
  const preview = document.getElementById('new-pf-file-preview');
  const uploadZone = document.getElementById('new-pf-upload-zone');
  const input = document.getElementById('new-pf-file');
  if (preview) {
    preview.innerHTML = '';
    preview.style.display = 'none';
  }
  if (uploadZone) uploadZone.style.display = 'flex';
  if (input) input.value = '';
}

// ── FILE UPLOAD HANDLER ──
function handleFileUpload(input, targetId) {
  const target = document.getElementById(targetId);
  if(!target) return;
  Array.from(input.files).forEach(f => {
    const size = f.size > 1048576 ? (f.size/1048576).toFixed(1)+' MB' : (f.size/1024).toFixed(0)+' KB';
    const ext = f.name.split('.').pop().toLowerCase();
    const icons = {pdf:'📄',ipynb:'📓',py:'🐍',zip:'📦',png:'🖼',jpg:'🖼'};
    const icon = icons[ext] || '📁';
    const row = document.createElement('div');
    row.className = 'uploaded-file-row';
    row.innerHTML = `<div class="uploaded-file-icon">${icon}</div><span class="uploaded-file-name">${f.name}</span><span class="uploaded-file-size">${size}</span><span class="badge badge-pending" style="font-size:.625rem;flex-shrink:0;">Staged</span><button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="this.parentNode.remove();markUnsaved()">✕</button>`;
    target.appendChild(row);
    markUnsaved();
  });
}

function handleDrop(event, inputId) {
  event.preventDefault();
  event.stopPropagation();
  const zone = event.currentTarget;
  if (zone) zone.classList.remove('drag-over');

  const files = event.dataTransfer?.files;
  if (!files || !files.length) return;

  const input = document.getElementById(inputId);
  if (!input) return;

  const dataTransfer = new DataTransfer();
  Array.from(files).forEach(file => dataTransfer.items.add(file));
  input.files = dataTransfer.files;
  input.dispatchEvent(new Event('change', { bubbles: true }));
}

function handleCredentialFileUpload(input) {
  const preview = document.getElementById('new-cred-file-preview');
  const uploadZone = document.getElementById('new-cred-upload-zone');
  if (!preview || !uploadZone || !input.files || !input.files[0]) return;

  const file = input.files[0];
  const size = file.size > 1048576 ? (file.size / 1048576).toFixed(1) + ' MB' : (file.size / 1024).toFixed(0) + ' KB';
  const ext = file.name.split('.').pop().toLowerCase();
  const icons = { pdf: '📄', jpg: '🖼', jpeg: '🖼', png: '🖼' };
  const icon = icons[ext] || '📄';

  uploadZone.style.display = 'none';
  preview.style.display = 'block';
  preview.innerHTML = `<div class="uploaded-file-row"><div class="uploaded-file-icon">${icon}</div><span class="uploaded-file-name">${file.name}</span><span class="uploaded-file-size">${size}</span><button class="btn btn-ghost btn-sm" style="color:var(--rust);" onclick="removeCredentialFile(this)">✕</button></div>`;
  markUnsaved();
}

function handleCredentialDrop(event) {
  event.preventDefault();
  event.stopPropagation();
  const zone = event.currentTarget;
  if (zone) zone.classList.remove('drag-over');
  const files = event.dataTransfer?.files;
  if (!files || !files.length) return;
  const input = document.getElementById('new-cred-file');
  if (!input) return;
  const dataTransfer = new DataTransfer();
  dataTransfer.items.add(files[0]);
  input.files = dataTransfer.files;
  input.dispatchEvent(new Event('change', { bubbles: true }));
}

function removeCredentialFile(button) {
  const preview = document.getElementById('new-cred-file-preview');
  const uploadZone = document.getElementById('new-cred-upload-zone');
  const input = document.getElementById('new-cred-file');
  if (preview) {
    preview.innerHTML = '';
    preview.style.display = 'none';
  }
  if (uploadZone) uploadZone.style.display = 'flex';
  if (input) input.value = '';
}

// ── AVATAR PREVIEW ──
function handleAvatar(input) {
  if(input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      const avatars = document.querySelectorAll('.avatar-xl, .avatar-md.preview');
      avatars.forEach(a => { a.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`; });
    };
    reader.readAsDataURL(input.files[0]);
    markUnsaved();
  }
}

populateCountrySelect();

function toggleDD() {
  document.getElementById('user-dd')?.classList.toggle('hidden');
}
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown')) document.getElementById('user-dd')?.classList.add('hidden');
});

// ── SCROLL SPY ──
const sections = ['sec-identity','sec-about','sec-skills','sec-credentials','sec-portfolio','sec-rates','sec-privacy'];
const links = document.querySelectorAll('.edit-nav-link[href^="#"]');
function updateActiveNav() {
  let current = sections[0];
  sections.forEach(id => {
    const el = document.getElementById(id);
    if(el && el.getBoundingClientRect().top < 120) current = id;
  });
  links.forEach(l => l.classList.toggle('active', l.getAttribute('href')==='#'+current));
}
document.querySelector('.edit-main').addEventListener('scroll', updateActiveNav);
window.addEventListener('scroll', updateActiveNav);

// ── WARN BEFORE LEAVE ──
window.addEventListener('beforeunload', e => { if(unsaved){ e.preventDefault(); e.returnValue=''; } });
