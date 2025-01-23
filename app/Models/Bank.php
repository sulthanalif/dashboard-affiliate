<?php

namespace App\Models;

use App\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\CausesActivity;

class Bank extends Model
{
    use SoftDeletes, LogActivity, CausesActivity;

    protected $logName = 'master_bank';

    protected $table = 'banks';

    protected $fillable = [
        'code',
        'name'
    ];

    public function affiliates()
    {
        return $this->hasMany(Affiliate::class);
    }
}
