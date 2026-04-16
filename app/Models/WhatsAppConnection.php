<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'waba_id',
    'phone_number_id',
    'display_phone_number',
    'verified_name',
    'access_token',
    'status',
    'bot_context',
])]
class WhatsAppConnection extends Model
{
    protected $table = 'whatsapp_connections';

    protected function casts(): array
    {
        return [
            'access_token' => 'encrypted',
            'bot_context' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

