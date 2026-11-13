@extends('admin.layout')
@section('title', 'Technical Specifications – '.$product->name)
@section('content')
<div class="page-head">
    <a class="icon-btn" href="{{ route('admin.products.index') }}" title="Back to products"><x-icon name="back" /></a>
    <h1>Technical Specifications<br>
        <small class="muted" style="font-size:13px;font-weight:400">{{ $product->name }} ·
            <a style="color:var(--accent)" href="{{ route('admin.products.edit', $product) }}">Edit product</a> ·
            <a style="color:var(--accent)" href="{{ route('admin.products.faqs.index', $product) }}">FAQs</a></small></h1>
</div>

<form class="card form-card" method="POST" action="{{ route('admin.products.specifications.update', $product) }}" id="spec-form" novalidate>
    @csrf @method('PUT')
    @if($errors->any())<div class="alert">Please fix the highlighted fields below.</div>@endif
    @if($imported)<div class="alert info">These rows were read from the old “Technical Specifications” text. Please check them and click <b>Save</b>.</div>@endif
    <p class="muted" style="margin-top:0">Add a <b>group</b> for each section shown on the product page (e.g. <i>General</i>, <i>Cutting Capacity</i>, <i>Power &amp; Drive</i>), then add <b>Label → Value</b> rows inside it. Each product has its own specifications. Use ↑ ↓ to change the order.</p>

    <div id="spec-groups">
        @foreach($groups as $g => $group)
            @include('admin.product-specs.group', ['g' => $g, 'group' => $group])
        @endforeach
    </div>
    <div class="hint" id="spec-empty" @if(count($groups)) hidden @endif>No specifications yet – add a group.</div>
    <button type="button" class="btn ghost" id="add-group" style="margin:6px 0 18px"><x-icon name="plus" width="16" height="16" /> Add Group</button>

    <div class="form-actions">
        <button class="btn" type="submit">Save Specifications</button>
        <a class="btn ghost" href="{{ route('admin.products.index') }}">Cancel</a>
    </div>
</form>

<template id="tpl-group">@include('admin.product-specs.group', ['g' => null, 'group' => ['group' => '', 'items' => [['label' => '', 'value' => '']]]])</template>
<template id="tpl-row">@include('admin.product-specs.row', ['g' => null, 'i' => null, 'item' => ['label' => '', 'value' => '']])</template>

<style>
.spec-group{border:1px solid var(--line);border-radius:10px;margin-bottom:14px;background:#fafbfc}
.spec-group-head{display:flex;gap:8px;align-items:center;padding:12px;border-bottom:1px solid var(--line)}
.spec-group-head .input{flex:1;font-weight:600}
.spec-rows{padding:12px}
.spec-row{display:grid;grid-template-columns:1fr 2fr auto;gap:8px;margin-bottom:8px;align-items:start}
.spec-row-head{display:grid;grid-template-columns:1fr 2fr auto;gap:8px;font-size:12px;font-weight:600;color:var(--muted);margin-bottom:6px}
.spec-row .btns,.spec-group-head .btns{display:flex;gap:4px}
.spec-group .error{margin:2px 0 6px}
.alert.info{background:#eff6ff;border-color:#bfdbfe;color:#1e3a8a}
@media(max-width:700px){.spec-row,.spec-row-head{grid-template-columns:1fr}}
</style>
<script>
(function () {
  var box = document.getElementById('spec-groups'), empty = document.getElementById('spec-empty');
  var tplGroup = document.getElementById('tpl-group'), tplRow = document.getElementById('tpl-row');
  var refresh = function () { empty.hidden = box.children.length > 0; };

  document.getElementById('add-group').addEventListener('click', function () {
    box.insertAdjacentHTML('beforeend', tplGroup.innerHTML);
    box.lastElementChild.querySelector('input').focus();
    refresh();
  });

  box.addEventListener('click', function (e) {
    var b = e.target.closest('button[data-act]');
    if (!b) return;
    e.preventDefault();
    var act = b.getAttribute('data-act');
    var group = b.closest('.spec-group'), row = b.closest('.spec-row');
    var el = row || group;
    if (act === 'add-row') {
      group.querySelector('.spec-row-list').insertAdjacentHTML('beforeend', tplRow.innerHTML);
      group.querySelector('.spec-row-list').lastElementChild.querySelector('input').focus();
    } else if (act === 'remove') {
      if (!row && !confirm('Remove this whole group and all its rows?')) return;
      el.remove(); refresh();
    } else if (act === 'up' && el.previousElementSibling) {
      el.parentNode.insertBefore(el, el.previousElementSibling);
    } else if (act === 'down' && el.nextElementSibling) {
      el.parentNode.insertBefore(el.nextElementSibling, el);
    }
  });

  // give every field its final name in on-screen order: groups[g][group], groups[g][items][i][label|value]
  document.getElementById('spec-form').addEventListener('submit', function () {
    [].forEach.call(box.querySelectorAll('.spec-group'), function (group, g) {
      group.querySelector('[data-f="group"]').name = 'groups[' + g + '][group]';
      [].forEach.call(group.querySelectorAll('.spec-row'), function (row, i) {
        row.querySelector('[data-f="label"]').name = 'groups[' + g + '][items][' + i + '][label]';
        row.querySelector('[data-f="value"]').name = 'groups[' + g + '][items][' + i + '][value]';
      });
    });
  });
  refresh();
})();
</script>
@endsection
