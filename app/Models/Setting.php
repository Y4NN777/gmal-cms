<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, ?string $group = null): void
    {
        // Determine group from key if not provided
        if (!$group) {
            $group = match (true) {
                str_starts_with($key, 'site_') => 'general',
                str_starts_with($key, 'contact_') => 'contact',
                str_starts_with($key, 'social_') => 'social',
                default => 'general',
            };
        }

        // Determine type
        $type = is_array($value) || (is_string($value) && (str_starts_with($value, '[') || str_starts_with($value, '{'))) ? 'json' : 'text';

        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
                'group' => $group,
            ]
        );
        
        // Clear both individual and grouped cache
        Cache::forget("setting_{$key}");
        Cache::forget('settings_grouped');
    }

    /**
     * Get all settings grouped by group
     */
    public static function getAllGrouped(): array
    {
        return Cache::remember('settings_grouped', 3600, function () {
            return self::all()->groupBy('group')->map(function ($settings) {
                return $settings->mapWithKeys(function ($setting) {
                    $value = $setting->value;
                    
                    // Decode JSON values
                    if ($setting->type === 'json' && is_string($value)) {
                        $decoded = json_decode($value, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $value = $decoded;
                        }
                    }
                    
                    return [$setting->key => $value];
                });
            })->toArray();
        });
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }
}
