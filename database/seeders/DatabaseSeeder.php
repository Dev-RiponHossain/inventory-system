<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Product ───────────────────────────────────────────────
        $product = Product::create([
            'name'           => 'Sample Product',
            'sku'            => 'PRD-001',
            'purchase_price' => 100.00,
            'sell_price'     => 200.00,
            'opening_stock'  => 50,
            'current_stock'  => 40,
            'description'    => 'Demo product as per assignment scenario',
        ]);

        // ── Sale ──────────────────────────────────────────────────
        $sale = Sale::create([
            'invoice_no'     => 'INV-' . date('Ymd') . '-0001',
            'product_id'     => $product->id,
            'customer_name'  => 'Demo Customer',
            'quantity'       => 10,
            'unit_price'     => 200.00,
            'gross_amount'   => 2000.00,
            'discount'       => 50.00,
            'net_amount'     => 1950.00,
            'vat_rate'       => 5,
            'vat_amount'     => 97.50,
            'total_payable'  => 2047.50,
            'amount_paid'    => 1000.00,
            'due_amount'     => 1047.50,
            'cogs'           => 1000.00,
            'sale_date'      => now()->toDateString(),
            'payment_status' => 'partial',
            'notes'          => 'Assignment demo sale',
        ]);

        // ── Journal Entry 1: Sale ─────────────────────────────────
        $je1 = JournalEntry::create([
            'entry_no'   => 'JE-' . date('Ymd') . '-0001',
            'reference'  => $sale->invoice_no,
            'narration'  => 'Sale of 10 units of Sample Product to Demo Customer',
            'entry_date' => now()->toDateString(),
            'type'       => 'sale',
        ]);
        JournalEntryLine::insert([
            ['journal_entry_id' => $je1->id, 'account_name' => 'Accounts Receivable', 'account_type' => 'asset',     'debit' => 2047.50, 'credit' => 0,       'created_at' => now(), 'updated_at' => now()],
            ['journal_entry_id' => $je1->id, 'account_name' => 'Sales Revenue',       'account_type' => 'income',    'debit' => 0,       'credit' => 1950.00, 'created_at' => now(), 'updated_at' => now()],
            ['journal_entry_id' => $je1->id, 'account_name' => 'VAT Payable',         'account_type' => 'liability', 'debit' => 0,       'credit' => 97.50,   'created_at' => now(), 'updated_at' => now()],
            ['journal_entry_id' => $je1->id, 'account_name' => 'Discount Allowed',    'account_type' => 'expense',   'debit' => 50.00,   'credit' => 0,       'created_at' => now(), 'updated_at' => now()],
            ['journal_entry_id' => $je1->id, 'account_name' => 'Sales (Gross)',       'account_type' => 'income',    'debit' => 0,       'credit' => 50.00,   'created_at' => now(), 'updated_at' => now()],
        ]);

        // ── Journal Entry 2: COGS ─────────────────────────────────
        $je2 = JournalEntry::create([
            'entry_no'   => 'JE-' . date('Ymd') . '-0002',
            'reference'  => $sale->invoice_no,
            'narration'  => 'COGS — 10 units of Sample Product @ 100 TK',
            'entry_date' => now()->toDateString(),
            'type'       => 'sale',
        ]);
        JournalEntryLine::insert([
            ['journal_entry_id' => $je2->id, 'account_name' => 'Cost of Goods Sold (COGS)', 'account_type' => 'expense', 'debit' => 1000.00, 'credit' => 0,       'created_at' => now(), 'updated_at' => now()],
            ['journal_entry_id' => $je2->id, 'account_name' => 'Inventory',                 'account_type' => 'asset',   'debit' => 0,       'credit' => 1000.00, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ── Journal Entry 3: Cash Received ────────────────────────
        $je3 = JournalEntry::create([
            'entry_no'   => 'JE-' . date('Ymd') . '-0003',
            'reference'  => $sale->invoice_no,
            'narration'  => 'Cash received from Demo Customer against ' . $sale->invoice_no,
            'entry_date' => now()->toDateString(),
            'type'       => 'payment',
        ]);
        JournalEntryLine::insert([
            ['journal_entry_id' => $je3->id, 'account_name' => 'Cash / Bank',         'account_type' => 'asset', 'debit' => 1000.00, 'credit' => 0,       'created_at' => now(), 'updated_at' => now()],
            ['journal_entry_id' => $je3->id, 'account_name' => 'Accounts Receivable', 'account_type' => 'asset', 'debit' => 0,       'credit' => 1000.00, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}