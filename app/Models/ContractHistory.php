<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'action',
        'field',
        'old_value',
        'new_value',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(
            Contract::class
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }
}
