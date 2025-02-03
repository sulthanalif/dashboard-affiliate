<?php

namespace App\Models;

use App\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\CausesActivity;

class Affiliate extends Model
{
    use LogActivity, CausesActivity, SoftDeletes;

    protected $logName = 'master_affiliate';

    protected $logAttributesToIgnore = ['password'];

    protected $table = 'affiliates';

    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'address',
        'phone',
        'password',
        'account_number',
        'bank_id',
        'account_name',
        'photo',
        'is_active',
        'is_rejected',
        'is_wp_affiliate',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
