<?php

namespace App\Utils;

use App\Models\User;

class AvatarUtils
{
    public static function getAvatarPath(User $user)
    {
        if($user->avatar) {
            return '/avatar';
        }
        //return gravatar
        $hash = md5(strtolower(trim($user->email)));
        return "https://www.gravatar.com/avatar/$hash?s=512&d=mp";

    }

}
