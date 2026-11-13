<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

/** Homepage "Numbers That Define Us" counters (Years Experience, Products Supplied …). */
class HomeStatController extends Controller
{
    public function edit()
    {
        $stats = Setting::homeStats();
        // always show 4 rows in the form
        $stats['items'] = array_pad($stats['items'], 4, ['value' => null, 'suffix' => '+', 'label' => '']);

        return view('admin.home-stats.form', ['stats' => $stats]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'subtitle' => ['nullable', 'string', 'max:300'],
            'items' => ['required', 'array', 'max:8'],
            'items.*.label' => ['nullable', 'string', 'max:60', 'required_with:items.*.value'],
            'items.*.value' => ['nullable', 'integer', 'min:0', 'max:99999999', 'required_with:items.*.label'],
            'items.*.suffix' => ['nullable', 'string', 'max:5'],
        ], [
            'items.*.label.required_with' => 'Enter a label for this counter.',
            'items.*.value.required_with' => 'Enter a number for this counter.',
            'items.*.value.integer' => 'Use whole numbers only (e.g. 40).',
        ]);

        $items = collect($data['items'])
            ->filter(fn ($i) => filled($i['label'] ?? null))
            ->map(fn ($i) => ['value' => (int) $i['value'], 'suffix' => (string) ($i['suffix'] ?? ''), 'label' => trim($i['label'])])
            ->values()->all();

        Setting::put('home_stats', [
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
            'items' => $items,
        ]);

        return redirect()->route('admin.home-stats.edit')->with('success', 'Home counters updated successfully.');
    }
}
