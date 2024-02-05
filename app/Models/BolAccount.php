<?php

namespace App\Models;

use Carbon\Traits\Serialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Str;

class BolAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'logo',
    ];



    /**
     * strtolower used inside snaking to avoid spliting the uppercase words.
     */
    public function getClientIdAttribute()
    {
        return env(strtoupper(Str::snake(strtolower($this->name)) . "_BOL_ACCOUNT_ID"));
    }
    public function getClientKeyAttribute()
    {
        return env(strtoupper(Str::snake(strtolower($this->name)) . "_BOL_ACCOUNT_KEY"));
    }

    #### START RELATIONS ###

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'bol_account_id');
    }
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'bol_account_id');
    }
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'bol_account_id');
    }
    #### END RELATIONS ###
    public static function getCachedRecords()
    {
        return Cache::remember('bol_accounts' , now()->addDay() , function(){
            return self::query()->pluck('name' , 'id');
        });
    }

}
