<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sale extends Model
{
    protected $fillable = [
        'invoice_no', 'product_id', 'customer_name', 'quantity',
        'unit_price', 'gross_amount', 'discount', 'net_amount',
        'vat_rate', 'vat_amount', 'total_payable', 'amount_paid',
        'due_amount', 'cogs', 'sale_date', 'payment_status', 'notes'
    ];

    protected $casts = [
        'sale_date' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function journalEntry(): HasOne
    {
        return $this->hasOne(JournalEntry::class, 'reference', 'invoice_no');
    }

    public function getProfitAttribute(): float
    {
        return $this->net_amount - $this->cogs;
    }
}