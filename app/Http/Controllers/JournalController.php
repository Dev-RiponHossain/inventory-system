<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $query = JournalEntry::with('lines')->latest('entry_date');

        if ($request->filled('from_date')) {
            $query->whereDate('entry_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('entry_date', '<=', $request->to_date);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $entries = $query->paginate(15);
        return view('journal.index', compact('entries'));
    }

    public function show(JournalEntry $journalEntry)
    {
        $journalEntry->load('lines');
        return view('journal.show', compact('journalEntry'));
    }
}