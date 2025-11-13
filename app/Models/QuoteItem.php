<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'quote_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'description',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the quote that owns the quote item.
     */
    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    /**
     * Get the product that owns the quote item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
