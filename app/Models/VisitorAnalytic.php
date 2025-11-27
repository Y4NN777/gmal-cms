<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorAnalytic extends Model
{
    protected $fillable = [
        'session_id',
        'ip_address',
        'user_agent',
        'page_url',
        'referrer',
        'country_code',
        'city',
        'device_type',
        'visit_duration',
        'page_views',
        'bounce_rate',
        'visited_at',
    ];

    protected $casts = [
        'visit_duration' => 'integer',
        'page_views' => 'integer',
        'bounce_rate' => 'decimal:2',
        'visited_at' => 'datetime',
    ];

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('visited_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('visited_at', now()->month);
    }

    public function scopeByDevice($query, string $device)
    {
        return $query->where('device_type', $device);
    }

    public function scopeByCountry($query, string $countryCode)
    {
        return $query->where('country_code', $countryCode);
    }
}
