<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStat extends Model
{
    protected $table = 'users_stats';

    protected $fillable = [
        'users_id',
        'partidas_totales',
        'victorias',
        'empates',
        'derrotas',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
