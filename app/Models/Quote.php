<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'quote_number',
        'client_id',
        'quote_date',
        'subtotal',
        'gst_amount',
        'total_amount',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'quote_date' => 'date',
        'subtotal' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the client that owns the quote.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the quote items for the quote.
     */
    public function quoteItems()
    {
        return $this->hasMany(QuoteItem::class);
    }

    /**
     * Generate a unique quote number.
     */
    public static function generateQuoteNumber()
    {
        $lastQuote = self::orderBy('id', 'desc')->first();
        $number = $lastQuote ? intval(substr($lastQuote->quote_number ?? '0', 4)) + 1 : 1;
        return 'QUO-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}

