<?php
namespace App\Traits;

use App\Models\BolAccount;
use App\Services\ApiService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToBol
{
    public function account(): BelongsTo
    {
        return $this->belongsTo(BolAccount::class, 'bol_account_id');
    }
}
