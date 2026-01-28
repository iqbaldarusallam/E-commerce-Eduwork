<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'status',
        'total_amount',
        'shipping_fee',
        'message',
        'order_id',
    ];

    protected $casts = [
        'total_amount' => 'integer',
        'shipping_fee' => 'integer',
    ];

    /**
     * Relationship: Transaction has many payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Helper to get the latest successful payment (if any)
     */
    public function latestSuccessfulPayment()
    {
        return $this->hasOne(Payment::class)->where('status', Payment::STATUS_SUCCESS)->latestOfMany('paid_at');
    }

    /**
     * Maintain backward compatibility: return paid_at from the latest successful payment
     */
    public function getPaidAtAttribute()
    {
        $p = $this->payments()->where('status', Payment::STATUS_SUCCESS)->orderByDesc('paid_at')->first();
        return $p ? $p->paid_at : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function getTotalAmountWithShippingAttribute()
    {
        // total_amount already includes shipping_fee in this application
        return $this->total_amount;
    }
}
