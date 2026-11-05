<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

/** Read-only list of "Inquire Now" submissions from the website. */
class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $items = Inquiry::with('product:id,name')
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%")->orWhere('phone', 'like', "%{$s}%")))
            ->when($request->status === 'unread', fn ($q) => $q->where('is_read', false))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.inquiries.index', ['items' => $items, 'unread' => Inquiry::where('is_read', false)->count()]);
    }

    public function show(Inquiry $inquiry)
    {
        if (! $inquiry->is_read) {
            $inquiry->update(['is_read' => true]);
        }

        return view('admin.inquiries.show', ['item' => $inquiry->load('product:id,name,slug')]);
    }

    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')->with('success', 'Inquiry deleted.');
    }
}
