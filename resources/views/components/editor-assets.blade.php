{{--
    CKEditor 5 + FAQ repeater – fully self-contained (does not depend on public/js/admin.js),
    printed once per page by components/editor.blade.php.
--}}
<style>
.editor-wrap .ck.ck-editor__editable_inline{min-height:180px;max-height:480px}
.editor-wrap.invalid .ck.ck-editor__main>.ck-editor__editable{border-color:#dc2626}
.editor-wrap .ck-content h2{font-size:20px}.editor-wrap .ck-content h3{font-size:17px}.editor-wrap .ck-content h4{font-size:15px}
.ck.ck-balloon-panel{z-index:1000}
.faq-row{border:1px solid #e4e7ec;border-radius:8px;padding:12px;margin-bottom:10px;background:#fafbfc}
.faq-row-head{display:flex;gap:8px;align-items:center;margin-bottom:8px}
.faq-row-head .input{flex:1}
.faq-row .field{margin:0}
.faq-row .error{margin-top:4px}
</style>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
(function () {
  var TOOLBARS = {
    full: ['heading', '|', 'bold', 'italic', 'link', '|', 'bulletedList', 'numberedList', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', '|', 'undo', 'redo'],
    basic: ['bold', 'italic', 'link', '|', 'bulletedList', 'numberedList', '|', 'undo', 'redo']
  };
  var editors = [];

  function initEditors(root) {
    if (!window.ClassicEditor) { console.warn('CKEditor could not be loaded from cdn.ckeditor.com'); return; }
    (root || document).querySelectorAll('textarea[data-editor]').forEach(function (ta) {
      if (ta.getAttribute('data-editor-ready')) return;
      ta.setAttribute('data-editor-ready', '1');
      ClassicEditor.create(ta, {
        toolbar: TOOLBARS[ta.getAttribute('data-editor')] || TOOLBARS.full,
        heading: { options: [
          { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
          { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
          { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
          { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
        ] },
        table: { contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'] },
        link: { addTargetToExternalLinks: true }
      }).then(function (ed) {
        editors.push({ ta: ta, ed: ed });
        ed.model.document.on('change:data', function () { ta.value = ed.getData(); });
      }).catch(function (err) { console.error('CKEditor', err); });
    });
  }

  function destroyEditors(root) {
    editors = editors.filter(function (x) {
      if (root.contains(x.ta)) { x.ed.destroy(); return false; }
      return true;
    });
  }

  function initRepeaters() {
    document.querySelectorAll('[data-repeater]').forEach(function (rep) {
      if (rep.getAttribute('data-repeater-ready')) return;
      rep.setAttribute('data-repeater-ready', '1');
      var list = rep.querySelector('[data-repeater-list]');
      var tpl = rep.querySelector('template[data-repeater-template]');
      var addBtn = rep.querySelector('[data-repeater-add]');
      var empty = rep.querySelector('[data-repeater-empty]');
      var next = parseInt(rep.getAttribute('data-next') || list.children.length, 10) || 0;
      var refresh = function () { if (empty) empty.hidden = list.children.length > 0; };

      addBtn.addEventListener('click', function (e) {
        e.preventDefault();
        var html = tpl.innerHTML.split('__INDEX__').join(String(next++));
        list.insertAdjacentHTML('beforeend', html);
        var row = list.lastElementChild;
        initEditors(row);
        refresh();
        var q = row.querySelector('input'); if (q) q.focus();
      });
      list.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-repeater-remove]');
        if (!btn) return;
        e.preventDefault();
        var row = btn.closest('[data-repeater-row]');
        destroyEditors(row); row.remove(); refresh();
      });
      refresh();
    });
  }

  function boot() {
    initEditors(document);
    initRepeaters();
    // Right before the form posts, write each editor into its textarea BASE64-ENCODED.
    // The hosting server's ModSecurity blocks requests containing raw HTML ("Not Acceptable!");
    // Laravel decodes it again (App\Support\EditorInput). Registered on document so it runs
    // after CKEditor's own submit handler.
    document.addEventListener('submit', function (e) {
      var f = e.target;
      f.querySelectorAll('textarea[data-editor]').forEach(function (ta) {
        var x = editors.filter(function (y) { return y.ta === ta; })[0];
        var html = x ? x.ed.getData() : ta.value;
        if (html.indexOf('__b64__:') === 0) return;
        ta.value = '__b64__:' + btoa(unescape(encodeURIComponent(html)));
      });
    });
    // coming back with the browser's Back button: show the HTML again, not the encoded text
    window.addEventListener('pageshow', function () {
      document.querySelectorAll('textarea[data-editor]').forEach(function (ta) {
        if (ta.value.indexOf('__b64__:') === 0) {
          try { ta.value = decodeURIComponent(escape(atob(ta.value.slice(8)))); } catch (err) {}
        }
      });
    });
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot); else boot();
})();
</script>
