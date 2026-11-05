<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use App\Support\Upload;
use Illuminate\Http\Request;

/** PDF catalogues for the website's /download page. */
class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $items = Catalogue::when($request->q, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->orderBy('sort_order')->latest()->paginate(20)->withQueryString();

        return view('admin.catalogues.index', compact('items'));
    }

    public function create()
    {
        return view('admin.catalogues.form', ['item' => new Catalogue(['status' => true, 'sort_order' => 0])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['file'] = Upload::store($request->file('file'), 'catalogues');
        $data['image'] = Upload::store($request->file('image'), 'catalogues');
        Catalogue::create($data);

        return redirect()->route('admin.catalogues.index')->with('success', 'Catalogue added successfully.');
    }

    public function edit(Catalogue $catalogue)
    {
        return view('admin.catalogues.form', ['item' => $catalogue]);
    }

    public function update(Request $request, Catalogue $catalogue)
    {
        $data = $this->validated($request, false);
        $data['file'] = Upload::replace($request->file('file'), $catalogue->file, 'catalogues');
        $data['image'] = Upload::replace($request->file('image'), $catalogue->image, 'catalogues', $request->boolean('remove_image'));
        $catalogue->update($data);

        return redirect()->route('admin.catalogues.index')->with('success', 'Catalogue updated successfully.');
    }

    public function destroy(Catalogue $catalogue)
    {
        Upload::delete($catalogue->file);
        Upload::delete($catalogue->image);
        $catalogue->delete();

        return back()->with('success', 'Catalogue deleted.');
    }

    private function validated(Request $request, bool $fileRequired): array
    {
        $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'sort_order' => ['nullable', 'integer'],
            'file' => [$fileRequired ? 'required' : 'nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,zip', 'max:30720'],
            'image' => ['nullable', 'image', 'max:5120'],
        ], ['file.required' => 'Please choose the catalogue file.', 'file.mimes' => 'Allowed files: PDF, Word, Excel or ZIP.', 'file.max' => 'The file must be 30MB or smaller.']);

        return ['title' => $request->title, 'sort_order' => (int) $request->sort_order, 'status' => $request->boolean('status')];
    }
}
