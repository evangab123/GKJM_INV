<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'activity',
        'id_objek',
        'entitas',
        'changess',
        'ip_address',
        'user_agent',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'username', 'username');
    }
}
