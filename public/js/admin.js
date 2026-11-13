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

  /* ---- CKEditor 5: <textarea data-editor="full|basic"> (see components/editor.blade.php) ---- */
  const TOOLBARS = {
    full: ['heading', '|', 'bold', 'italic', 'link', '|', 'bulletedList', 'numberedList', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', '|', 'undo', 'redo'],
    basic: ['bold', 'italic', 'link', '|', 'bulletedList', 'numberedList', '|', 'undo', 'redo'],
  };
  const editors = new Map();
  window.initEditors = (root = document) => {
    if (!window.ClassicEditor) return; // CDN blocked -> plain textarea still works
    $$('textarea[data-editor]', root).forEach((ta) => {
      if (ta.dataset.editorReady || ta.closest('template')) return;
      ta.dataset.editorReady = 1;
      ClassicEditor.create(ta, {
        toolbar: TOOLBARS[ta.dataset.editor] || TOOLBARS.full,
        heading: { options: [
          { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
          { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
          { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
          { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
        ] },
        table: { contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'] },
        link: { addTargetToExternalLinks: true },
      }).then((ed) => {
        editors.set(ta, ed);
        ed.model.document.on('change:data', () => { ta.value = ed.getData(); });
      }).catch((err) => console.error('CKEditor', err));
    });
  };
  window.destroyEditors = (root) => $$('textarea[data-editor]', root).forEach((ta) => { editors.get(ta)?.destroy(); editors.delete(ta); });
  initEditors();
  // make sure every editor writes back before the form posts
  $$('form').forEach((f) => f.addEventListener('submit', () => editors.forEach((ed, ta) => { if (f.contains(ta)) ta.value = ed.getData(); })));

  /* ---- repeater (FAQ rows): [data-repeater] > [data-repeater-list], <template data-repeater-template>, [data-repeater-add] ---- */
  $$('[data-repeater]').forEach((rep) => {
    const list = $('[data-repeater-list]', rep), tpl = $('template[data-repeater-template]', rep);
    let next = Number(rep.dataset.next || list.children.length);
    const empty = $('[data-repeater-empty]', rep);
    const refresh = () => { if (empty) empty.hidden = list.children.length > 0; };
    $('[data-repeater-add]', rep).addEventListener('click', () => {
      const html = tpl.innerHTML.replaceAll('__INDEX__', next++);
      list.insertAdjacentHTML('beforeend', html);
      const row = list.lastElementChild;
      initEditors(row); refresh();
      $('input', row)?.focus();
    });
    list.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-repeater-remove]');
      if (!btn) return;
      const row = btn.closest('[data-repeater-row]');
      destroyEditors(row); row.remove(); refresh();
    });
    refresh();
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
