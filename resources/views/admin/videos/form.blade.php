@extends('admin.layout')
@section('title', $item->exists ? 'Edit Video' : 'Add Video')
@section('content')
<x-form-page :title="$item->exists ? 'Edit Video' : 'Add Video'" :editing="$item->exists" :back="route('admin.videos.index')"
    :action="$item->exists ? route('admin.videos.update', $item) : route('admin.videos.store')">
    <x-field name="title" label="Video Title" :value="$item->title" required full />
    <x-field name="youtube_url" type="url" label="YouTube URL" :value="$item->youtube_url" required full placeholder="https://www.youtube.com/watch?v=…"
        hint="Paste the YouTube video link — the thumbnail is taken from YouTube automatically" />
    @if($item->embed_url)
        <div class="field full"><label>Preview</label>
            <iframe src="{{ $item->embed_url }}" style="width:100%;max-width:480px;aspect-ratio:16/9;border:0;border-radius:8px" allowfullscreen loading="lazy"></iframe></div>
    @endif
    <x-field name="sort_order" type="number" label="Display Order" :value="$item->sort_order" />
    <x-switch name="status" label="Status" :checked="$item->status" />
</x-form-page>
@endsection
