<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'page_id',
    'page_name',
    'page_access_token',
    'status',
    'bot_context',
])]
class FacebookPageConnection extends Model
{
    protected function casts(): array
    {
        return [
            'page_access_token' => 'encrypted',
            'bot_context' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
