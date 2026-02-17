<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropshippingApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'endpoint',
        'method',
        'request_data',
        'response_data',
        'response_code',
        'success',
        'error_message',
        'related_type',
        'related_id',
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
        'success' => 'boolean',
    ];

    /**
     * Scope to get successful logs
     */
    public function scopeSuccessful($query)
    {
        return $query->where('success', true);
    }

    /**
     * Scope to get failed logs
     */
    public function scopeFailed($query)
    {
        return $query->where('success', false);
    }

    /**
     * Scope to filter by endpoint
     */
    public function scopeEndpoint($query, $endpoint)
    {
        return $query->where('endpoint', $endpoint);
    }
}
