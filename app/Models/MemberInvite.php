<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'club_id',
        'code',
        'uses',
    ];

    public function consumers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'memberships', 'code_id', 'user_id');
    }
}
