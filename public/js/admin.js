/* Sanjay Sales admin – small vanilla JS helpers (no build step needed) */
document.addEventListener('DOMContentLoaded', () => {
  const $ = (s, el = document) => el.querySelector(s);
  const $$ = (s, el = document) => [...el.querySelectorAll(s)];

  /* ---- sidebar (mobile) + user menu ---- */
  const side = $('.side'), overlay = $('.overlay');
  $('.burger')?.addEventListener('click', () => { side.classList.add('open'); overlay.classList.add('show'); });
  overlay?.addEventListener('click', () => { side.classList.remove('open'); overlay.classList.remove('show'); });
  const userBtn = $('.user > button'), menu = $('.user .menu');
  userBtn?.addEventListener('click', (e) => { e.stopPropagation(); menu.hidden = !menu.hidden; });
  document.addEventListener('click', () => menu && (menu.hidden = true));

  /* ---- toasts fade out ---- */
  $$('.toast').forEach((t) => setTimeout(() => { t.classList.add('hide'); setTimeout(() => t.remove(), 500); }, 3500));

  /* ---- slug auto-fill: <input data-slug-from="name"> ---- */
  const slugify = (s) => s.toLowerCase().trim().replace(/&/g, 'and').replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
  $$('[data-slug-from]').forEach((slug) => {
    const src = document.querySelector(`[name="${slug.dataset.slugFrom}"]`);
    let touched = slug.value !== '';
    slug.addEventListener('input', () => { touched = slug.value !== ''; });
    slug.addEventListener('blur', () => { slug.value = slugify(slug.value); });
    src?.addEventListener('input', () => { if (!touched) slug.value = slugify(src.value); });
  });

  /* ---- switch label text: <label class="switch" data-on="Active" data-off="Inactive"> ---- */
  $$('.switch[data-on]').forEach((sw) => {
    const cb = $('input', sw), txt = $('em', sw);
    const upd = () => { if (txt) txt.textContent = cb.checked ? sw.dataset.on : sw.dataset.off; };
    cb.addEventListener('change', upd); upd();
  });

  /* ---- single file preview: <div data-file-preview> containing input[type=file] + .previews ---- */
  $$('[data-file-preview]').forEach((box) => {
    const input = $('input[type=file]', box), list = $('.previews', box), kind = box.dataset.filePreview;
    const removeFlag = $('input[data-remove-flag]', box);
    const render = (src, isNew) => {
      list.innerHTML = '';
      if (!src) return;
      const pv = document.createElement('div');
      pv.className = 'pv' + (box.dataset.wide !== undefined || kind === 'video' ? ' wide' : '');
      pv.innerHTML = (kind === 'video' ? `<video src="${src}" controls></video>` : `<img src="${src}" alt="">`) +
        (isNew ? '<span class="tag">new</span>' : '') + '<button type="button" title="Remove">×</button>';
      $('button', pv).onclick = () => {
        input.value = ''; render(null);
        if (removeFlag) removeFlag.value = '1';
      };
      list.appendChild(pv);
    };
    if (box.dataset.current) render(box.dataset.current, false);
    input.addEventListener('change', () => {
      const f = input.files[0];
      if (!f) return;
      if (removeFlag) removeFlag.value = '0';
      render(URL.createObjectURL(f), true);
    });
  });

  /* ---- multiple images preview (products) ---- */
  $$('[data-multi-preview]').forEach((box) => {
    const input = $('input[type=file]', box), list = $('.previews.new', box);
    let files = [];
    const sync = () => {
      const dt = new DataTransfer(); files.forEach((f) => dt.items.add(f)); input.files = dt.files;
      list.innerHTML = '';
      files.forEach((f, i) => {
        const pv = document.createElement('div'); pv.className = 'pv';
        pv.innerHTML = `<img src="${URL.createObjectURL(f)}" alt=""><span class="tag">new</span><button type="button">×</button>`;
        $('button', pv).onclick = () => { files.splice(i, 1); sync(); };
        list.appendChild(pv);
      });
    };
    input.addEventListener('change', () => { files = files.concat([...input.files]); sync(); });
  });
  // existing product images: ticking the × marks them for removal
  $$('.pv label.rm input').forEach((cb) => cb.addEventListener('change', () => cb.closest('.pv').classList.toggle('removed', cb.checked)));

  /* ---- simple rich text editor: <div data-rte="textareaName"> ---- */
  $$('[data-rte]').forEach((wrap) => {
    const ta = document.querySelector(`textarea[name="${wrap.dataset.rte}"]`), area = $('.rte-area', wrap);
    area.innerHTML = ta.value;
    const sync = () => { ta.value = area.innerHTML; };
    area.addEventListener('input', sync);
    $$('.rte-bar button', wrap).forEach((b) => b.addEventListener('mousedown', (e) => {
      e.preventDefault();
      const [cmd, val] = b.dataset.cmd.split(':');
      if (cmd === 'createLink') { const u = prompt('Link URL'); if (!u) return; document.execCommand(cmd, false, u); }
      else document.execCommand(cmd, false, val ? `<${val}>` : null);
      area.focus(); sync();
    }));
    ta.form?.addEventListener('submit', sync);
  });

  /* ---- dependent dropdown: <select data-depends-on="parentName"> with <option data-parent="id"> ---- */
  $$('select[data-depends-on]').forEach((child) => {
    const parent = child.form?.querySelector(`[name="${child.dataset.dependsOn}"]`);
    if (!parent) return;
    const opts = [...child.options].filter((o) => o.dataset.parent);
    const hint = child.parentElement.querySelector('[data-empty-hint]');
    const filter = () => {
      let any = false;
      opts.forEach((o) => {
        const show = parent.value !== '' && o.dataset.parent === parent.value;
        o.hidden = !show; o.disabled = !show; any = any || show;
        if (!show && o.selected) child.value = '';
      });
      child.disabled = parent.value === '' || !any;
      if (hint) hint.hidden = !(parent.value !== '' && !any);
    };
    parent.addEventListener('change', filter); filter();
  });

  /* ---- delete confirmation modal ---- */
  const modal = $('#confirm-modal');
  let pending = null;
  $$('form[data-confirm]').forEach((f) => f.addEventListener('submit', (e) => {
    if (f.dataset.confirmed) return;
    e.preventDefault(); pending = f;
    $('#confirm-text').textContent = f.dataset.confirm;
    modal.hidden = false;
  }));
  $('#confirm-yes')?.addEventListener('click', () => { if (pending) { pending.dataset.confirmed = 1; pending.submit(); } });
  $$('[data-close-modal]').forEach((b) => b.addEventListener('click', () => { modal.hidden = true; pending = null; }));
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && modal) modal.hidden = true; });

  /* ---- show/hide password ---- */
  $$('[data-toggle-pw]').forEach((b) => b.addEventListener('click', () => {
    const inputs = b.dataset.togglePw ? $$(b.dataset.togglePw) : [b.previousElementSibling];
    const show = inputs[0].type === 'password';
    inputs.forEach((i) => (i.type = show ? 'text' : 'password'));
    if (b.tagName === 'BUTTON') b.textContent = show ? 'HIDE' : 'SHOW';
  }));

  /* ---- prevent double submit ---- */
  $$('form:not([data-confirm])').forEach((f) => f.addEventListener('submit', () => {
    const btn = $('button[type=submit], button:not([type])', f);
    if (btn) setTimeout(() => { btn.disabled = true; btn.innerHTML = '<span class="spinner"></span>'; }, 0);
  }));
});
