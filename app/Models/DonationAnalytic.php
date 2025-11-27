<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonationAnalytic extends Model
{
    protected $fillable = [
        'donor_name',
        'email',
        'phone',
        'amount',
        'currency',
        'donation_type',
        'campaign_id',
        'payment_method',
        'payment_reference',
        'status',
        'donor_metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'donor_metadata' => 'array',
    ];

    // Relationships
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'campaign_id');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRecurring($query)
    {
        return $query->whereIn('donation_type', ['monthly', 'yearly']);
    }

    public function scopeOneTime($query)
    {
        return $query->where('donation_type', 'one_time');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('created_at', now()->year);
    }
}
