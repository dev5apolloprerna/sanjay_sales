<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Support\Upload;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $items = Client::orderBy('sort_order')->latest()->paginate(36);

        return view('admin.clients.index', compact('items'));
    }

    /** Upload page: several logos can be selected at once. */
    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'images' => ['required', 'array', 'max:30'],
            'images.*' => ['image', 'max:5120'],
        ], ['images.required' => 'Please choose at least one image.', 'images.*.image' => 'Each file must be an image.', 'images.*.max' => 'Each image must be 5MB or smaller.']);

        $order = (int) Client::max('sort_order');
        foreach ($request->file('images') as $file) {
            Client::create([
                'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), // editable later
                'image' => Upload::store($file, 'clients'),
                'sort_order' => ++$order,
                'status' => true,
            ]);
        }
        $n = count($request->file('images'));

        return redirect()->route('admin.clients.index')->with('success', "{$n} client logo(s) uploaded successfully.");
    }

    public function edit(Client $client)
    {
        return view('admin.clients.edit', ['item' => $client]);
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:150'],
            'sort_order' => ['nullable', 'integer'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);
        $data['image'] = Upload::replace($request->file('image'), $client->image, 'clients');
        $data['status'] = $request->boolean('status');
        $data['sort_order'] = (int) $request->sort_order;
        $client->update($data);

        return redirect()->route('admin.clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        Upload::delete($client->image);
        $client->delete();

        return back()->with('success', 'Client logo deleted.');
    }
}
