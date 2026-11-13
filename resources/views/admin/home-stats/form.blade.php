@extends('admin.layout')
@section('title', 'Home Counters')
@section('content')
<x-form-page title="Home Counters – “Numbers That Define Us”" editing :back="route('admin.dashboard')" :action="route('admin.home-stats.update')">
    <x-field name="title" label="Section Heading" :value="$stats['title']" required full />
    <x-field name="subtitle" label="Sub Heading (optional)" :value="$stats['subtitle']" full />

    <div class="field full">
        <label>Counters</label>
        <div class="stat-rows">
            <div class="stat-row head"><span>Label</span><span>Number</span><span>Suffix</span></div>
            @foreach($stats['items'] as $i => $s)
                <div class="stat-row">
                    <input class="input @error("items.$i.label") invalid @enderror" name="items[{{ $i }}][label]" value="{{ old("items.$i.label", $s['label'] ?? '') }}" placeholder="e.g. Years Experience">
                    <input class="input @error("items.$i.value") invalid @enderror" type="number" min="0" name="items[{{ $i }}][value]" value="{{ old("items.$i.value", $s['value'] ?? '') }}" placeholder="40">
                    <input class="input" name="items[{{ $i }}][suffix]" value="{{ old("items.$i.suffix", $s['suffix'] ?? '+') }}" placeholder="+" maxlength="5">
                </div>
                @foreach(['label', 'value'] as $f)
                    @error("items.$i.$f")<div class="error">{{ $message }}</div>@enderror
                @endforeach
            @endforeach
        </div>
        <div class="hint">Shown on the homepage as e.g. <b>40+</b> Years Experience. Leave a label empty to hide that counter.</div>
    </div>
</x-form-page>
@endsection
