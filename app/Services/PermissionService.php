<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PermissionService
{
    public static function check($user, $tabType, $action)
    {
        if ($user->role === 'superadmin') {
            return true;
        }
        
        $permission = $user->getTabPermission($tabType);
        $methodName = 'can_' . $action;
        
        return $permission->$methodName ?? false;
    }
}