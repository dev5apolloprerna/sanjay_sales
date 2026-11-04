<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $items = Video::when($request->q, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->orderBy('sort_order')->latest()->paginate(15)->withQueryString();

        return view('admin.videos.index', compact('items'));
    }

    public function create()
    {
        return view('admin.videos.form', ['item' => new Video(['status' => true, 'sort_order' => 0])]);
    }

    public function store(Request $request)
    {
        Video::create($this->validated($request));

        return redirect()->route('admin.videos.index')->with('success', 'Video added successfully.');
    }

    public function edit(Video $video)
    {
        return view('admin.videos.form', ['item' => $video]);
    }

    public function update(Request $request, Video $video)
    {
        $video->update($this->validated($request));

        return redirect()->route('admin.videos.index')->with('success', 'Video updated successfully.');
    }

    public function destroy(Video $video)
    {
        $video->delete();

        return back()->with('success', 'Video deleted.');
    }

    private function validated(Request $request): array
    {
        $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'youtube_url' => ['required', 'url', 'regex:~(youtube\.com/(watch\?.*v=|embed/|shorts/|live/)|youtu\.be/)[\w-]{11}~i'],
            'sort_order' => ['nullable', 'integer'],
        ], [
            'youtube_url.required' => 'Please paste the YouTube video link.',
            'youtube_url.regex' => 'Please enter a valid YouTube video link, e.g. https://www.youtube.com/watch?v=XXXXXXXXXXX',
        ]);

        return [
            'title' => $request->title,
            'youtube_url' => $request->youtube_url,
            'sort_order' => (int) $request->sort_order,
            'status' => $request->boolean('status'),
        ];
    }
}
