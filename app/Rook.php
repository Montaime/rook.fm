<?php

namespace App;

class Rook
{
    public static array $morphMap = [
        1 => \App\Models\User::class,

        10 => \App\Models\Club::class,
        11 => \App\Models\MemberInvite::class,
        12 => \App\Models\Membership::class,
        13 => \App\Models\Post::class,
        14 => \App\Models\Comment::class,
    ];
}
