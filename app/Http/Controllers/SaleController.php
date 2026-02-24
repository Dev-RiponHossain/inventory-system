<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('product')->latest()->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('current_stock', '>', 0)->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'    => 'required|exists:products,id',
            'customer_name' => 'required|string|max:255',
            'quantity'      => 'required|integer|min:1',
            'discount'      => 'required|numeric|min:0',
            'vat_rate'      => 'required|numeric|min:0|max:100',
            'amount_paid'   => 'required|numeric|min:0',
            'sale_date'     => 'required|date',
            'notes'         => 'nullable|string',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->current_stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Insufficient stock! Available: ' . $product->current_stock]);
        }

        DB::transaction(function () use ($validated, $product) {
            $unitPrice    = $product->sell_price;
            $grossAmount  = $validated['quantity'] * $unitPrice;
            $discount     = $validated['discount'];
            $netAmount    = $grossAmount - $discount;
            $vatAmount    = round($netAmount * ($validated['vat_rate'] / 100), 2);
            $totalPayable = $netAmount + $vatAmount;
            $amountPaid   = $validated['amount_paid'];
            $dueAmount    = $totalPayable - $amountPaid;
            $cogs         = $validated['quantity'] * $product->purchase_price;

            $paymentStatus = match(true) {
                $amountPaid >= $totalPayable => 'paid',
                $amountPaid > 0             => 'partial',
                default                     => 'due',
            };

            $invoiceNo = 'INV-' . date('Ymd') . '-' . str_pad(Sale::count() + 1, 4, '0', STR_PAD_LEFT);

            $sale = Sale::create([
                'invoice_no'     => $invoiceNo,
                'product_id'     => $product->id,
                'customer_name'  => $validated['customer_name'],
                'quantity'       => $validated['quantity'],
                'unit_price'     => $unitPrice,
                'gross_amount'   => $grossAmount,
                'discount'       => $discount,
                'net_amount'     => $netAmount,
                'vat_rate'       => $validated['vat_rate'],
                'vat_amount'     => $vatAmount,
                'total_payable'  => $totalPayable,
                'amount_paid'    => $amountPaid,
                'due_amount'     => max(0, $dueAmount),
                'cogs'           => $cogs,
                'sale_date'      => $validated['sale_date'],
                'payment_status' => $paymentStatus,
                'notes'          => $validated['notes'] ?? null,
            ]);

            $product->decrement('current_stock', $validated['quantity']);

            // Journal Entry 1: Sale
            $entryNo = 'JE-' . date('Ymd') . '-' . str_pad(JournalEntry::count() + 1, 4, '0', STR_PAD_LEFT);
            $je1 = JournalEntry::create([
                'entry_no'   => $entryNo,
                'reference'  => $invoiceNo,
                'narration'  => "Sale of {$validated['quantity']} unit(s) of {$product->name} to {$validated['customer_name']}",
                'entry_date' => $validated['sale_date'],
                'type'       => 'sale',
            ]);
            JournalEntryLine::create(['journal_entry_id' => $je1->id, 'account_name' => 'Accounts Receivable', 'account_type' => 'asset',     'debit' => $totalPayable, 'credit' => 0]);
            JournalEntryLine::create(['journal_entry_id' => $je1->id, 'account_name' => 'Sales Revenue',       'account_type' => 'income',    'debit' => 0, 'credit' => $netAmount]);
            JournalEntryLine::create(['journal_entry_id' => $je1->id, 'account_name' => 'VAT Payable',         'account_type' => 'liability', 'debit' => 0, 'credit' => $vatAmount]);
            if ($discount > 0) {
                JournalEntryLine::create(['journal_entry_id' => $je1->id, 'account_name' => 'Discount Allowed', 'account_type' => 'expense', 'debit' => $discount, 'credit' => 0]);
                JournalEntryLine::create(['journal_entry_id' => $je1->id, 'account_name' => 'Sales (Gross)',    'account_type' => 'income',  'debit' => 0, 'credit' => $discount]);
            }

            // Journal Entry 2: COGS
            $je2EntryNo = 'JE-' . date('Ymd') . '-' . str_pad(JournalEntry::count() + 1, 4, '0', STR_PAD_LEFT);
            $je2 = JournalEntry::create([
                'entry_no'   => $je2EntryNo,
                'reference'  => $invoiceNo,
                'narration'  => "COGS — {$validated['quantity']} unit(s) of {$product->name} @ {$product->purchase_price} TK",
                'entry_date' => $validated['sale_date'],
                'type'       => 'sale',
            ]);
            JournalEntryLine::create(['journal_entry_id' => $je2->id, 'account_name' => 'Cost of Goods Sold (COGS)', 'account_type' => 'expense', 'debit' => $cogs, 'credit' => 0]);
            JournalEntryLine::create(['journal_entry_id' => $je2->id, 'account_name' => 'Inventory',                 'account_type' => 'asset',   'debit' => 0,     'credit' => $cogs]);

            // Journal Entry 3: Cash Received
            if ($amountPaid > 0) {
                $je3EntryNo = 'JE-' . date('Ymd') . '-' . str_pad(JournalEntry::count() + 1, 4, '0', STR_PAD_LEFT);
                $je3 = JournalEntry::create([
                    'entry_no'   => $je3EntryNo,
                    'reference'  => $invoiceNo,
                    'narration'  => "Cash received from {$validated['customer_name']} against {$invoiceNo}",
                    'entry_date' => $validated['sale_date'],
                    'type'       => 'payment',
                ]);
                JournalEntryLine::create(['journal_entry_id' => $je3->id, 'account_name' => 'Cash / Bank',         'account_type' => 'asset', 'debit' => $amountPaid, 'credit' => 0]);
                JournalEntryLine::create(['journal_entry_id' => $je3->id, 'account_name' => 'Accounts Receivable', 'account_type' => 'asset', 'debit' => 0,           'credit' => $amountPaid]);
            }
        });

        return redirect()->route('sales.index')
            ->with('success', 'Sale recorded & Journal Entries created successfully!');
    }

    public function show(Sale $sale)
    {
        $sale->load('product');
        $journalEntries = JournalEntry::with('lines')
            ->where('reference', $sale->invoice_no)
            ->get();
        return view('sales.show', compact('sale', 'journalEntries'));
    }
}